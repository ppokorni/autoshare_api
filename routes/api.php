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
Route::get('vehicles/search', [\App\Http\Controllers\Api\VehicleController::class, 'search']);
Route::get('vehicles/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'getById']);
Route::get('vehicles/user/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'getUserVehicles']);
Route::post('vehicles/user/{id}/delete', [\App\Http\Controllers\Api\VehicleController::class, 'destroy']);
Route::post('vehicles/create', [\App\Http\Controllers\Api\VehicleController::class, 'store']);
Route::post('vehicles/update/{id}', [\App\Http\Controllers\Api\VehicleController::class, 'update']);

Route::get('availabilities', [\App\Http\Controllers\Api\AvailabilityController::class, 'index']);
Route::post('availabilities', [\App\Http\Controllers\Api\AvailabilityController::class, 'store']);
Route::get('availabilities/{id}', [\App\Http\Controllers\Api\AvailabilityController::class, 'show']);
Route::post('availabilities/{id}', [\App\Http\Controllers\Api\AvailabilityController::class, 'update']);
Route::post('availabilities/{id}/delete', [\App\Http\Controllers\Api\AvailabilityController::class, 'destroy']);

Route::post('rent', [\App\Http\Controllers\Api\RentController::class, 'store']);
