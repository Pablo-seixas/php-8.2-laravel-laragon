<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaidaRequest;
use App\Models\Produto;
use App\Models\Saida;
use App\Services\EstoqueService;
use App\Services\LogService;
use Illuminate\Http\Request;

class SaidaController extends Controller
{
    public function index()
    {
        $saidas = Saida::with('produto')->latest()->get();

        return view('saidas.index', compact('saidas'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('saidas.create', compact('produtos'));
    }

    public function store(SaidaRequest $request, EstoqueService $estoque, LogService $log)
    {
        $dados = $request->validated();

        $produto = Produto::findOrFail($dados['produto_id']);

        $resultado = $estoque->registrarSaida($produto, $dados);

        $resultado['status']
            ? $log->registrar($request, 'saida registrada', 'saidas', $produto->id, 'Saida do produto ' . $produto->nome)
            : null;

        return $resultado['status']
            ? redirect()->route('saidas.index')->with('success', $resultado['mensagem'])
            : back()->with('error', $resultado['mensagem']);
    }

    public function storeFromProduto(SaidaRequest $request, Produto $produto, EstoqueService $estoque, LogService $log)
    {
        $resultado = $estoque->registrarSaida($produto, $request->validated());

        $resultado['status']
            ? $log->registrar($request, 'saida registrada', 'saidas', $produto->id, 'Saida direta do produto ' . $produto->nome)
            : null;

        return $resultado['status']
            ? back()->with('success', $resultado['mensagem'])
            : back()->with('error', $resultado['mensagem']);
    }

    public function edit(Saida $saida)
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('saidas.edit', compact('saida', 'produtos'));
    }

    public function update(SaidaRequest $request, Saida $saida, LogService $log)
    {
        $saida->update($request->validated());

        $log->registrar($request, 'saida atualizada', 'saidas', $saida->id, 'Atualizacao de saida');

        return redirect()->route('saidas.index')->with('success', 'Saida atualizada com sucesso.');
    }

    public function destroy(Request $request, Saida $saida, LogService $log)
    {
        $id = $saida->id;

        $saida->delete();

        $log->registrar($request, 'saida removida', 'saidas', $id, 'Remocao de saida');

        return redirect()->route('saidas.index')->with('success', 'Saida removida com sucesso.');
    }
}
