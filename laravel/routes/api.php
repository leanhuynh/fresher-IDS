<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserControllerAPI;
use App\Http\Controllers\Api\RoleControllerAPI;
use App\Http\Controllers\Api\HotelControllerAPI;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/users')->group(function() {
    Route::get('/search', [UserControllerAPI::class, 'searchUsers'])->name('users.searchUsers');
    Route::post('/', [UserControllerAPI::class, 'store'])->name('users.store');
    Route::put('/{user}', [UserControllerAPI::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserControllerAPI::class, 'destroy'])->name('users.destroy');
});

Route::prefix('/roles')->group(function() {
    Route::get('/search', [RoleControllerAPI::class, 'searchRoles'])->name('roles.searchRoles');
    Route::put('/{role}', [RoleControllerAPI::class, 'update'])->name('roles.update');
    Route::post('/', [RoleControllerAPI::class, 'create'])->name('roles.create');
    Route::delete('/{role}', [RoleControllerAPI::class, 'destroy'])->name('roles.destroy');
});

Route::prefix('/hotels')->group(function() {
    Route::post('/', [HotelControllerAPI::class, 'create'])->name('hotels.create');
    Route::put('/{hotel}', [HotelControllerAPI::class, 'update'])->name('hotels.update');
    Route::get('/search', [HotelControllerAPI::class, 'searchHotels'])->name('hotels.search');
    Route::delete('/{hotel}', [HotelControllerAPI::class, 'destroy'])->name('hotels.destroy');
});