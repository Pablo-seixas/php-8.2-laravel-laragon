# Google Gemini CLI - Manual de Utilização

Projeto: Sistema de Controle de Estoque Laravel 11

Autor: Pablo Seixas

---

# Objetivo

Este documento serve como guia para utilização do Google Gemini CLI dentro deste projeto.

O objetivo NÃO é substituir o desenvolvedor.

O Gemini será utilizado como um Engenheiro de Software auxiliar para:

- analisar código
- encontrar bugs
- sugerir melhorias
- gerar documentação
- revisar arquitetura
- auxiliar no desenvolvimento

Toda alteração deverá ser validada pelo desenvolvedor antes de ser aplicada.

---

# Estrutura

```
ia/
│
├── gemini/
│   ├── README_GEMINI.md
│   ├── GEMINI.md
│   ├── prompts/
│   ├── analises/
│   ├── relatorios/
│   └── historico/
```

---

# Ambiente de Casa

PHP

```
C:\php\php.exe
```

Projeto

```
P:\code3\code\Crud-php-main
```

Gemini

```
C:\Users\SEU_USUARIO\AppData\Roaming\npm\gemini.cmd
```

Entrar no projeto

```powershell
cd P:\code3\code\Crud-php-main
```

Abrir Gemini

```powershell
& "C:\Users\SEU_USUARIO\AppData\Roaming\npm\gemini.cmd"
```

Modo Prompt

```powershell
gemini -p "Explique este projeto."
```

---

# Ambiente da Empresa

Projeto

```
C:\Users\pablo.seixas\Documents\code\Crud-php-main
```

Entrar

```powershell
cd C:\Users\pablo.seixas\Documents\code\Crud-php-main
```

Abrir Gemini

```powershell
gemini
```

---

# Configuração da API

Caso utilize API Key

PowerShell

```powershell
$env:GEMINI_API_KEY="SUA_API_KEY"
```

ou

```powershell
$env:GOOGLE_API_KEY="SUA_API_KEY"
```

---

# Comandos úteis

Abrir

```powershell
gemini
```

Versão

```powershell
gemini --version
```

Ajuda

```powershell
gemini --help
```

Modo Prompt

```powershell
gemini -p "Explique este código."
```

Listar extensões

```powershell
gemini --list-extensions
```

Listar sessões

```powershell
gemini --list-sessions
```

Retomar sessão

```powershell
gemini --resume latest
```

Modo somente leitura

```powershell
gemini --approval-mode plan
```

---

# Exemplos

Analisar projeto

```
Leia o arquivo @GEMINI.md.

Analise completamente este projeto Laravel.

Não altere nenhum arquivo.

Faça um relatório técnico.
```

---

Encontrar Bugs

```
Analise este projeto.

Liste bugs.

Não altere arquivos.
```

---

Performance

```
Analise gargalos de performance.

Sugira melhorias.
```

---

Segurança

```
Analise vulnerabilidades.

Não implemente nada.

Apenas gere relatório.
```

---

Arquitetura

```
Avalie a arquitetura Laravel.

Explique pontos fortes.

Explique pontos fracos.
```

---

Controllers

```
Analise @app/Http/Controllers

Encontre duplicações.
```

---

Views

```
Analise @resources/views

Sugira melhorias.
```

---

Models

```
Analise @app/Models

Sugira relacionamentos.
```

---

Rotas

```
Analise @routes/web.php

Encontre rotas duplicadas.
```

---

Banco

```
Analise migrations.

Sugira índices.

Sugira melhorias.
```

---

Dashboard

```
Analise somente a Central Analítica.

Sugira novos gráficos.

Não implemente.
```

---

# O que NÃO fazer

Nunca pedir:

- alterar .env
- alterar vendor
- apagar arquivos
- alterar migrations existentes sem autorização
- alterar autenticação sem planejamento

---

# Fluxo recomendado

1

ChatGPT

↓

Planeja arquitetura

↓

2

Gemini

↓

Analisa projeto

↓

3

Gemini

↓

Propõe implementação

↓

4

ChatGPT

↓

Valida arquitetura

↓

5

Gemini

↓

Implementa

↓

6

ChatGPT

↓

Code Review

---

# Boas práticas

Sempre trabalhar em pequenas alterações.

Sempre fazer commit antes.

Nunca implementar funcionalidades grandes em uma única interação.

Sempre pedir relatório antes da implementação.

Utilizar modo "plan" quando desejar apenas análise.

---

# Como este projeto utiliza o Gemini

Neste projeto o Gemini NÃO é utilizado como substituto do desenvolvedor.

Ele atua como um engenheiro de software assistente.

Suas principais funções são:

- revisar código
- identificar bugs
- sugerir melhorias
- analisar arquitetura
- gerar documentação
- localizar código duplicado
- revisar segurança
- revisar performance
- auxiliar na evolução do sistema
- explicar partes complexas do código
- apoiar estudos sobre Laravel e PHP

As decisões de arquitetura e implementação continuam sendo responsabilidade do desenvolvedor.

O objetivo é aumentar a produtividade, manter a qualidade do código e documentar a evolução do projeto de forma organizada e profissional.