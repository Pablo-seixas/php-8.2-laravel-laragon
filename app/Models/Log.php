<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'usuario',
        'tipo',
        'acao',
        'tabela',
        'registro_id',
        'ip',
        'descricao',
    ];
}
