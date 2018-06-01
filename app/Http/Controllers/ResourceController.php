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
        return view('resources.resources_view', compact(
            'resource',
            'resourceLevels',
            'resourceAuthors',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'resourcePublishers',
            'resourceAttachments',
            'relatedItems'
        ));   
    }
}
