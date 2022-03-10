<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

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

// Route::get('login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');
// AJAX
Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginSubmit'])->name('login.submit');
Route::post('/forgot_password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->name('forgot_password.submit');
Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'resetPasswordEmail'])->name('reset_password.email');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPasswordSubmit'])->name('reset_password.submit');
Route::post('/signup', [App\Http\Controllers\UserController::class, 'create'])->name('signup.submit');
Route::get('/verify-account/{token}', 'UserController@verify_user')->name('verify.email');
//email-send school AJAX
Route::post('school_email_send', [App\Http\Controllers\SchoolsController::class, 'schoolEmailSend'])->name('school_email_send.submit');
// email template  AJAX
Route::get('/template_variables', [App\Http\Controllers\EmailTemplateController::class, 'templateVariables'])->name('email.template_variables');
Route::post('/fetch_email_template', [App\Http\Controllers\EmailTemplateController::class, 'getEmailTemplate'])->name('email.fetch_email_template');
Route::post('/fetch_tc_cms_template', [App\Http\Controllers\TermCondController::class, 'getTcTemplate'])->name('tc.fetch_cms_template');



// Route::get('/permission-check', [App\Http\Controllers\AuthController::class, 'permission_check'])->name('check.permission');


Route::get('parameters', 'ParametersController@index')->name('parameters');

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

  Route::match(array('GET', 'POST'), "permission-check", array(
    'uses' => 'AuthController@permission_check',
    'as' => 'check.permission'
  ));
  // Add edit language translations from json 
  Route::get('languages', 'LanguageTranslationController@index')->name('languages');
  Route::post('translations/create', 'LanguageTranslationController@store')->name('translations.create');
  Route::post('translations/updateKey', 'LanguageTranslationController@transUpdateKey')->name('translation.update.json.key');
  Route::post('translations/update', 'LanguageTranslationController@transUpdate')->name('translation.update.json');
  Route::delete('translations/destroy/{key}', 'LanguageTranslationController@destroy')->name('translations.destroy');

  // add parameters for schools
  Route::get('/parameters/category', 'EventCategoryController@index')->name('event_category.index');
  Route::post('/add-event-category', 'EventCategoryController@addEventCategory')->name('event_category.create');
  Route::delete('/remove-event-category/{key}', 'EventCategoryController@removeEventCategory')->name('event_category.destroy');
  Route::get('/parameters/location', 'EventLocationController@index')->name('event_location.index');
  Route::post('/add-event-location', 'EventLocationController@addLocation')->name('event_location.create');
  Route::delete('/remove-event-location/{key}', 'EventLocationController@removeLocation')->name('event_location.destroy');
  Route::get('/parameters/level', 'EventLevelController@index')->name('event_level.index');
  Route::post('/add-event-level', 'EventLevelController@addLevel')->name('event_level.create');
  Route::delete('/remove-event-level/{key}', 'EventLevelController@removeLevel')->name('event_level.destroy');

  Route::prefix('admin')->group(function() {

    Route::resource('roles', "RoleController");
    Route::resource('permissions', "PermissionController");
        
    // Language 

    Route::get('/language', [App\Http\Controllers\LanguagesController::class, 'index'])->name('list.language');
    Route::post('/add-language', [App\Http\Controllers\LanguagesController::class, 'addUpdate'])->name('add.language');


    // email template 
    Route::get('/email-template', [App\Http\Controllers\EmailTemplateController::class, 'index'])->name('view.email_template');
    Route::post('/email-template', [App\Http\Controllers\EmailTemplateController::class, 'addUpdate'])->name('add.email_template');


    
    // tc template 
    Route::get('/term_cond/term_cond_cms', [App\Http\Controllers\TermCondController::class, 'index'])->name('view.term_cond_cms');
    Route::post('/term_cond/term_cond_cms', [App\Http\Controllers\TermCondController::class, 'addUpdate'])->name('add.term_cond_cms');

    // profile update
    Route::get('profile-update', 'ProfileController@userDetailUpdate');
    Route::post('profile-update', ['as' =>'profile.update','uses' =>'ProfileController@profileUpdate' ]);
    Route::post('update-profile-photo', ['as' =>'profile.update_photo','uses' =>'ProfileController@profilePhotoUpdate' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('delete-profile-photo', ['as' =>'profile.delete_photo','uses' =>'ProfileController@profilePhotoDelete' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    
    // School update
    Route::get('/schools', [App\Http\Controllers\SchoolsController::class, 'index'])->name('schools');
    Route::get('school-update/{school}', ['as' =>'school.update_by_id','uses' =>'SchoolsController@edit' ]);
  });

  // school 
  Route::get('school-update', 'SchoolsController@edit')->name('school-update');
  Route::post('school-update/{school}', ['as' =>'school.update','uses' =>'SchoolsController@update' ]);
  Route::post('school-user-update/{school}', ['as' =>'school.user_update','uses' =>'SchoolsController@userUpdate' ]);
  Route::post('update-school-logo', ['as' =>'school.update_logo','uses' =>'SchoolsController@logoUpdate' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
  Route::post('delete-school-logo', ['as' =>'school.delete_logo','uses' =>'SchoolsController@logoDelete' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
  


  Route::middleware(['select_role'])->group(function () {
    Route::get('/teachers', [App\Http\Controllers\TeachersController::class, 'index'])->name('teacherHome');
    Route::get('/teachers', [App\Http\Controllers\TeachersController::class, 'index'])->name('Home');
    Route::get('/add-teacher', [App\Http\Controllers\TeachersController::class, 'create']);
  });



});




