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

Route::get('/', [App\Http\Controllers\AuthController::class, 'index']);
Route::get('/teachers', [App\Http\Controllers\TeachersController::class, 'index']);
Route::get('/add-teacher', [App\Http\Controllers\TeachersController::class, 'create']);

// Route::get('login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginSubmit'])->name('login.submit');


Route::post('/signup', [App\Http\Controllers\UserController::class, 'create'])->name('signup.submit');
Route::get('/verify-account/{token}', [App\Http\Controllers\UserController::class, 'verifyUser'])->name('verify.email');

// Language 
Route::get('languages', 'LanguageTranslationController@index')->name('languages');
Route::post('translations/create', 'LanguageTranslationController@store')->name('translations.create');
Route::post('translations/updateKey', 'LanguageTranslationController@transUpdateKey')->name('translation.update.json.key');
Route::post('translations/update', 'LanguageTranslationController@transUpdate')->name('translation.update.json');
Route::delete('translations/destroy/{key}', 'LanguageTranslationController@destroy')->name('translations.destroy');
Route::get('check-translation', function(){
    \App::setLocale('fr');
    dd(__('website'));
});
Route::get('setlang/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});


// auth
Route::group(['middleware' => ['auth']], function () {
  Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});