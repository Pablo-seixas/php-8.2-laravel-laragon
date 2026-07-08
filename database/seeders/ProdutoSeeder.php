<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Copa',
            'Limpeza',
            'Tecnologia',
            'Escritorio',
            'Manutencao',
            'Mobiliario',
            'Eletrica',
            'Informatica',
        ];

        foreach ($categorias as $nome) {
            Categoria::firstOrCreate(['nome' => $nome]);
        }

        $produtos = [
            ['Copa', 'Cafe para copa', 'COP-001', 30, 5, 'Copa'],
            ['Copa', 'Acucar', 'COP-002', 20, 5, 'Copa'],
            ['Copa', 'Copo descartavel 200ml', 'COP-003', 500, 100, 'Copa'],
            ['Copa', 'Guardanapo de papel', 'COP-004', 100, 20, 'Copa'],
            ['Copa', 'Filtro de cafe', 'COP-005', 40, 10, 'Copa'],

            ['Limpeza', 'Papel higienico', 'LIM-001', 120, 30, 'Almoxarifado'],
            ['Limpeza', 'Papel toalha', 'LIM-002', 80, 20, 'Almoxarifado'],
            ['Limpeza', 'Detergente', 'LIM-003', 50, 10, 'Almoxarifado'],
            ['Limpeza', 'Desinfetante', 'LIM-004', 40, 10, 'Almoxarifado'],
            ['Limpeza', 'Agua sanitaria', 'LIM-005', 35, 8, 'Almoxarifado'],
            ['Limpeza', 'Saco de lixo 100L', 'LIM-006', 90, 20, 'Almoxarifado'],
            ['Limpeza', 'Luva de limpeza', 'LIM-007', 60, 15, 'Almoxarifado'],

            ['Tecnologia', 'Mouse USB', 'TEC-001', 25, 5, 'TI'],
            ['Tecnologia', 'Teclado USB', 'TEC-002', 20, 5, 'TI'],
            ['Tecnologia', 'Monitor LED', 'TEC-003', 10, 2, 'TI'],
            ['Tecnologia', 'Gabinete de computador', 'TEC-004', 8, 2, 'TI'],
            ['Tecnologia', 'Fonte ATX', 'TEC-005', 12, 3, 'TI'],
            ['Tecnologia', 'Cabo HDMI', 'TEC-006', 30, 5, 'TI'],
            ['Tecnologia', 'Cabo de rede', 'TEC-007', 100, 20, 'TI'],
            ['Tecnologia', 'Switch 8 portas', 'TEC-008', 6, 2, 'TI'],
            ['Tecnologia', 'Roteador wireless', 'TEC-009', 5, 1, 'TI'],
            ['Tecnologia', 'SSD 480GB', 'TEC-010', 10, 2, 'TI'],

            ['Escritorio', 'Caneta azul', 'ESC-001', 200, 50, 'Almoxarifado'],
            ['Escritorio', 'Caneta preta', 'ESC-002', 150, 40, 'Almoxarifado'],
            ['Escritorio', 'Papel A4', 'ESC-003', 80, 20, 'Almoxarifado'],
            ['Escritorio', 'Grampeador', 'ESC-004', 15, 3, 'Almoxarifado'],
            ['Escritorio', 'Clips', 'ESC-005', 100, 20, 'Almoxarifado'],
            ['Escritorio', 'Pasta suspensa', 'ESC-006', 60, 10, 'Almoxarifado'],

            ['Mobiliario', 'Cadeira de escritorio', 'MOB-001', 20, 5, 'Deposito'],
            ['Mobiliario', 'Mesa de escritorio', 'MOB-002', 10, 2, 'Deposito'],
            ['Mobiliario', 'Armario de aço', 'MOB-003', 6, 1, 'Deposito'],
            ['Mobiliario', 'Gaveteiro', 'MOB-004', 8, 2, 'Deposito'],

            ['Eletrica', 'Lampada LED', 'ELE-001', 70, 15, 'Manutencao'],
            ['Eletrica', 'Tomada', 'ELE-002', 40, 10, 'Manutencao'],
            ['Eletrica', 'Interruptor', 'ELE-003', 35, 8, 'Manutencao'],
            ['Eletrica', 'Filtro de linha', 'ELE-004', 25, 5, 'Manutencao'],

            ['Manutencao', 'Parafuso', 'MAN-001', 300, 50, 'Manutencao'],
            ['Manutencao', 'Bucha', 'MAN-002', 250, 50, 'Manutencao'],
            ['Manutencao', 'Fita isolante', 'MAN-003', 40, 10, 'Manutencao'],
            ['Manutencao', 'Extensao eletrica', 'MAN-004', 15, 3, 'Manutencao'],
        ];

        foreach ($produtos as [$categoriaNome, $nome, $codigo, $quantidade, $minimo, $localizacao]) {
            $categoria = Categoria::where('nome', $categoriaNome)->first();

            Produto::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'categoria_id' => $categoria->id,
                    'nome' => $nome,
                    'quantidade' => $quantidade,
                    'estoque_minimo' => $minimo,
                    'localizacao' => $localizacao,
                    'observacoes' => 'Produto cadastrado automaticamente para testes.',
                ]
            );
        }
    }
}
