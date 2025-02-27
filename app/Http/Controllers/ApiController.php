<?php

// php artisan serve --host 192.168.0.103 to host with IP

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\News;
use App\Models\Page;
use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceComment;
use App\Models\ResourceFavorite;
use App\Models\ResourceView;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiController extends Controller
{
    // User Profile
    public function user()
    {
        return auth()->user();
    }

    // Delete
    public function delete()
    {
        $id = Auth::id();

        $user = User::find($id);
        $user->username = 'deleted_user_'.time();
        $user->email = null;
        $user->status = 0;
        $user->save();

        $userProfile = UserProfile::where('user_id', $id)->first();
        $userProfile->first_name = null;
        $userProfile->last_name = null;
        $userProfile->gender = null;
        $userProfile->country = null;
        $userProfile->city = null;
        $userProfile->phone = null;
        $userProfile->save();

        $subscription = $user->subscription;
        if ($subscription) {
            $subscription->delete();
        }

        auth()->user()->tokens()->delete();

        return ['message' => 'User deleted successfully!'];
    }

    // Logout
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return ['message' => 'Logged out!'];
    }

    // Favorites
    public function favorites()
    {
        $favorites = ResourceFavorite::where('user_id', auth()->user()->id)->get(['resource_id']);
        $resources = Resource::whereIn('id', $favorites)->get();

        return $resources;
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['message' => 'Email and password are required'];
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return ['message' => 'Wrong email or password!'];
        }

        auth()->login($user);

        return ['token' => $user->createToken($user->username)->plainTextToken, 'user' => $user->username];
    }

    // Register
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users|nullable|regex:/^([a-zA-Z\d\._-]+)@(?!fmail.com)/', //Regex to block fmail.com domain
            'password' => 'required|string|min:8|regex:/^(?=.*[0-9])(?=.*[!@#$%^&.]).*$/',
        ], [
            'password.regex' => __('The password you entered doesn\'t have any special characters (!@#$%^&.) and (or) digits (0-9).'),
            'email.regex' => __('Please enter a valid email.'),
        ]);

        if ($validator->fails()) {
            return $validator->errors()->jsonSerialize();
        }

        $user = new User();
        $user->username = $this->getUserName($request['email']);
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->status = 1;
        $user->accessed_at = Carbon::now();
        $user->language = config('app.locale');

        $user->save();

        auth()->login($user);

        $userProfile = new UserProfile();
        $userProfile->user_id = $user->id;
        $userProfile->save();

        $userRole = new UserRole;
        $userRole->user_id = $user->id;
        $userRole->role_id = 6; //library user from roles table
        $userRole->save();

        return ['token' => $user->createToken($user->username)->plainTextToken, 'user' => $user->username];

    }

    // Pages
    public function pages($lang = 'en')
    {
        return Page::where('status', 1)->where('language', $lang)->paginate(32);
    }

    // Page
    public function page($id)
    {
        return Page::where('id', $id)->get();
    }

    // Page view
    public function pageView($id): View
    {
        $page = Page::find($id);

        $translation_id = $page->tnid;
        $translations = ($translation_id) ? Page::where('tnid', $translation_id)->get() : [];

        return view('pages.page_app_view', compact('page', 'translations'));
    }

    // News List
    public function newsList($lang = 'en')
    {
        return News::where('status', 1)->where('language', $lang)->orderBy('id', 'desc')->paginate(32);
    }

    // News
    public function news($id)
    {
        return News::where('id', $id)->get();
    }

    // News View
    public function newsView($id): View
    {
        //setting the search session empty
        DDLClearSession();

        $news = News::find($id);
        $translation_id = $news->tnid;
        if ($translation_id) {
            $translations = News::where('tnid', $translation_id)->get();
        } else {
            $translations = [];
        }

        return view('news.news_api_view', compact('news', 'translations'));
    }

    // Links
    public function links($lang = 'en')
    {
        return Menu::select(['id', 'title', 'path'])
            ->where('language', $lang)
            ->where('location', 'bottom-menu')
            ->orderBy('id', 'desc')
            ->get();
    }

    // Resources
    public function resources($lang = 'en')
    {
        return Resource::where('status', 1)->where('language', $lang)->paginate(32);
    }

    // Single Resource
    public function resource($id)
    {
        return Resource::where('id', $id)->get();
    }

    // Resource Categories
    public function resourceCategories($lang = 'en')
    {
        $resource = new Resource();

        return $resource->subjectIconsAndTotal($lang);
    }

    // Resource Attributes
    public function resourceAttributes($resourceId)
    {
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
            ->leftJoin('users as u', 'u.id', '=', 'resource_comments.user_id')
            ->where('resource_id', $resourceId)
            ->where('resource_comments.status', 1)
            ->get();

        if ($resource) {
            $translation_id = $resource->tnid;
            if ($translation_id) {
                $translations = $myResources->getResourceTranslations($translation_id);
            } else {
                $translations = [];
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
    public function resourceOffset(Request $request, $lang = 'en', $offset = 0)
    {

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
            ->when($subjectAreaIds, function ($query) use ($subjectAreaIds) {
                return $query->join('resource_subject_areas AS rsa', 'rsa.resource_id', '=', 'rs.id')
                    ->join('taxonomy_term_hierarchy AS tth', 'tth.tid', '=', 'rsa.tid')
                    ->where('tth.parent', $subjectAreaIds)
                    ->orWhere('tth.tid', $subjectAreaIds)
                    ->groupBy('tth.tid');
            })
            ->when($levelIds, function ($query) use ($levelIds) {
                return $query->join('resource_levels AS rl', function ($join) use ($levelIds) {
                    $join->on('rl.resource_id', '=', 'rs.id')
                        ->where('rl.tid', $levelIds);
                });
            })
            ->when($typeIds, function ($query) use ($typeIds) {
                return $query->join('resource_learning_resource_types AS rlrt', function ($join) use ($typeIds) {
                    $join->on('rlrt.resource_id', '=', 'rs.id')
                        ->where('rlrt.tid', $typeIds);
                });
            })
            ->when($searchQuery, function ($query) use ($searchQuery) {
                return $query->leftJoin('resource_authors AS ra', 'ra.resource_id', '=', 'rs.id')
                    ->leftJoin('resource_publishers AS rp', 'rp.resource_id', '=', 'rs.id')
                    ->leftJoin('taxonomy_term_data AS ttd', 'ttd.id', '=', 'ra.tid')
                    ->leftJoin('taxonomy_term_data AS ttdp', 'ttdp.id', '=', 'rp.tid')
                    ->where('rs.title', 'like', '%'.$searchQuery.'%')
                    ->orwhere('rs.abstract', 'like', '%'.$searchQuery.'%')
                    ->orwhere('ttd.name', 'like', '%'.$searchQuery.'%')
                    ->orwhere('ttdp.name', 'like', '%'.$searchQuery.'%');
            })
            ->when($request->filled('publisher'), function ($query) use ($request) {
                return $query->leftJoin('resource_publishers AS rpub', 'rpub.resource_id', '=', 'rs.id')
                    ->where('rpub.tid', $request['publisher']);
            })
            ->where('rs.language', $lang)
            ->where('rs.status', 1)
            ->orderBy('rs.created_at', 'desc')
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

        foreach ($resources->unique('id') as $resource) {
            $res['id'] = $resource->id;
            $res['title'] = $resource->title;
            $res['abstract'] = $resource->abstract;
            $res['img'] = getImagefromResource($resource->abstract);

            if ($lang == $resource->language) {
                array_push($results, $res);
            }
        }

        return $results;
    }

    // Featured Resources
    public function featuredResources($lang = 'en')
    {
        $resource = new Resource();

        return $resource->featuredCollections($lang);
    }

    // Filter Resources
    public function filterResources(Request $request)
    {
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

    // Send Resource Attachment
    public function getFile($fileId): BinaryFileResponse
    {

        $resourceAttachment = ResourceAttachment::where('resource_id', $fileId)->firstOrFail();
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
    }

    private function getUserName($email)
    {
        $username = substr($email, 0, strrpos($email, '@'));

        if (DB::table('users')->where('username', $username)->exists()) {
            return $username.time();
        }

        return $username;
    }
}
