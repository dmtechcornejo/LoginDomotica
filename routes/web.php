<?php

use App\Http\Controllers\DomoticaController;
use App\Models\BCamaraModel;
use App\Http\Controllers\GraficosController;
use Illuminate\Support\Facades\Route;
USE Illuminate\Support\Facades\DB;


// Ruta Inicio S/L
Route::get('/', function () {
    return view('layouts.index');
});
// Ruta Graficos
Route::get('/bcamara', [GraficosController::class, 'index'])->middleware(['auth'])->name('bcamara');;
Route::post('/bcamara', [GraficosController::class, 'index'])->middleware(['auth'])->name('bcamara');;

// Ruta Vehiculos
Route::get('/vehiculo', [DomoticaController::class, 'vehiculo'])->middleware(['auth'])->name('vehiculo');

// Ruta Inicio L
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
