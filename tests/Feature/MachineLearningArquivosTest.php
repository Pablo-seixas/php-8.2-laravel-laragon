<?php

namespace Tests\Feature;

use App\Services\MachineLearning\MetricasCientificas;
use App\Services\MachineLearning\PrevisaoEstoqueService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MachineLearningArquivosTest extends TestCase
{
    private array $arquivosCriados = [];

    protected function tearDown(): void
    {
        foreach ($this->arquivosCriados as $arquivo) {
            File::delete($arquivo);
        }

        parent::tearDown();
    }

    public function test_salva_json_valido_e_csv_compativel_com_excel(): void
    {
        $service = new PrevisaoEstoqueService(
            new MetricasCientificas()
        );

        $arquivos = $service->salvarResultados([[
            'produto' => '=SOMA(1;1)',
            'estoque_atual' => 10,
            'consumo_previsto_30_dias' => 5.5,
            'dias_restantes' => 60,
            'data_provavel_ruptura' => '01/01/2027',
            'tendencia' => 'estavel',
            'risco' => 'regular',
            'mae' => 1.2,
            'rmse' => 1.3,
            'r2' => 0.8,
            'status' => 'analisado',
        ]]);

        $json = $this->registrarArquivo($arquivos['json']);
        $csv = $this->registrarArquivo($arquivos['csv']);

        $this->assertFileExists($json);
        $this->assertFileExists($csv);
        $this->assertIsArray(json_decode(
            File::get($json),
            true,
            512,
            JSON_THROW_ON_ERROR
        ));

        $conteudoCsv = File::get($csv);

        $this->assertStringStartsWith("\xEF\xBB\xBFsep=;\r\n", $conteudoCsv);
        $this->assertStringContainsString("'=SOMA(1;1)", $conteudoCsv);
        $this->assertStringNotContainsString('.tmp', $arquivos['csv']);
    }

    public function test_nomes_nao_colidem_em_gravacoes_consecutivas(): void
    {
        $service = new PrevisaoEstoqueService(
            new MetricasCientificas()
        );

        $primeiro = $service->salvarResultados([]);
        $segundo = $service->salvarResultados([]);

        foreach ([$primeiro, $segundo] as $par) {
            $this->registrarArquivo($par['json']);
            $this->registrarArquivo($par['csv']);
        }

        $this->assertNotSame($primeiro['json'], $segundo['json']);
        $this->assertNotSame($primeiro['csv'], $segundo['csv']);
    }

    private function registrarArquivo(string $nome): string
    {
        $caminho = storage_path('app/machine-learning/resultados/' . $nome);
        $this->arquivosCriados[] = $caminho;

        return $caminho;
    }
}
