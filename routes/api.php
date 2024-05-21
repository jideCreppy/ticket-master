<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('V1')->group(function () {
    require_once __DIR__.'/api_v1.php';
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
