<?php

namespace Tests\Unit;

use App\Services\MachineLearning\MetricasCientificas;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MetricasCientificasTest extends TestCase
{
    private MetricasCientificas $metricas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metricas = new MetricasCientificas();
    }

    public function test_calcula_mae_rmse_e_r2_corretamente(): void
    {
        $resultado = $this->metricas->calcular(
            [1, 2, 3],
            [1, 4, 2]
        );

        $this->assertSame(1.0, $resultado['mae']);
        $this->assertSame(1.291, $resultado['rmse']);
        $this->assertSame(-1.5, $resultado['r2']);
    }

    public function test_r2_de_serie_constante_perfeita_e_um(): void
    {
        $resultado = $this->metricas->calcular(
            [5, 5, 5],
            [5, 5, 5]
        );

        $this->assertSame(1.0, $resultado['r2']);
    }

    public function test_rejeita_series_com_tamanhos_diferentes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->metricas->calcular([1, 2], [1]);
    }

    public function test_rejeita_nan_e_infinito(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->metricas->calcular([1, NAN], [1, INF]);
    }

    public function test_aceita_arrays_com_chaves_nao_sequenciais(): void
    {
        $resultado = $this->metricas->calcular(
            [2 => 10, 4 => 20],
            [7 => 12, 9 => 18]
        );

        $this->assertSame(2.0, $resultado['mae']);
        $this->assertSame(2.0, $resultado['rmse']);
        $this->assertSame(0.84, $resultado['r2']);
    }
}
