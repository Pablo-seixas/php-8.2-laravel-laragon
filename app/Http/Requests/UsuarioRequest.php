<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('usuario_tipo') === 'admin';
    }

   public function rules(): array
{
    $usuarioId = $this->route('usuario')?->id;

    return [
        'name' => 'required|min:3',

        'email' => $usuarioId
            ? 'required|email|unique:users,email,' . $usuarioId
            : 'required|email|unique:users,email',

        'password' => $usuarioId
            ? 'nullable|min:6|confirmed'
            : 'required|min:6|confirmed',

        'tipo' => 'required|in:admin,operador,consulta',

        'setor' => 'nullable|string|max:255',
        'unidade' => 'nullable|string|max:255',
        'ativo' => $usuarioId ? 'required|boolean' : 'nullable',

        'is_super_admin' => 'nullable|boolean',
        'can_manage_users' => 'nullable|boolean',
        'can_delete_users' => 'nullable|boolean',
    ];
}
}
