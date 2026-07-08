<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Produto;
use App\Models\Saida;

/**
 * Responsável pelos indicadores exibidos no Dashboard.
 */
class DashboardService
{
    /**
     * Retorna os principais indicadores do sistema.
     */
    public function dados(): array
    {
        $totalProdutos = Produto::count();

        $totalEstoque = Produto::sum('quantidade');

        $estoqueBaixo = Produto::whereColumn(
            'quantidade',
            '<=',
            'estoque_minimo'
        )->count();

        $saidasHoje = Saida::whereDate(
            'created_at',
            now()->toDateString()
        )->sum('quantidade');

        $ultimasSaidas = Saida::with('produto')
            ->latest()
            ->limit(5)
            ->get();

        return [
            'totalProdutos'  => $totalProdutos,
            'totalEstoque'   => $totalEstoque,
            'estoqueBaixo'   => $estoqueBaixo,
            'saidasHoje'     => $saidasHoje,
            'ultimasSaidas'  => $ultimasSaidas,

            // Utilizado pela View para destacar o estado do estoque.
            'statusEstoque' => $estoqueBaixo > 0
                ? 'Atenção'
                : 'Normal',

            // Classe CSS utilizada no Blade.
            'classeEstoque' => $estoqueBaixo > 0
                ? 'text-danger'
                : 'text-success',
        ];
    }
}