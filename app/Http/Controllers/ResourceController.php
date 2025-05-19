<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPublisherRequest;
use App\Jobs\WatermarkPDF;
use App\Mail\NewComment;
use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceAuthor;
use App\Models\ResourceComment;
use App\Models\ResourceCopyrightHolder;
use App\Models\ResourceCreativeCommon;
use App\Models\ResourceEducationalResource;
use App\Models\ResourceEducationalUse;
use App\Models\ResourceFavorite;
use App\Models\ResourceFlag;
use App\Models\ResourceIamAuthor;
use App\Models\ResourceKeyword;
use App\Models\ResourceLearningResourceType;
use App\Models\ResourceLevel;
use App\Models\ResourcePublisher;
use App\Models\ResourceSharePermission;
use App\Models\ResourceSubjectArea;
use App\Models\ResourceTranslationRight;
use App\Models\ResourceTranslator;
use App\Models\ResourceView;
use App\Models\Setting;
use App\Models\TaxonomyTerm;
use App\Traits\LanguageTrait;
use App\Traits\SitewidePageViewTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ResourceController extends Controller
{
    use LanguageTrait, SitewidePageViewTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index(Request $request): Factory|View|Application
    {
        $this->middleware('admin');

        //setting the search session empty
        DDLClearSession();

        $myResources = new Resource();
        $languages = $this->getLanguages();

        $resources = $myResources->filterResources($request->all());

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.resources.resources', compact('resources', 'filters', 'languages'));
    }

    public function updateTid(Request $request, $resourceId): RedirectResponse
    {
        $translatedResource = Resource::findOrFail($request->input('link'));

        $resource = Resource::findOrFail($resourceId);
        $resource->tnid = $translatedResource->id;
        $resource->save();

        return back();
    }

    public function list(Request $request): Factory|View|Application
    {
        //setting the search session empty
        DDLClearSession();
        $this->pageView($request, 'Resource List');

        $myResources = new Resource();

        //Getting all whatever in the parameterBag
        $everything = $request->all();

        if (isset($everything['search'])) {
            session(['search' => $everything['search']]);
        }

        $subjectAreaIds = [];
        $levelIds = [];
        $typeIds = [];

        //if subject_area exists in the request
        if ($request->filled('subject_area')) {
            $subjectAreaIds = $everything['subject_area'];
        }

        //if level exists in the request
        if ($request->filled('level')) {
            $levelIds = $everything['level'];
        }

        //if type exists
        if ($request->filled('type')) {
            $typeIds = $everything['type'];
        }

        $views = new ResourceView();
        $favorites = new ResourceFavorite();
        $comments = new ResourceComment();
        $resources = $myResources->paginateResourcesBy($request);

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data', 8);
        $types = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $levels = $myResources->resourceAttributesList('taxonomy_term_data', 13);

        if ($request->ajax()) {
            $resources = $myResources->paginateResourcesBy($request);

            return view('resources.resources_list_content', compact(
                'resources',
                'views',
                'favorites',
                'comments'
            ));
        }

        return view('resources.resources_list', compact(
            'resources',
            'subjects',
            'types',
            'levels',
            'subjectAreaIds',
            'levelIds',
            'typeIds',
            'views',
            'favorites',
            'comments'
        ));
    }

    public function viewPublicResource(Request $request, $resourceId): View|Factory|Redirector|RedirectResponse|Application
    {
            DDLClearSession();
            $myResources = new Resource();

            $resource = Resource::findOrFail($resourceId);

            if ($resource->status == 0 && ! (isAdmin() || isLibraryManager())) {  // We don't want anyone else to access unpublished resources
                abort(403);
            }

            $this->pageView($request, $resource->title);

            $relatedItems = $myResources->getRelatedResources($resourceId, $resource->subjects);
            $comments = ResourceComment::where('resource_id', $resourceId)->published()->get();

            $translation_id = $resource->tnid;
            if ($translation_id) {
                $translations = $myResources->getResourceTranslations($translation_id);
            } else {
                $translations = [];
            }

            $this->resourceViewCounter($request, $resourceId);
            Carbon::setLocale(app()->getLocale());

            return view('resources.resources_view', compact(
                'resource',
                'relatedItems',
                'comments',
                'translations'
            ));
    }

    public function createStepOne(Request $request): Factory|View|Application
    {
        $this->middleware('auth');
        $resource = $request->session()->get('resource1');

        return view('resources.resources_add_step1', compact('resource'));
    }

    public function postStepOne(Request $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'author' => 'string|nullable',
            'publisher' => 'string|nullable',
            'translator' => 'string|nullable',
            'language' => 'required',
            'abstract' => 'required',
        ]);

        $request->session()->put('resource1', $validatedData);

        return redirect('/resources/add/step2');
    }

    public function createStepTwo(Request $request): View|Factory|Redirector|RedirectResponse|Application
    {
        $resource1 = $request->session()->get('resource1');

        if (! $resource1) {
            return redirect('/resources/add/step1');
        }

        $resource = $request->session()->get('resource2');

        $resourceSubjectAreas = json_encode($resource['subject_areas'], JSON_NUMERIC_CHECK);
        $resourceLearningResourceTypes = json_encode($resource['learning_resources_types'], JSON_NUMERIC_CHECK);
        $EditEducationalUse = json_encode($resource['educational_use'], JSON_NUMERIC_CHECK);
        //$resourceKeywords = json_encode($resource['keywords']);

        $myResources = new Resource();

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data', 8);
        $keywords = $myResources->resourceAttributesList('taxonomy_term_data', 23);
        $learningResourceTypes = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $educationalUse = $myResources->resourceAttributesList('taxonomy_term_data', 25);
        $types = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $levels = $myResources->resourceAttributesList('taxonomy_term_data', 13);

        return view('resources.resources_add_step2', compact(
            'resource',
            'subjects',
            'keywords',
            'types',
            'levels',
            'learningResourceTypes',
            'educationalUse',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'EditEducationalUse'
        ));
    }

    public function postStepTwo(Request $request): Redirector|Application|RedirectResponse
    {
        $resource = $request->session()->get('resource2');

        $validatedData = $request->validate([
            'attachments.*' => 'file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,mpga,ppt,pptx,doc,docx,pdf,tif,tiff,mp3|max:131072', // Max file size is 128 MB
            'subject_areas' => 'required',
            'keywords' => 'string|nullable',
            'learning_resources_types' => 'required',
            'educational_use' => 'required',
            'level' => 'required',
        ]);

        if (isset($validatedData['attachments'])) {
            foreach ($validatedData['attachments'] as $attachments) {
                $fileMime = $attachments->getMimeType();
                $fileSize = $attachments->getSize();
                $fileName = $attachments->getClientOriginalName();
                $fileExtension = \File::extension($fileName);
                $fileName = auth()->user()->id.'_'.time().'.'.$fileExtension;
                //$attachments->storeAs($fileName,'private');
                Storage::disk('s3')->put('resources/'.$fileName, file_get_contents($attachments));
                $validatedData['attc'][] = [
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'file_mime' => $fileMime,
                ];
            }
            unset($validatedData['attachments']);
        }

        $validatedData = $this->getValidatedData($resource, $validatedData);

        $request->session()->put('resource2', $validatedData);

        return redirect('/resources/add/step3');
    }

    public function createStepThree(Request $request): View|Factory|Redirector|RedirectResponse|Application
    {
        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');

        if (! $resource1 || ! $resource2) {
            return redirect('/resources/add/step1');
        }

        $resource = $request->session()->get('resource3');

        $myResources = new Resource();

        $creativeCommons = $myResources->resourceAttributesList('taxonomy_term_data', 10);
        $creativeCommonsOther = $myResources->resourceAttributesList('taxonomy_term_data', 26);

        return view('resources.resources_add_step3', compact('resource', 'creativeCommons', 'creativeCommonsOther'));
    }

    /**
     * Store resource
     *
     * @throws Throwable
     */
    public function postStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'translation_rights' => 'integer',
            'educational_resource' => 'integer',
            'copyright_holder' => 'string|nullable',
            'iam_author' => 'integer',
            'creative_commons' => 'integer',
            'creative_commons_other' => 'integer',
        ]);

        $request->session()->put('resource3', $validatedData);

        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');
        $resource3 = $request->session()->get('resource3');
        if (isAdmin()) {
            $resource3['published'] = $request->input('published');
        } else {
            $resource3['published'] = 0;
        }

        $request->session()->forget('resource1');
        $request->session()->forget('resource2');
        $request->session()->forget('resource3');
        $request->session()->save();

        $finalArray = array_merge($resource1, $resource2, $resource3);

        $result = DB::transaction(function () use ($finalArray) {
            $myResources = new Resource();

            $myResources->title = $finalArray['title'];
            $myResources->abstract = $finalArray['abstract'];
            $myResources->language = $finalArray['language'];
            $myResources->user_id = Auth::id();
            $myResources->status = $finalArray['published'];
            $myResources->published_at = date('Y-m-d H:i:s');
            //inserting to resource table
            $myResources->save();

            $myResources = Resource::find($myResources->id);
            $myResources->tnid = $myResources->id;
            //updating resource table with tnid
            $myResources->save();

            if (isset($finalArray['attc'])) {
                foreach ($finalArray['attc'] as $attc) {
                    $myAttachments = new ResourceAttachment();
                    $myAttachments->resource_id = $myResources->id;
                    $myAttachments->file_name = $attc['file_name'];
                    $myAttachments->file_mime = $attc['file_mime'];
                    $myAttachments->file_size = $attc['file_size'];
                    $myAttachments->save();
                }
            }

            //Inserting Subject Areas
            foreach ($finalArray['subject_areas'] as $subject) {
                $mySubjects = new ResourceSubjectArea();
                $mySubjects->resource_id = $myResources->id;
                $mySubjects->tid = $subject;
                $mySubjects->save();
            }

            //Inserting Keywords
            $keywords = trim($finalArray['keywords'], ',');
            $keywords = explode(',', $keywords);
            foreach ($keywords as $kw) {
                $theTaxonomy = TaxonomyTerm::where('name', trim($kw))
                    ->where('vid', 23)
                    ->first();

                $myKeywords = new ResourceKeyword();
                $myKeywords->resource_id = $myResources->id;
                if ($theTaxonomy != null) {
                    $myKeywords->tid = $theTaxonomy->id;
                } else {
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 23;
                    $myTaxonomy->name = trim($kw);
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();
                    $myKeywords->tid = $myTaxonomy->id;
                }
                $myKeywords->save();
            }

            //Inserting Authors
            if (isset($finalArray['author'])) {
                $authors = trim($finalArray['author'], ',');
                $authors = explode(',', $authors);
                foreach ($authors as $author) {
                    $theTaxonomy = TaxonomyTerm::where('name', $author)
                        ->where('vid', 24)
                        ->first();

                    $myAuthor = new ResourceAuthor();
                    $myAuthor->resource_id = $myResources->id;

                    if ($theTaxonomy != null) {
                        $myAuthor->tid = $theTaxonomy->id;
                    } else {
                        $myTaxonomy = new TaxonomyTerm();
                        $myTaxonomy->vid = 24;
                        $myTaxonomy->name = $author;
                        $myTaxonomy->language = $finalArray['language'];
                        $myTaxonomy->save();

                        $myAuthor->tid = $myTaxonomy->id;
                    }
                    $myAuthor->save();
                }
            }

            //Inserting Publishers
            if (isset($finalArray['publisher'])) {
                $publisherName = trim($finalArray['publisher'], ',');
                $theTaxonomy = TaxonomyTerm::where('name', $publisherName)
                    ->where('vid', 9)
                    ->first();

                $myPublisher = new ResourcePublisher();
                $myPublisher->resource_id = $myResources->id;

                if ($theTaxonomy != null) {
                    $myPublisher->tid = $theTaxonomy->id;
                } else {
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 9;
                    $myTaxonomy->name = $publisherName;
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();

                    $myPublisher->tid = $myTaxonomy->id;
                }
                $myPublisher->save();
            }

            //Inserting Translators
            if (isset($finalArray['translator'])) {
                $translators = trim($finalArray['translator'], ',');
                $translators = explode(',', $translators);
                foreach ($translators as $translator) {
                    $theTaxonomy = TaxonomyTerm::where('name', $translator)
                        ->where('vid', 24)
                        ->first();

                    $myTranslator = new ResourceTranslator();
                    $myTranslator->resource_id = $myResources->id;

                    if ($theTaxonomy != null) {
                        $myTranslator->tid = $theTaxonomy->id;
                    } else {
                        $myTaxonomy = new TaxonomyTerm();
                        $myTaxonomy->vid = 24;
                        $myTaxonomy->name = $translator;
                        $myTaxonomy->language = $finalArray['language'];
                        $myTaxonomy->save();

                        $myTranslator->tid = $myTaxonomy->id;
                    }
                    $myTranslator->save();
                }
            }

            //Inserting Learning Resource Types
            foreach ($finalArray['learning_resources_types'] as $ltype) {
                $myLearningType = new ResourceLearningResourceType();
                $myLearningType->resource_id = $myResources->id;
                $myLearningType->tid = $ltype;
                $myLearningType->save();
            }

            //Inserting Educational Use
            foreach ($finalArray['educational_use'] as $edus) {
                $myEdu = new ResourceEducationalUse();
                $myEdu->resource_id = $myResources->id;
                $myEdu->tid = $edus;
                $myEdu->save();
            }

            //Inserting Resource Levels
            foreach ($finalArray['level'] as $level) {
                $myLevel = new ResourceLevel();
                $myLevel->resource_id = $myResources->id;
                $myLevel->tid = $level;
                $myLevel->save();
            }

            //Inserting Translation Rights
            if (isset($finalArray['translation_rights'])) {
                $myTranslationRight = new ResourceTranslationRight();
                $myTranslationRight->resource_id = $myResources->id;
                $myTranslationRight->value = $finalArray['translation_rights'];
                $myTranslationRight->save();
            }

            //Inserting Educational Resource
            if (isset($finalArray['educational_resource'])) {
                $myEduResource = new ResourceEducationalResource();
                $myEduResource->resource_id = $myResources->id;
                $myEduResource->value = $finalArray['educational_resource'];
                $myEduResource->save();
            }

            //Inserting ResourceIamAuthor
            if (isset($finalArray['iam_author'])) {
                $myIamAuthor = new ResourceIamAuthor();
                $myIamAuthor->resource_id = $myResources->id;
                $myIamAuthor->value = $finalArray['iam_author'];
                $myIamAuthor->save();
            }

            //Inserting Copyright Holder
            if (isset($finalArray['copyright_holder'])) {
                $myCopyrightHolder = new ResourceCopyrightHolder();
                $myCopyrightHolder->resource_id = $myResources->id;
                $myCopyrightHolder->value = $finalArray['copyright_holder'];
                $myCopyrightHolder->save();
            }

            //Inserting Creative Commons
            if (isset($finalArray['creative_commons'])) {
                $myCC = new ResourceCreativeCommon();
                $myCC->resource_id = $myResources->id;
                $myCC->tid = $finalArray['creative_commons'];
                $myCC->save();
            }

            //Inserting Sharing Permission
            if (isset($finalArray['creative_commons_other'])) {
                $mySharePermit = new ResourceSharePermission();
                $mySharePermit->resource_id = $myResources->id;
                $mySharePermit->tid = $finalArray['creative_commons_other'];
                $mySharePermit->save();
            }

            return true;
        });

        if ($result and isAdmin()) {
            Session::flash('alert', [
                'message' => __('Resource successfully added!'),
                'level' => 'success',
            ]);

            return redirect('/home');
        } elseif ($result) {
            Session::flash('alert', [
                'message' => __('Resource successfully added! It will be published after review.'),
                'level' => 'success',
            ]);

            return redirect('/home');
        }

        Session::flash('alert', [
            'message' => __('Resource couldn\'t be added.'),
            'level' => 'danger',
        ]);

        return redirect('/home');

    }

    public function attributes($entity, Request $request)
    {
        $myResources = new Resource();
        $keyword = $request->only('term');
        if (! $keyword) {
            return redirect('/home');
        }
        if ($entity == 'authors') {
            $records = $myResources->searchResourceAttributes($keyword['term'], 'taxonomy_term_data', 24);

            return response()->json($records->toArray());
        } elseif ($entity == 'publishers') {
            $records = $myResources->searchResourceAttributes($keyword['term'], 'taxonomy_term_data', 9);

            return response()->json($records->toArray());
        } elseif ($entity == 'translators') {
            $records = $myResources->searchResourceAttributes($keyword['term'], 'taxonomy_term_data', 22);

            return response()->json($records->toArray());
        } elseif ($entity == 'keywords') {
            $records = $myResources->searchResourceAttributes($keyword['term'], 'taxonomy_term_data', 23);

            return response()->json($records->toArray());
        }

        return redirect('/home');
    }

    public function resourceFavorite(Request $request): bool|string
    {
        $myResources = new Resource();

        $resourceId = $request->input('resourceId');
        $userId = $request->input('userId');

        if (! $userId) {
            return json_encode('notloggedin');
        }

        $favorite = resourceFavorite::where('resource_id', $resourceId)->first();

        if ($favorite != null) {
            $favorite->delete();

            return json_encode('deleted');
        } else {
            $favorite = new ResourceFavorite;
            $favorite->resource_id = $resourceId;
            $favorite->user_id = $userId;
            $favorite->save();

            return json_encode('added');
        }
    }

    public function flag(Request $request): Redirector|Application|RedirectResponse
    {
        $userId = $request->input('userid');
        $resourceId = $request->input('resource_id');

        if (empty($userId)) {
            return redirect('login');
        } elseif (empty($resourceId)) {
            return redirect('home');
        }

        $flag = new ResourceFlag;
        $flag->resource_id = $resourceId;
        $flag->user_id = $userId;
        $flag->type = $request->input('type');
        $flag->details = $request->input('details');
        $flag->save();

        return redirect('resource/'.$resourceId)
            ->with('success', __('Your flag report is now registered! We will get back to you as soon as possible!'));
    }

    public function comment(Request $request): Redirector|Application|RedirectResponse
    {
        $userId = $request->input('userid');
        $resourceId = $request->input('resource_id');

        if (empty($userId)) {
            return redirect('login');
        }

        $comment = new ResourceComment;
        $comment->resource_id = $resourceId;
        $comment->user_id = $userId;
        $comment->comment = $request->input('comment');
        $comment->save();

        if (config('mail.send_email') == 'yes') {
            Mail::to(Setting::find(1)->website_email)->send(new NewComment($comment));
        }

        Session::flash('alert', [
            'message' => __('Your comment is successfully registered. We will publish it after review.'),
            'level' => 'success',
        ]);

        return redirect('resource/'.$resourceId);
    }

    public function resourceViewCounter(Request $request, $resourceId)
    {
        $myResources = new Resource();

        $userAgentParser = parse_user_agent($request);
        $userAgent = [
            'resource_id' => $resourceId,
            'userid' => Auth::id() ?: 0,
            'ip' => $request->ip(),
            'browser_name' => $userAgentParser['browser'],
            'browser_version' => $userAgentParser['version'],
            'platform' => $userAgentParser['platform'],
        ];

        $myResources->updateResourceCounter($userAgent);
    }

    public function createStepOneEdit($resourceId, Request $request): Factory|View|Application
    {
        $this->middleware('admin');

        $myResources = new Resource();

        $resource = $request->session()->get('resource1');
        if ($resource == null) {
            $resource = (array) $myResources->getResources($resourceId);
        }

        return view('resources.resources_edit_step1', compact('resource'));
    }

    public function postStepOneEdit($resourceId, Request $request): Redirector|Application|RedirectResponse
    {
        $this->middleware('admin');

        $validatedData = $request->validate([
            'title' => 'required',
            'author' => 'string|nullable',
            'publisher' => 'string|nullable',
            'translator' => 'string|nullable',
            'language' => 'required',
            'abstract' => 'required',
        ]);

        $validatedData['id'] = $resourceId;
        $validatedData['status'] = $request->input('status');
        $request->session()->put('resource1', $validatedData);

        return redirect('/resources/edit/step2/'.$resourceId);
    }

    public function createStepTwoEdit($resourceId, Request $request): View|Factory|Redirector|RedirectResponse|Application
    {
        $this->middleware('admin');

        $resource1 = $request->session()->get('resource1');

        if (! $resource1) {
            return redirect('/resources/edit/step1');
        }

        $myResources = new Resource();

        $resourceSubjectAreas = [];
        $resourceLearningResourceTypes = [];
        $EditEducationalUse = [];
        $resourceLevels = [];
        $resourceKeywords = [];
        $resourceAttachments = [];

        $resource = $request->session()->get('resource2');

        if (isset($resource['subject_areas'])) {
            $resourceSubjectAreas = $resource['subject_areas'];
        } else {
            $dataSubjects = $myResources->resourceAttributes($resourceId, 'resource_subject_areas', 'tid', 'taxonomy_term_data');
            foreach ($dataSubjects as $item) {
                array_push($resourceSubjectAreas, $item->id);
            }
        }

        if (isset($resource['learning_resources_types'])) {
            $resourceLearningResourceTypes = $resource['learning_resources_types'];
        } else {
            $dataResourceTypes = $myResources->resourceAttributes($resourceId, 'resource_learning_resource_types', 'tid', 'taxonomy_term_data');
            foreach ($dataResourceTypes as $item) {
                array_push($resourceLearningResourceTypes, $item->id);
            }
        }

        if ($resource && isset($resource['keywords'])) {
            $resourceKeywords = explode(',', $resource['keywords']);
        } else {
            $dataKeywords = $myResources->resourceAttributes($resourceId, 'resource_keywords', 'tid', 'taxonomy_term_data');
            foreach ($dataKeywords as $item) {
                array_push($resourceKeywords, $item->name);
            }
        }

        if (isset($resource['educational_use'])) {
            $EditEducationalUse = $resource['educational_use'];
        } else {
            $dataEducationalUse = $myResources->resourceAttributes($resourceId, 'resource_educational_uses', 'tid', 'taxonomy_term_data');
            foreach ($dataEducationalUse as $item) {
                array_push($EditEducationalUse, $item->id);
            }
        }

        if (isset($resource['level'])) {
            $resourceLevels = $resource['level'];
        } else {
            $dataLevels = $myResources->resourceAttributes($resourceId, 'resource_levels', 'tid', 'taxonomy_term_data');
            foreach ($dataLevels as $item) {
                array_push($resourceLevels, $item->id);
            }
        }

        if (isset($resource['attc'])) {
            foreach ($resource['attc'] as $item) {
                $resourceAttachments[] = [
                    'file_name' => $item['file_name'],
                    'file_size' => $item['file_size'],
                    'file_mime' => $item['file_mime'],
                ];
            }
        } else {
            $dataAttachments = $myResources->resourceAttachments($resourceId)->toArray();
            foreach ($dataAttachments as $item) {
                $resourceAttachments[] = [
                    'file_name' => $item->file_name,
                    'file_size' => $item->file_size,
                    'file_mime' => $item->file_mime,
                ];
            }
            $resource['attc'] = $resourceAttachments;
            $request->session()->put('resource2', $resource);
            $request->session()->save();
        }

        $resourceSubjectAreas = json_encode($resourceSubjectAreas, JSON_NUMERIC_CHECK);
        $resourceLearningResourceTypes = json_encode($resourceLearningResourceTypes, JSON_NUMERIC_CHECK);
        $resourceKeywords = $resourceKeywords ? implode(',', $resourceKeywords) : '';
        $EditEducationalUse = json_encode($EditEducationalUse, JSON_NUMERIC_CHECK);

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data', 8);
        $keywords = $myResources->resourceAttributesList('taxonomy_term_data', 23);
        $learningResourceTypes = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $educationalUse = $myResources->resourceAttributesList('taxonomy_term_data', 25);
        $types = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $levels = $myResources->resourceAttributesList('taxonomy_term_data', 13);
        $resource['id'] = $resourceId;

        return view('resources.resources_edit_step2', compact(
            'resource',
            'subjects',
            'keywords',
            'types',
            'levels',
            'learningResourceTypes',
            'educationalUse',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'EditEducationalUse',
            'resourceAttachments',
            'resourceLevels',
            'resourceKeywords'
        ));
    }

    public function postStepTwoEdit($resourceId, Request $request): Redirector|Application|RedirectResponse
    {
        $this->middleware('admin');

        $resource = $request->session()->get('resource2');
        $validatedData = $request->validate([
            'attachments.*' => 'file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,mpga,ppt,pptx,doc,docx,pdf,tif,tiff,mp3',
            'subject_areas' => 'required',
            'keywords' => 'string|nullable',
            'learning_resources_types' => 'required',
            'educational_use' => 'required',
            'level' => 'required',
        ]);

        if (isset($validatedData['attachments'])) {
            foreach ($validatedData['attachments'] as $attachments) {
                $fileMime = $attachments->getMimeType();
                $fileSize = $attachments->getSize();
                $fileName = $attachments->getClientOriginalName();
                $fileExtension = \File::extension($fileName);
                $fileName = auth()->user()->id.'_'.time().'.'.$fileExtension;
                //$attachments->storeAs($fileName,'private');
                unset($validatedData['attachments']);
                Storage::disk('s3')->put('resources/'.$fileName, file_get_contents($attachments));
                $validatedData['attc'][] = [
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'file_mime' => $fileMime,
                ];
            }
        }

        $validatedData = $this->getValidatedData($resource, $validatedData);

        $validatedData['resourceid'] = $resourceId;
        $request->session()->put('resource2', $validatedData);
        $request->session()->save();

        return redirect('/resources/edit/step3/'.$resourceId);
    }

    public function createStepThreeEdit($resourceId, Request $request): View|Factory|Redirector|RedirectResponse|Application
    {
        $this->middleware('admin');

        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');

        if (! $resource1 || ! $resource2) {
            return redirect('/resources/edit/step1');
        }

        $resource = $request->session()->get('resource3');

        $dbRecords = Resource::find($resourceId);

        $myResources = new Resource();

        $creativeCommons = $myResources->resourceAttributesList('taxonomy_term_data', 10);
        $creativeCommonsOther = $myResources->resourceAttributesList('taxonomy_term_data', 26);

        $resource['id'] = $resourceId;
        $resource['status'] = $resource1['status'];

        return view('resources.resources_edit_step3', compact('dbRecords', 'resource', 'creativeCommons', 'creativeCommonsOther'));
    }

    /**
     * Store resource
     *
     * @throws Throwable
     */
    public function postStepThreeEdit($resourceId, Request $request): Redirector|Application|RedirectResponse
    {
        $this->middleware('admin');

        $validatedData = $request->validate([
            'translation_rights' => 'integer',
            'educational_resource' => 'integer',
            'iam_author' => 'integer',
            'copyright_holder' => 'string|nullable',
            'creative_commons' => 'integer',
            'creative_commons_other' => 'integer',
        ]);

        $request->session()->put('resource3', $validatedData);

        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');
        $resource3 = $request->session()->get('resource3');
        $resource3['published'] = $request->input('published');

        $request->session()->forget('resource1');
        $request->session()->forget('resource2');
        $request->session()->forget('resource3');
        $request->session()->save();

        $finalArray = array_merge($resource1, $resource2, $resource3);

        $result = DB::transaction(function () use ($resourceId, $finalArray) {
            $myResources = Resource::find($resourceId);

            $myResources->title = $finalArray['title'];
            $myResources->abstract = $finalArray['abstract'];
            $myResources->language = $finalArray['language'];
            $myResources->status = $finalArray['published'];
            $myResources->published_at = date('Y-m-d H:i:s');
            //inserting to resource table
            $myResources->save();

            //Updating Attachments
            if (isset($finalArray['attc'])) {
                ResourceAttachment::where('resource_id', $resourceId)->delete();
                foreach ($finalArray['attc'] as $attc) {
                    $myAttachments = new ResourceAttachment();
                    $myAttachments->resource_id = $resourceId;
                    $myAttachments->file_name = $attc['file_name'];
                    $myAttachments->file_mime = $attc['file_mime'];
                    $myAttachments->file_size = $attc['file_size'];
                    $myAttachments->save();
                }
            }

            //Deleting Subject Areas
            $theSubjects = ResourceSubjectArea::where('resource_id', $resourceId);
            $theSubjects?->delete();

            //Inserting Subject Areas
            foreach ($finalArray['subject_areas'] as $subject) {
                $mySubjects = new ResourceSubjectArea();
                $mySubjects->resource_id = $myResources->id;
                $mySubjects->tid = $subject;
                $mySubjects->save();
            }

            //Delete Keywords
            $theKeyword = ResourceKeyword::where('resource_id', $resourceId);
            $theKeyword?->delete();

            if (isset($finalArray['keywords'])) {
                $keywords = trim($finalArray['keywords'], ',');
                $keywords = explode(',', $keywords);
                if ($keywords != null) {
                    foreach ($keywords as $kw) {
                        $theTaxonomy = TaxonomyTerm::where('name', trim($kw))
                            ->where('vid', 23)
                            ->first();

                        if ($theTaxonomy != null) {
                            $myKeywords = new ResourceKeyword();
                            $myKeywords->resource_id = $myResources->id;
                            $myKeywords->tid = $theTaxonomy->id;
                            $myKeywords->save();
                        } else {
                            $myTaxonomy = new TaxonomyTerm();
                            $myTaxonomy->vid = 23;
                            $myTaxonomy->name = trim($kw);
                            $myTaxonomy->language = $finalArray['language'];
                            $myTaxonomy->save();

                            $myKeywords = new ResourceKeyword();
                            $myKeywords->resource_id = $myResources->id;
                            $myKeywords->tid = $myTaxonomy->id;
                            $myKeywords->save();
                        }
                    }
                }
            }

            //Deleting Authors
            $theAuthors = ResourceAuthor::where('resource_id', $resourceId);
            $theAuthors?->delete();

            //Inserting Authors
            $authors = trim($finalArray['author'], ',');
            $authors = explode(',', $authors);
            foreach ($authors as $author) {
                $theTaxonomy = TaxonomyTerm::where('name', $author)
                    ->where('vid', 24)
                    ->first();

                $myAuthor = new ResourceAuthor();
                $myAuthor->resource_id = $resourceId;
                if ($theTaxonomy != null) {
                    $myAuthor->tid = $theTaxonomy->id;
                } else {
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 24;
                    $myTaxonomy->name = $author;
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();

                    $myAuthor->tid = $myTaxonomy->id;
                }
                $myAuthor->save();
            }

            //Deleting Publishers
            $thePublisher = ResourcePublisher::where('resource_id', $resourceId);
            if ($thePublisher != null) {
                $thePublisher->delete();
            }

            //Inserting Publisher
            if (isset($finalArray['publisher'])) {
                $publisherName = trim($finalArray['publisher'], ',');
                $theTaxonomy = TaxonomyTerm::where('name', $publisherName)
                    ->where('vid', 9)
                    ->first();

                $myPublisher = new ResourcePublisher();
                $myPublisher->resource_id = $resourceId;
                if ($theTaxonomy != null) {
                    $myPublisher->tid = $theTaxonomy->id;
                } else {
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 9;
                    $myTaxonomy->name = $publisherName;
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();

                    $myPublisher->tid = $myTaxonomy->id;
                }
                $myPublisher->save();
            }

            //Deleting Translators
            $theTranslator = ResourceTranslator::where('resource_id', $resourceId);
            $theTranslator?->delete();

            //Inserting Translators
            if (isset($finalArray['translator'])) {
                $translators = trim($finalArray['translator'], ',');
                $translators = explode(',', $translators);
                foreach ($translators as $translator) {
                    $theTaxonomy = TaxonomyTerm::where('name', $translator)
                        ->where('vid', 24)
                        ->first();

                    $myTranslator = new ResourceTranslator();
                    $myTranslator->resource_id = $resourceId;
                    if ($theTaxonomy != null) {
                        $myTranslator->tid = $theTaxonomy->id;
                        $myTranslator->save();
                    } else {
                        $myTaxonomy = new TaxonomyTerm();
                        $myTaxonomy->vid = 24;
                        $myTaxonomy->name = $translator;
                        $myTaxonomy->language = $finalArray['language'];
                        $myTaxonomy->save();

                        $myTranslator->tid = $myTaxonomy->id;
                    }
                    $myTranslator->save();
                }
            }

            //Deleting Learning Resource Types
            $theLearningResourceType = ResourceLearningResourceType::where('resource_id', $resourceId);
            $theLearningResourceType?->delete();

            //Inserting Learning Resource Types
            foreach ($finalArray['learning_resources_types'] as $ltype) {
                $myLearningType = new ResourceLearningResourceType();
                $myLearningType->resource_id = $resourceId;
                $myLearningType->tid = $ltype;
                $myLearningType->save();
            }

            //Deleting Educational Use
            $theEduUse = ResourceEducationalUse::where('resource_id', $resourceId);
            $theEduUse?->delete();

            //Inserting Educational Use
            foreach ($finalArray['educational_use'] as $edus) {
                $myEdu = new ResourceEducationalUse();
                $myEdu->resource_id = $resourceId;
                $myEdu->tid = $edus;
                $myEdu->save();
            }

            //Deleting Levels
            $theLevels = ResourceLevel::where('resource_id', $resourceId);
            $theLevels?->delete();

            //Inserting Resource Levels
            foreach ($finalArray['level'] as $level) {
                $myLevel = new ResourceLevel();
                $myLevel->resource_id = $resourceId;
                $myLevel->tid = $level;
                $myLevel->save();
            }

            //Deleting Translation Rights
            $theTransRight = ResourceTranslationRight::where('resource_id', $resourceId);
            $theTransRight?->delete();

            if (isset($finalArray['translation_rights'])) {
                //Inserting Translation Rights
                $myTranslationRight = new ResourceTranslationRight();
                $myTranslationRight->resource_id = $resourceId;
                $myTranslationRight->value = $finalArray['translation_rights'];
                $myTranslationRight->save();
            }

            //Deleting Educational Resource
            $theEduResource = ResourceEducationalResource::where('resource_id', $resourceId);
            $theEduResource?->delete();

            if (isset($finalArray['educational_resource'])) {
                //Inserting Educational Resource
                $myEduResource = new ResourceEducationalResource();
                $myEduResource->resource_id = $resourceId;
                $myEduResource->value = $finalArray['educational_resource'];
                $myEduResource->save();
            }

            //Deleting I am author
            $theIamAuthor = ResourceIamAuthor::where('resource_id', $resourceId);
            $theIamAuthor?->delete();

            if (isset($finalArray['iam_author'])) {
                //Inserting i am author
                $myIamAuthor = new ResourceIamAuthor();
                $myIamAuthor->resource_id = $resourceId;
                $myIamAuthor->value = $finalArray['iam_author'];
                $myIamAuthor->save();
            }

            //Deleting Copyright Holder
            $theCopyrightHolder = ResourceCopyrightHolder::where('resource_id', $resourceId);
            $theCopyrightHolder?->delete();

            if (isset($finalArray['copyright_holder'])) {
                //Inserting Copyright Holder
                $myCopyrightHolder = new ResourceCopyrightHolder();
                $myCopyrightHolder->resource_id = $resourceId;
                $myCopyrightHolder->value = $finalArray['copyright_holder'];
                $myCopyrightHolder->save();
            }

            //Deleting Creative Commons
            $theCC = ResourceCreativeCommon::where('resource_id', $resourceId);
            $theCC?->delete();

            if (isset($finalArray['creative_commons'])) {
                //Inserting Creative Commons
                $myCC = new ResourceCreativeCommon();
                $myCC->resource_id = $resourceId;
                $myCC->tid = $finalArray['creative_commons'];
                $myCC->save();
            }

            //Deleting Creative Commons
            $theCCOther = ResourceSharePermission::where('resource_id', $resourceId);
            $theCCOther?->delete();

            if (isset($finalArray['creative_commons_other'])) {
                //Inserting Sharing Permission
                $mySharePermit = new ResourceSharePermission();
                $mySharePermit->resource_id = $resourceId;
                $mySharePermit->tid = $finalArray['creative_commons_other'];
                $mySharePermit->save();
            }

            return true;
        });

        if ($result) {
            return redirect('/resource/'.$resourceId)->with('success', __('Resource updated successfully'));
        }

        return redirect('/resource/'.$resourceId)->with('error', __('Resource could not be updated.'));
    }

    public function deleteFile($resourceId, $fileName): Redirector|Application|RedirectResponse
    {
        $this->middleware('admin');

        Storage::disk('s3')->delete($fileName);
        ResourceAttachment::where('resource_id', $resourceId)->where('file_name', $fileName)->delete();
        $resource2 = session('resource2');
        $resource2Attc = $resource2['attc'];

        if ($resource2Attc) {
            for ($i = 0; $i < count($resource2Attc); $i++) {
                if ($resource2Attc[$i]['file_name'] == $fileName) {
                    unset($resource2Attc[$i]);
                }
            }
            $resource2['attc'] = array_values($resource2Attc);
            session()->put('resource2', $resource2);
        }

        return redirect('/resources/edit/step2/'.$resourceId)->with('success', 'File successfully deleted!');
    }

    public function published($resourceId): RedirectResponse
    {
        $this->middleware('admin');

        $rs = Resource::find($resourceId);
        if ($rs->status == 1) {
            $rs->status = 0;
        } else {
            $rs->status = 1;
            $rs->published_at = date('Y-m-d H:i:s');
        }
        $rs->save();

        return back();
    }

    /**
     * Delete a resource
     */
    public function deleteResource($resourceId): RedirectResponse
    {
        $resource = Resource::find($resourceId);
        $resource->delete();

        return back()->with('error', 'You deleted the record!');
    }

    /**
     * Download a watermarked file attached to a resource
     *
     *
     * @param  $time
     *
     * @throws FileNotFoundException
     */
    public function downloadFile($resourceId, $fileId, $hash): BinaryFileResponse
    {
        $secret = config('s3.config.secret');
        $user = Auth::id();
        $calculated_hash = hash('sha256', $secret * ($user + $resourceId + $fileId));
        if ($calculated_hash == $hash) {
            $resource = Resource::findOrFail($resourceId);
            $all_attachments = $resource->attachments;
            $attachment = null;
            foreach ($all_attachments as $attach) {
                if ($attach->id == $fileId) {
                    $attachment = $attach;
                }
            }
            if (! $attachment) {
                abort(404);
            }

            $file_name = $attachment->file_name;
            $file_mime = $attachment->file_mime;

            $headers = [
                'Content-Type' => $file_mime,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$file_name}",
                'filename' => $file_name,
            ];

            $file = Storage::disk('s3')->get('resources/'.$file_name);

            if (! $file) {
                abort('404');
            }

            $temp_file = tempnam(
                sys_get_temp_dir(), $file_name.'_'
            );
            file_put_contents($temp_file, $file);
            /** TODO: Fix watermark PDF files
            if (! $attachment->file_watermarked && $file_mime == 'application/pdf') {
                WatermarkPDF::dispatch($attachment, $temp_file, $resource);
            }
            */

            return response()
                ->download($temp_file, $file_name, $headers)
                ->deleteFileAfterSend();
        } else {
            abort(403);
        }
    }

    public function getValidatedData(mixed $resource, array $validatedData): array
    {
        if (isset($resource['attc'])) {
            for ($i = 0; $i < count($resource['attc']); $i++) {
                $validatedData['attc'][] = [
                    'file_name' => $resource['attc'][$i]['file_name'],
                    'file_size' => $resource['attc'][$i]['file_size'],
                    'file_mime' => $resource['attc'][$i]['file_mime'],
                ];
            }
        }

        return $validatedData;
    }

    public function viewFile($fileId, $key): BinaryFileResponse
    {
        $secret = config('s3.config.secret');
        $decrypted_key = decrypt($key);
        $received_time = $decrypted_key / ($secret ? $secret : 1);
        $current_time = time();

        if ($current_time - $received_time < 300) { // 300 - tolerance of 5 minutes
            $resourceAttachment = ResourceAttachment::findOrFail($fileId);
            try {
                $file = Storage::disk('s3')->get('resources/'.$resourceAttachment->file_name);
            } catch (FileNotFoundException $e) {
                Log::error($e);
                abort(404);
            }
            $temp_file = tempnam(
                sys_get_temp_dir(), $resourceAttachment->file_name.'_'
            );
            file_put_contents($temp_file, $file);

            return response()
                ->download($temp_file, $resourceAttachment->file_name, [], 'inline')
                ->deleteFileAfterSend();
        } else {
            abort(403);
        }
    }

    public function resourcesWithNoPublishers(Request $request){

        DDLClearSession();

        $myResources = new Resource();
        $languages = $this->getLanguages();

        $query = Resource::query()->with(['publishers', 'user:id,username']); // Use Eloquent model instead of DB facade

        // Apply filters based on the request array
        $query->when($request->title, function ($query) use ($request) {
            return $query->where('title', 'like', '%'.$request['title'].'%');
        })
        ->when($request->status, function ($query) use ($request) {
            return $query->where('status', $request['status']);
        })
        ->when($request->language, function ($query) use ($request) {
            return $query->where('language', $request['language']);
        });
        

        if ($request->without_publisher ? true : false) {
            $query->doesntHave('publishers');
        }

        $resources = $query->orderBy('resources.created_at', 'desc')->paginate(10);

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.resources.resources-without-publisher', compact('resources', 'filters', 'languages'));

    }


    public function addPublisher(AddPublisherRequest $request)
    {
        DB::beginTransaction();

        try {
            $resource = Resource::findOrFail($request->resource_id);
            $existingPublisher = $resource->publishers()->first();
            if($existingPublisher){
                $existingPublisher->name = $request->name;
                $existingPublisher->save();
            }else{
                $theTaxonomy = TaxonomyTerm::where('name', $request->name)
                    ->where('vid', 9)
                    ->first();
    
                $myPublisher = new ResourcePublisher();
                $myPublisher->resource_id = $resource->id;
    
                if ($theTaxonomy != null) {
                    $myPublisher->tid = $theTaxonomy->id;
                } else {
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 9;
                    $myTaxonomy->name = $request->name;
                    $myTaxonomy->language = $resource->language;
                    $myTaxonomy->save();
    
                    $myPublisher->tid = $myTaxonomy->id;
                }
    
                $myPublisher->save();
            }


            DB::commit();

            return response()->json(['message' => "($request->name) is added as publisher for ($resource->title)"], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to add publisher: ' . $e->getMessage()], 500);
        }
    }
}
