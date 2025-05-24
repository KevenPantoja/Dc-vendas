<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_id',
        'forma_pagamento_id',
        'valor_total',
    ];

    // Relacionamento com Itens da Venda
    public function itens()
    {
        return $this->hasMany(ItemVenda::class);
    }

    // Relacionamento com Parcelas
    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }

    // Relacionamento com Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacionamento com Forma de Pagamento
    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    // Relacionamento com Usuário (vendedor)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
