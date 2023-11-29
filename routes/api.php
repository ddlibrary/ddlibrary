<?php

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

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user', 'ApiController@user');
    Route::post('/logout', 'ApiController@logout');
    Route::post('/favorites', 'ApiController@favorites');
});

// Login
Route::post('/login', 'ApiController@login');

// Register
Route::post('/register', 'ApiController@register');

// Pages route
Route::get('/pages/{lang?}', 'ApiController@pages');
Route::get('/page/{id}', 'ApiController@page');
Route::get('/page_view/{id}', 'ApiController@pageView');
//News items
Route::get('/news_list/{lang?}', 'ApiController@newsList');
Route::get('/news/{id}', 'ApiController@news');
Route::get('/news_view/{id}', 'ApiController@newsView');
// Useful Links
Route::get('/links/{lang?}', 'ApiController@links');
// Resources
Route::get('/resources/{lang?}', 'ApiController@resources');
Route::get('/resource/{id}', 'ApiController@resource');
Route::get('/resource_categories/{lang?}', 'ApiController@resourceCategories');
Route::get('/resource_attributes/{id}', 'ApiController@resourceAttributes');
Route::get('/resources/{lang}/{offset}', 'ApiController@resourceOffset');
Route::get('/featured_resources/{lang?}', 'ApiController@featuredResources');
Route::get('/filter_resources/{lang?}', 'ApiController@filterResources');
Route::get('/resource/getFile/{fileId}', 'ApiController@getFile');
