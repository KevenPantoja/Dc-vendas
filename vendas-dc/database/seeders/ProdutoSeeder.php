<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        DB::table('produtos')->insert([
            [
                'nome' => 'Hambúrguer Clássico',
                'descricao' => 'Hambúrguer tradicional com queijo e alface',
                'preco' => 15.90,
                'estoque' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Batata Frita',
                'descricao' => 'Porção média de batata frita crocante',
                'preco' => 8.50,
                'estoque' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Refrigerante Lata',
                'descricao' => 'Refrigerante 350ml em lata',
                'preco' => 5.00,
                'estoque' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Milkshake de Chocolate',
                'descricao' => 'Milkshake cremoso com cobertura de chocolate',
                'preco' => 12.00,
                'estoque' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
