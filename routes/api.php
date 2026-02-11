<?php

use App\Http\Controllers\Web\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/file", [FileController::class, 'index']);
