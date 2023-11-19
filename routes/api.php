<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getUserData', [UserController::class, 'getUserData']);
    Route::get('/GetProduct/{id}',[ProductController::class,"GetProduct"]);
    Route::post('/AddProduct',[ProductController::class,"AddProduct"]);
    Route::post('/UpdateProduct/{id}',[ProductController::class,"UpdateProduct"]);
    Route::post('/Search/{id}',[ProductController::class,"Search"]);
    Route::delete('/Delete/{id}',[ProductController::class,"Delete"]);
});

Route::post('/Login',[UserController::class,"Login"]);
Route::post('/Register',[UserController::class,"Register"]);
// Route::post('/AddProduct',[ProductController::class,"AddProduct"]);
Route::get('/Productlist',[ProductController::class,"Productlist"]);
// Route::get('/GetProduct/{id}',[ProductController::class,"GetProduct"]);
// Route::post('/UpdateProduct/{id}',[ProductController::class,"UpdateProduct"]);
// Route::delete('/Delete/{id}',[ProductController::class,"Delete"]);

