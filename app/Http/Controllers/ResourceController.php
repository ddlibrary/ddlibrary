<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use Illuminate\Support\Facades\Storage;

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
    
    public function index()
    {
        $this->middleware('admin');
        $myResources = new Resource();
        $resources = $myResources->paginateResources();
        return view('admin.resources',compact('resources'));
    }

    public function viewResource($resourceId)
    {
        $this->middleware('admin');
        $myResources = new Resource();
        $resource = Resource::resources()->where('resourceid',$resourceId)->first();
        $resourceLevels = $myResources->resourceAttributes($resourceId,'resources_levels','resource_level', 'static_levels');
        $resourceAuthors = $myResources->resourceAttributes($resourceId,'resources_authors','author_name','static_authors');
        //$resourceAttachments = $myResources->resourceAttributes($resourceId,'resources_attachments','file_name'); 
        $resourceSubjectAreas = $myResources->resourceAttributes($resourceId,'resources_subject_areas','subject_area','static_subject_areas');
        $resourceLearningResourceTypes = $myResources->resourceAttributes($resourceId,'resources_learning_resource_types','learning_resource_type','static_learning_resource_types');
        $resourcePublishers = $myResources->resourceAttributes($resourceId,'resources_publishers','publisher_name','static_publishers');
        return view('admin.resources.view_resource', compact(
            'resource',
            'resourceLevels',
            'resourceAuthors',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'resourcePublishers'
        ));
    }

    public function list(Request $request)
    {
        $myResources = new Resource();

        //Getting all whatever in the parameterBag
        $everything = $request->all();
        //A global query that attaches to the url
        $queryTxt = '';

        $subjectAreaIds = array();
        $levelIds = array();
        $typeIds = array();

        //if subject_area exists in the request
        if($request->filled('subject_area')){
            for($i=0; $i<count($everything['subject_area']); $i++)
            {
                $queryTxt = $queryTxt.'subject_area='.$everything['subject_area'][$i].'&';
            }
            $subjectAreaIds = $everything['subject_area'];
        }

        //if level exists in the request
        if($request->filled('level')){
            for($i=0; $i<count($everything['level']); $i++)
            {
                $queryTxt = $queryTxt.'level='.$everything['level'][$i].'&';
            }
            $levelIds = $everything['level'];
        }

        //if type exists
        if($request->filled('type')){
            for($i=0; $i<count($everything['type']); $i++)
            {
                $queryTxt = $queryTxt.'type='.$everything['type'][$i].'&';
            }
            $typeIds = $everything['type'];
        }

        //to get rid of the final &
        if($queryTxt){
            $queryTxt = rtrim($queryTxt, '&');
        }

        $searchQuery = $request->input('search');

        if($queryTxt){
            $resources = $myResources->paginateResourcesBy($subjectAreaIds, $levelIds, $typeIds);
            if(count($subjectAreaIds) > 0){
                $resources->appends(['subject_area' => $subjectAreaIds])->links();
            }elseif(count($levelIds) > 0){
                $resources->appends(['level' => $levelIds])->links();    
            }elseif(count($typeIds) > 0){
                $resources->appends(['type' => $typeIds])->links(); 
            }
        }elseif($searchQuery){
            $resources = $myResources->searchResources($searchQuery);
            $resources->appends(['search' => $searchQuery])->links();
            session(['search' => $searchQuery]);
        }else{
            $resources = $myResources->paginateResources();
        }
        $subjects = $myResources->resourceAttributesList('taxonomy_term_data',8);
        $types = $myResources->resourceAttributesList('taxonomy_term_data', 7);
        $levels = $myResources->resourceAttributesList('taxonomy_term_data', 13);
        return view('resources.resources_list', compact('resources','subjects','types','levels','subjectAreaIds','levelIds','typeIds'));
    }

    public function viewPublicResource($resourceId)
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

        $translation_id = $resource->tnid;
        if($translation_id){
            $translations = $myResources->getResourceTranslations($translation_id);
        }else{
            $translations = array();
        }

        return view('resources.resources_view', compact(
            'resource',
            'resourceLevels',
            'resourceAuthors',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'resourcePublishers',
            'resourceAttachments',
            'relatedItems',
            'translations'
        ));   
    }

    public function createStepOne(Request $request)
    {
        $this->middleware('auth');
        //$request->session()->flush();
        $resource = $request->session()->get('resource1');
        return view('resources.resources_add_step1', compact('resource'));
    }

    public function postStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|unique:resources_data',
            'author' => 'required',
            'publisher' => 'required',
            'translator' => '',
            'language' => 'required',
            'abstract' => 'required',
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
            'educationalUse'
        ));
    }

    public function postStepTwo(Request $request)
    {
        $resource = $request->session()->get('resource2');

        $validatedData = $request->validate([
            'attachments.*' => 'file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff',
            'subject_areas' => 'required',
            'keywords' => 'required',
            'learning_resources_types' => 'required',
            'educational_use' => 'required',
            'level' => 'required',
        ]);

        if(isset($validatedData['attachments'])){
            $i = 0;
            foreach($validatedData['attachments'] as $attachments){
                $fileName = $attachments->getClientOriginalName();
                $attachments->storeAs('attachments', $fileName);
                unset($validatedData['attachments'][$i]);
                $validatedData['attachments'][] = $fileName;
                $i++;
            }
        }

        if(isset($resource['attachments'])){
            foreach($resource['attachments'] as $attc){
                $validatedData['attachments'][] = $attc;
            }
        }

        //dd($validatedData);

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
        return view('resources.resources_add_step3', compact('resource'));
    }

    /**
     * Store resource
     *
     */
    public function postStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'translation_rights' => 'integer',
            'educational_resource' => 'integer',
            'copyright_holder' => 'string',
            'creative_commons' => 'integer',
            'creative_commons_other' => 'integer'
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
        return dd($finalArray);
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
}
