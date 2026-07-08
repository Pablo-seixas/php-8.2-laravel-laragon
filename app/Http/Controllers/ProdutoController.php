<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;
use App\Services\ProdutoService;
use App\Services\LogService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request, ProdutoService $produtoService)
    {
        $produtos = $produtoService->listar($request);

        $categorias = $produtoService->categorias();

        return view('produtos.index', compact('produtos', 'categorias'));
    }

    public function show(Produto $produto)
    {
        return view('produtos.show', compact('produto'));
    }

    public function create(ProdutoService $produtoService)
    {
        $categorias = $produtoService->categorias();

        return view('produtos.create', compact('categorias'));
    }

    public function store(ProdutoRequest $request, ProdutoService $produtoService, LogService $log)
    {
        $produto = $produtoService->salvar($request->validated());

        $log->registrar($request, 'produto cadastrado', 'produtos', $produto->id, 'Cadastro do produto ' . $produto->nome);

        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso.');
    }

    public function edit(Produto $produto, ProdutoService $produtoService)
    {
        $categorias = $produtoService->categorias();

        return view('produtos.edit', compact('produto', 'categorias'));
    }

    public function update(ProdutoRequest $request, Produto $produto, ProdutoService $produtoService, LogService $log)
    {
        $produtoService->atualizar($produto, $request->validated());

        $log->registrar($request, 'produto atualizado', 'produtos', $produto->id, 'Atualizacao do produto ' . $produto->nome);

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Request $request, Produto $produto, ProdutoService $produtoService, LogService $log)
    {
        $id = $produto->id;
        $nome = $produto->nome;

        $produtoService->remover($produto);

        $log->registrar($request, 'produto removido', 'produtos', $id, 'Remocao do produto ' . $nome);

        return redirect()->route('produtos.index')->with('success', 'Produto removido com sucesso.');
    }
}

