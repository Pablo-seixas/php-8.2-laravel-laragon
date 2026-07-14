# Revisão completa do módulo Machine Learning

Data da revisão: 14/07/2026

## Escopo preservado

Esta revisão foi feita sem reescrever a arquitetura, substituir o algoritmo `LeastSquares`, mudar nomes de rotas, alterar controllers, modificar a interface visual, reorganizar pastas ou interferir no sistema de backup.

Arquivos principais inspecionados:

- `app/Services/MachineLearning/PrevisaoEstoqueService.php`;
- `app/Services/MachineLearning/MetricasCientificas.php`;
- `app/Http/Controllers/MachineLearningController.php`;
- `resources/views/machine-learning/index.blade.php`;
- `routes/web.php`.

## Correções implementadas

### 1. Divisão treino/teste

**Causa:** com o mínimo de cinco dias, a divisão de 80% deixava quatro amostras para treino e apenas uma para teste. O R² não é cientificamente definido com uma única observação.

**Solução:** foi mantida a divisão cronológica e o percentual de 80%, mas agora são reservadas pelo menos duas observações para teste e três para treino. Não há embaralhamento, evitando vazamento de dados futuros.

### 2. MAE, RMSE e R²

**Causa:** as séries podiam ter tamanhos diferentes ou conter `NAN`/`INF`; os índices ausentes eram silenciosamente substituídos por zero. O R² de uma série constante perfeitamente prevista retornava zero. A soma direta dos quadrados no RMSE era menos estável numericamente.

**Solução:** as séries agora são validadas, normalizadas para índices sequenciais e rejeitam valores não numéricos ou não finitos. O MAE usa média incremental, o RMSE usa norma euclidiana com `hypot()`, e o R² retorna 1 para uma série constante perfeitamente prevista e 0 para uma série constante não explicada.

As fórmulas permanecem as definições científicas usuais:

- MAE: média dos erros absolutos;
- RMSE: raiz da média dos erros quadráticos;
- R²: `1 - soma_resíduos / soma_total`.

Valores negativos de R² continuam permitidos, pois indicam corretamente que o modelo ficou pior que a previsão pela média.

### 3. Previsões não finitas

**Causa:** uma extrapolação numericamente instável poderia produzir `NAN` ou `INF`, contaminando somas, métricas, JSON e a tela.

**Solução:** toda previsão produzida pelo `LeastSquares` passa por uma normalização centralizada. Valores não finitos viram zero e previsões negativas continuam limitadas a zero, preservando a regra já usada pelo módulo.

### 4. Ruptura do estoque

**Causa:** a ruptura era calculada dividindo o estoque pela média das 30 previsões. Esse método ignora a ordem temporal e distorce a data quando existe tendência crescente ou decrescente.

**Solução:** a ruptura passou a usar o consumo previsto acumulado dia a dia. O mesmo modelo linear é extrapolado, com limite defensivo de dez anos. Se a previsão cair para zero por 30 dias consecutivos sem consumir todo o estoque, a ruptura fica indefinida. Estoque já zerado resulta em zero dias restantes.

### 5. Concorrência e nomes dos arquivos

**Causa:** os nomes tinham precisão de um segundo. Dois treinamentos no mesmo segundo podiam sobrescrever o JSON e o CSV.

**Solução:** os nomes agora incluem microssegundos e oito caracteres aleatórios criptograficamente seguros. JSON e CSV são escritos primeiro em arquivos temporários e movidos apenas depois de concluídos, reduzindo o risco de leitura parcial.

### 6. JSON vazio, inválido, `NAN` e `INF`

**Causa:** o carregamento usava `json_decode()` sem `JSON_THROW_ON_ERROR`. Um JSON mais recente, porém truncado ou inválido, fazia a página perder resultados válidos anteriores.

**Solução:** a leitura usa `JSON_THROW_ON_ERROR` e percorre os arquivos do mais recente ao mais antigo até encontrar um JSON válido. Um treinamento sem produtos continua gerando `[]`, que é JSON válido. A normalização existente de valores não finitos foi mantida.

### 7. CSV para Excel

**Causa:** embora o delimitador `;` estivesse correto para a localidade brasileira, faltavam indicações consistentes de codificação e separador. Conteúdo iniciado por `=`, `+`, `-` ou `@` poderia ser interpretado pelo Excel como fórmula.

**Solução:** o CSV agora contém BOM UTF-8, diretiva `sep=;`, terminações de linha CRLF e proteção contra injeção de fórmulas. O arquivo continua sendo CSV, abre diretamente no Excel com acentos e colunas organizadas e não exige biblioteca adicional.

### 8. Listagem e arquivos temporários

**Causa:** qualquer arquivo presente na pasta de resultados poderia aparecer na interface, inclusive resíduos temporários.

**Solução:** a listagem aceita somente arquivos finais `previsoes_*.json` e `previsoes_*.csv`. Arquivos `.tmp` não são apresentados nem disponibilizados pelo fluxo normal.

## Itens revisados sem alteração

### Controller

O controller mantém injeção de dependência, limite de execução, redirecionamento e download. Não foi alterado conforme a restrição do projeto. O nome recebido no download possui validação contra `..` e caracteres fora da lista permitida, e a rota está dentro do middleware administrativo.

### Rotas

As três rotas `ml.index`, `ml.treinar` e `ml.baixar` foram preservadas. Todas permanecem protegidas pelos middlewares de acesso, ausência de cache e perfil administrativo.

### Interface

A view foi inspecionada e preservada. Ela usa escape automático do Blade para dados e nomes de arquivos e trata chaves ausentes com valores padrão.

### Timezone

A listagem converte o `mtime` para `config('app.timezone')`. A configuração atual do projeto usa `America/Bahia`; nenhuma configuração global foi modificada.

### Série temporal

As saídas continuam agrupadas por dia e dias sem saída continuam preenchidos com zero. Isso é correto se ausência de registro significar consumo zero. Caso existam falhas de registro operacional, esses zeros devem ser interpretados como uma limitação da qualidade dos dados.

## Limitações científicas mantidas conscientemente

- A regressão continua linear e univariada, conforme solicitado.
- Não são modeladas sazonalidade, feriados, categoria, preço ou promoções.
- A extrapolação de longo prazo tem incerteza crescente.
- O R² com apenas duas amostras de teste é calculável, mas ainda pouco representativo.
- Não existe intervalo de confiança para as previsões.
- O processamento de todos os produtos ocorre de forma síncrona e pode ficar lento com grande volume histórico.

Esses pontos não são bugs corrigíveis sem ampliar o modelo ou alterar a arquitetura.

## Testes adicionados

- cálculo conhecido de MAE, RMSE e R²;
- R² de série constante perfeitamente prevista;
- rejeição de séries com tamanhos diferentes;
- rejeição de `NAN` e `INF`;
- suporte a arrays com chaves não sequenciais;
- validade do JSON produzido;
- compatibilidade do CSV com Excel;
- proteção contra fórmula no CSV;
- ausência de colisão em gravações consecutivas.

Resultado: 7 testes, 17 assertions, todos aprovados no PHPUnit 10.5 com PHP 8.2.32.

## Classificação final

| Critério | Nota | Justificativa resumida |
|---|---:|---|
| Arquitetura | 8,0 | Boa separação entre controller, serviço, métricas e view. |
| Código | 8,5 | Código legível, contratos preservados e casos-limite agora validados. |
| Machine Learning | 6,5 | Regressão correta para tendência simples, mas sem sazonalidade ou variáveis adicionais. |
| Robustez | 8,5 | Gravação atômica, nomes únicos, JSON tolerante a corrupção e números finitos. |
| Escalabilidade | 6,0 | Treinamento síncrono e carregamento integral do histórico por produto. |
| Valor científico | 7,0 | Métricas corretas e validação temporal, com limitações próprias do modelo linear. |

**Nota geral aproximada: 7,4/10.**
