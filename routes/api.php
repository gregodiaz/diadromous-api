<?php

use App\Http\Controllers\Api\V1\TravelController as TravelV1;
use App\Http\Controllers\Api\V1\TicketController as TicketV1;
use App\Http\Controllers\UserTokenController;
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

Route::controller(UserTokenController::class)->group(function () {
    Route::post('register', 'createUser');
    Route::post('login', 'loginUser');
});

Route::apiResource('v1/travels', TravelV1::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('v1/travels.tickets', TicketV1::class)->except('update');
    Route::controller(TicketV1::class)->group(function () {
        Route::get('/v1/tickets', 'all');
    });
});
