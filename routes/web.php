<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RubrosController;
use App\Http\Controllers\CuponesController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuponAgregarController;

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

//Rutas para cliente
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes');
Route::get('/clientes/{id}/cupones', [ClienteController::class, 'showCupones'])->name('clientes_cupones');


//Ruta para agregar cupones--ACCESO DESDE ADMINISTRADOR DE EMPRESA EXTERNA
Route::match(['get', 'post'], '/cupones/crear', [CuponAgregarController::class, 'crear'])->name('cupones.crear');

