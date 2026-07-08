<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    protected $fillable = [
        'produto_id',
        'quantidade',
        'setor',
        'responsavel',
        'unidade',
        'observacao',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
