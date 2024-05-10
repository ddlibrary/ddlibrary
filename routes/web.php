<?php

use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FlagController;
use App\Http\Controllers\GlossaryController;
use App\Http\Controllers\GlossarySubjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoryWeaverController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SurveyAnswerController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveyQuestionOptionController;
use App\Http\Controllers\SurveySettingController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\TaxonomyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VocabularyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Honeypot\ProtectAgainstSpam;

if (env('APP_ENV') === 'production') {
    \URL::forceScheme('https');
}

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::prefix(LaravelLocalization::setLocale())->middleware('localeSessionRedirect', 'localizationRedirect', 'localeViewPath')->group(function () {

    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);

    Route::get('login/facebook', [LoginController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('login/facebook/callback', [LoginController::class, 'handleFacebookCallback']);
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/admin', [DashboardController::class, 'index'])->middleware('admin');
    //Users
    Route::get('admin/users', [UserController::class, 'index'])->middleware('admin');
    Route::post('admin/users', [UserController::class, 'index'])->name('user')->middleware('admin');
    Route::get('admin/users/users-data', [UserController::class, 'usersData'])->middleware('admin');
    Route::get('user/profile', [UserController::class, 'viewUser'])->where('userId', '[0-9]+')->name('user-view');
    Route::get('user/favorites', [UserController::class, 'favorites'])->where('userId', '[0-9]+')->name('user-favorites');
    Route::get('user/uploaded-resources', [UserController::class, 'uploadedResources'])->where('userId', '[0-9]+')->name('user-uploaded-resources');
    Route::post('user/update_profile', [UserController::class, 'updateProfile'])->name('user-profile-update');
    Route::get('admin/user/edit/{userId}', [UserController::class, 'edit'])->name('edit_user')->middleware('admin');
    Route::post('admin/user/update/{userId}', [UserController::class, 'update'])->name('update_user')->middleware('admin');
    Route::get('admin/user/delete/{userId}', [UserController::class, 'deleteUser'])->middleware('admin');
    Route::get('admin/user/export', [UserController::class, 'exportUsers'])->middleware('admin');
    //Resources
    Route::get('admin/resources', [ResourceController::class, 'index'])->middleware('auth');
    Route::post('admin/resources', [ResourceController::class, 'index'])->name('resources')->middleware('admin');
    Route::any('resources/list', [ResourceController::class, 'list'])->name('resourceList');
    Route::get('resources/priorities', [ReportController::class, 'resourcePriorities']);
    Route::get('resources/priorities/exclusion', [ReportController::class, 'resourcePrioritiesExclusion'])->middleware('LibraryManager');
    Route::post('resources/priorities/exclusion/add/{id}', [ReportController::class, 'resourcePrioritiesExclusionModify'])->middleware('LibraryManager');
    Route::post('resources/priorities/exclusion/remove/{id}', [ReportController::class, 'resourcePrioritiesExclusionModify'])->middleware('LibraryManager');
    Route::get('resource/{resourceId}', [ResourceController::class, 'viewPublicResource']);
    Route::get('resource/view/{fileId}/{key}', [ResourceController::class, 'viewFile']);
    Route::get('resource/{resourceId}/download/{fileId}/{hash}', [ResourceController::class, 'downloadFile'])->name('download-file')->middleware('auth')->middleware('verified');
    Route::get('resources', [ResourceController::class, 'list']);
    Route::get('resources/add/step1', [ResourceController::class, 'createStepOne'])->name('step1')->middleware('auth')->middleware('verified');
    Route::post('resources/add/step1', [ResourceController::class, 'postStepOne']);
    Route::get('resources/add/step2', [ResourceController::class, 'createStepTwo'])->name('step2')->middleware('auth')->middleware('verified');
    Route::post('resources/add/step2', [ResourceController::class, 'postStepTwo']);
    Route::get('resources/add/step3', [ResourceController::class, 'createStepThree'])->name('step3')->middleware('auth')->middleware('verified');
    Route::post('resources/add/step3', [ResourceController::class, 'postStepThree'])->middleware(ProtectAgainstSpam::class);
    Route::get('resources/attributes/{entity}', [ResourceController::class, 'attributes']);
    Route::post('resources/flag', [ResourceController::class, 'flag'])->name('flag');
    Route::post('resources/comment', [ResourceController::class, 'comment'])->name('comment')->middleware('auth')->middleware('verified');
    Route::get('admin/resource/published/{resourceId}', [ResourceController::class, 'published']);
    Route::get('admin/resource/delete/{resourceId}', [ResourceController::class, 'deleteResource'])->middleware('admin');
    Route::get('resources/edit/step1/{resourceId}', [ResourceController::class, 'createStepOneEdit'])->name('edit1')->middleware('LibraryManager');
    Route::post('resources/edit/step1/{resourceId}', [ResourceController::class, 'postStepOneEdit'])->middleware('LibraryManager');
    Route::get('resources/edit/step2/{resourceId}', [ResourceController::class, 'createStepTwoEdit'])->name('edit2')->middleware('LibraryManager');
    Route::post('resources/edit/step2/{resourceId}', [ResourceController::class, 'postStepTwoEdit'])->middleware('LibraryManager');
    Route::get('resources/edit/step3/{resourceId}', [ResourceController::class, 'createStepThreeEdit'])->name('edit3')->middleware('LibraryManager');
    Route::post('resources/edit/step3/{resourceId}', [ResourceController::class, 'postStepThreeEdit'])->middleware('LibraryManager');
    Route::post('resource/{resourceId}', [ResourceController::class, 'updateTid'])->middleware('admin')->name('updatetid');
    //delete file
    Route::get('delete/file/{resourceId}/{fileName}', [ResourceController::class, 'deleteFile'])->name('delete-file');
    //Contact
    Route::get('contact-us', [ContactController::class, 'create']);
    Route::post('contact-us', [ContactController::class, 'store'])->name('contact')->middleware(ProtectAgainstSpam::class);
    Route::get('admin/contacts', [ContactController::class, 'index'])->middleware('admin');
    Route::get('admin/contacts/read/{id}', [ContactController::class, 'read'])->middleware('admin');
    Route::get('admin/contacts/delete/{id}', [ContactController::class, 'delete'])->middleware('admin');
    //Report
    Route::get('admin/reports/ga', [ReportController::class, 'gaReport'])->middleware('admin');
    Route::get('admin/reports/resources', [ReportController::class, 'resourceReport'])->middleware('admin');
    Route::get('admin/reports/resources/subjects', [ReportController::class, 'resourceSubjectReport'])->middleware('admin');
    Route::get('admin/reports/languages', [ReportController::class, 'resourceLanguageReport'])->middleware('admin');
    //Downloads
    Route::get('admin/reports/downloads', [DownloadController::class, 'index'])->middleware('admin');
    Route::post('admin/reports/downloads', [DownloadController::class, 'index'])->name('downloads')->middleware('admin');
    //Pages
    Route::get('admin/pages', [PageController::class, 'index'])->middleware('admin');
    Route::get('admin/get-pages', [PageController::class, 'getPages'])->name('getpages')->middleware('admin');
    Route::get('admin/pages/view/{pageId}', [PageController::class, 'view'])->middleware('admin');
    Route::get('page/{pageId}', [PageController::class, 'view'])->where('pageId', '[0-9]+');
    Route::get('/about-education-afghanistan', function () {
        return redirect('page/22');
    });
    Route::get('page/edit/{pageId}', [PageController::class, 'edit'])->middleware('admin');
    Route::post('page/update/{pageId}', [PageController::class, 'update'])->name('update_page')->middleware('admin');
    Route::get('page/create', [PageController::class, 'create'])->middleware('admin');
    Route::post('page/store', [PageController::class, 'store'])->name('add_page')->middleware('admin');
    Route::get('page/translate/{pageId}/{pageTnid}', [PageController::class, 'translate'])->middleware('admin');
    Route::get('page/add/translate/{pageId}/{lang}', [PageController::class, 'addTranslate'])->middleware('admin');
    Route::post('page/add/translate/{pageId}/{lang}', [PageController::class, 'addPostTranslate'])->name('add_page_translate')->middleware('admin');
    //News
    Route::get('admin/news', [NewsController::class, 'index'])->middleware('admin');
    Route::get('admin/get-news', [NewsController::class, 'getNews'])->name('getnews')->middleware('admin');
    Route::get('news/{newsId}', [NewsController::class, 'view'])->where('newsId', '[0-9]+');
    Route::get('news/edit/{newsId}', [NewsController::class, 'edit'])->middleware('admin');
    Route::post('news/update/{newsId}', [NewsController::class, 'update'])->name('update_news')->middleware('admin');
    Route::get('news/create', [NewsController::class, 'create'])->middleware('admin');
    Route::post('news/store', [NewsController::class, 'store'])->name('add_news')->middleware('admin');
    Route::get('news/translate/{newsId}/{newsTnid}', [NewsController::class, 'translate'])->middleware('admin');
    Route::get('news/add/translate/{newsId}/{lang}', [NewsController::class, 'addTranslate'])->middleware('admin');
    Route::post('news/add/translate/{newsId}/{lang}', [NewsController::class, 'addPostTranslate'])->name('add_news_translate')->middleware('admin');
    //Menu
    Route::prefix('admin')->middleware('admin')->group(function(){
        Route::controller(MenuController::class)->group(function(){
            Route::get('menu', 'index');
            Route::post('menu', 'index')->name('menulist');
            Route::get('menu/add/{menuId}', 'create');
            Route::post('menu/store', 'store')->name('store_menu');
            Route::get('menu/edit/{menuId}', 'edit');
            Route::post('menu/update/{menuId}', 'update')->name('update_menu');
            Route::get('menu/translate/{menuId}', 'translate');
            Route::post('menu/translate/{menuId}', 'translate_menu')->name('translateMenu');
            Route::get('menu/sort', 'sort')->name('sort_menu');
            Route::get('menu/ajax_get_parents', 'ajax_get_parents')->name('ajax_get_parents');
        });

        //Settings
        Route::controller(SettingController::class)->group(function(){
            Route::get('settings', 'edit');
            Route::post('settings', 'update')->name('settings');
        });

        Route::resource('subscribers', SubscriberController::class)->only('index', 'destroy');

        //Comments
        Route::prefix('comments')->controller(CommentController::class)->group(function(){
            Route::get('/', 'index');
            Route::get('delete/{commentId}', 'delete');
            Route::get('published/{commentId}', 'published');
        });

        //Flags
        Route::get('flags', [FlagController::class, 'index']);
        //Taxonomy
        Route::get('taxonomy', [TaxonomyController::class, 'index'])->name('gettaxonomylist');
        Route::post('taxonomy', [TaxonomyController::class, 'index'])->name('posttaxonomylist');
        Route::get('taxonomy/edit/{vid}/{tid}', [TaxonomyController::class, 'edit'])->name('taxonomyedit');
        Route::post('taxonomy/edit/{vid}/{tid}', [TaxonomyController::class, 'update'])->name('taxonomyedit');
        Route::get('taxonomy/translate/{tid}', [TaxonomyController::class, 'translate']);
        Route::get('taxonomy/create', [TaxonomyController::class, 'create'])->name('taxonomycreate');
        Route::post('taxonomy/store', [TaxonomyController::class, 'store'])->name('taxonomystore');
        Route::get('taxonomy/create-translate/{tid}/{tnid}/{lang}', [TaxonomyController::class, 'createTranslate'])->name('taxonomytranslatecreate');
        Route::post('taxonomy/store-translate/{tnid}', [TaxonomyController::class, 'storeTranslate'])->name('taxonomytranslatestore');
        //Taxonomy Vocabulary
        Route::get('vocabulary', [VocabularyController::class, 'index'])->name('vocabularylist');
        Route::get('vocabularies', [VocabularyController::class, 'getVocabularies'])->name('getvocabularies');
        Route::get('vocabulary/create', [VocabularyController::class, 'create'])->name('vocabularycreate');
        Route::post('vocabulary/store', [VocabularyController::class, 'store'])->name('vocabularystore');
        Route::get('vocabulary/edit/{vid}', [VocabularyController::class, 'edit'])->name('vocabularyedit');
        Route::post('vocabulary/edit/{vid}', [VocabularyController::class, 'update'])->name('vocabularyedit');
        //Sync
        Route::get('/sync', [SyncController::class, 'index']);
        Route::get('/run_sync', [SyncController::class, 'SyncIt']);
    });

    //Glossary
    Route::get('glossary', [GlossaryController::class, 'index']);
    Route::post('glossary', [GlossaryController::class, 'index'])->name('glossary');
    Route::get('glossary/create', [GlossaryController::class, 'create'])->name('glossary_create')->middleware('LibraryManager');
    Route::post('glossary/store', [GlossaryController::class, 'store'])->name('glossary_store')->middleware('LibraryManager');
    Route::post('glossary/update', [GlossaryController::class, 'update'])->name('glossary_update')->middleware('LibraryManager');
    Route::post('glossary/delete/{id}', [GlossaryController::class, 'destroy'])->name('glossary_delete')->middleware('LibraryManager');
    Route::post('glossary/approve/{id}', [GlossaryController::class, 'approve'])->name('glossary_approve')->middleware('LibraryManager');
    //Impact Page
    Route::get('/impact', [ImpactController::class, 'index']);

    //admin, survey
    Route::get('admin/surveys', [SurveyController::class, 'index']);
    Route::get('admin/survey/edit/{id}', [SurveyController::class, 'edit']);
    Route::get('admin/survey/view/{id}/{tnid}', [SurveyController::class, 'view']);
    Route::get('admin/survey/report/{id}', [SurveyController::class, 'report'])->middleware('admin');
    Route::get('admin/survey/add/translate/{id}/{lang}', [SurveyController::class, 'addTranslate']);
    Route::get('admin/survey/create', [SurveyController::class, 'create']);
    Route::get('admin/survey/delete/{id}', [SurveyController::class, 'delete']);
    Route::post('admin/update_survey/{id}', [SurveyController::class, 'update'])->name('update_survey');
    Route::post('admin/survey/create', [SurveyController::class, 'store'])->name('create_survey');
    //question
    Route::get('admin/survey/questions/{id}', [SurveyQuestionController::class, 'index']);
    Route::get('admin/survey/{surveyid}/question/view/{id}/{tnid}', [SurveyQuestionController::class, 'view']);
    Route::get('admin/survey/question/add/translate/{id}/{lang}', [SurveyQuestionController::class, 'addTranslate']);
    Route::post('admin/survey/question/add', [SurveyQuestionController::class, 'store'])->name('create_question');
    Route::get('admin/survey/question/add/{id}', [SurveyQuestionController::class, 'create']);
    Route::get('admin/survey/question/delete/{id}', [SurveyQuestionController::class, 'delete']);
    //option
    Route::get('admin/survey/question/option/delete/{id}', [SurveyQuestionOptionController::class, 'delete']);
    Route::get('admin/survey/{survey_id}/question/{id}/view_options', [SurveyQuestionOptionController::class, 'index']);
    Route::get('admin/survey/question/{questionid}/option/{optionid}/view/{tnid}', [SurveyQuestionOptionController::class, 'view']);
    Route::get('admin/survey/question/option/add/translate/{id}/{lang}', [SurveyQuestionOptionController::class, 'addTranslate']);
    Route::get('admin/survey/{survey_id}/question/{id}/option/create', [SurveyQuestionOptionController::class, 'create']);
    Route::post('admin/survey/question/option/add', [SurveyQuestionOptionController::class, 'store'])->name('create_option');
    //result
    Route::get('admin/survey_questions', [SurveyAnswerController::class, 'allQuestions']);
    Route::get('admin/survey_question/answers/{id}', [SurveyAnswerController::class, 'questionAnswers']);
    Route::post('/survey/store', [SurveyAnswerController::class, 'storeUserSurvey'])->name('survey');
    //setting
    Route::get('admin/survey_time', [SurveySettingController::class, 'getSurveyModalTime']);
    Route::get('admin/edit_survey_modal_time', [SurveySettingController::class, 'editSurveyModalTime']);
    Route::post('admin/update_survey_modal_time/{id}', [SurveySettingController::class, 'updateSurveyModalTime'])->name('update_survey_modal_time');
    Route::get('admin/create_survey_modal_time', [SurveySettingController::class, 'createSurveyModalTime']);
    Route::post('admin/store_survey_modal_time', [SurveySettingController::class, 'storeSurveyModalTime'])->name('store_survey_modal_time');
    //Analytics
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->middleware('admin');
    Route::post('/admin/analytics', [AnalyticsController::class, 'show'])->name('analytics')->middleware('admin');
    //admin, glossary
    Route::get('admin/glossary_subjects', [GlossarySubjectController::class, 'index'])->middleware('admin')->name('glossary_subjects_list');
    Route::get('admin/glossary_subjects/create', [GlossarySubjectController::class, 'create'])->middleware('admin');
    Route::get('admin/glossary_subjects/edit/{id}', [GlossarySubjectController::class, 'edit'])->middleware('admin');
    Route::post('admin/glossary_subjects/update', [GlossarySubjectController::class, 'update'])->middleware('admin')->name('glossary_subjects_update');
    //StoryWeaver
    Route::get('/storyweaver/confirm/{landing_page}', [StoryWeaverController::class, 'storyWeaverConfirmation'])->name('storyweaver-confirm')->middleware('auth')->middleware('verified');
    Route::get('/storyweaver/auth', [StoryWeaverController::class, 'storyWeaverAuth'])->name('storyweaver-auth')->middleware('auth')->middleware('verified');
    //Adding old DDL routes
    Route::get('/user/register', [RegisterController::class, 'showRegistrationForm']);
    Route::get('/user', [LoginController::class, 'showLoginForm']);
    Route::get('/access-library', [ResourceController::class, 'createStepOne'])->middleware('auth')->middleware('verified');
    Route::get('/node/add', [ResourceController::class, 'createStepOne'])->middleware('auth')->middleware('verified');
    Route::get('/node/add/resourcefile', [ResourceController::class, 'createStepOne'])->middleware('auth')->middleware('verified');
    Route::get('/add/resourcefile', [ResourceController::class, 'createStepOne'])->middleware('auth')->middleware('verified');
    Route::get('/node/{resourceId}', [ResourceController::class, 'viewPublicResource']);
    Route::get('/user/logout', [LoginController::class, 'logout']);
    Route::get('/user/password', [ForgotPasswordController::class, 'showLinkRequestForm']);
    Route::get('/volunteer', function () {
        return redirect('page/1532');
    });
    Route::get('/support-library', function () {
        return redirect('page/21');
    });
    //Auth
    Route::middleware(ProtectAgainstSpam::class)->group(function () {
        Auth::routes(['verify' => true]);
    });
    Route::get('/logout', function () {
        Auth::logout();

        return redirect('/home');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::prefix('subscribe')->middleware(['auth', 'verified'])->controller(SubscribeController::class)->group(function(){
        Route::get('/', 'index')->name('subscribe.index');
        Route::post('', 'store')->name('subscribe.store');
    });
});
Route::prefix('laravel-filemanager')->middleware('web', 'auth')->group(function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
Route::post('resources/favorite', [ResourceController::class, 'resourceFavorite']);
Route::get('/storage/{resource_id}/{file_id}/{file_name}', FileController::class)->where(['file_name' => '.*']);
