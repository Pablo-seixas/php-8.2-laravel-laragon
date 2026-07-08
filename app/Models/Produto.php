<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produto extends Model
{
    protected $fillable = [
        'categoria_id',
        'nome',
        'codigo',
        'quantidade',
        'estoque_minimo',
        'localizacao',
        'observacoes',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function status(): string
    {
        return $this->quantidade <= $this->estoque_minimo
            ? 'Estoque baixo'
            : 'Normal';
    }
}