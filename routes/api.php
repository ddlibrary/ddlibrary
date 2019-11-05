<?php

use Illuminate\Http\Request;

use App\Resource;
use App\User;
use App\News;
use App\Page;
use App\Http\Resources\Resource as apiResource;
use App\Http\Resources\User as apiUser;
use App\Http\Resources\News as apiNews;
use App\Http\Resources\Page as apiPage;

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
//All resources based on language
Route::get('/resources/{lang?}', function ($lang="en") {
    return apiResource::collection(Resource::where('status', 1)->where('language', $lang)->paginate(32));
});

//Single resource based on language
Route::get('/resource/{id}/{lang?}', function ($id, $lang="en") {
    return apiResource::collection(Resource::where('status', 1)->where('language', $lang)->where('id',$id)->get());
});

//All users
Route::get('/users', function () {
    return apiUser::collection(User::where('status', 1)->paginate(32));
});

//Single User
Route::get('/user/{id}', function ($id) {
    return apiUser::collection(User::where('status', 1)->where('id', $id)->get());
});

// #(http://localhost:8000/api/show_users)
Route::get('/show_users','UserController@listUsers'); 

// this api route storing the users information 
//#(http://localhost:8000/api/store_user)
Route::post('/store_user','UserController@insert_user');

// this api route deleting the selected user 
//#(http://localhost:8000/api/delete_user)
Route::get('/delete_user/{id}','UserController@user_delete');

// this api route reteriving the single user 
//#(http://localhost:8000/api/get_user/user_id)
Route::get('/get_user/{id}','UserController@user_get');

// this api route update the selected user information 
//#(http://localhost:8000/api/update_user/user_id)
Route::post('/update_user/{id}','UserController@user_update');

// #(http://localhost:8000/api/user/show_users)
Route::get('/user/show_users','UserController@listUsers'); 

// this api route storing the users information 
//#(http://localhost:8000/api/user/store_user)
Route::post('/user/store_user','UserController@insert_user');

// this api route deleting the selected user 
//#(http://localhost:8000/api/user/delete_user)
Route::get('/user/delete_user/{id}','UserController@user_delete');

// this api route reteriving the single user 
//#(http://localhost:8000/api/user/get_user/user_id)
Route::get('/user/get_user/{id}','UserController@user_get');

// this api route update the selected user information 
//#(http://localhost:8000/api/user/update_user/user_id)
Route::post('/user/update_user/{id}','UserController@user_update');


//All pages
Route::get('/pages/{lang?}', function ($lang="en") {
    return apiPage::collection(Page::where('status', 1)->where('language', $lang)->paginate(32));
});

//Single page
Route::get('/page/{id}/{lang?}', function ($id, $lang="en") {
    return apiPage::collection(Page::where('status', 1)->where('language', $lang)->where('id', $id)->get());
});

//This endpoint returns all news items
Route::get('/news/{lang?}', function ($lang="en") {
    return apiNews::collection(News::where('status', 1)->where('language', $lang)->paginate(32));
});

//This endpoint returns a single news item
Route::get('/news/{id}/{lang?}', function ($id, $lang="en") {
    return apiNews::collection(News::where('status', 1)->where('language', $lang)->where('id', $id)->get());
});