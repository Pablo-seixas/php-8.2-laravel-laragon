<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Controla o cadastro, edição e permissões dos usuários.
 */
class UsuarioController extends Controller
{
    /**
     * Busca o usuário que está logado na sessão atual.
     */
    private function usuarioLogado(): ?User
    {
        return User::find(session('usuario_id'));
    }

    /**
     * Verifica se o usuário atual é o administrador principal.
     */
    private function ehAdminPrincipal(): bool
    {
        return session('usuario_id') === 0
            || (bool) optional($this->usuarioLogado())->is_super_admin;
    }

    /**
     * Lista os usuários cadastrados.
     */
    public function index(): View
    {
        $usuarios = User::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Exibe a tela de cadastro de usuário.
     */
    public function create(): View
    {
        return view('usuarios.create');
    }

    /**
     * Salva um novo usuário.
     */
    public function store(UsuarioRequest $request): RedirectResponse
    {
        $dados = $request->validated();
        $adminPrincipal = $this->ehAdminPrincipal();

        User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'tipo' => $dados['tipo'],
            'setor' => $dados['setor'] ?? null,
            'unidade' => $dados['unidade'] ?? null,
            'trocar_senha' => true,
            'ativo' => true,
            'is_super_admin' => $adminPrincipal && !empty($dados['is_super_admin']),
            'can_manage_users' => $adminPrincipal && !empty($dados['can_manage_users']),
            'can_delete_users' => $adminPrincipal && !empty($dados['can_delete_users']),
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Exibe a tela de edição de usuário.
     */
    public function edit(User $usuario): View
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Atualiza os dados de um usuário.
     */
    public function update(
        UsuarioRequest $request,
        User $usuario
    ): RedirectResponse {
        $dados = $request->validated();

        if (!$this->ehAdminPrincipal()) {
            unset(
                $dados['is_super_admin'],
                $dados['can_manage_users'],
                $dados['can_delete_users']
            );
        }

        $dados['password'] = !empty($dados['password'])
            ? Hash::make($dados['password'])
            : null;

        $dados = array_filter(
            $dados,
            fn ($valor) => $valor !== null
        );

        $usuario->update($dados);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário atualizado.');
    }

    /**
     * Remove um usuário do sistema.
     */
    public function destroy(User $usuario): RedirectResponse
    {
        $logado = $this->usuarioLogado();

        if ($usuario->tipo === 'admin' && !$this->ehAdminPrincipal()) {
            return back()->with(
                'error',
                'Somente o administrador principal pode excluir administradores.'
            );
        }

        if ($usuario->id === session('usuario_id')) {
            return back()->with(
                'error',
                'Você não pode excluir sua própria conta.'
            );
        }

        if (!$this->ehAdminPrincipal() && !optional($logado)->can_delete_users) {
            return back()->with(
                'error',
                'Você não tem permissão para excluir usuários.'
            );
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário removido.');
    }

    /**
     * Reseta a senha do usuário para o padrão inicial.
     */
    public function resetarSenha(User $usuario): RedirectResponse
    {
        if ($usuario->tipo === 'admin' && !$this->ehAdminPrincipal()) {
            return back()->with(
                'error',
                'Somente o administrador principal pode resetar senha de administradores.'
            );
        }

        $usuario->update([
            'password' => Hash::make('15-15-15'),
            'trocar_senha' => true,
        ]);

        return back()->with('success', 'Senha resetada para 15-15-15.');
    }
}