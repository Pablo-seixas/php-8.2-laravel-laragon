<?php

namespace App\Services\MachineLearning;

use App\Models\Produto;
use App\Models\Saida;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Phpml\Regression\LeastSquares;
use RuntimeException;

class PrevisaoEstoqueService
{
    public function __construct(
        private MetricasCientificas $metricas
    ) {
    }

    public function analisarTodos(): array
    {
        $produtos = Produto::orderBy('nome')->get();
        $resultados = [];

        foreach ($produtos as $produto) {
            $resultados[] = $this->analisarProduto($produto);
        }

        return $resultados;
    }

    public function analisarProduto(Produto $produto): array
    {
        $serie = $this->montarSerieDiaria($produto->id);

        if (count($serie) < 5) {
            return [
                'produto_id' => $produto->id,
                'produto' => $produto->nome,
                'estoque_atual' => (int) $produto->quantidade,
                'status' => 'historico_insuficiente',
                'mensagem' => 'São necessários pelo menos 5 dias de histórico.',
            ];
        }

        $samples = [];
        $targets = [];

        foreach ($serie as $indice => $quantidade) {
            $samples[] = [$indice + 1];
            $targets[] = (float) $quantidade;
        }

        $total = count($samples);

        $quantidadeTreino = max(
            3,
            (int) floor($total * 0.80)
        );

        $quantidadeTreino = min(
            $quantidadeTreino,
            $total - 2
        );

        $samplesTreino = array_slice(
            $samples,
            0,
            $quantidadeTreino
        );

        $targetsTreino = array_slice(
            $targets,
            0,
            $quantidadeTreino
        );

        $samplesTeste = array_slice(
            $samples,
            $quantidadeTreino
        );

        $targetsTeste = array_slice(
            $targets,
            $quantidadeTreino
        );

        $modelo = new LeastSquares();

        $modelo->train(
            $samplesTreino,
            $targetsTreino
        );

        $previstosTeste = array_map(
            fn (array $sample): float => $this->normalizarPrevisao(
                $modelo->predict($sample)
            ),
            $samplesTeste
        );

        $metricas = $this->metricas->calcular(
            $targetsTeste,
            $previstosTeste
        );

        $previsoes30Dias = [];

        for ($dia = 1; $dia <= 30; $dia++) {
            $indiceFuturo = $total + $dia;

            $previsoes30Dias[] = $this->normalizarPrevisao(
                $modelo->predict([$indiceFuturo])
            );
        }

        $consumoPrevisto30 = array_sum($previsoes30Dias);
        $mediaDiariaPrevista = $consumoPrevisto30 / 30;

        [$diasRestantes, $dataRuptura] = $this->calcularRuptura(
            max(0, (int) $produto->quantidade),
            $modelo,
            $total
        );

        $primeiraPrevisao = $previsoes30Dias[0] ?? 0;
        $ultimaPrevisao = $previsoes30Dias[29] ?? 0;

        $tendencia = match (true) {
            $ultimaPrevisao > $primeiraPrevisao * 1.10
                => 'crescente',

            $ultimaPrevisao < $primeiraPrevisao * 0.90
                => 'decrescente',

            default => 'estavel',
        };

        $risco = match (true) {
            $diasRestantes === null
                => 'sem_consumo',

            $diasRestantes <= 15
                => 'critico',

            $diasRestantes <= 30
                => 'atencao',

            default => 'regular',
        };

        return [
            'produto_id' => $produto->id,
            'produto' => $produto->nome,
            'estoque_atual' => (int) $produto->quantidade,
            'amostras' => $total,
            'consumo_previsto_30_dias' => round(
                $consumoPrevisto30,
                2
            ),
            'media_diaria_prevista' => round(
                $mediaDiariaPrevista,
                2
            ),
            'dias_restantes' => $diasRestantes,
            'data_provavel_ruptura' => $dataRuptura,
            'tendencia' => $tendencia,
            'risco' => $risco,
            'mae' => $metricas['mae'],
            'rmse' => $metricas['rmse'],
            'r2' => $metricas['r2'],
            'status' => 'analisado',
        ];
    }

    private function montarSerieDiaria(int $produtoId): array
    {
        $saidas = Saida::query()
            ->where('produto_id', $produtoId)
            ->orderBy('created_at', 'asc')
            ->get([
                'quantidade',
                'created_at',
            ]);

        if ($saidas->isEmpty()) {
            return [];
        }

        $agrupadas = $saidas
            ->groupBy(function ($saida): string {
                return $saida->created_at->format('Y-m-d');
            })
            ->map(function (Collection $grupo): float {
                return (float) $grupo->sum('quantidade');
            });

        $dataInicial = $saidas
            ->first()
            ->created_at
            ->copy()
            ->startOfDay();

        $dataFinal = $saidas
            ->last()
            ->created_at
            ->copy()
            ->startOfDay();

        $serie = [];

        while ($dataInicial->lessThanOrEqualTo($dataFinal)) {
            $data = $dataInicial->format('Y-m-d');

            $serie[] = (float) $agrupadas->get($data, 0);

            $dataInicial->addDay();
        }

        return $serie;
    }

    private function normalizarPrevisao(mixed $valor): float
    {
        $previsao = (float) $valor;

        if (!is_finite($previsao)) {
            return 0.0;
        }

        return max(0.0, $previsao);
    }

    private function calcularRuptura(
        int $estoqueAtual,
        LeastSquares $modelo,
        int $totalAmostras
    ): array {
        if ($estoqueAtual <= 0) {
            return [0, now()->format('d/m/Y')];
        }

        $consumoAcumulado = 0.0;
        $diasSemConsumo = 0;

        for ($dia = 1; $dia <= 3650; $dia++) {
            $consumo = $this->normalizarPrevisao(
                $modelo->predict([$totalAmostras + $dia])
            );

            if ($consumo <= 0.0) {
                $diasSemConsumo++;

                if ($diasSemConsumo >= 30) {
                    return [null, null];
                }
            } else {
                $diasSemConsumo = 0;
                $consumoAcumulado += $consumo;
            }

            if ($consumoAcumulado >= $estoqueAtual) {
                return [
                    $dia,
                    now()->addDays($dia)->format('d/m/Y'),
                ];
            }
        }

        return [null, null];
    }

    public function salvarResultados(array $resultados): array
    {
        $pasta = storage_path(
            'app/machine-learning/resultados'
        );

        File::ensureDirectoryExists($pasta);

        $codigo = now()->format('Y-m-d_H-i-s-u')
            . '_' . bin2hex(random_bytes(4));

        $json = $pasta
            . DIRECTORY_SEPARATOR
            . "previsoes_{$codigo}.json";

        $csv = $pasta
            . DIRECTORY_SEPARATOR
            . "previsoes_{$codigo}.csv";

        $resultadosNormalizados = $this->normalizarParaJson(
            $resultados
        );

        $conteudoJson = json_encode(
            $resultadosNormalizados,
            JSON_PRETTY_PRINT
            | JSON_UNESCAPED_UNICODE
            | JSON_INVALID_UTF8_SUBSTITUTE
            | JSON_THROW_ON_ERROR
        );

        $jsonTemporario = $json . '.tmp';

        File::put($jsonTemporario, $conteudoJson, true);
        File::move($jsonTemporario, $json);

        $csvTemporario = $csv . '.tmp';

        $arquivo = fopen($csvTemporario, 'xb');

        if ($arquivo === false) {
            throw new RuntimeException(
                'Não foi possível criar o arquivo CSV.'
            );
        }

        fwrite($arquivo, "\xEF\xBB\xBF");
        fwrite($arquivo, "sep=;\r\n");

        fputcsv(
            $arquivo,
            [
                'produto',
                'estoque_atual',
                'consumo_previsto_30_dias',
                'dias_restantes',
                'data_provavel_ruptura',
                'tendencia',
                'risco',
                'mae',
                'rmse',
                'r2',
                'status',
                'mensagem',
            ],
            ';',
            '"',
            '\\',
            "\r\n"
        );

        foreach ($resultadosNormalizados as $resultado) {
            fputcsv(
                $arquivo,
                $this->normalizarLinhaCsv([
                    $resultado['produto'] ?? '',
                    $resultado['estoque_atual'] ?? 0,
                    $resultado['consumo_previsto_30_dias'] ?? '',
                    $resultado['dias_restantes'] ?? '',
                    $resultado['data_provavel_ruptura'] ?? '',
                    $resultado['tendencia'] ?? '',
                    $resultado['risco'] ?? '',
                    $resultado['mae'] ?? '',
                    $resultado['rmse'] ?? '',
                    $resultado['r2'] ?? '',
                    $resultado['status'] ?? '',
                    $resultado['mensagem'] ?? '',
                ]),
                ';',
                '"',
                '\\',
                "\r\n"
            );
        }

        fclose($arquivo);
        File::move($csvTemporario, $csv);


        return [
            'json' => basename($json),
            'csv' => basename($csv),
        ];
    }

    public function listarArquivos(): array
    {
        $pasta = storage_path(
            'app/machine-learning/resultados'
        );

        if (!File::exists($pasta)) {
            return [];
        }

        return collect(File::files($pasta))
            ->filter(function ($arquivo): bool {
                $extensao = strtolower($arquivo->getExtension());

                return in_array($extensao, ['csv', 'json'], true)
                    && preg_match(
                        '/^previsoes_[A-Za-z0-9_-]+\.(csv|json)$/',
                        $arquivo->getFilename()
                    ) === 1;
            })
            ->sortByDesc(
                fn ($arquivo) => $arquivo->getMTime()
            )
            ->map(function ($arquivo): array {
                return [
                    'nome' => $arquivo->getFilename(),

                    'tamanho' => $this->formatarTamanhoArquivo(
                        $arquivo->getSize()
                    ),

                    'data' => Carbon::createFromTimestamp(
                        $arquivo->getMTime()
                    )
                        ->setTimezone(
                            config('app.timezone', 'America/Bahia')
                        )
                        ->format('d/m/Y H:i:s'),
                ];
            })
            ->values()
            ->all();
    }

    public function carregarUltimosResultados(): array
    {
        $pasta = storage_path(
            'app/machine-learning/resultados'
        );

        if (!File::exists($pasta)) {
            return [];
        }

        $arquivos = collect(File::files($pasta))
            ->filter(function ($item): bool {
                return strtolower(
                    $item->getExtension()
                ) === 'json'
                    && $item->getSize() > 0
                    && str_starts_with(
                        $item->getFilename(),
                        'previsoes_'
                    );
            })
            ->sortByDesc(function ($item): int {
                return $item->getMTime();
            });

        foreach ($arquivos as $arquivo) {
            try {
                $resultados = json_decode(
                    File::get($arquivo->getPathname()),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            } catch (\JsonException) {
                continue;
            }

            if (is_array($resultados)) {
                return $resultados;
            }
        }

        return [];
    }

    private function normalizarParaJson(mixed $valor): mixed
    {
        if (is_array($valor)) {
            $normalizado = [];

            foreach ($valor as $chave => $item) {
                $normalizado[$chave] = $this->normalizarParaJson(
                    $item
                );
            }

            return $normalizado;
        }

        if (is_float($valor)) {
            if (is_nan($valor) || is_infinite($valor)) {
                return 0.0;
            }

            return $valor;
        }

        if (is_string($valor)) {
            return mb_convert_encoding(
                $valor,
                'UTF-8',
                'UTF-8'
            );
        }

        return $valor;
    }

    private function normalizarLinhaCsv(array $linha): array
    {
        return array_map(function (mixed $valor): mixed {
            if (!is_string($valor) || $valor === '') {
                return $valor;
            }

            $valor = mb_convert_encoding($valor, 'UTF-8', 'UTF-8');
            $primeiroCaractere = mb_substr(ltrim($valor), 0, 1);

            if (in_array(
                $primeiroCaractere,
                ['=', '+', '-', '@', "\t", "\r"],
                true
            )) {
                return "'" . $valor;
            }

            return $valor;
        }, $linha);
    }

    private function formatarTamanhoArquivo(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' bytes';
        }

        if ($bytes < 1048576) {
            return number_format(
                $bytes / 1024,
                2,
                ',',
                '.'
            ) . ' KB';
        }

        return number_format(
            $bytes / 1048576,
            2,
            ',',
            '.'
        ) . ' MB';
    }
}