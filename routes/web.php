<?php

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

// app/Http/routes.php

Route::group(
[
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
],
function()
{
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

    Route::get('/', 'HomeController@index');

    Route::get('/admin', 'DashboardController@index')->middleware('auth');

    //Users
    Route::get('admin/users', 'UserController@index')->middleware('admin');
    Route::post('admin/users', 'UserController@index')->name('user')->middleware('admin');
    Route::get('admin/users/users-data', 'UserController@usersData')->middleware('admin');
    Route::get('users/view/{userId}', 'UserController@viewUser');
    Route::get('admin/users/update/{userId}', 'UserController@updateUser')->middleware('auth');

    //Resources
    Route::get('admin/resources', 'ResourceController@index')->middleware('auth');
    Route::post('admin/resources', 'ResourceController@index')->name('resources')->middleware('auth');
    Route::any('resources/list', 'ResourceController@list')->name('resourceList');
    Route::get('resources/view/{resourceId}', 'ResourceController@viewPublicResource');
    Route::get('resources', 'ResourceController@list');
    Route::get('resources/add/step1', 'ResourceController@createStepOne')->name('step1')->middleware('auth');
    Route::post('resources/add/step1', 'ResourceController@postStepOne');
    Route::get('resources/add/step2', 'ResourceController@createStepTwo')->name('step2')->middleware('auth');
    Route::post('resources/add/step2', 'ResourceController@postStepTwo');
    Route::get('resources/add/step3', 'ResourceController@createStepThree')->name('step3')->middleware('auth');
    Route::post('resources/add/step3', 'ResourceController@postStepThree');
    Route::get('resources/attributes/{entity}', 'ResourceController@attributes');
    Route::post('resources/flag', 'ResourceController@flag')->name('flag');
    Route::post('resources/comment', 'ResourceController@comment')->name('comment')->middleware('auth');

    //Report
    Route::get('admin/reports/ddl', 'ReportController@index')->middleware('auth');
    Route::get('admin/reports/ga', 'ReportController@gaReport')->middleware('auth');

    //Pages
    Route::get('admin/pages','PageController@index')->middleware('auth');
    Route::get('admin/pages/view/{pageId}','PageController@view')->middleware('auth');
    Route::get('pages/view/{pageId}','PageController@view');

    //News
    Route::get('admin/news','NewsController@index')->middleware('auth');
    Route::get('news/view/{newsId}','NewsController@view');

    //Menu
    Route::get('admin/menu','MenuController@index')->middleware('auth');

    //Menu
    Route::get('admin/settings','SettingController@index')->middleware('auth');

    //Comments
    Route::get('admin/comments','CommentController@index')->middleware('admin');

    //Flags
    Route::get('admin/flags','FlagController@index')->middleware('admin');

    Auth::routes();

    //Adding old DDL routes
    Route::get('/user/register', 'Auth\RegisterController@showRegistrationForm');
    Route::get('/user', 'Auth\LoginController@showLoginForm');
    Route::get('/access-library', 'ResourceController@createStepOne')->name('step1')->middleware('auth');
    Route::get('/user/logout', 'Auth\LoginController@logout');
    Route::get('/password', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/volunteer', function() {
        return redirect('pages/view/1532');
    });

    Route::get('/support-library', function() {
        return redirect('pages/view/21');
    });

    Route::get('/logout', function() {
        Auth::logout();
        return redirect('/home');
    });
    Route::get('/home', 'HomeController@index')->name('home');
});
/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
Route::post('resources/favorite', 'ResourceController@resourceFavorite');
