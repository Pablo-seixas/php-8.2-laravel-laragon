<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produto_id' => $this->route('produto')
                ? 'nullable'
                : 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'setor' => 'required|string|max:255',
            'responsavel' => 'nullable|string|max:255',
            'unidade' => 'nullable|string|max:255',
            'observacao' => 'nullable|string',
        ];
    }
}
