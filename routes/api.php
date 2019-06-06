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

//All pages
Route::get('/pages/{lang?}', function ($lang="en") {
    return apiPage::collection(Page::where('status', 1)->where('language', $lang)->paginate(32));
});

//Single page
Route::get('/page/{id}/{lang?}', function ($id, $lang="en") {
    return apiPage::collection(Page::where('status', 1)->where('language', $lang)->where('id', $id)->get());
});

//All News
Route::get('/news/{lang?}', function ($lang="en") {
    return apiNews::collection(News::where('status', 1)->where('language', $lang)->paginate(32));
});

//Single News
Route::get('/news/{id}/{lang?}', function ($id, $lang="en") {
    return apiNews::collection(News::where('status', 1)->where('language', $lang)->where('id', $id)->get());
});