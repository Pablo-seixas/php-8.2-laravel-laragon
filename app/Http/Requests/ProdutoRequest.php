<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $produtoId = $this->route('produto')?->id;

        return [
            'categoria_id' => 'required|exists:categorias,id',
            'nome' => 'required|min:3',
            'codigo' => $produtoId
                ? 'required|unique:produtos,codigo,' . $produtoId
                : 'required|unique:produtos,codigo',
            'quantidade' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'localizacao' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
        ];
    }
}
