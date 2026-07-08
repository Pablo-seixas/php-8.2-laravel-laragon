<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntradaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'fornecedor' => 'nullable|string|max:255',
            'responsavel' => 'nullable|string|max:255',
            'unidade' => 'nullable|string|max:255',
            'observacao' => 'nullable|string',
        ];
    }
}
