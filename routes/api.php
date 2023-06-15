<?php

use App\Http\Controllers\Admin\CausaController;
use App\Http\Controllers\Admin\ComunidadController;
use App\Http\Controllers\Admin\DonacionController;
use App\Http\Controllers\Admin\EncryptionController;
use App\Http\Controllers\Admin\RegimenFiscalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/get-causas', [CausaController::class, 'getCausasAPI'] );
Route::get('/get-comunidades', [ComunidadController::class, 'getComunidadesAPI']);
Route::get('/get-regimenes', [RegimenFiscalController::class, 'getRegimenesAPI']);

Route::post('/encrypt-info', [EncryptionController::class, 'encrypt']);
Route::post('/decrypt-info', [EncryptionController::class, 'decrypt']);

Route::post('/donate', [DonacionController::class, 'donate']);

