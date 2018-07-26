<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\ResourceLevel;
use App\ResourceAttachment;
use App\ResourceSubjectArea;
use App\TaxonomyTerm;
use App\ResourceKeyword;
use App\ResourceLearningResourceType;
use App\ResourceEducationalUse;
use App\ResourceTranslationRight;
use App\ResourceEducationalResource;
use App\ResourceCopyrightHolder;
use App\ResourceCreativeCommon;
use App\ResourceSharePermission;
use App\ResourceAuthor;
use App\ResourcePublisher;
use App\ResourceTranslator;
use App\ResourceIamAuthor;

use App\ResourceComment;
use App\ResourceFlag;
use App\ResourceFavorite;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Session;

class ResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function index(Request $request)
    {
        $this->middleware('admin');

        //setting the search session empty
        $request->session()->forget('resource1');
        $request->session()->forget('resource2');
        $request->session()->forget('resource3');
        $request->session()->save();

        $myResources = new Resource();

        $resources = $myResources->filterResources($request->all());

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.resources.resources',compact('resources','filters'));
    }

    public function list(Request $request)
    {
        //setting the search session empty
        session()->forget(['resource1','resource2','resource3','search']);
        session()->save();
        
        $myResources = new Resource();

        //Getting all whatever in the parameterBag
        $everything = $request->all();

        if(isset($everything['search'])){
            session(['search' => $everything['search']]);
        }

        $subjectAreaIds = array();
        $levelIds = array();
        $typeIds = array();

        //if subject_area exists in the request
        if($request->filled('subject_area')){
            $subjectAreaIds = $everything['subject_area'];
        }

        //if level exists in the request
        if($request->filled('level')){
            $levelIds = $everything['level'];
        }

        //if type exists
        if($request->filled('type')){
            $typeIds = $everything['type'];
        }

        $resources = $myResources->paginateResourcesBy($request);

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data',8);
        $types = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $levels = $myResources->resourceAttributesList('taxonomy_term_data', 13);

        return view('resources.resources_list', compact('resources','subjects','types','levels','subjectAreaIds','levelIds','typeIds'));
    }

    public function viewPublicResource(Request $request, $resourceId)
    {
        //setting the search session empty
        session()->forget(['resource1','resource2','resource3','search']);
        session()->save();

        $myResources = new Resource();

        $resource = Resource::findOrFail($resourceId);

        $relatedItems = $myResources->getRelatedResources($resourceId, $resource->subjects);
        $comments = ResourceComment::published()->get();
        if($resource){
            $translation_id = $resource->tnid;
            if($translation_id){
                $translations = $myResources->getResourceTranslations($translation_id);
            }else{
                $translations = array();
            }
        }

        $this->resourceViewCounter($request, $resourceId);

        return view('resources.resources_view', compact(
            'resource',
            'relatedItems',
            'comments',
            'translations'
        ));   
    }

    public function createStepOne(Request $request)
    {
        $this->middleware('auth');
        $resource = $request->session()->get('resource1');
        return view('resources.resources_add_step1', compact('resource'));
    }

    public function postStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'title'         => 'required',
            'author'        => 'string|nullable',
            'publisher'     => 'string|nullable',
            'translator'    => 'string|nullable',
            'language'      => 'required',
            'abstract'      => 'required',
        ]);

        $request->session()->put('resource1', $validatedData);

        return redirect('/resources/add/step2');
    }

    public function createStepTwo(Request $request)
    {
        $resource1 = $request->session()->get('resource1');

        if(!$resource1){
            return redirect('/resources/add/step1');
        }
        
        $resource = $request->session()->get('resource2');

        $resourceSubjectAreas = json_encode($resource['subject_areas'], JSON_NUMERIC_CHECK);
        $resourceLearningResourceTypes = json_encode($resource['learning_resources_types'], JSON_NUMERIC_CHECK);
        $EditEducationalUse = json_encode($resource['educational_use'], JSON_NUMERIC_CHECK);
        //$resourceKeywords = json_encode($resource['keywords']);

        $myResources = new Resource();

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data',8);
        $keywords = $myResources->resourceAttributesList('taxonomy_term_data',23);
        $learningResourceTypes = $myResources->resourceAttributesList('taxonomy_term_data',7);
        $educationalUse = $myResources->resourceAttributesList('taxonomy_term_data',25);
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

    public function postStepTwo(Request $request)
    {
        $resource = $request->session()->get('resource2');

        $validatedData = $request->validate([
            'attachments.*'             => 'file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff',
            'subject_areas'             => 'required',
            'keywords'                  => 'string|nullable',
            'learning_resources_types'  => 'required',
            'educational_use'           => 'required',
            'level'                     => 'required',
        ]);

        if(isset($validatedData['attachments'])){
            foreach($validatedData['attachments'] as $attachments){
                $fileMime = $attachments->getMimeType();
                $fileSize = $attachments->getClientSize();
                $fileName = $attachments->getClientOriginalName();
                //$attachments->storeAs($fileName,'private');
                Storage::disk('private')->put($fileName, file_get_contents($attachments));
                $validatedData['attc'][] = array(
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'file_mime' => $fileMime
                );
            }
            unset($validatedData['attachments']);
        }

        if(isset($resource['attc'])){
            for($i=0; $i<count($resource['attc']); $i++){
                $validatedData['attc'][] = array(
                    'file_name' => $resource['attc'][$i]['file_name'],
                    'file_size' => $resource['attc'][$i]['file_size'],
                    'file_mime' => $resource['attc'][$i]['file_mime'],
                );
            }
        }

        $request->session()->put('resource2', $validatedData);
        return redirect('/resources/add/step3');
    }

    public function createStepThree(Request $request)
    {
        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');

        if(!$resource1 || !$resource2){
            return redirect('/resources/add/step1');
        }

        $resource = $request->session()->get('resource3');

        $myResources = new Resource();

        $creativeCommons        = $myResources->resourceAttributesList('taxonomy_term_data', 10);
        $creativeCommonsOther   = $myResources->resourceAttributesList('taxonomy_term_data', 26);

        return view('resources.resources_add_step3', compact('resource', 'creativeCommons', 'creativeCommonsOther'));
    }

    /**
     * Store resource
     *
     */
    public function postStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'translation_rights'        => 'integer',
            'educational_resource'      => 'integer',
            'copyright_holder'          => 'string|nullable',
            'iam_author'                => 'integer',
            'creative_commons'          => 'integer',
            'creative_commons_other'    => 'integer'
        ]);

        $request->session()->put('resource3', $validatedData);

        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');
        $resource3 = $request->session()->get('resource3');

        $request->session()->forget('resource1');
        $request->session()->forget('resource2');
        $request->session()->forget('resource3');
        $request->session()->save();

        $finalArray = array_merge($resource1, $resource2, $resource3);

        $result = DB::transaction(function () use($finalArray) {
            $myResources = new Resource();

            $myResources->title = $finalArray['title'];
            $myResources->abstract = $finalArray['abstract'];
            $myResources->language = $finalArray['language'];
            $myResources->user_id = Auth::id();
            $myResources->status = 0;
            //inserting to resource table
            $myResources->save();

            $myResources = Resource::find($myResources->id);
            $myResources->tnid = $myResources->id;
            //updating resource table with tnid
            $myResources->save();

            if(isset($finalArray['attc'])){
                foreach($finalArray['attc'] as $attc){
                    $myAttachments = new ResourceAttachment();
                    $myAttachments->resource_id = $myResources->id;
                    $myAttachments->file_name = $attc['file_name'];
                    $myAttachments->file_mime = $attc['file_mime'];
                    $myAttachments->file_size = $attc['file_size'];
                    $myAttachments->save();
                }
            }

            //Inserting Subject Areas
            foreach($finalArray['subject_areas'] as $subject){
                $mySubjects = new ResourceSubjectArea();
                $mySubjects->resource_id = $myResources->id;
                $mySubjects->tid = $subject;
                $mySubjects->save();
            }

            //Inserting Keywords
            $keywords = trim($finalArray['keywords'], ",");
            $keywords = explode(',',$keywords);
            foreach($keywords as $kw){
                $theTaxonomy = TaxonomyTerm::where('name', trim($kw))
                                            ->where('vid', 23)
                                            ->first();
                if(count($theTaxonomy)){
                    $myKeywords = new ResourceKeyword();
                    $myKeywords->resource_id = $myResources->id;
                    $myKeywords->tid = $theTaxonomy->id;
                    $myKeywords->save();
                }else{
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

            //Inserting Authors
            if(isset($finalArray['author'])){
                $authors = trim($finalArray['author'], ",");
                $authors = explode(',',$authors);
                foreach($authors as $author){
                    $theTaxonomy = TaxonomyTerm::where('name', $author)
                                                ->where('vid', 24)
                                                ->first();

                    if(count($theTaxonomy)){
                        $myAuthor = new ResourceAuthor();
                        $myAuthor->resource_id = $myResources->id;
                        $myAuthor->tid = $theTaxonomy->id;
                        $myAuthor->save();
                    }else{
                        $myTaxonomy = new TaxonomyTerm();
                        $myTaxonomy->vid = 24;
                        $myTaxonomy->name = $author;
                        $myTaxonomy->language = $finalArray['language'];
                        $myTaxonomy->save();

                        $myAuthor = new ResourceAuthor();
                        $myAuthor->resource_id = $myResources->id;
                        $myAuthor->tid = $myTaxonomy->id;
                        $myAuthor->save();   
                    }
                }
            }

            //Inserting Publishers
            if(isset($finalArray['publisher'])){
                $publisherName = trim($finalArray['publisher'],",");
                $theTaxonomy = TaxonomyTerm::where('name', $publisherName)
                                            ->where('vid', 9)
                                            ->first();

                if(count($theTaxonomy)){
                    $myPublisher = new ResourcePublisher();
                    $myPublisher->resource_id = $myResources->id;
                    $myPublisher->tid = $theTaxonomy->id;
                    $myPublisher->save();
                }else{
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 9;
                    $myTaxonomy->name = $publisherName;
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();

                    $myPublisher = new ResourcePublisher();
                    $myPublisher->resource_id = $myResources->id;
                    $myPublisher->tid = $myTaxonomy->id;
                    $myPublisher->save();
                }
            }

            //Inserting Translators
            if(isset($finalArray['translator'])){
                $translators = trim($finalArray['translator'], ",");
                $translators = explode(',',$translators);
                foreach($translators as $translator){
                    $theTaxonomy = TaxonomyTerm::where('name', $translator)
                                                ->where('vid', 24)
                                                ->first();

                    if(count($theTaxonomy)){
                        $myTranslator = new ResourceTranslator();
                        $myTranslator->resource_id = $myResources->id;
                        $myTranslator->tid = $theTaxonomy->id;
                        $myTranslator->save();
                    }else{
                        $myTaxonomy = new TaxonomyTerm();
                        $myTaxonomy->vid = 24;
                        $myTaxonomy->name = $translator;
                        $myTaxonomy->language = $finalArray['language'];
                        $myTaxonomy->save();

                        $myTranslator = new ResourceTranslator();
                        $myTranslator->resource_id = $myResources->id;
                        $myTranslator->tid = $myTaxonomy->id;
                        $myTranslator->save();
                    }
                }
            }

            //Inserting Learning Resource Types
            foreach($finalArray['learning_resources_types'] as $ltype){
                $myLearningType = new ResourceLearningResourceType();
                $myLearningType->resource_id = $myResources->id;
                $myLearningType->tid = $ltype;
                $myLearningType->save();
            }

            //Inserting Educational Use
            foreach($finalArray['educational_use'] as $edus){
                $myEdu = new ResourceEducationalUse();
                $myEdu->resource_id = $myResources->id;
                $myEdu->tid = $edus;
                $myEdu->save();
            }

            //Inserting Resource Levels
            foreach($finalArray['level'] as $level){
                $myLevel = new ResourceLevel();
                $myLevel->resource_id = $myResources->id;
                $myLevel->tid = $level;
                $myLevel->save();
            }

            //Inserting Translation Rights
            if(isset($finalArray['translation_rights'])){
                $myTranslationRight = new ResourceTranslationRight();
                $myTranslationRight->resource_id = $myResources->id;
                $myTranslationRight->value = $finalArray['translation_rights'];
                $myTranslationRight->save();
            }

            //Inserting Educational Resource
            if(isset($finalArray['educational_resource'])){
                $myEduResource = new ResourceEducationalResource();
                $myEduResource->resource_id = $myResources->id;
                $myEduResource->value = $finalArray['educational_resource'];
                $myEduResource->save();
            }

            //Inserting ResourceIamAuthor
            if(isset($finalArray['iam_author'])){
                $myIamAuthor = new ResourceIamAuthor();
                $myIamAuthor->resource_id = $myResources->id;
                $myIamAuthor->value = $finalArray['iam_author'];
                $myIamAuthor->save();
            }

            //Inserting Copyright Holder
            if(isset($finalArray['copyright_holder'])){
                $myCopyrightHolder = new ResourceCopyrightHolder();
                $myCopyrightHolder->resource_id = $myResources->id;
                $myCopyrightHolder->value = $finalArray['copyright_holder'];
                $myCopyrightHolder->save();
            }

            //Inserting Creative Commons
            if(isset($finalArray['creative_commons'])){
                $myCC = new ResourceCreativeCommon();
                $myCC->resource_id = $myResources->id;
                $myCC->tid = $finalArray['creative_commons'];
                $myCC->save();
            }

            //Inserting Sharing Permission
            if(isset($finalArray['creative_commons_other'])){
                $mySharePermit = new ResourceSharePermission();
                $mySharePermit->resource_id = $myResources->id;
                $mySharePermit->tid = $finalArray['creative_commons_other'];
                $mySharePermit->save();
            }

            return true;
        });

        if($result){
            return redirect('/home')->with('success','Resource successfully added! It will be published after review.');
        }
    }

    public function attributes($entity, Request $request)
    {
        $myResources = new Resource();
        $keyword = $request->only('term');
        if(!$keyword){
            return;
        }
        if($entity == "authors"){
            $records = $myResources->searchResourceAttributes($keyword['term'],'taxonomy_term_data', 24);
            return response()->json($records->toArray());
        }elseif($entity == "publishers"){
            $records = $myResources->searchResourceAttributes($keyword['term'],'taxonomy_term_data', 9);
            return response()->json($records->toArray());    
        }elseif($entity == "translators"){
            $records = $myResources->searchResourceAttributes($keyword['term'],'taxonomy_term_data', 22);
            return response()->json($records->toArray());    
        }elseif($entity == "keywords"){
            $records = $myResources->searchResourceAttributes($keyword['term'],'taxonomy_term_data', 23);
            return response()->json($records->toArray());    
        }
    }

    public function resourceFavorite(Request $request)
    {
        $myResources = new Resource();
        
        $resourceId = $request->input('resourceId');
        $userId = $request->input('userId');

        if(!$userId){
            return json_encode("notloggedin");
        }

        $favorite = resourceFavorite::where('resource_id', $resourceId)->first();

        if(count($favorite)){
            $favorite->delete();
            return json_encode("deleted");
        }else{
            $favorite = new ResourceFavorite;
            $favorite->resource_id = $resourceId;
            $favorite->user_id = $userId;
            $favorite->save();

            return json_encode("added");
        }
    }

    public function flag(Request $request)
    {
        $myResources = new Resource();

        $userId = $request->input('userid');
        $resourceId = $request->input('resource_id');

        if(empty($userId)){
            return redirect('login');
        }

        $flag = new ResourceFlag;
        $flag->resource_id = $resourceId;
        $flag->user_id = $userId;
        $flag->type = $request->input('type');
        $flag->details = $request->input('details');
        $flag->save();

        return redirect('resource/'.$resourceId)
            ->with('success', 'Your flag report is now registered! We will get back to you as soon as possible!');
    }

    public function comment(Request $request)
    {
        $myResources = new Resource();

        $userId = $request->input('userid');
        $resourceId = $request->input('resource_id');

        if(empty($userId)){
            return redirect('login');
        }

        $comment = new ResourceComment;
        $comment->resource_id = $resourceId;
        $comment->user_id = $userId;
        $comment->comment = $request->input('comment');
        $comment->save();
        
        return redirect('resource/'.$resourceId)
            ->with('success', 'Your comment is successfully registered. We will publish it after review.');
    }

    public function resourceViewCounter(Request $request, $resourceId)
    {
        $myResources = new Resource();

        $userAgentParser = parse_user_agent($request);
        $userAgent = array(
            'resource_id'       => $resourceId,
            'userid'            => Auth::id() ?: 0,
            'ip'                => $request->ip(),
            'browser_name'      => $userAgentParser['browser'],
            'browser_version'   => $userAgentParser['version'],
            'platform'          => $userAgentParser['platform']
        );

        $myResources->updateResourceCounter($userAgent);
    }

    public function createStepOneEdit($resourceId, Request $request)
    {
        $myResources = new Resource();

        $resource = $request->session()->get('resource1');
        if(count($resource)){
            $resource = $resource;
        }else{
            $resource = (array) $myResources->getResources($resourceId);
        }
        return view('resources.resources_edit_step1', compact('resource'));
    }

    public function postStepOneEdit($resourceId, Request $request)
    {
        $validatedData = $request->validate([
            'title'         => 'required',
            'author'        => 'string|nullable',
            'publisher'     => 'string|nullable',
            'translator'    => 'string|nullable',
            'language'      => 'required',
            'abstract'      => 'required',
        ]);

        $validatedData['id'] = $resourceId;
        $validatedData['status'] = $request->input('status');
        $request->session()->put('resource1', $validatedData);

        return redirect('/resources/edit/step2/'.$resourceId);
    }

    public function createStepTwoEdit($resourceId, Request $request)
    {
        $resource1 = $request->session()->get('resource1');

        if(!$resource1){
            return redirect('/resources/edit/step1');
        }

        $myResources = new Resource();

        $resourceSubjectAreas = array(); 
        $resourceLearningResourceTypes = array(); 
        $EditEducationalUse = array();
        $resourceLevels = array();
        $resourceKeywords = array();
        $resourceAttachments = array();

        $resource = $request->session()->get('resource2');

        if(isset($resource['subject_areas'])){
            $resourceSubjectAreas = $resource['subject_areas'];    
        }else{
            $dataSubjects = $myResources->resourceAttributes($resourceId,'resource_subject_areas','tid','taxonomy_term_data');
            foreach($dataSubjects AS $item)
            {
                array_push($resourceSubjectAreas, $item->id);
            }
        }

        if(isset($resource['learning_resources_types'])){
            $resourceLearningResourceTypes = $resource['learning_resources_types'];    
        }else{
            $dataResourceTypes = $myResources->resourceAttributes($resourceId,'resource_learning_resource_types','tid','taxonomy_term_data');
            foreach($dataResourceTypes AS $item)
            {
                array_push($resourceLearningResourceTypes, $item->id);
            }
        }

        if($resource && isset($resource['keywords'])){
            $resourceKeywords = explode(',',$resource['keywords']);
        }else{
            $dataKeywords = $myResources->resourceAttributes($resourceId,'resource_keywords','tid', 'taxonomy_term_data');
            foreach($dataKeywords AS $item)
            {
                array_push($resourceKeywords, $item->name);
            }
        }

        if(isset($resource['educational_use'])){
            $EditEducationalUse = $resource['educational_use'];    
        }else{
            $dataEducationalUse = $myResources->resourceAttributes($resourceId,'resource_educational_uses','tid','taxonomy_term_data');
            foreach($dataEducationalUse AS $item)
            {
                array_push($EditEducationalUse, $item->id);
            }
        }

        if(isset($resource['level'])){
            $resourceLevels = $resource['level'];    
        }else{
            $dataLevels = $myResources->resourceAttributes($resourceId,'resource_levels','tid', 'taxonomy_term_data');
            foreach($dataLevels AS $item)
            {
                array_push($resourceLevels, $item->id);
            }
        }

        if(isset($resource['attc'])){
            foreach($resource['attc'] as $item) {
                $resourceAttachments[] = array(
                    'file_name' => $item['file_name'],
                    'file_size' => $item['file_size'],
                    'file_mime' => $item['file_mime']
                );
            }
        }else{
            $dataAttachments = $myResources->resourceAttachments($resourceId)->toArray();
            foreach($dataAttachments AS $item){
                $resourceAttachments[] = array(
                    'file_name' => $item->file_name,
                    'file_size' => $item->file_size,
                    'file_mime' => $item->file_mime
                );
            }
            $resource['attc'] = $resourceAttachments;
            $request->session()->put('resource2', $resource);
            $request->session()->save();
        }

        $resourceSubjectAreas = json_encode($resourceSubjectAreas, JSON_NUMERIC_CHECK);
        $resourceLearningResourceTypes = json_encode($resourceLearningResourceTypes, JSON_NUMERIC_CHECK);
        $resourceKeywords = count($resourceKeywords)?implode(',',$resourceKeywords):"";
        $EditEducationalUse = json_encode($EditEducationalUse, JSON_NUMERIC_CHECK);

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data',8);
        $keywords = $myResources->resourceAttributesList('taxonomy_term_data',23);
        $learningResourceTypes = $myResources->resourceAttributesList('taxonomy_term_data',7);
        $educationalUse = $myResources->resourceAttributesList('taxonomy_term_data',25);
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

    public function postStepTwoEdit($resourceId, Request $request)
    {
        $resource = $request->session()->get('resource2');
        $validatedData = $request->validate([
            'attachments.*'             => 'file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff',
            'subject_areas'             => 'required',
            'keywords'                  => 'string|nullable',
            'learning_resources_types'  => 'required',
            'educational_use'           => 'required',
            'level'                     => 'required'
        ]);

        if(isset($validatedData['attachments'])){
            foreach($validatedData['attachments'] as $attachments){
                $fileMime = $attachments->getMimeType();
                $fileSize = $attachments->getClientSize();
                $fileName = $attachments->getClientOriginalName();
                //$attachments->storeAs($fileName,'private');
                unset($validatedData['attachments']);
                Storage::disk('private')->put($fileName, file_get_contents($attachments));
                $validatedData['attc'][] = array(
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'file_mime' => $fileMime
                );
            }
        }

        if(isset($resource['attc'])){
            for($i=0; $i<count($resource['attc']); $i++){
                $validatedData['attc'][] = array(
                    'file_name' => $resource['attc'][$i]['file_name'],
                    'file_size' => $resource['attc'][$i]['file_size'],
                    'file_mime' => $resource['attc'][$i]['file_mime'],
                ); 
            }
        }

        $validatedData['resourceid'] = $resourceId;
        $request->session()->put('resource2', $validatedData);
        $request->session()->save();
        return redirect('/resources/edit/step3/'.$resourceId);
    }

    public function createStepThreeEdit($resourceId, Request $request)
    {
        $resource1 = $request->session()->get('resource1');
        $resource2 = $request->session()->get('resource2');

        if(!$resource1 || !$resource2){
            return redirect('/resources/edit/step1');
        }

        $resource = $request->session()->get('resource3');

        $dbRecords = Resource::find($resourceId);

        $myResources = new Resource();

        $creativeCommons        = $myResources->resourceAttributesList('taxonomy_term_data', 10);
        $creativeCommonsOther   = $myResources->resourceAttributesList('taxonomy_term_data', 26);

        $resource['id'] = $resourceId;
        $resource['status'] = $resource1['status'];

        return view('resources.resources_edit_step3', compact('dbRecords', 'resource', 'creativeCommons', 'creativeCommonsOther'));
    }

    /**
     * Store resource
     *
     */
    public function postStepThreeEdit($resourceId, Request $request)
    {
        $validatedData = $request->validate([
            'translation_rights'        => 'integer',
            'educational_resource'      => 'integer',
            'iam_author'                => 'integer',
            'copyright_holder'          => 'string|nullable',
            'creative_commons'          => 'integer',
            'creative_commons_other'    => 'integer'
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

        $result = DB::transaction(function () use($resourceId, $finalArray) {
            $myResources = Resource::find($resourceId);

            $myResources->title = $finalArray['title'];
            $myResources->abstract = $finalArray['abstract'];
            $myResources->language = $finalArray['language'];
            $myResources->status = $finalArray['published'];
            //inserting to resource table
            $myResources->save();


            //Updating Attachments
            if(isset($finalArray['attc'])){
                ResourceAttachment::where('resource_id', $resourceId)->delete();
                foreach($finalArray['attc'] as $attc){
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
            if(count($theSubjects)){
                $theSubjects->delete();
            }

            //Inserting Subject Areas
            foreach($finalArray['subject_areas'] as $subject){
                $mySubjects = new ResourceSubjectArea();
                $mySubjects->resource_id = $myResources->id;
                $mySubjects->tid = $subject;
                $mySubjects->save();
            }

            //Delete Keywords
            $theKeyword = ResourceKeyword::where('resource_id', $resourceId);
            if(count($theKeyword)){
                $theKeyword->delete();
            }

            if(isset($finalArray['keywords'])){
                $keywords = trim($finalArray['keywords'], ",");
                $keywords = explode(',',$keywords);
                if(count($keywords) > 0){
                    foreach($keywords as $kw){
                        $theTaxonomy = TaxonomyTerm::where('name', trim($kw))
                                                    ->where('vid', 23)
                                                    ->first();

                        if(count($theTaxonomy)){
                            $myKeywords = new ResourceKeyword();
                            $myKeywords->resource_id = $myResources->id;
                            $myKeywords->tid = $theTaxonomy->id;
                            $myKeywords->save();
                        }else{
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
            if(count($theAuthors)){
                $theAuthors->delete();
            }

            //Inserting Authors
            $authors = trim($finalArray['author'], ",");
            $authors = explode(',',$authors);
            foreach($authors as $author){
                $theTaxonomy = TaxonomyTerm::where('name', $author)
                                            ->where('vid', 24)
                                            ->first();

                if(count($theTaxonomy)){
                    $myAuthor = new ResourceAuthor();
                    $myAuthor->resource_id = $resourceId;
                    $myAuthor->tid = $theTaxonomy->id;
                    $myAuthor->save();
                }else{
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 24;
                    $myTaxonomy->name = $author;
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();

                    $myAuthor = new ResourceAuthor();
                    $myAuthor->resource_id = $resourceId;
                    $myAuthor->tid = $myTaxonomy->id;
                    $myAuthor->save();
                }
            }

            //Deleting Publishers
            $thePublisher = ResourcePublisher::where('resource_id', $resourceId);
            if(count($thePublisher)){
                $thePublisher->delete();
            }

            //Inserting Publisher
            if(isset($finalArray['publisher'])){
                $publisherName = trim($finalArray['publisher'],",");
                $theTaxonomy = TaxonomyTerm::where('name', $publisherName)
                                            ->where('vid', 9)
                                            ->first();

                if(count($theTaxonomy)){
                    $myPublisher = new ResourcePublisher();
                    $myPublisher->resource_id = $resourceId;
                    $myPublisher->tid = $theTaxonomy->id;
                    $myPublisher->save();
                }else{
                    $myTaxonomy = new TaxonomyTerm();
                    $myTaxonomy->vid = 9;
                    $myTaxonomy->name = $publisherName;
                    $myTaxonomy->language = $finalArray['language'];
                    $myTaxonomy->save();

                    $myPublisher = new ResourcePublisher();
                    $myPublisher->resource_id = $resourceId;
                    $myPublisher->tid = $myTaxonomy->id;
                    $myPublisher->save();
                }
            }

            //Deleting Translators
            $theTranslator = ResourceTranslator::where('resource_id', $resourceId);
            if(count($theTranslator)){
                $theTranslator->delete();
            }

            //Inserting Translators
            if(isset($finalArray['translator'])){
                $translators = trim($finalArray['translator'], ",");
                $translators = explode(',',$translators);
                foreach($translators as $translator){
                    $theTaxonomy = TaxonomyTerm::where('name', $translator)
                                                ->where('vid', 24)
                                                ->first();

                    if(count($theTaxonomy)){
                        $myTranslator = new ResourceTranslator();
                        $myTranslator->resource_id = $resourceId;
                        $myTranslator->tid = $theTaxonomy->id;
                        $myTranslator->save();
                    }else{
                        $myTaxonomy = new TaxonomyTerm();
                        $myTaxonomy->vid = 24;
                        $myTaxonomy->name = $translator;
                        $myTaxonomy->language = $finalArray['language'];
                        $myTaxonomy->save();

                        $myTranslator = new ResourceTranslator();
                        $myTranslator->resource_id = $resourceId;
                        $myTranslator->tid = $myTaxonomy->id;
                        $myTranslator->save();
                    }
                }
            }

            //Deleting Learning Resource Types
            $theLearningResourceType = ResourceLearningResourceType::where('resource_id', $resourceId);
            if(count($theLearningResourceType)){
                $theLearningResourceType->delete();
            }

            //Inserting Learning Resource Types
            foreach($finalArray['learning_resources_types'] as $ltype){
                $myLearningType = new ResourceLearningResourceType();
                $myLearningType->resource_id = $resourceId;
                $myLearningType->tid = $ltype;
                $myLearningType->save();
            }

            //Deleting Educational Use
            $theEduUse = ResourceEducationalUse::where('resource_id', $resourceId);
            if(count($theEduUse)){
                $theEduUse->delete();
            }

            //Inserting Educational Use
            foreach($finalArray['educational_use'] as $edus){
                $myEdu = new ResourceEducationalUse();
                $myEdu->resource_id = $resourceId;
                $myEdu->tid = $edus;
                $myEdu->save();
            }

            //Deleting Levels
            $theLevels = ResourceLevel::where('resource_id', $resourceId);
            if(count($theLevels)){
                $theLevels->delete();
            }

            //Inserting Resource Levels
            foreach($finalArray['level'] as $level){
                $myLevel = new ResourceLevel();
                $myLevel->resource_id = $resourceId;
                $myLevel->tid = $level;
                $myLevel->save();
            }

            //Deleting Translation Rights
            $theTransRight = ResourceTranslationRight::where('resource_id', $resourceId);
            if(count($theTransRight)){
                $theTransRight->delete();
            }

            if(isset($finalArray['translation_rights'])){
                //Inserting Translation Rights
                $myTranslationRight = new ResourceTranslationRight();
                $myTranslationRight->resource_id = $resourceId;
                $myTranslationRight->value = $finalArray['translation_rights'];
                $myTranslationRight->save();
            }

            //Deleting Educational Resource
            $theEduResource = ResourceEducationalResource::where('resource_id', $resourceId);
            if(count($theEduResource)){
                $theEduResource->delete();
            }

            if(isset($finalArray['educational_resource'])){
                //Inserting Educational Resource
                $myEduResource = new ResourceEducationalResource();
                $myEduResource->resource_id = $resourceId;
                $myEduResource->value = $finalArray['educational_resource'];
                $myEduResource->save();
            }

            //Deleting I am author
            $theIamAuthor = ResourceIamAuthor::where('resource_id', $resourceId);
            if(count($theIamAuthor)){
                //Deleting I am author
                $theIamAuthor->delete();
            }

            if(isset($finalArray['iam_author'])){
                //Inserting i am author
                $myIamAuthor = new ResourceIamAuthor();
                $myIamAuthor->resource_id = $resourceId;
                $myIamAuthor->value = $finalArray['iam_author'];
                $myIamAuthor->save();
            }

            //Deleting Copyright Holder
            $theCopyrightHolder = ResourceCopyrightHolder::where('resource_id', $resourceId);
            if(count($theCopyrightHolder)){
                $theCopyrightHolder->delete();
            }

            if(isset($finalArray['copyright_holder'])){
                //Inserting Copyright Holder
                $myCopyrightHolder = new ResourceCopyrightHolder();
                $myCopyrightHolder->resource_id = $resourceId;
                $myCopyrightHolder->value = $finalArray['copyright_holder'];
                $myCopyrightHolder->save();
            }

            //Deleting Creative Commons
            $theCC = ResourceCreativeCommon::where('resource_id', $resourceId);
            if(count($theCC)){
                $theCC->delete();
            }

            if(isset($finalArray['creative_commons'])){
                //Inserting Creative Commons
                $myCC = new ResourceCreativeCommon();
                $myCC->resource_id = $resourceId;
                $myCC->tid = $finalArray['creative_commons'];
                $myCC->save();
            }

            //Deleting Creative Commons
            $theCCOther = ResourceSharePermission::where('resource_id', $resourceId);
            if(count($theCCOther)){
                $theCCOther->delete();
            }

            if(isset($finalArray['creative_commons_other'])){
                //Inserting Sharing Permission
                $mySharePermit = new ResourceSharePermission();
                $mySharePermit->resource_id = $resourceId;
                $mySharePermit->tid = $finalArray['creative_commons_other'];
                $mySharePermit->save();
            }
            return true;
        });

        if($result){
            return redirect('/resource/'.$resourceId)->with('success','Resource updated successfully');
        }
    }

    public function deleteFile($resourceId, $fileName)
    {   
        Storage::disk('private')->delete($fileName);
        ResourceAttachment::where('resource_id', $resourceId)->where('file_name', $fileName)->delete();
        Session::flash('success', "file successfully deleted!");
        $resource2 = session('resource2');
        $resource2Attc = $resource2['attc'];

        if($resource2Attc){
            for($i=0; $i<count($resource2Attc); $i++){
                if($resource2Attc[$i]['file_name'] == $fileName){
                    unset($resource2Attc[$i]);
                }
            }
            $resource2['attc'] = array_values($resource2Attc);
            session()->put('resource2', $resource2);
        }
        return back();
    }
}
