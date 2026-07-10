<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Registro dos backups gerados pelo sistema.
 */
class BackupRegistro extends Model
{
    protected $fillable = [
        'nome_arquivo',
        'caminho',
        'tamanho_bytes',
        'tamanho_formatado',
        'ano',
        'mes',
        'semana',
        'data_backup',
        'status',
    ];

    public function statusTexto(): string
    {
        return $this->status === 'criado' ? 'Criado' : 'Removido';
    }

    public function classeStatus(): string
    {
        return $this->status === 'criado' ? 'text-success' : 'text-danger';
    }
}