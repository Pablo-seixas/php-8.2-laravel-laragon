<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntradaRequest;
use App\Models\Entrada;
use App\Models\Produto;
use App\Services\EntradaService;
use App\Services\LogService;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    public function index()
    {
        $entradas = Entrada::with('produto')->latest()->get();

        return view('entradas.index', compact('entradas'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('entradas.create', compact('produtos'));
    }

    public function store(EntradaRequest $request, EntradaService $entradaService, LogService $log)
    {
        $dados = $request->validated();

        $produto = Produto::findOrFail($dados['produto_id']);

        $resultado = $entradaService->registrarEntrada($produto, $dados);

        $resultado['status']
            ? $log->registrar($request, 'entrada registrada', 'entradas', $produto->id, 'Entrada do produto ' . $produto->nome)
            : null;

        return $resultado['status']
            ? redirect()->route('entradas.index')->with('success', $resultado['mensagem'])
            : back()->with('error', $resultado['mensagem']);
    }

    public function destroy(Request $request, Entrada $entrada, LogService $log)
    {
        $id = $entrada->id;

        $entrada->delete();

        $log->registrar($request, 'entrada removida', 'entradas', $id, 'Remocao de entrada');

        return redirect()->route('entradas.index')->with('success', 'Entrada removida com sucesso.');
    }
}
