<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RubrosController;
use App\Http\Controllers\CuponesController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadosController;


/* RUTAS QUE NO ES NECESARIO INICIAR SESION */

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');


/* RUTAS QUE ES NECESARIO INICIAR SESION */
Route::middleware('auth.sesion')->group(function () {

    Route::middleware('auth.empleado')->group(function () {
        //Rutas de cupones
        Route::match(['get', 'post'], '/cupones/canjear/{id?}', [CuponesController::class, 'canjear'])->name('cupones.canjear');
    });

    /* RUTAS DE EMPRESA EXTERNA */

    Route::middleware('auth.empresa')->group(function () {
        //Rutas de cupones
        Route::get('/cupones', [CuponesController::class, 'index'])->name('cupones.index');
        Route::match(['get', 'post'], '/cupones/crear', [CuponesController::class, 'create'])->name('cupones.create');
        Route::match(['get', 'put'], '/cupones/editar/{id}', [CuponesController::class, 'update'])->name('cupones.update');
        Route::delete('/cupones/descartar/{id}', [CuponesController::class, 'destroy'])->name('cupones.destroy');

        //Rutas de empleados
        Route::get('/empleados', [EmpleadosController::class, 'index'])->name('empleados.index');
        Route::match(['get', 'post'], '/empleados/crear', [EmpleadosController::class, 'create'])->name('empleados.create');
        Route::match(['get', 'put'], '/empleados/editar/{id}', [EmpleadosController::class, 'update'])->name('empleados.update');
        Route::delete('/empleados/eliminar/{id}', [EmpleadosController::class, 'destroy'])->name('empleados.destroy');
    });



    /* RUTAS DE CUPONERA */

    Route::middleware('auth.empresa:CUPON')->group(function () {
        //Rutas de cupones
        Route::get('/empresas/{empresa}/cupones', [CuponesController::class, 'indexEmpresa'])->name('empresa.cupones.index');
        Route::match(['get', 'put'], '/cupones/aprobar/{id}', [CuponesController::class, 'aprobar'])->name('cupones.aprobar');

        //Rutas para empresa
        Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
        Route::match(['get', 'post'], '/empresas/crear', [EmpresaController::class, 'create'])->name('empresas.create');
        Route::match(['get', 'put'], '/empresas/editar/{id}', [EmpresaController::class, 'update'])->name('empresas.update');
        Route::delete('/empresas/eliminar/{id}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

        // Rutas para Rubros 
        Route::get('/rubros', [RubrosController::class, 'index'])->name('rubros.index');
        Route::match(['get', 'post'], '/rubros/crear', [RubrosController::class, 'create'])->name('rubros.create');
        Route::match(['get', 'put'], '/rubros/editar/{id}', [RubrosController::class, 'update'])->name('rubros.update');
        Route::delete('/rubros/eliminar/{id}', [RubrosController::class, 'destroy'])->name('rubros.destroy');

        //Rutas para cliente
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/{id}/cupones', [ClienteController::class, 'showCupones'])->name('cliente.cupones.index');
    });
});
