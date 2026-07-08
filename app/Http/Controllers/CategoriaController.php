<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|min:3|unique:categorias,nome',
        ]);

        Categoria::create($dados);

        return back()->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return back()->with('success', 'Categoria removida com sucesso.');
    }
}
