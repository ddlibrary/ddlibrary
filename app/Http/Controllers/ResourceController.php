<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        $myResources = new Resource();

        $resources = $myResources->filterResources($request->all());

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.resources.resources',compact('resources','filters'));
    }

    public function list(Request $request)
    {
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
        $myResources = new Resource();
        $resource = Resource::resources()->where('resourceid',$resourceId)->first();
        $resourceLevels = $myResources->resourceAttributes($resourceId,'resources_levels','resource_level_tid', 'taxonomy_term_data');
        $resourceAuthors = $myResources->resourceAttributes($resourceId,'resources_authors','author_name_tid','taxonomy_term_data');
        $resourceAttachments = $myResources->resourceAttachments($resourceId); 
        $resourceSubjectAreas = $myResources->resourceAttributes($resourceId,'resources_subject_areas','subject_area_tid','taxonomy_term_data');
        $resourceLearningResourceTypes = $myResources->resourceAttributes($resourceId,'resources_learning_resource_types','learning_resource_type_tid','taxonomy_term_data');
        $resourcePublishers = $myResources->resourceAttributes($resourceId,'resources_publishers','publisher_name_tid','taxonomy_term_data');
        $relatedItems = $myResources->getRelatedResources($resourceId, $resourceSubjectAreas);
        $comments = $myResources->getComments($resourceId);
        $favorite = $myResources->getFavorite($resourceId);

        $translation_id = $resource->tnid;
        if($translation_id){
            $translations = $myResources->getResourceTranslations($translation_id);
        }else{
            $translations = array();
        }

        $this->resourceViewCounter($request, $resourceId);

        return view('resources.resources_view', compact(
            'resource',
            'resourceLevels',
            'resourceAuthors',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'resourcePublishers',
            'resourceAttachments',
            'relatedItems',
            'translations',
            'comments',
            'favorite'
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

        $myResources = new Resource();
        $resourceSubjectAreas = array();
        $resourceLearningResourceTypes = array();
        $EditEducationalUse = array();
        $resourceAttachments = array();

        if(session('edit')){
            $resourceId = session('edit');
            $dataResourceTypes = $myResources->resourceAttributes($resourceId,'resources_learning_resource_types','learning_resource_type_tid','taxonomy_term_data');
            $dataSubjects = $myResources->resourceAttributes($resourceId,'resources_subject_areas','subject_area_tid','taxonomy_term_data');
            $dataEducationalUse = $myResources->resourceAttributes($resourceId,'resources_educational_uses','educational_use','taxonomy_term_data');
            $resourceAttachments = $myResources->resourceAttachments($resourceId);

            foreach($dataSubjects AS $item)
            {
                array_push($resourceSubjectAreas, $item->tid);
            }

            foreach($dataResourceTypes AS $item)
            {
                array_push($resourceLearningResourceTypes, $item->tid);
            }

            foreach($dataEducationalUse AS $item)
            {
                array_push($EditEducationalUse, $item->tid);
            }

            $resourceSubjectAreas = json_encode($resourceSubjectAreas);
            $resourceLearningResourceTypes = json_encode($resourceLearningResourceTypes);
            $EditEducationalUse = json_encode($EditEducationalUse);
            $resource['attachments'] = $resourceAttachments->toArray();
            //dd($resource['attachments']);
        }else{
            $resource = $request->session()->get('resource2');
        }

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
            'keywords'                  => 'required',
            'learning_resources_types'  => 'required',
            'educational_use'           => 'required',
            'level'                     => 'required',
        ]);

        if(isset($validatedData['attachments'])){
            $i = 0;
            foreach($validatedData['attachments'] as $attachments){
                $fileMime = $attachments->getMimeType();
                $fileSize = $attachments->getClientSize();
                $fileName = $attachments->getClientOriginalName();
                //$attachments->storeAs($fileName,'private');
                Storage::disk('private')->put($fileName, file_get_contents($attachments));
                unset($validatedData['attachments'][$i]);
                $validatedData['attachments'][$i]['name'] = $fileName;
                $validatedData['attachments'][$i]['size'] = $fileSize;
                $validatedData['attachments'][$i]['mime'] = $fileMime;
                $i++;
            }
        }

        if(isset($resource['attachments'])){
            for($i=0; $i<count($resource['attachments']); $i++){
                $validatedData['attachments'][$i]['name'] = $resource['attachments'][$i]['name'];
                $validatedData['attachments'][$i]['size'] = $resource['attachments'][$i]['size'];
                $validatedData['attachments'][$i]['mime'] = $resource['attachments'][$i]['mime'];
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
        $creativeCommonsOther   = $myResources->resourceAttributesList('taxonomy_term_data', 27);

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
            'copyright_holder'          => 'string',
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

        $myResources = new Resource();

        $insertAttachment = $myResources->insertResources($finalArray);
        return redirect('/home');
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
        
        $parameters = $request->only('resourceId', 'userId');
        
        $resourceId = $parameters['resourceId'];
        $userId = $parameters['userId'];

        if(!$userId){
            return json_encode("notloggedin");
        }

        $result = $myResources->insertFavorite($resourceId, $userId);
        return json_encode($result);
    }

    public function flag(Request $request)
    {
        $myResources = new Resource();

        $params = $request->only('resourceid', 'userid','type','details');
        $userId = $params['userid'];
        $resourceId = $params['resourceid'];

        if(empty($userId)){
            return redirect('login');
        }

        if($myResources->insertFlag($params)){
            Session()->flash('msg', "Your flag report is now registered! We will get back to you as soon as possible!");
            return redirect('resources/view/'.$resourceId);
        }
    }

    public function comment(Request $request)
    {
        $myResources = new Resource();

        $params = $request->only('resourceid', 'userid','comment');
        $userId = $params['userid'];
        $resourceId = $params['resourceid'];

        if(empty($userId)){
            return redirect('login');
        }

        if($myResources->insertComment($params)){
            Session()->flash('success', "Your comment is successfully registered. We will publish it after review.");
            return redirect('resources/view/'.$resourceId);
        }
    }

    public function resourceViewCounter(Request $request, $resourceId)
    {
        $myResources = new Resource();

        $userAgentParser = parse_user_agent($request);
        $userAgent = array(
            'resourceid'        => $resourceId,
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

        if($resource){
            $resource = (object) $resource;
        }else{
            $resource = $myResources->getResources($resourceId, 'step1');
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

        $validatedData['resourceid'] = $resourceId;

        $request->session()->put('resource1', $validatedData);

        return redirect('/resources/edit/step2/'.$resourceId);
    }

    public function createStepTwoEdit($resourceId, Request $request)
    {
        $resource1 = $request->session()->get('resource1');

        if(!$resource1){
            return redirect('/resources/edit/step1');
        }

        $resource = $request->session()->get('resource2');
        
        $myResources = new Resource();

        $resourceSubjectAreas = array(); 
        $resourceLearningResourceTypes = array(); 
        $EditEducationalUse = array();

        $dataResourceTypes = $myResources->resourceAttributes($resourceId,'resources_learning_resource_types','learning_resource_type_tid','taxonomy_term_data');
        $dataSubjects = $myResources->resourceAttributes($resourceId,'resources_subject_areas','subject_area_tid','taxonomy_term_data');
        $dataEducationalUse = $myResources->resourceAttributes($resourceId,'resources_educational_uses','educational_use','taxonomy_term_data');
        $resourceAttachments = $myResources->resourceAttachments($resourceId)->toArray();

        array_push($resource['attachments'], $resourceAttachments);

        dd($resource);

        foreach($dataSubjects AS $item)
        {
            array_push($resourceSubjectAreas, $item->tid);
        }

        foreach($dataResourceTypes AS $item)
        {
            array_push($resourceLearningResourceTypes, $item->tid);
        }

        foreach($dataEducationalUse AS $item)
        {
            array_push($EditEducationalUse, $item->tid);
        }

        $resourceSubjectAreas = json_encode($resourceSubjectAreas);
        $resourceLearningResourceTypes = json_encode($resourceLearningResourceTypes);
        $EditEducationalUse = json_encode($EditEducationalUse);

        $subjects = $myResources->resourceAttributesList('taxonomy_term_data',8);
        $keywords = $myResources->resourceAttributesList('taxonomy_term_data',23);
        $learningResourceTypes = $myResources->resourceAttributesList('taxonomy_term_data',7);
        $educationalUse = $myResources->resourceAttributesList('taxonomy_term_data',25);
        $types = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $levels = $myResources->resourceAttributesList('taxonomy_term_data', 13);
        $resource['resourceid'] = $resourceId;

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
            'resourceAttachments'
        ));
    }

    public function postStepTwoEdit($resourceId, Request $request)
    {
        $resource = (object) $request->session()->get('resource2');

        $validatedData = $request->validate([
            'attachments.*'             => 'file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff',
            'subject_areas'             => 'required',
            'keywords'                  => 'required',
            'learning_resources_types'  => 'required',
            'educational_use'           => 'required',
            'level'                     => 'required',
        ]);

        if(isset($validatedData['attachments'])){
            $i = 0;
            foreach($validatedData['attachments'] as $attachments){
                $fileMime = $attachments->getMimeType();
                $fileSize = $attachments->getClientSize();
                $fileName = $attachments->getClientOriginalName();
                //$attachments->storeAs($fileName,'private');
                Storage::disk('private')->put($fileName, file_get_contents($attachments));
                unset($validatedData['attachments'][$i]);
                $validatedData['attachments'][$i]['file_name'] = $fileName;
                $validatedData['attachments'][$i]['file_size'] = $fileSize;
                $validatedData['attachments'][$i]['file_mime'] = $fileMime;
                $i++;
            }
        }

        if(isset($resource->attachments)){
            for($i=0; $i<count($resource->attachments); $i++){
                $validatedData['attachments'][$i]['file_name'] = $resource->attachments[$i]['file_name'];
                $validatedData['attachments'][$i]['file_size'] = $resource->attachments[$i]['file_size'];
                $validatedData['attachments'][$i]['file_mime'] = $resource->attachments[$i]['file_mime'];
            }
        }

        $validatedData['resourceid'] = $resourceId;

        $request->session()->put('resource2', $validatedData);
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

        $myResources = new Resource();

        $creativeCommons        = $myResources->resourceAttributesList('taxonomy_term_data', 10);
        $creativeCommonsOther   = $myResources->resourceAttributesList('taxonomy_term_data', 27);

        $resource['resourceid'] = $resourceId;

        return view('resources.resources_edit_step3', compact('resource', 'creativeCommons', 'creativeCommonsOther'));
    }

    /**
     * Store resource
     *
     */
    public function postStepThreeEdit(Request $request)
    {
        $validatedData = $request->validate([
            'translation_rights'        => 'integer',
            'educational_resource'      => 'integer',
            'copyright_holder'          => 'string',
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

        $myResources = new Resource();
dd($finalArray);
        $insertAttachment = $myResources->insertResources($finalArray);
        return redirect('/home');
    }
}
