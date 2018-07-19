<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Config;

class Resource extends Model
{
    public function levels()
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'resource_levels', 'resource_id', 'tid');
    }
    
    public function IamAuthors()
    {
        return $this->HasOne(ResourceIamAuthor::class);
    }

    public function keywords()
    {
        return $this->hasMany(ResourceKeyword::class);
    }

    public function LearningResourceTypes()
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'resource_learning_resource_types', 'resource_id', 'tid');
    }

    public function publishers()
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'resource_publishers', 'resource_id', 'tid');
    }

    public function SharePermissions()
    {
        return $this->hasOne(ResourceSharePermission::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'resource_subject_areas', 'resource_id', 'tid');
    }

    public function subjectsIcons()
    {
        return $this->belongsToMany(StaticSubjectIcons::class, 'resource_subject_areas', 'resource_id', 'tid');
    }

    public function TranslationRights()
    {
        return $this->hasOne(ResourceTranslationRight::class);
    }

    public function views()
    {
        return $this->hasMany(ResourceView::class);
    }

    public function attachments()
    {
        return $this->hasMany(ResourceAttachment::class);
    }

    public function authors()
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'resource_authors', 'resource_id', 'tid');
    }

    public function comments()
    {
        return $this->hasMany(ResourceComment::class);
    }

    public function CopyrightHolder()
    {
        return $this->hasOne(ResourceCopyrightHolder::class);
    }

    public function CreativeCommons()
    {
        return $this->belongsToMany(TaxonomyTerm::class, 'resource_creative_commons', 'resource_id', 'creative_commons');
    }

    public function EducationalResources()
    {
        return $this->hasMany(ResourceEducationalResource::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'resource_favorites', 'resource_id', 'user_id');
    }

    public function flags()
    {
        return $this->hasMany(ResourceFlag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopePublished($query)
    {
        return $query->where('status',1);
    }

    public function getResources($resourceId, $step)
    {
        if($step == "step1"){
            return  DB::table('resources AS rs')
            ->select(
                'rs.id',
                'rs.language', 
                'rs.status',
                'rs.title',
                'rs.abstract',
                'ttda.name AS author',
                'ttdp.name AS publisher',
                'ttdt.name AS translator'
            )
            ->leftJoin('resource_authors AS ra', 'ra.resource_id', '=', 'rs.id')
            ->leftJoin('resource_publishers AS rp', 'rp.resource_id', '=', 'rs.id')
            ->leftJoin('resource_translators AS rt', 'rt.resource_id', '=', 'rs.id')
            ->leftJoin('taxonomy_term_data AS ttda', 'ttda.id', '=', 'ra.tid')
            ->leftJoin('taxonomy_term_data AS ttdp', 'ttdp.id', '=', 'rp.tid')
            ->leftJoin('taxonomy_term_data AS ttdt', 'ttdt.id', '=', 'rt.tid')
            ->where('rs.language',Config::get('app.locale'))
            ->where('rs.id', $resourceId)
            ->orderBy('rs.created_at','desc')
            ->first();   
        }
    }

    public function filterResources($requestArray)
    {
        $resources = DB::table('resources AS rs')
            ->select(
                'rs.id',
                'rs.language', 
                'rs.title',
                'rs.abstract',
                'rs.user_id',
                'rs.tnid',
                'users.username AS addedby',
                'rs.status',
                'rs.created_at',
                'rs.updated_at'
            )
            ->LeftJoin('users', 'users.id', '=', 'rs.user_id')
            ->LeftJoin('resource_subject_areas AS rsa', 'rsa.resource_id', '=', 'rs.id')
            ->LeftJoin('resource_levels AS rl', 'rl.resource_id', '=', 'rs.id')
            ->LeftJoin('resource_learning_resource_types AS rlrt', 'rlrt.resource_id', '=', 'rs.id')
            ->LeftJoin('resource_attachments AS ra','ra.resource_id','=','rs.id')
            ->when(!empty($requestArray['title']), function($query) use($requestArray){
                return $query
                    ->where('rs.title', 'like', '%'.$requestArray['title'].'%');
            })
            ->when(isset($requestArray['status']), function($query) use($requestArray){
                return $query
                    ->where('rs.status', $requestArray['status']);
            })
            ->when(isset($requestArray['language']), function($query) use($requestArray){
                return $query
                    ->where('rs.language', $requestArray['language']);
            })
            ->when(isset($requestArray['subject_area']), function($query) use($requestArray){
                return $query
                    ->where('rsa.tid', $requestArray['subject_area']);
            })
            ->when(isset($requestArray['level']), function($query) use($requestArray){
                return $query
                    ->where('rl.tid', $requestArray['level']);
            })
            ->when(isset($requestArray['type']), function($query) use($requestArray){
                return $query
                    ->where('rlrt.tid', $requestArray['type']);
            })
            ->when(isset($requestArray['format']), function($query) use($requestArray){
                return $query
                    ->where('ra.file_mime', $requestArray['format']);
            })
            ->orderBy('rs.created_at','desc')
            ->groupBy(
                'rs.id',
                'rs.language', 
                'rs.title',
                'rs.abstract',
                'rs.user_id',
                'users.username',
                'rs.status',
                'rs.updated_at',
                'rs.tnid',
                'rs.created_at'
            )
            ->paginate(10);
        
        return $resources;
    }

    public function paginateResources()
    {
        $users = DB::table('resources AS rs')
            ->select(
                'rs.id',
                'rs.language', 
                'rs.title',
                'rs.abstract',
                'rs.user_id',
                'users.username AS author',
                'rs.status',
                'rs.updated_at'
            )
            ->join('users', 'users.id', '=', 'rs.user_id')
            ->where('rs.language',Config::get('app.locale'))
            ->where('rs.status', 1)
            ->orderBy('rs.created','desc')
            ->groupBy(
                'rs.id',
                'rs.language', 
                'rs.title',
                'rs.abstract',
                'rs.user_id',
                'users.username',
                'rs.status',
                'rs.updated_at',
                'rs.created_at'
            )
            ->paginate(32);

        return $users;
    }

    public function resourceAttributes($resourceId, $tableName, $fieldName, $staticTable)
    {
        $records = DB::table($tableName)
                ->select($staticTable.'.name', $staticTable.'.id')
                ->join($staticTable, $staticTable.'.id', '=', $tableName.'.'.$fieldName)
                ->where('resource_id',$resourceId)
                ->get();
        return $records;
    }

    public function searchResourceAttributes($keyword, $staticTable, $vid)
    {
        $records = DB::table($staticTable)
                ->select($staticTable.'.name AS value')
                ->where($staticTable.'.name','like','%'.$keyword.'%')
                ->where($staticTable.'.vid',$vid)
                ->get();
        return $records;
    }

    public function resourceAttributesList($tableName, $vid)
    {
        $records = DB::table($tableName)
                ->join('taxonomy_term_hierarchy AS tth', 'tth.tid','=',$tableName.'.id')
                ->where('vid', $vid)
                ->where('language',Config::get('app.locale'))
                ->orderBy($tableName.'.id')
                ->get();
        return $records;
    }

    //Total resources based on language
    public function totalResourcesByLanguage()
    {
        $records = DB::table('resources AS rs')
                    ->select(
                        'rs.language',
                        DB::raw('count(rs.id) as total')
                    )
                    ->groupBy('rs.language')
                    ->get();
        return $records;   
    }

    //Total resources based on subject area
    public function totalResourcesBySubject()
    {
        $records = DB::table('resources AS rs')
                    ->select(
                        'ttd.name',
                        'ttd.id',
                        'rs.language',
                        DB::raw('count(rs.id) as total')
                    )
                    ->join('resource_subject_areas AS rsa','rsa.resource_id','=','rs.id')
                    ->join('taxonomy_term_data AS ttd', function($join){
                        $join->on('ttd.id','=','rsa.tid')
                            ->where('ttd.vid', 8);
                    })
                    ->groupBy(
                        'ttd.name', 
                        'ttd.id', 
                        'rs.language')
                    ->orderby('rs.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    public function paginateResourcesBy($request)
    {
        $subjectAreaIds = $request['subject_area'];
        $levelIds = $request['level'];
        $typeIds = $request['type'];

        if($sessionQuery = session('search')){
            $searchQuery = $sessionQuery;
        }else{
            $searchQuery = $request->input('search');
        }

        $records = DB::table('resources AS rs')
            ->select(
                'rs.id',
                'rs.language', 
                'rs.title',
                'rs.abstract',
                'rs.user_id',
                'users.username AS author',
                'rs.status',
                'rs.updated_at',
                DB::raw('count(rf.resource_id) as totalfavorite'),
                DB::raw('count(rc.resource_id) as totalcomments'),
                DB::raw('count(rv.resource_id) as totalviews')
            )
            ->leftJoin('users', 'users.id', '=', 'rs.user_id')
            ->leftJoin('resource_favorites AS rf', 'rf.resource_id', '=', 'rs.id')
            ->leftJoin('resource_comments AS rc', 'rc.resource_id', '=', 'rs.id')
            ->leftJoin('resource_views AS rv', 'rv.resource_id', '=', 'rs.id')
            ->when(count($subjectAreaIds) > 0, function($query) use($subjectAreaIds){
                return $query->join('resource_subject_areas AS rsa', function ($join) use($subjectAreaIds) {
                    $join->on('rsa.resource_id', '=', 'rs.id')
                        ->whereIn('rsa.tid', $subjectAreaIds);
                });
            })
            ->when(count($levelIds) > 0, function($query)  use($levelIds){
                return $query->join('resource_levels AS rl', function ($join) use($levelIds) {
                    $join->on('rl.resource_id', '=', 'rs.id')
                        ->whereIn('rl.tid', $levelIds);
                });
            })
            ->when(count($typeIds) > 0, function($query)  use($typeIds){
                return $query->join('resource_learning_resource_types AS rlrt', function ($join) use($typeIds) {
                    $join->on('rlrt.resource_id', '=', 'rs.id')
                            ->whereIn('rlrt.tid', $typeIds);
                    });
            })
            ->when(count($searchQuery) > 0, function($query)  use($searchQuery){
                return $query->where('rs.title','like','%'.$searchQuery.'%')
                    ->orwhere('rs.abstract', 'like' , '%'.$searchQuery.'%');
            })
            ->where('rs.language',Config::get('app.locale'))
            ->where('rs.status', 1)
            ->groupBy(
                'rs.id',
                'rs.language', 
                'rs.title',
                'rs.abstract',
                'rs.user_id',
                'users.username',
                'rs.status',
                'rs.updated_at'
            )
            ->paginate(32);

        return $records;    
    }

    //Total resources based on level
    public function totalResourcesByLevel()
    {
        $records = DB::table('resources AS rs')
            ->select(
                'ttd.id', 
                'ttd.name', 
                'rs.language',
                DB::Raw('count(rs.id) as total')
            )
            ->join('resource_levels AS rl','rl.resource_id','=','rs.id')
            ->join('taxonomy_term_data AS ttd', function($join){
                $join->on('ttd.id','=','rl.tid')
                    ->where('ttd.vid', 13);
            })
            ->groupBy(
                'ttd.name', 
                'ttd.id', 
                'rs.language')
            ->orderBy('rs.language')
            ->orderBy('total','DESC')
            ->get();
        return $records;   
    }

    //Total resources based on Resource Type
    public function totalResourcesByType()
    {
        $records = DB::table('resources AS rs')
            ->select(
                'ttd.id',
                'ttd.name',
                'rs.language',
                DB::Raw('count(rs.id) as total')
            )
            ->join('resource_learning_resource_types AS rlrt','rlrt.resource_id','=','rs.id')
            ->join('taxonomy_term_data AS ttd', function($join){
                $join->on('ttd.id','=','rlrt.tid')
                    ->where('ttd.vid', 7);
            })
            ->groupBy(
                'ttd.id',
                'ttd.name',
                'rs.language'
            )
            ->orderby('rs.language')
            ->orderBy('total','DESC')
            ->get();
        return $records;   
    }

    //Total resources by attachment type (format)
    public function totalResourcesByFormat()
    {
        $records = DB::table('resources AS rs')
            ->select(
                'ra.file_mime', 
                'rs.language',
                DB::raw('count(rs.id) as total')
            )
            ->join('resource_attachments AS ra','ra.resource_id','=','rs.id')
            ->groupBy('ra.file_mime', 'rs.language')
            ->orderby('rs.language')
            ->orderBy('total','DESC')
            ->get();
        return $records;   
    }

    public function getRelatedResources($resourceId, $subjectAreas)
    {
        $ids = array();
        foreach($subjectAreas AS $item)
        {
            array_push($ids, $item->id);
        }

        $records = DB::table('resources AS rs')
            ->select(
                'rs.id',
                'rs.title',
                'rs.abstract'
            )
            ->join('resource_subject_areas AS rsa','rsa.resource_id','=','rs.id')
            //not to include the record itself in the related items part
            ->where('rs.id','!=', $resourceId)
            ->where('rs.status', 1)
            ->whereIn('rsa.tid',$ids)
            ->limit(5)
            ->get();
        
        return $records;
    }

    public function subjectIconsAndTotal()
    {
        $records = DB::table('resource_subject_areas AS sarea')
            ->select(
                'sticons.file_name', 
                'ttd.name', 
                'sarea.tid AS subject_area',
                DB::raw('count(sarea.tid) AS total')
            )
            ->leftJoin('taxonomy_term_data AS ttd', function($join){
                $join->on('ttd.id', '=', 'sarea.tid')
                    ->where('ttd.vid', 8);
            })
            ->join('static_subject_area_icons AS sticons','sticons.tid','=','ttd.id')
            ->where('ttd.language', Config::get('app.locale'))
            ->groupBy(
                'sarea.tid', 
                'sticons.file_name',
                'ttd.name'
            )
            ->get();
        return $records;
    }

    public function featuredCollections()
    {
        $records = DB::table('featured_collections AS fcid')
            ->select(
                'fcid.id', 
                'ttd.name', 
                'fcid.icon', 
                'ttd.language', 
                'fu.url', 
                'frt.type_id', 
                'frs.subject_id', 
                'frls.level_id'
            )
            ->leftJoin('taxonomy_term_data AS ttd', function($join){
                $join->on('ttd.id', '=', 'fcid.name_tid')
                    ->where('ttd.vid', 21);
            })
            ->leftJoin('featured_resource_levels AS frl','frl.fcid', '=', 'fcid.id')
            ->leftJoin('featured_urls AS fu','fu.fcid', '=' ,'fcid.id')
            ->leftJoin('featured_resource_types AS frt','frt.fcid', '=', 'fcid.id')
            ->leftJoin('featured_resource_subjects AS frs', 'frs.fcid', '=', 'fcid.id')
            ->leftJoin('featured_resource_levels AS frls', 'frls.fcid', '=', 'fcid.id')
            ->where('ttd.language',Config::get('app.locale'))
            ->orderBy('fcid.id')
            ->get();

        return $records;
    }

    public function resourceAttachments($resourceId)
    {
        $records = DB::table('resource_attachments AS ra')
            ->select('*')
            ->where('ra.resource_id', $resourceId)
            ->get();
        return $records;
    }

    public function getResourceTranslations($resourceId)
    {
        $record = DB::table('resources AS rs')
            ->select(
                'rs.id',
                'rs.language'
            )
            ->where('rs.tnid', $resourceId)
            ->get();
        return $record;
    }

    public function saveTheResource($resource=array())
    {
        return true;
    }

    public function insertFavorite($resourceId, $userId)
    {
        $record = DB::table('resource_favorites')
            ->where('resource_id', $resourceId)
            ->where('user_id', $userId)
            ->first();

        if($record){
            DB::table('resource_favorites')
                ->where('resource_id', $resourceId)
                ->where('user_id', $userId)
                ->delete();

            return "deleted";
        }else{
            $record = DB::table('resource_favorites')->insertGetId([
                'resource_id'    => $resourceId,
                'user_id'        => $userId,
                'created_at'       => \Carbon\Carbon::now(),
                'updated_at'       => \Carbon\Carbon::now()
            ]);

            if($record){
                return "added";
            }
        }
    }

    public function insertFlag($params)
    {
        $record = DB::table('resource_flags')->insertGetId([
            'resource_id'    => $params['resource_id'],
            'user_id'        => $params['userid'],
            'type'          => $params['type'],
            'details'       => $params['details'],
            'created_at'       => \Carbon\Carbon::now(),
            'updated_at'       => \Carbon\Carbon::now()
        ]);
          
        return $record;
    }

    public function insertComment($params)
    {
        $record = DB::table('resource_comments')->insertGetId([
            'resource_id'    => $params['resource_id'],
            'user_id'        => $params['userid'],
            'comment'       => $params['comment'],
            'status'        => 0,
            'created'       => \Carbon\Carbon::now(),
            'updated'       => \Carbon\Carbon::now()
        ]);
          
        return $record;
    }

    public function updateResourceCounter($data)
    {
        return DB::table('resource_views')->insertGetId([
            'resource_id'       => $data['resource_id'],
            'user_id'            => $data['userid'],
            'ip'                => $data['ip'],
            'browser_name'      => $data['browser_name'],
            'browser_version'   => $data['browser_version'],
            'platform'          => $data['platform'],
            'created_at'           => \Carbon\Carbon::now()->timestamp
        ]);
    }

    public function insertResources($resourceId, $arr)
    {
        if($resourceId){
            //Main 
            DB::table('resources')->insert([
                'resourceid'        => $resourceId,
                'created'           => $arr['resource_created'],
                'updated'           => \Carbon\Carbon::now()->timestamp
            ]);
            $resourceId = $resourceId;
        }else{
            //Main 
            $resourceId = DB::table('resources')->insertGetId([
                'created'           => \Carbon\Carbon::now()->timestamp
            ]);
        }

        //Resource Data
        DB::table('resources_data')->insert([
            'resourceid'        => $resourceId,
            'title'             => $arr['title'],
            'abstract'          => $arr['abstract'],
            'language'          => $arr['language'],
            'userid'            => Auth::id(),
            'status'            => isset($arr['published'])?$arr['published']:0,
            'tnid'              => $resourceId,
            'created'           => isset($arr['resource_created'])?$arr['resource_created']:\Carbon\Carbon::now()->timestamp,
            'updated'           => \Carbon\Carbon::now()->timestamp
        ]);

        //Resource attachments
        for($i=0; $i<count($arr['attc']); $i++){
            DB::table('resources_attachments')->insertGetId([
                'resourceid'        => $resourceId,
                'file_name'         => $arr['attc'][$i]['file_name'],
                'file_mime'         => $arr['attc'][$i]['file_mime'],
                'file_size'         => $arr['attc'][$i]['file_size']
            ]);
        }

        //Author
        if(isset($arr['author'])){
            $authorId = DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 24,
                'name'      => $arr['author'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_authors')->insert([
                'resourceid'            => $resourceId,
                'author_name_tid'       => $authorId
            ]);
        }

        //Publisher
        if(isset($arr['publisher'])){
            $publisherId = DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 9,
                'name'      => $arr['publisher'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_publishers')->insert([
                'resourceid'          => $resourceId,
                'publisher_name_tid'  => $publisherId
            ]);
        }

        //Translator
        if(isset($arr['translator'])){
            $translatorId = DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 22,
                'name'      => $arr['translator'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_translators')->insert([
                'resourceid'            => $resourceId,
                'translator_name_tid'   => $translatorId
            ]);
        }

        //Copyright holder
        if(isset($arr['copyright_holder'])){
            $copyrightHolderId = DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 26,
                'name'      => $arr['copyright_holder'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_copyright_holders')->insert([
                'resourceid'         => $resourceId,
                'copyright_holder'   => $copyrightHolderId
            ]);
        }

        //Creative commons
        if(isset($arr['creative_commons'])){
            $creativeCommonsId = DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 10,
                'name'      => $arr['creative_commons'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_creative_commons')->insert([
                'resourceid'         => $resourceId,
                'creative_commons'   => $creativeCommonsId
            ]);
        }

        //Creative commons other
        if(isset($arr['creative_commons_other'])){
            $creativeCommonsOtherId =DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 27,
                'name'      => $arr['creative_commons_other'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_creative_commons')->insert([
                'resourceid'         => $resourceId,
                'creative_commons'   => $creativeCommonsOtherId
            ]);
        }

        //Keywords
        if(isset($arr['keywords'])){
            $keywordsId =DB::table('taxonomy_term_data')->insertGetId([
                'vid'       => 23,
                'name'      => $arr['keywords'],
                'language'  => $arr['language']
            ]);

            DB::table('resources_keywords')->insert([
                'resourceid'    => $resourceId,
                'keyword'       => $keywordsId
            ]);
        }

        //Resource Subjects
        foreach($arr['subject_areas'] AS $sa){
            DB::table('resources_subject_areas')->insert([
                'resourceid'        => $resourceId,
                'subject_area_tid'  => $sa
            ]);
        }

        //Learn resource types
        foreach($arr['learning_resources_types'] AS $ltype){
            DB::table('resources_learning_resource_types')->insert([
                'resourceid'        => $resourceId,
                'learning_resource_type_tid'  => $ltype
            ]);
        }

        //Educational use
        foreach($arr['educational_use'] AS $eduse){
            DB::table('resources_educational_uses')->insert([
                'resourceid'        => $resourceId,
                'educational_use'  => $eduse
            ]);
        }

        //Resource levels
        foreach($arr['level'] AS $level){
            DB::table('resources_levels')->insert([
                'resourceid'            => $resourceId,
                'resource_level_tid'    => $level
            ]);
        }

        //Translation rights
        if(isset($arr['translation_rights'])){
            DB::table('resources_translation_rights')->insert([
                'resourceid'           => $resourceId,
                'translation_right'    => $arr['translation_rights']
            ]);
        }

        //Educational resource
        if(isset($arr['educational_resource'])){
            DB::table('resources_educational_resources')->insert([
                'resourceid'           => $resourceId,
                'educational_resource' => $arr['educational_resource']
            ]);
        }

        //Creative commons
        if(isset($arr['creative_commons'])){
            DB::table('resources_creative_commons')->insert([
                'resourceid'            => $resourceId,
                'creative_commons'      => $arr['creative_commons']
            ]);
        }
    }

    public function deleteResources($resourceId)
    {
        $created = DB::table('resources')
            ->select('created')
            ->first();
        
        DB::table('resources')
        ->where('resourceid', $resourceId)
        ->delete();

        return $created->created;
    }
}
