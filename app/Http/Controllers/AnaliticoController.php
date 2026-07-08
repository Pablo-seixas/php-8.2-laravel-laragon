<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnaliticoController extends Controller
{
    public function index()
    {
        return view('analitico.index');
    }

    public function dados()
    {
        $periodo = request('periodo', '30');

        $inicio = match ($periodo) {
            'hoje' => Carbon::today(),
            '7' => Carbon::now()->subDays(7),
            '30' => Carbon::now()->subDays(30),
            'ano' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(30),
        };

        return response()->json([
            'kpis' => [
                'produtos' => DB::table('produtos')->count(),
                'estoque' => DB::table('produtos')->sum('quantidade'),
                'estoque_baixo' => DB::table('produtos')->whereColumn('quantidade', '<=', 'estoque_minimo')->count(),
                'saidas_periodo' => DB::table('saidas')->where('created_at', '>=', $inicio)->sum('quantidade'),
                'entradas_periodo' => DB::table('entradas')->where('created_at', '>=', $inicio)->sum('quantidade'),
            ],

            'produtosPorCategoria' => DB::table('produtos')
                ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
                ->select('categorias.nome as nome', DB::raw('COUNT(produtos.id) as total'))
                ->groupBy('categorias.nome')
                ->orderByDesc('total')
                ->get(),

            'estoquePorProduto' => DB::table('produtos')
                ->select('nome', 'quantidade')
                ->orderByDesc('quantidade')
                ->limit(10)
                ->get(),

            'saidasPorSetor' => DB::table('saidas')
                ->where('created_at', '>=', $inicio)
                ->select('setor as nome', DB::raw('SUM(quantidade) as total'))
                ->groupBy('setor')
                ->orderByDesc('total')
                ->get(),

            'entradasSaidasPorDia' => DB::table('entradas')
                ->select(DB::raw("date(created_at) as dia"), DB::raw("SUM(quantidade) as entradas"), DB::raw("0 as saidas"))
                ->where('created_at', '>=', $inicio)
                ->groupBy(DB::raw("date(created_at)"))
                ->unionAll(
                    DB::table('saidas')
                        ->select(DB::raw("date(created_at) as dia"), DB::raw("0 as entradas"), DB::raw("SUM(quantidade) as saidas"))
                        ->where('created_at', '>=', $inicio)
                        ->groupBy(DB::raw("date(created_at)"))
                )
                ->get()
                ->groupBy('dia')
                ->map(function ($grupo, $dia) {
                    return [
                        'dia' => Carbon::parse($dia)->format('d/m'),
                        'entradas' => collect($grupo)->sum('entradas'),
                        'saidas' => collect($grupo)->sum('saidas'),
                    ];
                })
                ->values(),

            'alertas' => DB::table('produtos')
                ->whereColumn('quantidade', '<=', 'estoque_minimo')
                ->select('nome', 'quantidade', 'estoque_minimo')
                ->orderBy('quantidade')
                ->limit(8)
                ->get(),
        ]);
    }
}
