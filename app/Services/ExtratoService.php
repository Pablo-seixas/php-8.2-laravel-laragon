<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtratoService
{
    public function movimentacoes(Request $request)
    {
        $entradas = DB::table('entradas')
            ->join('produtos', 'entradas.produto_id', '=', 'produtos.id')
            ->select(
                'produtos.nome as produto',
                'produtos.codigo',
                'entradas.quantidade',
                'entradas.responsavel',
                'entradas.unidade',
                'entradas.created_at',
                DB::raw("'entrada' as tipo"),
                DB::raw("'+' as sinal")
            );

        $saidas = DB::table('saidas')
            ->join('produtos', 'saidas.produto_id', '=', 'produtos.id')
            ->select(
                'produtos.nome as produto',
                'produtos.codigo',
                'saidas.quantidade',
                'saidas.responsavel',
                'saidas.unidade',
                'saidas.created_at',
                DB::raw("'saida' as tipo"),
                DB::raw("'-' as sinal")
            );

        return DB::query()
            ->fromSub($entradas->unionAll($saidas), 'movimentacoes')
            ->when($request->produto, fn($q) => $q->where('produto', 'like', "%{$request->produto}%"))
            ->when($request->codigo, fn($q) => $q->where('codigo', 'like', "%{$request->codigo}%"))
            ->when($request->tipo, fn($q) => $q->where('tipo', $request->tipo))
            ->when($request->responsavel, fn($q) => $q->where('responsavel', 'like', "%{$request->responsavel}%"))
            ->when($request->unidade, fn($q) => $q->where('unidade', 'like', "%{$request->unidade}%"))
            ->when($request->data_inicio, fn($q) => $q->whereDate('created_at', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('created_at', '<=', $request->data_fim))
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
