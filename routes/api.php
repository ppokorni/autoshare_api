<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users/{user_id}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
Route::post('/users/{user_id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');

Route::post('auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::get('vehicles/list', [\App\Http\Controllers\Api\VehicleController::class, 'index']);
Route::get('vehicles/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'getById']);
Route::post('vehicles/create', [\App\Http\Controllers\Api\VehicleController::class, 'store']);
Route::post('vehicles/update/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'update']);
