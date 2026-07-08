<?php

namespace App\Services;

use App\Models\Entrada;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

class EntradaService
{
    public function registrarEntrada(Produto $produto, array $dados): array
    {
        return DB::transaction(function () use ($produto, $dados) {
            $produto->update([
                'quantidade' => $produto->quantidade + $dados['quantidade']
            ]);

            Entrada::create([
                'produto_id' => $produto->id,
                'quantidade' => $dados['quantidade'],
                'setor' => $dados['setor'] ?? 'Almoxarifado',
                'fornecedor' => $dados['fornecedor'] ?? null,
                'responsavel' => $dados['responsavel'] ?? null,
                'unidade' => $dados['unidade'] ?? null,
                'observacao' => $dados['observacao'] ?? null,
            ]);

            return [
                'status' => true,
                'mensagem' => $dados['quantidade'] > 1
                    ? 'Entradas registradas com sucesso.'
                    : 'Entrada registrada com sucesso.'
            ];
        });
    }
}
