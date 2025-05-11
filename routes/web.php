<?php

use App\Http\Controllers\RubrosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuponesController;

Route::get('/', function () {
    return view('welcome');
});
//ruta para listas cupones pendientes
Route::get('/cupones', [CuponesController::class, 'pendientes'])->name('cupones.pendientes');
Route::get('/cupones/editar/{id}', [CuponesController::class, 'editarestado'])->name('cupones.editarestado');
Route::put('/cupones/actualizar/{id}', [CuponesController::class, 'actualizarestado'])->name('cupones.actualizarestado');


// Rutas para Rubros 
Route::get('/rubros', [RubrosController::class, 'index'])->name('rubros.index');
Route::post('/rubros/crear', [RubrosController::class, 'store'])->name('rubros.store');
Route::put('/rubros/actualizar/{id}', [RubrosController::class, 'update'])->name('rubros.update');
Route::delete('/rubros/eliminar/{id}', [RubrosController::class, 'destroy'])->name('rubros.destroy');