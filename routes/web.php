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
use Laravel\Socialite\Facades\Socialite;
use Spatie\Honeypot\ProtectAgainstSpam;

if (env('APP_ENV') === 'production') {
    \URL::forceScheme('https');
}

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

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
    Route::get('user/profile', 'UserController@viewUser')->where('userId', '[0-9]+')->name('user-view');
    Route::get('user/favorites', 'UserController@favorites')->where('userId', '[0-9]+')->name('user-favorites');
    Route::get('user/uploaded-resources', 'UserController@uploadedResources')->where('userId', '[0-9]+')->name('user-uploaded-resources');
    Route::post('user/update_profile', 'UserController@updateProfile')->name('user-profile-update');
    Route::get('admin/user/edit/{userId}', 'UserController@edit')->name('edit_user')->middleware('admin');
    Route::post('admin/user/update/{userId}', 'UserController@update')->name('update_user')->middleware('admin');
    Route::get('admin/user/delete/{userId}', 'UserController@deleteUser')->middleware('admin');
    Route::get('admin/user/export', 'UserController@exportUsers')->middleware('admin');
    //Resources
    Route::get('admin/resources', 'ResourceController@index')->middleware('auth');
    Route::post('admin/resources', 'ResourceController@index')->name('resources')->middleware('admin');
    Route::get('resources/list', 'ResourceController@list')->name('resourceList');
    Route::get('resources/filter', 'ResourceController@resourceFilter')->name('resourceFilter');
    Route::get('resources/filter/subject', 'ResourceController@getSubjectChildren');
    Route::get('resource/{resourceId}', 'ResourceController@viewPublicResource');
    Route::get('resource/no-show/{state}', function ($state) {
        return View::make('resources.resources_view_no_show', compact('state'));
    });
    Route::get('resource/{resourceId}/download/{fileId}', 'ResourceController@downloadFile')->name('download-file')->middleware('auth')->middleware('verified');
    Route::get('resources', 'ResourceController@list');
    Route::get('resources/add/step1', 'ResourceController@createStepOne')->name('step1')->middleware('auth')->middleware('verified');
    Route::post('resources/add/step1', 'ResourceController@postStepOne');
    Route::get('resources/add/step2', 'ResourceController@createStepTwo')->name('step2')->middleware('auth')->middleware('verified');
    Route::post('resources/add/step2', 'ResourceController@postStepTwo');
    Route::get('resources/add/step3', 'ResourceController@createStepThree')->name('step3')->middleware('auth')->middleware('verified');
    Route::post('resources/add/step3', 'ResourceController@postStepThree')->middleware(ProtectAgainstSpam::class);
    Route::get('resources/attributes/{entity}', 'ResourceController@attributes');
    Route::post('resources/flag', 'ResourceController@flag')->name('flag');
    Route::post('resources/comment', 'ResourceController@comment')->name('comment')->middleware('auth')->middleware('verified');
    Route::get('admin/resource/published/{resourceId}', 'ResourceController@published');
    Route::get('admin/resource/delete/{resourceId}', 'ResourceController@deleteResource')->middleware('admin');
    Route::get('resources/edit/step1/{resourceId}', 'ResourceController@createStepOneEdit')->name('edit1')->middleware('LibraryManager');
    Route::post('resources/edit/step1/{resourceId}', 'ResourceController@postStepOneEdit')->middleware('LibraryManager');
    Route::get('resources/edit/step2/{resourceId}', 'ResourceController@createStepTwoEdit')->name('edit2')->middleware('LibraryManager');
    Route::post('resources/edit/step2/{resourceId}', 'ResourceController@postStepTwoEdit')->middleware('LibraryManager');
    Route::get('resources/edit/step3/{resourceId}', 'ResourceController@createStepThreeEdit')->name('edit3')->middleware('LibraryManager');
    Route::post('resources/edit/step3/{resourceId}', 'ResourceController@postStepThreeEdit')->middleware('LibraryManager');
    Route::post('resource/{resourceId}', 'ResourceController@updateTid')->middleware('admin')->name('updatetid');
    //delete file
    Route::get('delete/file/{resourceId}/{fileName}', 'ResourceController@deleteFile')->name('delete-file');
    //Contact 
    Route::get('contact-us', 'ContactController@create');
    Route::post('contact-us', 'ContactController@store')->name('contact')->middleware(ProtectAgainstSpam::class);
    Route::get('admin/contacts', 'ContactController@index')->middleware('admin');
    Route::get('admin/contacts/read/{id}', 'ContactController@read')->middleware('admin');
    Route::get('admin/contacts/delete/{id}', 'ContactController@delete')->middleware('admin');
    //Report
    Route::get('admin/reports/ga', 'ReportController@gaReport')->middleware('admin');
    //Downloads
    Route::get('admin/reports/downloads','DownloadController@index')->middleware('admin');
    Route::post('admin/reports/downloads','DownloadController@index')->name('downloads')->middleware('admin');
    //Pages
    Route::get('admin/pages','PageController@index')->middleware('admin');
    Route::get('admin/get-pages','PageController@getPages')->name('getpages')->middleware('admin');
    Route::get('admin/pages/view/{pageId}','PageController@view')->middleware('admin');
    Route::get('page/{pageId}','PageController@view')->where('pageId', '[0-9]+');
    Route::get('/about-education-afghanistan', function() {
        return redirect('page/22');
    });
    Route::get('page/edit/{pageId}','PageController@edit')->middleware('admin');
    Route::post('page/update/{pageId}','PageController@update')->name('update_page')->middleware('admin');
    Route::get('page/create','PageController@create')->middleware('admin');
    Route::post('page/store','PageController@store')->name('add_page')->middleware('admin');
    Route::get('page/translate/{pageId}/{pageTnid}','PageController@translate')->middleware('admin');
    Route::get('page/add/translate/{pageId}/{lang}','PageController@addTranslate')->middleware('admin');
    Route::post('page/add/translate/{pageId}/{lang}','PageController@addPostTranslate')->name('add_page_translate')->middleware('admin');
    //News
    Route::get('admin/news','NewsController@index')->middleware('admin');    
    Route::get('admin/get-news','NewsController@getNews')->name('getnews')->middleware('admin');
    Route::get('news/{newsId}','NewsController@view')->where('newsId', '[0-9]+');
    Route::get('news/edit/{newsId}','NewsController@edit')->middleware('admin');
    Route::post('news/update/{newsId}','NewsController@update')->name('update_news')->middleware('admin');
    Route::get('news/create','NewsController@create')->middleware('admin');
    Route::post('news/store','NewsController@store')->name('add_news')->middleware('admin');
    Route::get('news/translate/{newsId}/{newsTnid}','NewsController@translate')->middleware('admin');
    Route::get('news/add/translate/{newsId}/{lang}','NewsController@addTranslate')->middleware('admin');
    Route::post('news/add/translate/{newsId}/{lang}','NewsController@addPostTranslate')->name('add_news_translate')->middleware('admin');
    //Menu
    Route::get('admin/menu','MenuController@index')->middleware('admin');
    Route::post('admin/menu','MenuController@index')->middleware('admin')->name('menulist');
    Route::get('admin/menu/add/{menuId}','MenuController@create')->middleware('admin');
    Route::post('admin/menu/store','MenuController@store')->name('store_menu')->middleware('admin');
    Route::get('admin/menu/edit/{menuId}','MenuController@edit')->middleware('admin');
    Route::post('admin/menu/update/{menuId}','MenuController@update')->name('update_menu')->middleware('admin');
    Route::get('admin/menu/translate/{menuId}','MenuController@translate')->middleware('admin');
    Route::post('admin/menu/translate/{menuId}','MenuController@translate_menu')->name('translateMenu')->middleware('admin');
    Route::get('admin/menu/sort','MenuController@sort')->name('sort_menu')->middleware('admin');    
    Route::get('admin/menu/ajax_get_parents','MenuController@ajax_get_parents')->name('ajax_get_parents')->middleware('admin');
    //Settings
    Route::get('admin/settings','SettingController@edit')->middleware('admin');
    Route::post('admin/settings', 'SettingController@update')->name('settings');
    //Comments
    Route::get('admin/comments','CommentController@index')->middleware('admin');
    Route::get('admin/comments/delete/{commentId}','CommentController@delete')->middleware('admin');
    Route::get('admin/comments/published/{commentId}', 'CommentController@published');
    //Flags
    Route::get('admin/flags','FlagController@index')->middleware('admin');
    //Taxonomy
    Route::get('admin/taxonomy','TaxonomyController@index')->name('taxonomylist')->middleware('admin');
    Route::post('admin/taxonomy','TaxonomyController@index')->name('taxonomylist')->middleware('admin');
    Route::get('admin/taxonomy/edit/{vid}/{tid}','TaxonomyController@edit')->name('taxonomyedit')->middleware('admin');
    Route::post('admin/taxonomy/edit/{vid}/{tid}','TaxonomyController@update')->name('taxonomyedit')->middleware('admin');
    Route::get('admin/taxonomy/translate/{tid}','TaxonomyController@translate')->middleware('admin');
    Route::get('admin/taxonomy/create','TaxonomyController@create')->name('taxonomycreate')->middleware('admin');
    Route::post('admin/taxonomy/store','TaxonomyController@store')->name('taxonomystore')->middleware('admin');
    Route::get('admin/taxonomy/create-translate/{tid}/{tnid}/{lang}','TaxonomyController@createTranslate')->name('taxonomytranslatecreate')->middleware('admin');
    Route::post('admin/taxonomy/store-translate/{tnid}','TaxonomyController@storeTranslate')->name('taxonomytranslatestore')->middleware('admin');
    //Taxonomy Vocabulary
    Route::get('admin/vocabulary','VocabularyController@index')->name('vocabularylist')->middleware('admin');    
    Route::get('admin/vocabularies','VocabularyController@getVocabularies')->name('getvocabularies')->middleware('admin');
    Route::get('admin/vocabulary/create','VocabularyController@create')->name('vocabularycreate')->middleware('admin');
    Route::post('admin/vocabulary/store','VocabularyController@store')->name('vocabularystore')->middleware('admin');
    Route::get('admin/vocabulary/edit/{vid}','VocabularyController@edit')->name('vocabularyedit')->middleware('admin');
    Route::post('admin/vocabulary/edit/{vid}','VocabularyController@update')->name('vocabularyedit')->middleware('admin');
    //Sync
    Route::get('/admin/sync', 'SyncController@index');
    Route::get('/admin/run_sync', 'SyncController@SyncIt');
    //Glossary
    Route::get('glossary','GlossaryController@index')->middleware('auth')->middleware('verified');
    Route::post('glossary','GlossaryController@index')->name('glossary');
    Route::get('glossary/create', 'GlossaryController@create')->name('glossary_create')->middleware('LibraryManager');
    Route::post('glossary/store', 'GlossaryController@store')->name('glossary_store')->middleware('LibraryManager');
    Route::post('glossary/update', 'GlossaryController@update')->name('glossary_update')->middleware('LibraryManager');
    Route::post('glossary/delete/{id}', 'GlossaryController@destroy')->name('glossary_delete')->middleware('LibraryManager');
    Route::post('glossary/approve/{id}', 'GlossaryController@approve')->name('glossary_approve')->middleware('LibraryManager');
    //Impact Page
    Route::get('/impact','ImpactController@index');
    
    //admin, survey
    Route::get('admin/surveys','SurveyController@index');
    Route::get('admin/survey/edit/{id}','SurveyController@edit');
    Route::get('admin/survey/view/{id}/{tnid}','SurveyController@view');
    Route::get('admin/survey/report/{id}','SurveyController@report')->middleware('admin');
    Route::get('admin/survey/add/translate/{id}/{lang}','SurveyController@addTranslate');
    Route::get('admin/survey/create','SurveyController@create');
    Route::get('admin/survey/delete/{id}','SurveyController@delete');
    Route::post('admin/update_survey/{id}','SurveyController@update')->name('update_survey');
    Route::post('admin/survey/create','SurveyController@store')->name('create_survey');
    //question
    Route::get('admin/survey/questions/{id}','SurveyQuestionController@index');
    Route::get('admin/survey/{surveyid}/question/view/{id}/{tnid}','SurveyQuestionController@view');
    Route::get('admin/survey/question/add/translate/{id}/{lang}','SurveyQuestionController@addTranslate');
    Route::post('admin/survey/question/add','SurveyQuestionController@store')->name('create_question');
    Route::get('admin/survey/question/add/{id}','SurveyQuestionController@create');
    Route::get('admin/survey/question/delete/{id}','SurveyQuestionController@delete');
    //option
    Route::get('admin/survey/question/option/delete/{id}','SurveyQuestionOptionController@delete');
    Route::get('admin/survey/{survey_id}/question/{id}/view_options','SurveyQuestionOptionController@index');
    Route::get('admin/survey/question/{questionid}/option/{optionid}/view/{tnid}','SurveyQuestionOptionController@view');
    Route::get('admin/survey/question/option/add/translate/{id}/{lang}','SurveyQuestionOptionController@addTranslate');
    Route::get('admin/survey/{survey_id}/question/{id}/option/create','SurveyQuestionOptionController@create');
    Route::post('admin/survey/question/option/add','SurveyQuestionOptionController@store')->name('create_option');
    //result
    Route::get('admin/survey_questions','SurveyAnswerController@allQuestions');
    Route::get('admin/survey_question/answers/{id}','SurveyAnswerController@questionAnswers');
    Route::post('/survey/store','SurveyAnswerController@storeUserSurvey')->name('survey');
    //setting
    Route::get('admin/survey_time','SurveySettingController@getSurveyModalTime');
    Route::get('admin/edit_survey_modal_time','SurveySettingController@editSurveyModalTime');
    Route::post('admin/update_survey_modal_time/{id}','SurveySettingController@updateSurveyModalTime')->name('update_survey_modal_time');
    Route::get('admin/create_survey_modal_time','SurveySettingController@createSurveyModalTime');
    Route::post('admin/store_survey_modal_time','SurveySettingController@storeSurveyModalTime')->name('store_survey_modal_time');
    //Analytics
    Route::get('/admin/analytics','AnalyticsController@index')->middleware('admin');
    Route::post('/admin/analytics','AnalyticsController@show')->name('analytics')->middleware('admin');
    //admin, glossary
    Route::get('admin/glossary_subjects','GlossarySubjectController@index')->middleware('admin')->name('glossary_subjects_list');
    Route::get('admin/glossary_subjects/create','GlossarySubjectController@create')->middleware('admin');
    Route::get('admin/glossary_subjects/edit/{id}','GlossarySubjectController@edit')->middleware('admin');
    Route::post('admin/glossary_subjects/update','GlossarySubjectController@update')->middleware('admin')->name('glossary_subjects_update');
    //StoryWeaver
    Route::get('/storyweaver/confirm/{landing_page}', 'StoryWeaverController@storyWeaverConfirmation')->name('storyweaver-confirm')->middleware('auth')->middleware('verified');
    Route::get('/storyweaver/auth', 'StoryWeaverController@storyWeaverAuth')->name('storyweaver-auth')->middleware('auth')->middleware('verified');
    //Adding old DDL routes
    Route::get('/user/register', 'Auth\RegisterController@showRegistrationForm');
    Route::get('/user', 'Auth\LoginController@showLoginForm');
    Route::get('/access-library', 'ResourceController@createStepOne')->middleware('auth')->middleware('verified');
    Route::get('/node/add', 'ResourceController@createStepOne')->middleware('auth')->middleware('verified');
    Route::get('/node/add/resourcefile', 'ResourceController@createStepOne')->middleware('auth')->middleware('verified');
    Route::get('/add/resourcefile', 'ResourceController@createStepOne')->middleware('auth')->middleware('verified');
    Route::get('/node/{resourceId}', 'ResourceController@viewPublicResource');
    Route::get('/user/logout', 'Auth\LoginController@logout');
    Route::get('/user/password', 'Auth\ForgotPasswordController@showLinkRequestForm');

    Route::get('/volunteer', function() {
        return redirect('page/1532');
    });
    Route::get('/support-library', function() {
        return redirect('page/21');
    });
    Route::get('/disclaimer', function() {
        return redirect('page/35');
    });
    Route::get('/terms', function() {
        return redirect('page/33');
    });
    Route::get('/privacy-policy', function() {
        return redirect('page/34');
    });
    Route::get('/oer', function() {
        return redirect('page/812');
    });
    Route::get('/help', function() {
        return redirect('page/1275');
    });
    //Auth
    Route::middleware(ProtectAgainstSpam::class)->group(function() {
        Auth::routes(['verify' => true]);
    });
    Route::get('/logout', function() {
        Auth::logout();
        return redirect('/home');
    });
    Route::get('/home', 'HomeController@index')->name('home');
});
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
Route::post('resources/favorite', 'ResourceController@resourceFavorite');
Route::get('/storage/{resource_id}/{file_id}/{file_name}', 'FileController')->where(['file_name' => '.*']);

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('google')->user();

    dd($user);
});
