<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\TeachersController::class, 'index']);

Route::get('login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginSubmit'])->name('login.submit');


// auth
Route::group(['middleware' => ['auth']], function () {
  Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});