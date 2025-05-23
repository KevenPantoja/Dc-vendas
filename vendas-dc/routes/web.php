<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\ClienteController; // Import do controller de clientes


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
    Route::resource('vendas', VendaController::class);
    Route::get('vendas/{id}/exportar-pdf', [VendaController::class, 'exportarPdf'])->name('vendas.exportarPdf');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
});


require __DIR__.'/auth.php';
