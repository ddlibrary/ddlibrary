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
        $resourceLevels = $myResources->resourceAttributes($resourceId,'resources_levels','resource_level');
        $resourceAuthors = $myResources->resourceAttributes($resourceId,'resources_authors','author_name');
        $resourceAttachments = $myResources->resourceAttributes($resourceId,'resources_attachments','file_name'); 
        $resourceSubjectAreas = $myResources->resourceAttributes($resourceId,'resources_subject_areas','subject_area');
        $resourceLearningResourceTypes = $myResources->resourceAttributes($resourceId,'resources_learning_resource_types','learning_resource_type');
        $resourcePublishers = $myResources->resourceAttributes($resourceId,'resources_publishers','publisher_name');
        return view('admin.resources.view_resource', compact(
            'resource',
            'resourceLevels',
            'resourceAuthors',
            'resourceAttachments',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'resourcePublishers'
        ));
    }

    public function list(Request $request)
    {
        $myResources = new Resource();

        $subjectArea = $request->only('subject_area');
        if($subjectArea){
            $resources = $myResources->paginateResourcesBySubjectArea($subjectArea['subject_area']);
            $resources->appends(['subject_area' => $subjectArea['subject_area']])->links();
        }else{
            if(session('search')){
                $searchQuery = session('session');    
            }else{
                $searchQuery = $request->input('search');
                session(['search' => $searchQuery]);
            }
            $resources = $myResources->searchResources($searchQuery);
        }
        return view('resources.resources_list', compact('resources'));
    }

    public function viewPublicResource($resourceId)
    {
        $myResources = new Resource();
        $resource = Resource::resources()->where('resourceid',$resourceId)->first();
        $resourceLevels = $myResources->resourceAttributes($resourceId,'resources_levels','resource_level');
        $resourceAuthors = $myResources->resourceAttributes($resourceId,'resources_authors','author_name');
        $resourceAttachments = $myResources->resourceAttributes($resourceId,'resources_attachments','file_name'); 
        $resourceSubjectAreas = $myResources->resourceAttributes($resourceId,'resources_subject_areas','subject_area');
        $resourceLearningResourceTypes = $myResources->resourceAttributes($resourceId,'resources_learning_resource_types','learning_resource_type');
        $resourcePublishers = $myResources->resourceAttributes($resourceId,'resources_publishers','publisher_name');
        $relatedItems = $myResources->getRelatedResources($resourceId, $resourceSubjectAreas);
        return view('resources.resources_view', compact(
            'resource',
            'resourceLevels',
            'resourceAuthors',
            'resourceAttachments',
            'resourceSubjectAreas',
            'resourceLearningResourceTypes',
            'resourcePublishers',
            'relatedItems'
        ));   
    }

    public function latestResources()
    {
        $myResources = new Resource();
        $resources = $myResources->paginateResources();

        return view('resources.resources_list', compact('resources'));
    }
}
