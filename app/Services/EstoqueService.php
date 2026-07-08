<?php

namespace App\Services;

use App\Models\Produto;
use App\Models\Saida;
use Illuminate\Support\Facades\DB;

class EstoqueService
{
    public function registrarSaida(Produto $produto, array $dados): array
    {
        return $produto->quantidade < $dados['quantidade']
            ? ['status' => false, 'mensagem' => 'Quantidade insuficiente em estoque.']
            : DB::transaction(function () use ($produto, $dados) {
                $produto->update([
                    'quantidade' => $produto->quantidade - $dados['quantidade']
                ]);

                Saida::create([
                    'produto_id' => $produto->id,
                    'quantidade' => $dados['quantidade'],
                    'setor' => $dados['setor'],
                    'responsavel' => $dados['responsavel'] ?? null,
                    'unidade' => $dados['unidade'] ?? null,
                    'observacao' => $dados['observacao'] ?? null,
                ]);

                return [
                    'status' => true,
                    'mensagem' => $produto->quantidade <= 0
                        ? 'Saida registrada. Produto ficou sem estoque.'
                        : 'Saida registrada com sucesso.'
                ];
            });
    }
}
