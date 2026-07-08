<?php

namespace App\Http\Controllers;

use App\Services\RelatorioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function saidas(Request $request, RelatorioService $relatorio)
    {
        $saidas = $relatorio->saidas($request);

        return view('relatorios.saidas', compact('saidas'));
    }

    public function entradas(Request $request)
    {
        $entradas = DB::getSchemaBuilder()->hasTable('entradas')
            ? DB::table('entradas')
                ->join('produtos', 'entradas.produto_id', '=', 'produtos.id')
                ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
                ->select(
                    'produtos.nome',
                    'categorias.nome as categoria',
                    'entradas.setor',
                    'entradas.quantidade',
                    'entradas.responsavel',
                    'entradas.created_at'
                )
                ->when($request->data, fn($q) =>
                    $q->whereDate('entradas.created_at', $request->data)
                )
                ->orderBy('entradas.created_at', 'desc')
                ->get()
            : collect();

        return view('relatorios.entradas', compact('entradas'));
    }
}
