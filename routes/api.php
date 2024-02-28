<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user', [ApiController::class, 'user']);
    Route::post('/user/delete', [ApiController::class, 'delete']);
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::post('/favorites', [ApiController::class, 'favorites']);
});

// Login
Route::post('/login', [ApiController::class, 'login']);

// Register
Route::post('/register', [ApiController::class, 'register']);

// Pages route
Route::get('/pages/{lang?}', [ApiController::class, 'pages']);
Route::get('/page/{id}', [ApiController::class, 'page']);
Route::get('/page_view/{id}', [ApiController::class, 'pageView']);
//News items
Route::get('/news_list/{lang?}', [ApiController::class, 'newsList']);
Route::get('/news/{id}', [ApiController::class, 'news']);
Route::get('/news_view/{id}', [ApiController::class, 'newsView']);
// Useful Links
Route::get('/links/{lang?}', [ApiController::class, 'links']);
// Resources
Route::get('/resources/{lang?}', [ApiController::class, 'resources']);
Route::get('/resource/{id}', [ApiController::class, 'resource']);
Route::get('/resource_categories/{lang?}', [ApiController::class, 'resourceCategories']);
Route::get('/resource_attributes/{id}', [ApiController::class, 'resourceAttributes']);
Route::get('/resources/{lang}/{offset}', [ApiController::class, 'resourceOffset']);
Route::get('/featured_resources/{lang?}', [ApiController::class, 'featuredResources']);
Route::get('/filter_resources/{lang?}', [ApiController::class, 'filterResources']);
Route::get('/resource/getFile/{fileId}', [ApiController::class, 'getFile']);
