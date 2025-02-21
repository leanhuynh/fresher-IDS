<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('hotels.index');
    // return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/users')->middleware('auth')->group(function() {
    Route::get('/', [UserController::class, 'index'])->name('users.index')->middleware('admin');
    Route::get('/create', [UserController::class, 'createUser'])->name('users.createUser')->middleware('admin');
    Route::get('/profile', [UserController::class, 'viewUserProfileById'])->name('users.getProfile');
    Route::get('/edit/{user}', [UserController::class, 'editUserProfileById'])->name('users.editProfile')->middleware('admin');
});

Route::prefix('/hotels')->middleware('auth')->group(function() {
    Route::get('/create', [HotelController::class, 'createHotel'])->name('hotels.createHotel');
    Route::get('/', [HotelController::class, 'index'])->name('hotels.index');
    Route::get('/view/{hotel}', [HotelController::class, 'viewHotel'])->name('hotels.viewHotel')->middleware('hotel.owner');
    Route::get('/edit/{hotel}', [HotelController::class, 'editHotel'])->name('hotels.editHotel')->middleware('hotel.owner');
});

Route::prefix('/roles')->middleware(['auth', 'admin'])->group(function() {
    Route::get('/', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/create', [RoleController::class, 'createRole'])->name('roles.createRole');
    Route::get('/edit/{role}', [RoleController::class, 'editRole'])->name('roles.editRole');
});