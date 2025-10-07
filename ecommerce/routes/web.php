<?php

use App\http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProdutoController;

Route::get('/', [HomeController::class,'index'])->name('Index');

Route::get('/Tipo', [TipoController::class,'index'])->name('Tipo.index');
Route::get('/Tipo/create', [TipoController::class,'create'])->name('Tipo.create');
Route::post('/Tipo', [TipoController::class,'store'])->name('Tipo.store');
Route::get('/Tipo/{tipo}', [TipoController::class,'show'])->name('Tipo.show');
Route::get('/Tipo/{tipo}/edit', [TipoController::class,'edit'])->name('Tipo.edit');
Route::put('/Tipo/{tipo}', [TipoController::class,'update'])->name('Tipo.update');
Route::delete('/Tipo/{tipo}', [TipoController::class,'destroy'])->name('Tipo.destroy');

Route::get('/Marca', [MarcaController::class,'index'])->name('Marca.index');
Route::get('/Marca/create', [MarcaController::class,'create'])->name('Marca.create');
Route::post('/Marca', [MarcaController::class,'store'])->name('Marca.store');
Route::get('/Marca/{marcas}', [MarcaController::class,'show'])->name('Marca.show');
Route::get('/Marca/{Marca}/edit', [MarcaController::class,'edit'])->name('Marca.edit');
Route::put('/Marca/{Marca}', [MarcaController::class,'update'])->name('Marca.update');
Route::delete('/Marca/{Marca}', [MarcaController::class,'destroy'])->name('Marca.destroy');

Route::get('/Produto', [ProdutoController::class,'index'])->name('Produto.index');
Route::get('/Produto/create', [ProdutoController::class,'create'])->name('Produto.create');
Route::post('/Produto', [ProdutoController::class,'store'])->name('Produto.store');
Route::get('/Produto/{Produto}', [ProdutoController::class,'show'])->name('Produto.show');
Route::get('/Produto/{Produto}/edit', [ProdutoController::class,'edit'])->name('Produto.edit');
Route::put('/Produto/{Produto}', [ProdutoController::class,'update'])->name('Produto.update');
Route::delete('/Produto/{Produto}', [ProdutoController::class,'destroy'])->name('Produto.destroy');