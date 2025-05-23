<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome', 'cpf', 'tipo_pessoa', 'nome_social', 'rg', 'consumidor_final', 'contribuinte',
        'limite_vendas', 'rua', 'numero', 'bairro', 'cep', 'email', 'telefone', 'celular'
    ];
}
