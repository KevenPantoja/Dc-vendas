<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendaController;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return redirect()->route('vendas.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Modificado para não gerar erro do método show
    Route::resource('vendas', VendaController::class)->except(['show']);

    Route::get('vendas/{id}/exportar-pdf', [VendaController::class, 'exportarPdf'])->name('vendas.exportarPdf');
    Route::post('/clientes/ajax', [VendaController::class, 'storeClienteAjax'])->name('clientes.storeAjax');

    // ✅ Removido o caminho duplicado para evitar ambiguidade
    Route::get('/clientes/create', [VendaController::class, 'createCliente'])->name('clientes.create');

    Route::post('/clientes', [VendaController::class, 'storeCliente'])->name('clientes.store');

    // ✅ Mantido o acesso pela rota usada na view (cliente_add)
    Route::get('/vendas/clientes/novo', [VendaController::class, 'createCliente'])->name('vendas.createCliente');  
});

Route::prefix('vendas')->group(function () {
    Route::get('produtos', [VendaController::class, 'indexProdutos'])->name('vendas.produtos.index');
    Route::get('produtos/novo', [VendaController::class, 'createProduto'])->name('vendas.produtos.create');
    Route::post('produtos', [VendaController::class, 'storeProduto'])->name('vendas.produtos.store');
});



require __DIR__.'/auth.php';
