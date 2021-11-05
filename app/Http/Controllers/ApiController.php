<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Resource;
use App\User;
use App\News;
use App\Page;
use App\Menu;
use App\ResourceAttachment;
use App\ResourceAuthor;
use App\ResourceComment;
use App\ResourceView;
use App\ResourceFlag;
use App\ResourceFavorite;

class ApiController extends Controller
{
    // User Profile
    public function user(){
        return auth()->user();
    }

    // Logout
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [ 'message' => 'Logged out!' ];
    }

    // Favorites
    public function favorites(){
        $favorites = ResourceFavorite::where('user_id', auth()->user()->id)->get(['resource_id']);
        $resources = Resource::whereIn('id', $favorites)->get();
        return $resources;
    }

    // Login
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'error' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return $user->createToken($request->device_name)->plainTextToken;
    }

    // Pages
    public function pages($lang="en") {
        return Page::where('status', 1)->where('language', $lang)->paginate(32);
    }

    // Page
    public function page($id) {
        return Page::where('id', $id)->get();
    }

    // Page view
    public function pageView($id) {
        $page = Page::find($id);
    
        $translation_id = $page->tnid;
        $translations = ($translation_id) ? Page::where('tnid', $translation_id)->get() : array();
    
        return view('pages.page_app_view', compact('page','translations'));
    }

    // News List
    public function newsList($lang="en") {
        return News::where('status', 1)->where('language', $lang)->orderBy('id', 'desc')->paginate(32);
    }

    // News
    public function news($id) {
        return News::where('id', $id)->get();
    }

    // News View
    public function newsView($id) {
        //setting the search session empty
        DDLClearSession();
    
        $news = News::find($id);
        $translation_id = $news->tnid;
        if($translation_id){
            $translations = News::where('tnid',$translation_id)->get();
        }else{
            $translations = array();
        }
        return view('news.news_api_view', compact('news','translations'));
    }

    // Links
    public function links($lang="en") {
        return Menu::select(['id', 'title', 'path'])
            ->where('language', $lang)
            ->where('location', 'bottom-menu')
            ->orderBy('id', 'desc')
            ->get();
    }

    // Resources
    public function resources($lang="en") {
        return Resource::where('status', 1)->where('language', $lang)->paginate(32);
    }

    // Single Resource
    public function resource($id) {
        return Resource::where('id',$id)->get();
    }

    // Resource Categories
    public function resourceCategories($lang="en") {
        $resource = new Resource();
        return $resource->subjectIconsAndTotal($lang);
    }

    // Resource Attributes
    public function resourceAttributes($resourceId) {
        //setting the search session empty
        DDLClearSession();
        
        $myResources = new Resource();
        $views = new ResourceView();
    
        $attachments = new ResourceAttachment();
        $attachments = $attachments->where('resource_id', $resourceId)->get();
    
        $resource = Resource::findOrFail($resourceId);
        $authors = $resource->authors;
    
        $result = [];
    
        $relatedItems = $myResources->getRelatedResources($resourceId, $resource->subjects);
        $comments = ResourceComment::select(
            'comment',
            'u.username'
        )
        ->leftJoin('users as u','u.id','=','resource_comments.user_id')
        ->where('resource_id', $resourceId)
        ->where('resource_comments.status', 1)
        ->get();
    
        if($resource){
            $translation_id = $resource->tnid;
            if($translation_id){
                $translations = $myResources->getResourceTranslations($translation_id);
            }else{
                $translations = array();
            }
        }
    
        $result['authors'] = $authors;
        $result['levels'] = $resource->levels;
        $result['subjects'] = $resource->subjects;
        $result['LearningResourceTypes'] = $resource->LearningResourceTypes;
        $result['publishers'] = $resource->publishers;
        $result['translations'] = $translations;
        $result['CreativeCommons'] = $resource->CreativeCommons;
        $result['attachments'] = $attachments;
        $result['related_items'] = $relatedItems;
        $result['comments'] = $comments;
        $result['views'] = $views->resourceCount($resourceId);
        
        return $result;
    }

    // Resource Offset
    public function resourceOffset(Request $request, $lang="en", $offset=0) {

        $searchQuery = $request['search'];
    
        $subjectAreaIds = $request['subject_area'];
        $levelIds = $request['level'];
        $typeIds = $request['type'];
    
        $resources = DB::table('resources AS rs')
        ->select(
            'rs.id',
            'rs.language', 
            'rs.abstract',
            'rs.title',
            'rs.status'
        )
        ->when($subjectAreaIds, function($query) use($subjectAreaIds){
            return $query->join('resource_subject_areas AS rsa', 'rsa.resource_id', '=', 'rs.id')
                    ->join('taxonomy_term_hierarchy AS tth','tth.tid','=','rsa.tid')
                    ->where('tth.parent', $subjectAreaIds)
                    ->orWhere('tth.tid', $subjectAreaIds)
                    ->groupBy('tth.tid');
        })
        ->when($levelIds, function($query)  use($levelIds){
            return $query->join('resource_levels AS rl', function ($join) use($levelIds) {
                $join->on('rl.resource_id', '=', 'rs.id')
                    ->where('rl.tid', $levelIds);
            });
        })
        ->when($typeIds, function($query)  use($typeIds){
            return $query->join('resource_learning_resource_types AS rlrt', function ($join) use($typeIds) {
                $join->on('rlrt.resource_id', '=', 'rs.id')
                        ->where('rlrt.tid', $typeIds);
                });
        })
        ->when($searchQuery, function($query)  use($searchQuery){
            return $query->leftJoin('resource_authors AS ra','ra.resource_id','=','rs.id')
                ->leftJoin('resource_publishers AS rp','rp.resource_id','=','rs.id')
                ->leftJoin('taxonomy_term_data AS ttd','ttd.id','=','ra.tid')
                ->leftJoin('taxonomy_term_data AS ttdp','ttdp.id','=','rp.tid')
                ->where('rs.title','like','%'.$searchQuery.'%')
                ->orwhere('rs.abstract', 'like' , '%'.$searchQuery.'%')
                ->orwhere('ttd.name', 'like' , '%'.$searchQuery.'%')
                ->orwhere('ttdp.name', 'like' , '%'.$searchQuery.'%');
        })
        ->when($request->filled('publisher'), function($query) use($request){
            return $query->leftJoin('resource_publishers AS rpub','rpub.resource_id','=','rs.id')
                ->where('rpub.tid', $request['publisher']);
        })
        ->where('rs.language', $lang)
        ->where('rs.status', 1)
        ->orderBy('rs.created_at','desc')
        ->groupBy(
            'rs.id',
            'rs.language', 
            'rs.title',
            'rs.abstract',
            'rs.created_at'
        )
        ->limit(32)
        ->offset($offset)
        ->get();
    
        $results = [];
    
        foreach($resources->unique('id') as $resource)
        {
            $res['id'] = $resource->id;
            $res['title'] = $resource->title;
            $res['abstract'] = $resource->abstract;
            $res['img'] = getImagefromResource($resource->abstract);
            
            if($lang == $resource->language)
            array_push($results, $res);
        }
    
        return $results;
    }

    // Featured Resources
    public function featuredResources($lang="en") {
        $resource = new Resource();
        return $resource->featuredCollections($lang);
    }

    // Filter Resources
    public function filterResources(Request $request) {
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
    
        $views = new ResourceView();
        $favorites = new ResourceFavorite();
        $comments = new ResourceComment();
        $resources = $myResources->paginateResourcesBy($request);
    
        $subjects = $myResources->resourceAttributesList('taxonomy_term_data',8);
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
    
        return compact(
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
        );
    }
}