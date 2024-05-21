<?php

use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TicketsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->apiResource('/tickets', TicketsController::class);
Route::middleware(['auth:sanctum'])->apiResource('/authors', AuthorsController::class);
Route::middleware(['auth:sanctum'])->apiResource('/authors.tickets', AuthorTicketsController::class);
