<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TiposUsuario;
use App\Models\TipoUsuarios;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ["auth:sanctum"]], function () {
    //todas las rutas que estan dentro de este grupo se podra hacer su peticion con token
    Route::get('usuario',               [AuthController::class, 'index']);
    Route::get('usuario/search/{name}', [AuthController::class, 'showUser']);
    Route::delete('usuario/{id}',       [AuthController::class, 'destroy']);
    Route::put('usuarios/{id}',         [AuthController::class, 'update']);

    Route::resource('tipo',             TiposUsuario::class);
    Route::get('tipo/calcular/edad',    [TiposUsuario::class, 'calcularEdad']);
    Route::get('tipos/usuario',        [TiposUsuario::class, 'ListadoTipoUsuario']);
});




Route::post('usuario', [AuthController::class, 'store']);
Route::post('logeo', [AuthController::class, 'logear']);
