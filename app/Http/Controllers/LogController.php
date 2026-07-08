<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = Log::query()
            ->when($request->usuario, fn($q) => $q->where('usuario', 'like', "%{$request->usuario}%"))
            ->when($request->acao, fn($q) => $q->where('acao', 'like', "%{$request->acao}%"))
            ->when($request->tabela, fn($q) => $q->where('tabela', 'like', "%{$request->tabela}%"))
            ->when($request->data_inicio, fn($q) => $q->whereDate('created_at', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('created_at', '<=', $request->data_fim))
            ->latest()
            ->get();

        return view('logs.index', compact('logs'));
    }
}
