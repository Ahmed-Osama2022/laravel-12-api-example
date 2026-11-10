<?php

use App\Http\Controllers\Api\V1\AboutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
  Route::get('/about', AboutController::class)->name('about');
});

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
