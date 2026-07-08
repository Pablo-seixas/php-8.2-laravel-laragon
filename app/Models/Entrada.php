<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    protected $fillable = [
        'produto_id',
        'quantidade',
        'setor',
        'fornecedor',
        'responsavel',
        'unidade',
        'observacao',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
