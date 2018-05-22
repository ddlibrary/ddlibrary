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


    Route::get('/admin', 'DashboardController@index');

    //Users
    Route::get('admin/users', 'UserController@index');
    Route::get('admin/users/view/{userId}', 'UserController@viewUser');
    Route::get('admin/users/update/{userId}', 'UserController@updateUser');

    //Resources
    Route::get('admin/resources', 'ResourceController@index');
    Route::get('admin/resources/view/{resourceId}', 'ResourceController@viewResource');
    Route::any('resources/list', 'ResourceController@list')->name('resourceList');
    Route::get('resources/view/{resourceId}', 'ResourceController@viewPublicResource');
    Route::get('resources', 'ResourceController@latestResources');

    //Report
    Route::get('admin/reports/ddl', 'ReportController@index');
    Route::get('admin/reports/ga', 'ReportController@gaReport');

    //Pages
    Route::get('admin/pages','PageController@index');
    Route::get('admin/pages/view/{pageId}','PageController@view');
    Route::get('pages/view/{pageId}','PageController@view');

    //News
    Route::get('admin/news','NewsController@index');
    Route::get('admin/news/view{newsId}','NewsController@view');
    Route::get('news/view/{newsId}','NewsController@view');

    //Menu
    Route::get('admin/menu','MenuController@index');

    //Menu
    Route::get('admin/settings','SettingController@index');

    Auth::routes();

    Route::get('/logout', function() {
        Auth::logout();
        return redirect('/home');
    });
    Route::get('/home', 'HomeController@index')->name('home');
});

/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
