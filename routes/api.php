<?php

use App\Http\Controllers\Web\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/companies/{company}/orcamento', [FileController::class, 'index'])->name('companies.orcamento');
