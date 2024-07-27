<?php

use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TicketsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResources(
        [
            'tickets' => TicketsController::class,
            'authors' => AuthorsController::class,
            'authors.tickets' => AuthorTicketsController::class,
        ]
    );
});
