<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;





Route::post('/login',[AuthController::class,'login']);
Route::get('/refresh',[AuthController::class,'refresh']);
Route::get('/logout',[AuthController::class,'logout']);
Route::get('/me',[AuthController::class,'me']);

Route::resource('user', AuthController::class);


Route::middleware(['auth:api'])->group(function () {
    Route::resource('post', PostController::class);
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
