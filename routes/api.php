<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api")->group(function () {
    Route::resources([
        'posts' => PostController::class,
    ]);
})
;

Route::post('/register', [AuthController::class, 'Register']);
Route::post('/login', [AuthController::class, 'Login']);