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

    Route::get('/admin', 'DashboardController@index')->middleware('admin');

    //Users
    Route::get('admin/users', 'UserController@index')->middleware('admin');
    Route::post('admin/users', 'UserController@index')->name('user')->middleware('admin');
    Route::get('admin/users/users-data', 'UserController@usersData')->middleware('admin');
    Route::get('user/{userId}', 'UserController@viewUser')->where('userId', '[0-9]+')->name('user-view');
    Route::get('admin/user/edit/{userId}', 'UserController@updateUser')->middleware('admin');

    //Resources
    Route::get('admin/resources', 'ResourceController@index')->middleware('auth');
    Route::post('admin/resources', 'ResourceController@index')->name('resources')->middleware('admin');
    Route::any('resources/list', 'ResourceController@list')->name('resourceList');
    Route::get('resource/{resourceId}', 'ResourceController@viewPublicResource');
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

    Route::get('resources/edit/step1/{resourceId}', 'ResourceController@createStepOneEdit')->name('edit1')->middleware('admin');
    Route::post('resources/edit/step1/{resourceId}', 'ResourceController@postStepOneEdit')->middleware('admin');
    Route::get('resources/edit/step2/{resourceId}', 'ResourceController@createStepTwoEdit')->name('edit2')->middleware('admin');
    Route::post('resources/edit/step2/{resourceId}', 'ResourceController@postStepTwoEdit')->middleware('admin');
    Route::get('resources/edit/step3/{resourceId}', 'ResourceController@createStepThreeEdit')->name('edit3')->middleware('admin');
    Route::post('resources/edit/step3/{resourceId}', 'ResourceController@postStepThreeEdit')->middleware('admin');

    //delete file
    Route::get('delete/file/{resourceId}/{fileName}', 'ResourceController@deleteFile')->name('delete-file')->middleware('admin');

    //Contact 
    Route::get('contact-us', 'ContactController@create');
    Route::post('contact-us', 'ContactController@store')->name('contact');

    //Report
    Route::get('admin/reports/ddl', 'ReportController@index')->middleware('admin');
    Route::get('admin/reports/ga', 'ReportController@gaReport')->middleware('admin');

    //Pages
    Route::get('admin/pages','PageController@index')->middleware('admin');
    Route::get('admin/pages/view/{pageId}','PageController@view')->middleware('admin');
    Route::get('page/{pageId}','PageController@view')->where('pageId', '[0-9]+');
    Route::get('/about-education-afghanistan', function() {
        return redirect('page/22');
    });
    Route::get('page/edit/{pageId}','PageController@edit')->middleware('admin');
    Route::post('page/update/{pageId}','PageController@update')->name('update_page')->middleware('admin');
    Route::get('page/create','PageController@create')->middleware('admin');
    Route::post('page/store','PageController@store')->name('add_page')->middleware('admin');

    //News
    Route::get('admin/news','NewsController@index')->middleware('admin');
    Route::get('news/{newsId}','NewsController@view')->where('newsId', '[0-9]+');
    Route::get('news/edit/{newsId}','NewsController@edit')->middleware('admin');
    Route::post('news/update/{newsId}','NewsController@update')->name('update_news')->middleware('admin');
    Route::get('news/create','NewsController@create')->middleware('admin');
    Route::post('news/store','NewsController@store')->name('add_news')->middleware('admin');
    Route::get('news/translate/{newsId}','NewsController@translate')->middleware('admin');

    //Menu
    Route::get('admin/menu','MenuController@index')->middleware('admin');

    //Settings
    Route::get('admin/settings','SettingController@edit')->middleware('admin');
    Route::post('admin/settings', 'SettingController@update')->name('settings');

    //Comments
    Route::get('admin/comments','CommentController@index')->middleware('admin');

    //Flags
    Route::get('admin/flags','FlagController@index')->middleware('admin');

    Auth::routes();

    //Adding old DDL routes
    Route::get('/user/register', 'Auth\RegisterController@showRegistrationForm');
    Route::get('/user', 'Auth\LoginController@showLoginForm');
    Route::get('/access-library', 'ResourceController@createStepOne')->middleware('auth');
    Route::get('/node/add', 'ResourceController@createStepOne')->middleware('auth');
    Route::get('/node/add/resourcefile', 'ResourceController@createStepOne')->middleware('auth');
    Route::get('/add/resourcefile', 'ResourceController@createStepOne')->middleware('auth');
    Route::get('/node/{resourceId}', 'ResourceController@viewPublicResource');
    Route::get('/user/logout', 'Auth\LoginController@logout');
    Route::get('/user/password', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/volunteer', function() {
        return redirect('page/1532');
    });

    Route::get('/support-library', function() {
        return redirect('page/21');
    });

    Route::get('/logout', function() {
        Auth::logout();
        return redirect('/home');
    });
    Route::get('/home', 'HomeController@index')->name('home');
});
/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
Route::post('resources/favorite', 'ResourceController@resourceFavorite');
Route::get('/storage/{resource_id}/{file_id}/{file_name}', 'FileController')->where(['file_name' => '.*']);
