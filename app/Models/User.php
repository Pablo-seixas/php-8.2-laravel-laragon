<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Representa os usuários do sistema.
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
        'setor',
        'unidade',
        'trocar_senha',
        'ativo',
        'is_super_admin',
        'can_manage_users',
        'can_delete_users',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Verifica se o usuário é administrador.
     */
    public function ehAdmin(): bool
    {
        return $this->tipo === 'admin';
    }

    /**
     * Verifica se o usuário é operador.
     */
    public function ehOperador(): bool
    {
        return $this->tipo === 'operador';
    }

    /**
     * Verifica se o usuário é apenas consulta.
     */
    public function ehConsulta(): bool
    {
        return $this->tipo === 'consulta';
    }

    /**
     * Retorna o status atual do usuário.
     */
    public function status(): string
    {
        return $this->ativo ? 'Ativo' : 'Bloqueado';
    }

    /**
     * Retorna o nome amigável do perfil.
     */
    public function perfil(): string
    {
        return match ($this->tipo) {
            'admin' => 'Administrador',
            'operador' => 'Operador',
            'consulta' => 'Consulta',
            default => 'Usuário',
        };
    }
}