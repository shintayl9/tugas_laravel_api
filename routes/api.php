<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('mahasiswas', MahasiswaController::class);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'role'])->group(function () {
    Route::get('/mahasiswas', [MahasiswaController::class, 'index']);
    Route::get('/mahasiswas/{id}', [MahasiswaController::class, 'show']);
    Route::post('/mahasiswas', [MahasiswaController::class, 'store'])->middleware('role:admin');
    Route::put('/mahasiswas/{id}', [MahasiswaController::class, 'update'])->middleware('role:admin');
    Route::delete('/mahasiswas/{id}', [MahasiswaController::class, 'destroy'])->middleware('role:admin');
});