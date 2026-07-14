<?php

namespace App\Services\MachineLearning;

use InvalidArgumentException;

class MetricasCientificas
{
    public function calcular(array $reais, array $previstos): array
    {
        $this->validarAmostras($reais, $previstos);

        $reais = array_map('floatval', array_values($reais));
        $previstos = array_map('floatval', array_values($previstos));

        return [
            'mae' => round($this->mae($reais, $previstos), 4),
            'rmse' => round($this->rmse($reais, $previstos), 4),
            'r2' => round($this->r2($reais, $previstos), 4),
        ];
    }

    private function mae(array $reais, array $previstos): float
    {
        if (count($reais) === 0) {
            return 0;
        }

        $media = 0.0;

        foreach ($reais as $indice => $real) {
            $erroAbsoluto = abs($real - $previstos[$indice]);
            $media += ($erroAbsoluto - $media) / ($indice + 1);
        }

        return $media;
    }

    private function rmse(array $reais, array $previstos): float
    {
        if (count($reais) === 0) {
            return 0;
        }

        $normaEuclidiana = 0.0;

        foreach ($reais as $indice => $real) {
            $erro = $real - $previstos[$indice];
            $normaEuclidiana = hypot($normaEuclidiana, $erro);
        }

        return $normaEuclidiana / sqrt(count($reais));
    }

    private function r2(array $reais, array $previstos): float
    {
        if (count($reais) < 2) {
            return 0;
        }

        $media = 0.0;

        foreach ($reais as $indice => $real) {
            $media += ($real - $media) / ($indice + 1);
        }

        $total = 0.0;
        $residuo = 0.0;

        foreach ($reais as $indice => $real) {
            $total += ($real - $media) ** 2;
            $residuo += ($real - $previstos[$indice]) ** 2;
        }

        if ($total == 0.0) {
            return $residuo == 0.0 ? 1.0 : 0.0;
        }

        return 1 - ($residuo / $total);
    }

    private function validarAmostras(array $reais, array $previstos): void
    {
        if (count($reais) !== count($previstos)) {
            throw new InvalidArgumentException(
                'As séries real e prevista devem ter o mesmo tamanho.'
            );
        }

        foreach ([$reais, $previstos] as $serie) {
            foreach ($serie as $valor) {
                if (!is_numeric($valor) || !is_finite((float) $valor)) {
                    throw new InvalidArgumentException(
                        'As séries devem conter apenas números finitos.'
                    );
                }
            }
        }
    }
}