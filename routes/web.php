<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ContactFormController;

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
Route::post('stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('subscription.webhook');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginSubmit'])->name('login.submit');
Route::post('/forgot_password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->name('forgot_password.submit');
Route::post('/forgot_username', [App\Http\Controllers\AuthController::class, 'forgotUsername'])->name('forgot_username.submit');

Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'resetPasswordEmail'])->name('reset_password.email');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPasswordSubmit'])->name('reset_password.submit');
Route::post('/signup', [App\Http\Controllers\UserController::class, 'create'])->name('signup.submit');
Route::get('/verify-account/{token}', 'UserController@verify_user')->name('verify.email');
Route::post('/change_first_password', [App\Http\Controllers\AuthController::class, 'changeFirstPassword'])->name('change_password.first');


// after user add verify it
Route::get('/verify-user-account/{token}', 'UserController@verify_user_added')->name('add.verify.email');
Route::post('/add-user', [App\Http\Controllers\UserController::class, 'create_verified_user'])->name('user.add')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/user_active_school', [App\Http\Controllers\UserController::class, 'active_school'])->name('user.active_school')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/retrieve-user-account/{token}', 'UserController@retrieve_user_added')->name('retrieve.verify.email');

//ADMIN
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

//Categories for faqs
Route::get('/admin/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.list')->middleware('permission:superadmin');
Route::get('/admin/categories/add', [App\Http\Controllers\CategoryController::class, 'add'])->name('categories.add')->middleware('permission:superadmin');
Route::get('/admin/categories/show/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
Route::get('/admin/categories/update/{category}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:superadmin');
Route::post('/admin/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create')->middleware('permission:superadmin');
Route::put('/admin/categories/update/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update')->middleware('permission:superadmin');
Route::delete('/admin/categories/remove/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.remove')->middleware('permission:superadmin');

//Coupons
Route::get('/admin/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupon.index');
Route::get('/admin/create-coupon', [App\Http\Controllers\CouponController::class, 'showCreateForm'])->name('coupons.create');
Route::post('/admin/create-coupon', [App\Http\Controllers\CouponController::class, 'store'])->name('coupons.store');

//Plans
Route::get('/admin/plans', [App\Http\Controllers\PlanController::class, 'index'])->name('plan.index')->middleware('permission:superadmin');
Route::get('/admin/create-plan', [App\Http\Controllers\PlanController::class, 'showCreateForm'])->name('plans.create')->middleware('permission:superadmin');
Route::post('/admin/create-plan', [App\Http\Controllers\PlanController::class, 'store'])->name('plans.store')->middleware('permission:superadmin');

//Tasks
Route::get('/admin/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('task.index');
Route::get('/admin/create-task', [App\Http\Controllers\TaskController::class, 'showCreateForm'])->name('tasks.create');
Route::post('/admin/create-task', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');

//Alerts
Route::get('/admin/alerts', [App\Http\Controllers\AlertController::class, 'index'])->name('alert.index')->middleware('permission:superadmin');
Route::get('/admin/create-alert', [App\Http\Controllers\AlertController::class, 'showCreateForm'])->name('alerts.create')->middleware('permission:superadmin');
Route::post('/admin/create-alert', [App\Http\Controllers\AlertController::class, 'store'])->name('alerts.store')->middleware('permission:superadmin');

//Contacts Form
Route::get('/admin/contacts', [ContactFormController::class, 'index'])->name('contacts.index')->middleware('permission:superadmin');

//Faqs
Route::get('/faqs-tutos', [App\Http\Controllers\FaqController::class, 'tutos'])->name('faqs.tutos'); //for users
Route::get('/faqs-tutos/show/{faq}', [App\Http\Controllers\FaqController::class, 'tutosShow'])->name('faqs.tutos.show'); //for users

//Subscriptions
Route::get('/admin/subscriptions', [App\Http\Controllers\SubscriptionController::class, 'getSubscription'])->name('subscriptions.getSubscription')->middleware('permission:superadmin'); //for users
Route::get('/admin/revenues', [App\Http\Controllers\SubscriptionController::class, 'getActiveSubscriptions'])->name('subscriptions.getActiveSubscriptions')->middleware('permission:superadmin'); //for users




//for admins
Route::get('/admin/faqs', [App\Http\Controllers\FaqController::class, 'index'])->name('faqs.list')->middleware('permission:superadmin');
Route::get('/admin/faqs/add', [App\Http\Controllers\FaqController::class, 'add'])->name('faqs.add')->middleware('permission:superadmin');
Route::get('/admin/faqs/show/{faq}', [App\Http\Controllers\FaqController::class, 'show'])->name('faqs.show');
Route::get('/admin/faqs/update/{faq}', [App\Http\Controllers\FaqController::class, 'edit'])->name('faqs.edit')->middleware('permission:superadmin');
Route::post('/admin/faqs/create', [App\Http\Controllers\FaqController::class, 'create'])->name('faqs.create')->middleware('permission:superadmin');
Route::put('/admin/faqs/update/{faq}', [App\Http\Controllers\FaqController::class, 'update'])->name('faqs.update')->middleware('permission:superadmin');
Route::delete('/admin/faqs/remove/{faq}', [App\Http\Controllers\FaqController::class, 'destroy'])->name('faqs.remove')->middleware('permission:superadmin');

Route::get('contact-form', [ContactFormController::class, 'showForm'])->name('contact.form');
Route::get('contact-staff', [ContactFormController::class, 'showFormStaff'])->name('contact.staff');
Route::post('contact-form', [ContactFormController::class, 'submitForm'])->name('contact.form.submit');

//email-send school AJAX
Route::post('school_email_send', [App\Http\Controllers\SchoolsController::class, 'schoolEmailSend'])->name('school_email_send.submit');

//email-send teacher AJAX
Route::post('teacher_email_send', [App\Http\Controllers\TeachersController::class, 'teacherEmailSend'])->name('teacher_email_send.submit');


//email-send student AJAX
Route::post('student_email_send', [App\Http\Controllers\StudentsController::class, 'studentEmailSend'])->name('student_email_send.submit');

//email-fetch pay reminder AJAX
Route::post('pay_reminder_email_fetch', [App\Http\Controllers\InvoiceController::class, 'payReminderEmailFetch'])->name('pay_reminder_email_fetch.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//email-fetch pay reminder AJAX
Route::post('pay_reminder_email', [App\Http\Controllers\InvoiceController::class, 'payReminderEmailSend'])->name('pay_reminder_email_send.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// update_payment_status AJAX
Route::post('update_payment_status', [App\Http\Controllers\InvoiceController::class, 'updatePaymentStatus'])->name('update_payment_status.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//AJAX get student lessons for invoice
Route::post('get_student_lessons', [App\Http\Controllers\InvoiceController::class, 'getStudentLessons'])->name('get_student_lessons.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//AJAX get teacher lessons for invoice
Route::post('get_teacher_lessons', [App\Http\Controllers\InvoiceController::class, 'getTeacherLessons'])->name('get_teacher_lessons.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//AJAX generate teacher invoice
Route::post('generate_teacher_invoice', [App\Http\Controllers\InvoiceController::class, 'generateTeacherInvoice'])->name('generate_teacher_invoice.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//AJAX generate student invoice
Route::post('generate_student_invoice', [App\Http\Controllers\InvoiceController::class, 'generateStudentInvoice'])->name('generate_teacher_invoice.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// update_payment_status AJAX
Route::post('update_invoice_discount', [App\Http\Controllers\InvoiceController::class, 'updateInvoiceDiscount'])->name('update_payment_status.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


// update_payment_status AJAX
Route::post('update_invoice_info', [App\Http\Controllers\InvoiceController::class, 'updateInvoiceInfo'])->name('update_invoice_info.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//AJAX update teacher discount
Route::post('teacher_update_discount_perc', [App\Http\Controllers\TeachersController::class, 'updateDiscountPerc'])->name('teacher_update_discount_perc.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


// email template  AJAX
Route::get('/template_variables', [App\Http\Controllers\EmailTemplateController::class, 'templateVariables'])->name('email.template_variables');
Route::post('/fetch_email_template', [App\Http\Controllers\EmailTemplateController::class, 'getEmailTemplate'])->name('email.fetch_email_template');
Route::post('/fetch_tc_cms_template', [App\Http\Controllers\TermCondController::class, 'getTcTemplate'])->name('tc.fetch_cms_template');


//confirm event AJAX
Route::post('confirm_event', [App\Http\Controllers\AgendaController::class, 'confirmEvent'])->name('confirm_event.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/{school}/get_event', [App\Http\Controllers\AgendaController::class, 'getEvent'])->name('event.get')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/{school}/copy_paste_events', [App\Http\Controllers\AgendaController::class, 'copyPasteEvent'])->name('event.copy_paste')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/get_event', [App\Http\Controllers\AgendaController::class, 'getEvent'])->name('event1.get')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/get_locations', [App\Http\Controllers\AgendaController::class, 'getLocations'])->name('event.get_locations')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/get_teachers', [App\Http\Controllers\AgendaController::class, 'getTeachers'])->name('event.get_teachers')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/get_event_category', [App\Http\Controllers\AgendaController::class, 'getEventCategory'])->name('event.get_event_category')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/get_school_currency', [App\Http\Controllers\AgendaController::class, 'getSchoolCurrency'])->name('event.get_school_currency')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/get_students', [App\Http\Controllers\AgendaController::class, 'getStudents'])->name('event.get_students')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/copy_paste_events', [App\Http\Controllers\AgendaController::class, 'copyPasteEvent'])->name('event1.copy_paste')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('delete_multiple_events', [App\Http\Controllers\AgendaController::class, 'deleteMultipleEvent'])->name('multiple_event.delete')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('delete_event', [App\Http\Controllers\AgendaController::class, 'deleteEvent'])->name('event.delete')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('validate_multiple_events', [App\Http\Controllers\AgendaController::class, 'validateMultipleEvent'])->name('multiple_event.validate')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('delete_invoice', [App\Http\Controllers\InvoiceController::class, 'deleteInvoice'])->name('invoice.delete')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('unlock_invoice', [App\Http\Controllers\InvoiceController::class, 'unlockInvoice'])->name('invoice.unlock')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

//
Route::post('/get_province_by_country', [App\Http\Controllers\ProvincesController::class, 'getProvinceByCountry'])->name('get_province_by_country')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Route::get('/permission-check', [App\Http\Controllers\AuthController::class, 'permission_check'])->name('check.permission');

Route::get('parameters', 'ParametersController@index')->name('parameters');

Route::get('/check-username/{username}', [App\Http\Controllers\UserController::class, 'checkUsername']);

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

  Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'welcome'])->name('Home');
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
  Route::post('/add-school-parameters', 'SchoolsController@addParameters')->name('school_parameter.create')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
  Route::get('/parameters/category', 'EventCategoryController@index')->name('event_category.index');
  Route::post('/add-event-category', 'EventCategoryController@addEventCategory')->name('event_category.create');
  Route::delete('/remove-event-category/{key}', 'EventCategoryController@removeEventCategory')->name('event_category.destroy');
  Route::get('/parameters/location', 'EventLocationController@index')->name('event_location.index');
  Route::post('/add-event-location', 'EventLocationController@addLocation')->name('event_location.create');
  Route::delete('/remove-event-location/{key}', 'EventLocationController@removeLocation')->name('event_location.destroy');
  Route::get('/parameters/level', 'EventLevelController@index')->name('event_level.index');
  Route::post('/add-event-level', 'EventLevelController@addLevel')->name('event_level.create');
  Route::delete('/remove-event-level/{key}', 'EventLevelController@removeLevel')->name('event_level.destroy');

  //taxes
  Route::delete('/remove-event-tax/{key}', 'TeachersController@removeTax')->name('event_taxes.destroy');


  // payment routes
  Route::get('/subscriber-list', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriber_list');

  Route::get('/subscription/upgrade-plan', [App\Http\Controllers\SubscriptionController::class, 'upgradePlan'])->name('subscription.upgradePlan');
  Route::get('/subscription/plan-list', [App\Http\Controllers\SubscriptionController::class, 'subscribePlanList'])->name('subscription.list');
  Route::get('/subscribe/{plan_id}', [App\Http\Controllers\SubscriptionController::class, 'supscribePlan'])->name('subscribe.plan');
  Route::post('/subscribe/store', [App\Http\Controllers\SubscriptionController::class, 'supscribePlanStore'])->name('subscribe.store');

  Route::get('/subscription/upgrade-new-plan', [App\Http\Controllers\SubscriptionController::class, 'upgradeNewPlan'])->name('subscribe.upgradeNewPlan');
  Route::post('/subscribe/upgrade-plan-store', [App\Http\Controllers\SubscriptionController::class, 'storeUpgradePlan'])->name('subscribe.storeUpgradePlan');

  Route::get('single-invoice-payment/{payment_id}', [App\Http\Controllers\SubscriptionController::class, 'singlePayment'])->name('subscription.payment');
  Route::post('single-subscription/{payment_id}', [App\Http\Controllers\SubscriptionController::class, 'storeSinglePayment'])->name('subscription.singleCharge');

  Route::get('/congratulations', [App\Http\Controllers\SubscriptionController::class, 'mySubscription'])->name('mySubscription.congratulations');

  Route::get('/subscription/cancel-plan', [App\Http\Controllers\SubscriptionController::class, 'cancelPlan'])->name('subscription.cancelPlan');

    Route::get('/user/disable', [App\Http\Controllers\UserController::class, 'disable_user'])->name('user.disable_user');
    Route::post('/deactivate_user', [App\Http\Controllers\UserController::class, 'deactivate'])->name('user.deactivate');

  Route::prefix('admin')->group(function() {

    Route::resource('roles', "RoleController");
    Route::resource('permissions', "PermissionController");

    // ical personal events
    Route::post('/{school}/icalendar/personnel-events', [App\Http\Controllers\AgendaController::class, 'icalPersonalEvents'])->name('ical.personalEventss')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/icalendar/personnel-events', [App\Http\Controllers\AgendaController::class, 'icalPersonalEvents'])->name('ical.personalEvents');


    // Language
    Route::get('/language', [App\Http\Controllers\LanguagesController::class, 'index'])->name('list.language');
    Route::post('/add-language', [App\Http\Controllers\LanguagesController::class, 'addUpdate'])->name('add.language');

    // currency
    Route::get('/currency', [App\Http\Controllers\CurrencyController::class, 'index'])->name('list.currency');
    Route::post('/add-currency', [App\Http\Controllers\CurrencyController::class, 'addUpdate'])->name('add.currency');


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
    Route::get('/schools', [App\Http\Controllers\SchoolsController::class, 'index'])->name('schools')->middleware('permission:superadmin');
    Route::get('school-update/{school}', ['as' =>'school.update_by_id','uses' =>'SchoolsController@edit' ]);


    // add parameters for schools
    Route::get('/{school}/parameters/category', 'EventCategoryController@index')->name('admin_event_category.index');
    Route::post('/add-event-category', 'EventCategoryController@addEventCategory')->name('admin_event_category.create');
    Route::get('/{school}/parameters/location', 'EventLocationController@index')->name('admin_event_location.index');
    Route::post('/add-event-location', 'EventLocationController@addLocation')->name('admin_event_location.create');
    Route::get('/{school}/parameters/level', 'EventLevelController@index')->name('admin_event_level.index');
    Route::post('/add-event-level', 'EventLevelController@addLevel')->name('admin_event_level.create');

    // Teachers
    Route::get('/{school}/teachers', [App\Http\Controllers\TeachersController::class, 'index'])->name('adminTeachers');
    Route::match(array('GET', 'POST'), "/{school}/add-teacher", array(
      'uses' => 'TeachersController@create',
      'as' => 'admin.teachers.create'
    ));
    Route::get('/{school}/edit-teacher/{teacher}', [App\Http\Controllers\TeachersController::class, 'edit'])->name('adminEditTeacher');
    Route::match(array('GET', 'POST'), "/{school}/export-teacher", array(
        'uses' => 'TeachersController@exportExcel',
        'as' => 'admin.teacher.export'
    ));
    Route::match(array('GET', 'POST'), "/{school}/import-teacher", array(
        'uses' => 'TeachersController@importExcel',
        'as' => 'admin.teacher.import'
    ))->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    // Students
    Route::get('/{school}/students', [App\Http\Controllers\StudentsController::class, 'index'])->name('adminStudents');
    Route::match(array('GET', 'POST'), "/{school}/add-student", array(
      'uses' => 'StudentsController@create',
      'as' => 'admin.student.create'
    ));
    Route::match(array('GET', 'POST'), "/{school}/export-student", array(
      'uses' => 'StudentsController@exportExcel',
      'as' => 'admin.student.export'
    ));
    Route::match(array('GET', 'POST'), "/{school}/import-student", array(
      'uses' => 'StudentsController@importExcel',
      'as' => 'admin.student.import'
    ))->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/{school}/edit-student/{student}', [App\Http\Controllers\StudentsController::class, 'edit'])->name('adminEditStudent');

    Route::get('/{school}/add-event', [App\Http\Controllers\LessonsController::class, 'addEvent'])->name('event.create');
    Route::post('/{school}/add-event', [App\Http\Controllers\LessonsController::class, 'addEventAction'])->name('event.createAction');
    Route::get('/{school}/edit-event/{event}', [App\Http\Controllers\LessonsController::class, 'editEvent'])->name('event.edit');
    Route::post('/{school}/edit-event/{event}', [App\Http\Controllers\LessonsController::class, 'editEventAction'])->name('event.editAction');
    Route::get('/{school}/view-event/{event}', [App\Http\Controllers\LessonsController::class, 'viewEvent'])->name('event.view');
    Route::get('/{school}/add-lesson', [App\Http\Controllers\LessonsController::class, 'addLesson'])->name('lesson.create');
    Route::post('/{school}/add-lesson', [App\Http\Controllers\LessonsController::class, 'addLessonAction'])->name('lesson.createAction');
    Route::get('/{school}/edit-lesson/{lesson}', [App\Http\Controllers\LessonsController::class, 'editLesson'])->name('lesson.edit');
    Route::post('/{school}/edit-lesson/{lesson}', [App\Http\Controllers\LessonsController::class, 'editLessonAction'])->name('lesson.editAction');
    Route::get('/{school}/view-lesson/{lesson}', [App\Http\Controllers\LessonsController::class, 'viewLesson'])->name('lesson.view');
    Route::get('/{school}/student-off', [App\Http\Controllers\LessonsController::class, 'studentOff'])->name('studentOff.create');
    Route::post('/{school}/student-off', [App\Http\Controllers\LessonsController::class, 'studentOffAction'])->name('studentOff.createAction');
    Route::get('/{school}/edit-student-off/{id}', [App\Http\Controllers\LessonsController::class, 'editStudentOff'])->name('studentOff.edit');
    Route::post('/{school}/edit-student-off/{id}', [App\Http\Controllers\LessonsController::class, 'editStudentOffAction'])->name('studentOff.editAction');
    Route::get('/{school}/view-student-off/{id}', [App\Http\Controllers\LessonsController::class, 'viewStudentOff'])->name('studentOff.view');
    Route::get('/{school}/coach-off', [App\Http\Controllers\LessonsController::class, 'coachOff'])->name('coachOff.create');
    Route::post('/{school}/coach-off', [App\Http\Controllers\LessonsController::class, 'coachOffAction'])->name('coachOff.createAction');
    Route::get('/{school}/edit-coach-off/{id}', [App\Http\Controllers\LessonsController::class, 'editCoachOff'])->name('coachOff.edit');
    Route::post('/{school}/edit-coach-off/{id}', [App\Http\Controllers\LessonsController::class, 'editCoachOffAction'])->name('coachOff.editAction');
    Route::get('/{school}/view-coach-off/{id}', [App\Http\Controllers\LessonsController::class, 'viewCoachOff'])->name('coachOff.view');


    // Invoice
    Route::get('/invoices', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoiceList');
    Route::get('/{school}/report', [App\Http\Controllers\InvoiceController::class, 'report'])->name('invoiceReport');
    Route::post('report', [App\Http\Controllers\InvoiceController::class, 'getReport'])->name('getReport')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/{school}/invoices/{type?}', [App\Http\Controllers\InvoiceController::class, 'index'])->name('adminInvoiceList');
    Route::get('/{school}/student-invoices/{type?}', [App\Http\Controllers\InvoiceController::class, 'student_invoice_list'])->name('studentInvoiceList.id')->middleware('checkStripeSubscription');
    Route::get('/{school}/teacher-invoices/{type?}', [App\Http\Controllers\InvoiceController::class, 'teacher_invoice_list'])->name('teacherInvoiceList.id');
    Route::get('/invoice/{invoice}', [App\Http\Controllers\InvoiceController::class, 'view'])->name('invoice.view');
    Route::get('/{school}/modification-invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'modificationInvoice'])->name('adminmodificationInvoice');
    Route::get('/modification-invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'modificationInvoice'])->name('modificationInvoice');
    Route::get('/manual-invoice', [App\Http\Controllers\InvoiceController::class, 'manualInvoice'])->name('manualInvoice');
    Route::get('/{school}/manual-invoice', [App\Http\Controllers\InvoiceController::class, 'manualInvoice'])->name('adminmanualInvoice');
    Route::get('/{school}/manual-invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'updatemanualInvoice'])->name('adminupdatemanualInvoice');
    Route::post('/getAbsentStudent', [App\Http\Controllers\AgendaController::class, 'getAbsentStudent'])->name('agenda.getAbsentStudent')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

  }); //Admin scope end



  // school
  Route::get('school-update', 'SchoolsController@edit')->name('school-update');
  Route::post('school-update/{school}', ['as' =>'school.update','uses' =>'SchoolsController@update' ]);
  Route::post('school-user-update/{school}', ['as' =>'school.user_update','uses' =>'SchoolsController@userUpdate' ]);
  Route::post('update-school-logo', ['as' =>'school.update_logo','uses' =>'SchoolsController@logoUpdate' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
  Route::post('delete-school-logo', ['as' =>'school.delete_logo','uses' =>'SchoolsController@logoDelete' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);



  Route::middleware(['select_role'])->group(function () {

    Route::get('/agenda', [App\Http\Controllers\AgendaController::class, 'index'])->name('agenda');
    Route::get('/{school}/agenda', [App\Http\Controllers\AgendaController::class, 'index'])->name('agenda.id');

    Route::get('/agenda-v2', [App\Http\Controllers\AgendaController::class, 'calendar'])->name('agenda.v2');

    Route::get('/teachers', [App\Http\Controllers\TeachersController::class, 'index'])->name('teacherHome');
    Route::get('/add-teacher', [App\Http\Controllers\TeachersController::class, 'create']);
    Route::match(array('GET', 'POST'), "add-teacher", array(
      'uses' => 'TeachersController@create',
      'as' => 'teachers.create'
    ));
    Route::match(array('GET', 'POST'), "export-teacher", array(
        'uses' => 'TeachersController@exportExcel',
        'as' => 'teacher.export'
    ));
    Route::match(array('GET', 'POST'), "import-teacher", array(
        'uses' => 'TeachersController@importExcel',
        'as' => 'teacher.import'
    ))->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/edit-teacher/{teacher}', [App\Http\Controllers\TeachersController::class, 'edit'])->name('editTeacher');
    Route::post('/edit-teacher/{teacher}', [App\Http\Controllers\TeachersController::class, 'update'])->name('editTeacherAction');
    Route::delete('/{school}/teacher/{teacher}', [App\Http\Controllers\TeachersController::class, 'destroy'])->name('teacherDelete');

    Route::post('/{school}/teacher/{teacher}', [App\Http\Controllers\TeachersController::class, 'changeStatus'])->name('teacherStatus');
    Route::post('/{school}/teacher_email_send/{teacher}', [App\Http\Controllers\TeachersController::class, 'teacherInvitation'])->name('teacherInvitation');


    Route::get('/account', [App\Http\Controllers\TeachersController::class, 'self_edit'])->name('updateTeacher');
    Route::post('/update-teacher', [App\Http\Controllers\TeachersController::class, 'self_update'])->name('updateTeacherAction');

    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'] )->name('calendar.settings');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'store'])->name('calendar.settings.store');

    Route::get('/account/subscription', [App\Http\Controllers\TeachersController::class, 'self_edit'])->name('profile.plan');


    //AJAX action
    Route::post('/{school}/add-teacher-action', [App\Http\Controllers\TeachersController::class, 'AddTeacher'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('/update-price/{teacher}', [App\Http\Controllers\TeachersController::class, 'priceUpdate'])->name('updatePriceAction');
    Route::post('/self-update-price', [App\Http\Controllers\TeachersController::class, 'selfPriceUpdate'])->name('selfUpdatePriceAction');
    Route::post('/self-update-taxe', [App\Http\Controllers\TeachersController::class, 'selfTaxeUpdate'])->name('selfUpdateTaxeAction');

    Route::post('update-teacher-photo', ['as' =>'teacher.update_photo','uses' =>'TeachersController@profilePhotoUpdate' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('delete-teacher-photo', ['as' =>'teacher.delete_photo','uses' =>'TeachersController@profilePhotoDelete' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('teacher-user-update/{user}', ['as' =>'teacher.user_update','uses' =>'TeachersController@userUpdate' ]);


    // Route::post('/{school}/add-student-action', [App\Http\Controllers\TeachersController::class, 'AddTeacher'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
  });
    Route::get('/update-student', [App\Http\Controllers\StudentsController::class, 'self_edit'])->name('updateStudent');
    Route::post('/update-student', [App\Http\Controllers\StudentsController::class, 'self_update'])->name('updateStudentAction');


    Route::get('/students', [App\Http\Controllers\StudentsController::class, 'index'])->name('studentHome');
    Route::post('/add-student-action', [App\Http\Controllers\StudentsController::class, 'AddStudent'])->name('student.createAction');
    Route::get('/edit-student/{student}', [App\Http\Controllers\StudentsController::class, 'edit'])->name('editStudent');
    Route::post('/edit-student/{student}', [App\Http\Controllers\StudentsController::class, 'editStudentAction'])->name('editStudentAction');
    Route::match(array('GET', 'POST'), "add-student", array(
      'uses' => 'StudentsController@create',
      'as' => 'student.create'
    ));
    Route::match(array('GET', 'POST'), "export-student", array(
      'uses' => 'StudentsController@exportExcel',
      'as' => 'student.export'
    ));
    Route::match(array('GET', 'POST'), "import-student", array(
      'uses' => 'StudentsController@importExcel',
      'as' => 'student.import'
    ))->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::delete('/{school}/student/{student}', [App\Http\Controllers\StudentsController::class, 'destroy'])->name('studentDelete');
    Route::delete('/{school}/student/{student}', [App\Http\Controllers\StudentsController::class, 'destroyStudent'])->name('studentDeleteDestroy');


    Route::post('/{school}/student/{student}', [App\Http\Controllers\StudentsController::class, 'changeStatus'])->name('studentStatus');
    Route::post('/{school}/student_email_send/{student}', [App\Http\Controllers\StudentsController::class, 'studentInvitation'])->name('studentInvitation');
    Route::get('/{school}/student_email_send/{student}', [App\Http\Controllers\StudentsController::class, 'studentInvitationGet'])->name('studentInvitationGet');
    Route::get('/{school}/student_password_send/{student}', [App\Http\Controllers\StudentsController::class, 'studentPasswordGet'])->name('studentPasswordGet');

    // Route::post('update-student-photo', ['as' =>'student.update_photo','uses' =>'StudentsController@profilePhotoUpdate' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    // Route::post('delete-student-photo', ['as' =>'student.delete_photo','uses' =>'StudentsController@profilePhotoDelete' ])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('student-user-update/{user}', ['as' =>'student.user_update','uses' =>'StudentsController@userUpdate' ]);
    Route::post('/students/delete', [App\Http\Controllers\StudentsController::class, 'delete'])->name('students.delete');

    Route::get('/agenda', [App\Http\Controllers\AgendaController::class, 'index'])->name('agenda');
    Route::get('/{school}/agenda', [App\Http\Controllers\AgendaController::class, 'index'])->name('agenda.id');

    Route::get('/{school}/add-event', [App\Http\Controllers\LessonsController::class, 'addEvent'])->name('event.create');
    Route::post('/{school}/add-event', [App\Http\Controllers\LessonsController::class, 'addEventAction'])->name('event.createAction');
    Route::get('/{school}/edit-event/{event}', [App\Http\Controllers\LessonsController::class, 'editEvent'])->name('event.edit');
    Route::post('/{school}/edit-event/{event}', [App\Http\Controllers\LessonsController::class, 'editEventAction'])->name('event.editAction');
    Route::get('/{school}/view-event/{event}', [App\Http\Controllers\LessonsController::class, 'viewEvent'])->name('event.view');
    Route::get('/{school}/add-lesson', [App\Http\Controllers\LessonsController::class, 'addLesson'])->name('lesson.create');
    Route::post('/{school}/add-lesson', [App\Http\Controllers\LessonsController::class, 'addLessonAction'])->name('lesson.createAction');
    Route::get('/{school}/edit-lesson/{lesson}', [App\Http\Controllers\LessonsController::class, 'editLesson'])->name('lesson.edit');
    Route::post('/{school}/edit-lesson/{lesson}', [App\Http\Controllers\LessonsController::class, 'editLessonAction'])->name('lesson.editAction');
    Route::get('/{school}/view-lesson/{lesson}', [App\Http\Controllers\LessonsController::class, 'viewLesson'])->name('lesson.view');
    Route::get('/{school}/student-off', [App\Http\Controllers\LessonsController::class, 'studentOff'])->name('studentOff.create');
    Route::post('/{school}/student-off', [App\Http\Controllers\LessonsController::class, 'studentOffAction'])->name('studentOff.createAction');
    Route::get('/{school}/view-student-off/{id}', [App\Http\Controllers\LessonsController::class, 'viewStudentOff'])->name('studentOff.view');
    Route::get('/{school}/edit-student-off/{id}', [App\Http\Controllers\LessonsController::class, 'editStudentOff'])->name('studentOff.edit');
    Route::post('/{school}/edit-student-off/{id}', [App\Http\Controllers\LessonsController::class, 'editStudentOffAction'])->name('studentOff.editAction');
    Route::get('/{school}/coach-off', [App\Http\Controllers\LessonsController::class, 'coachOff'])->name('coachOff.create');
    Route::post('/{school}/coach-off', [App\Http\Controllers\LessonsController::class, 'coachOffAction'])->name('coachOff.createAction');
    Route::get('/{school}/edit-coach-off/{id}', [App\Http\Controllers\LessonsController::class, 'editCoachOff'])->name('coachOff.edit');
    Route::post('/{school}/edit-coach-off/{id}', [App\Http\Controllers\LessonsController::class, 'editCoachOffAction'])->name('coachOff.editAction');
    Route::get('/{school}/view-coach-off/{id}', [App\Http\Controllers\LessonsController::class, 'viewCoachOff'])->name('coachOff.view');
    Route::post('/{school}/student-attend-action/{id}', [App\Http\Controllers\LessonsController::class, 'StudentAttendAction'])->name('studentAttend.Action');
    Route::post('check-lesson-price', 'LessonsController@lessonPriceCheck')->name('lessonPriceCheck');
     Route::post('check-lesson-fixed-price', 'LessonsController@lessonFixedPrice')->name('lessonFixedPrice');
    Route::get('invoice', 'InvoiceController@view')->name('invoice');
    Route::post('invoice_data', 'InvoiceController@invoiceData')->name('invoiceData');
    Route::post('/{school}/store_invoice_data', 'InvoiceController@invoiceDataSave')->name('invoiceDataSave');
    Route::post('/{school}/update_invoice_data', 'InvoiceController@invoiceDataUpdate')->name('invoiceDataUpdate');

    Route::get('/invoice-pdf', [App\Http\Controllers\InvoiceController::class, 'generateInvoicePDF'])->name('generateInvoicePDF');
    Route::get('/report-pdf', [App\Http\Controllers\InvoiceController::class, 'generateReportPDF'])->name('generateReportPDF');

});

