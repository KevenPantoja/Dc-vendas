<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->string('nome'); 
            $table->string('cpf')->unique()->nullable(); 
            $table->string('tipo_pessoa')->default('F');
            $table->string('nome_social')->nullable();
            $table->string('rg')->nullable(); 
            $table->boolean('consumidor_final')->default(false); 
            $table->boolean('contribuinte')->default(false); 
            $table->decimal('limite_vendas', 15, 2)->default(0); 

            // Endereço
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep', 20)->nullable();

            // Contatos
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
