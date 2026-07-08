<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoService
{
    public function listar(Request $request)
    {
        return Produto::with('categoria')
            ->when($request->busca, fn($q) =>
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('codigo', 'like', "%{$request->busca}%")
            )
            ->when($request->categoria_id, fn($q) =>
                $q->where('categoria_id', $request->categoria_id)
            )
            ->when($request->estoque === 'baixo', fn($q) =>
                $q->whereColumn('quantidade', '<=', 'estoque_minimo')
            )
            ->when($request->estoque === 'normal', fn($q) =>
                $q->whereColumn('quantidade', '>', 'estoque_minimo')
            )
            ->latest()
            ->get();
    }

    public function categorias()
    {
        return Categoria::orderBy('nome')->get();
    }

    public function salvar(array $dados): Produto
    {
        return Produto::create($dados);
    }

    public function atualizar(Produto $produto, array $dados): bool
    {
        return $produto->update($dados);
    }

    public function remover(Produto $produto): bool
    {
        return $produto->delete();
    }
}
