<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AcessoController extends Controller
{
    public function index()
    {
        return view('acesso');
    }

    public function entrar(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $usuario = User::where('email', $dados['email'])->first();

        $adminInicial = $dados['email'] === 'admin@local' && $dados['password'] === '15-15-15';

        return $adminInicial
            ? $this->entrarAdminInicial()
            : $this->validarUsuario($usuario, $dados['password']);
    }

    private function entrarAdminInicial()
    {
        session([
            'usuario_id' => 0,
            'usuario_nome' => 'Administrador Inicial',
            'usuario_tipo' => 'admin',
            'usuario_setor' => 'Administracao',
            'usuario_unidade' => 'Matriz',
        ]);

        return redirect()->route('dashboard');
    }

    private function validarUsuario($usuario, string $senha)
    {
        $valido = $usuario && $usuario->ativo && Hash::check($senha, $usuario->password);

        return $valido
            ? $this->acessoPermitido($usuario)
            : back()->with('error', 'Usuario ou senha invalidos.');
    }

    private function acessoPermitido(User $usuario)
    {
        session([
            'usuario_id' => $usuario->id,
            'usuario_nome' => $usuario->name,
            'usuario_tipo' => $usuario->tipo,
            'usuario_setor' => $usuario->setor,
            'usuario_unidade' => $usuario->unidade,
        ]);

        return $usuario->trocar_senha
            ? redirect()->route('senha.editar')
            : redirect()->route('dashboard');
    }

    public function editarSenha()
    {
        return view('usuarios.senha');
    }

    public function atualizarSenha(Request $request)
    {
        $dados = $request->validate([
            'password' => 'required|min:6',
        ]);

        $usuario = User::find(session('usuario_id'));

        $usuario?->update([
            'password' => Hash::make($dados['password']),
            'trocar_senha' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Senha atualizada com sucesso.');
    }

    public function sair()
    {
        session()->flush();

        return redirect('/');
    }
}
