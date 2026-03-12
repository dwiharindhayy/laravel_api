<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController; // tambahkan ini
use App\Http\Controllers\Api\AuthController;

// route public
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

// route yang butuh login
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class,'logout']);

});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route Category
Route::apiResource('/categories', CategoryController::class);

// Route Product
Route::apiResource('/products', ProductController::class);