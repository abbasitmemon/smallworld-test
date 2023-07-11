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
Route::post('signup', [AuthController::class, 'signup']);

//Authenticated Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);


    Route::resource('movies', MovieController::class);
    /*
    endpoints For movie Api
    1 Listing and search filter api
        Endpoint : /api/movies?search=
        Request Method : POST
    2 View full details of a perticular movie
        Endpoint : /api//movies/{episode_id}
        Request Method : GET
    3 Update Basic movie details of a perticular movie
        Endpoint : /api//movies/{episode_id}
        Request Method : PUT
    3 Delete a perticular movie
        Endpoint : /api//movies/{episode_id}
        Request Method : DELETE

    */
});
