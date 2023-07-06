<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth Module
Route::post('login', [AuthController::class, 'login']);
Route::post('signUp', [AuthController::class, 'signUp']);

//Authenticated Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('movies', MovieController::class);
});
