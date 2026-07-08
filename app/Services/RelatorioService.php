<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioService
{
    public function saidas(Request $request)
    {
        return DB::table('saidas')
            ->join('produtos', 'saidas.produto_id', '=', 'produtos.id')
            ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
            ->select(
                'produtos.nome as produto',
                'produtos.codigo',
                'categorias.nome as categoria',
                'saidas.setor',
                'saidas.unidade',
                'saidas.responsavel',
                'saidas.quantidade',
                'saidas.observacao',
                'saidas.created_at'
            )
            ->when($request->produto, fn($q) => $q->where('produtos.nome', 'like', "%{$request->produto}%"))
            ->when($request->codigo, fn($q) => $q->where('produtos.codigo', 'like', "%{$request->codigo}%"))
            ->when($request->categoria, fn($q) => $q->where('categorias.nome', 'like', "%{$request->categoria}%"))
            ->when($request->setor, fn($q) => $q->where('saidas.setor', 'like', "%{$request->setor}%"))
            ->when($request->unidade, fn($q) => $q->where('saidas.unidade', 'like', "%{$request->unidade}%"))
            ->when($request->responsavel, fn($q) => $q->where('saidas.responsavel', 'like', "%{$request->responsavel}%"))
            ->when($request->data_inicio, fn($q) => $q->whereDate('saidas.created_at', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('saidas.created_at', '<=', $request->data_fim))
            ->when($request->hora_inicio, fn($q) => $q->whereTime('saidas.created_at', '>=', $request->hora_inicio))
            ->when($request->hora_fim, fn($q) => $q->whereTime('saidas.created_at', '<=', $request->hora_fim))
            ->orderBy('saidas.created_at', 'desc')
            ->get();
    }
}
