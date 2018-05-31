<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Config;

class Resource extends Model
{
    public function scopeResources()
    {
        $resources = DB::table('resources')
            ->select(
                'resources.resourceid',
                'resources.language', 
                'resources.title',
                'resources.abstract',
                'resources.userid',
                'resources.creative_commons',
                'users.username AS author',
                'resources.status',
                'resources.updated'
            )
            ->leftJoin('users', 'users.id', '=', 'resources.userid')
            ->where('resources.language',Config::get('app.locale'))
            ->orderBy('resources.created','desc')
            ->get();
        
        if($resources){
            return $resources;
        }else{
            return abort(404);
        }
    }

    public function paginateResources()
    {
        $users = DB::table('resources')
            ->select(
                'resources.resourceid',
                'resources.language', 
                'resources.title',
                'resources.abstract',
                'resources.userid',
                'users.username AS author',
                'resources.status',
                'resources.updated'
            )
            ->join('users', 'users.id', '=', 'resources.userid')
            ->where('resources.language',Config::get('app.locale'))
            ->orderBy('resources.created','desc')
            ->paginate(32);

        return $users;
    }

    public function resourceAttributes($resourceId, $tableName, $fieldName, $staticTable)
    {
        $records = DB::table($tableName)
                ->select($staticTable.'.name', $staticTable.'.id')
                ->join($staticTable, $staticTable.'.id', '=', $tableName.'.'.$fieldName)
                ->where('resourceid',$resourceId)
                ->get();
        return $records;
    }

    public function resourceAttributesList($tableName)
    {
        $records = DB::table($tableName)
                ->where('language',Config::get('app.locale'))
                ->get();
        return $records;
    }

    public function totalResources()
    {
        $records = DB::table('resources')
                    ->selectRaw('resources.resourceid as totalResources')
                    ->count();
        return $records;
    }

    //Total resources based on language
    public function totalResourcesByLanguage()
    {
        $records = DB::table('resources')
                    ->select('language')
                    ->selectRaw('count(resourceid) as total')
                    ->groupBy('language')
                    ->get();
        return $records;   
    }

    //Total resources based on subject area
    public function totalResourcesBySubject()
    {
        $records = DB::table('resources')
                    ->select(
                        'static_subject_areas.name',
                        'static_subject_areas.id',
                        'resources.language',
                        DB::raw('count(resources.resourceid) as total')
                    )
                    ->join('resources_subject_areas','resources_subject_areas.resourceid','=','resources.resourceid')
                    ->join('static_subject_areas','static_subject_areas.id','=','resources_subject_areas.subject_area')
                    ->groupBy(
                        'static_subject_areas.name', 
                        'static_subject_areas.id', 
                        'resources.language')
                    ->orderby('resources.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    public function paginateResourcesBy($subjectAreaIds, $levelIds, $typeIds){
        $records = DB::table('resources')
            ->select(
                'resources.resourceid',
                'resources.language', 
                'resources.title',
                'resources.abstract',
                'resources.userid',
                'users.username AS author',
                'resources.status',
                'resources.updated'
            )
            ->join('users', 'users.id', '=', 'resources.userid')
            ->when(count($subjectAreaIds) > 0, function($query) use($subjectAreaIds){
                return $query->join('resources_subject_areas', function ($join) use($subjectAreaIds) {
                    $join->on('resources_subject_areas.resourceid', '=', 'resources.resourceid')
                        ->whereIn('resources_subject_areas.subject_area', $subjectAreaIds);
                });
            })
            ->when(count($levelIds) > 0, function($query)  use($levelIds){
                return $query->join('resources_levels', function ($join) use($levelIds) {
                    $join->on('resources_levels.resourceid', '=', 'resources.resourceid')
                        ->whereIn('resources_levels.resource_level', $levelIds);
                });
            })
            ->when(count($typeIds) > 0, function($query)  use($typeIds){
                return $query->join('resources_learning_resource_types', function ($join) use($typeIds) {
                $join->on('resources_learning_resource_types.resourceid', '=', 'resources.resourceid')
                        ->whereIn('resources_learning_resource_types.learning_resource_type', $typeIds);
                });
            })
            ->where('resources.language',Config::get('app.locale'))
            ->paginate(32);

        return $records;    
    }

    //Total resources based on level
    public function totalResourcesByLevel()
    {
        $records = DB::table('resources')
                    ->select(
                        'static_levels.id', 
                        'static_levels.name', 
                        'resources.language',
                        DB::Raw('count(resources.resourceid) as total')
                    )
                    ->join('resources_levels','resources_levels.resourceid','=','resources.resourceid')
                    ->join('static_levels','static_levels.id','=','resources_levels.resource_level')
                    ->groupBy(
                        'static_levels.name', 
                        'static_levels.id', 
                        'resources.language')
                    ->orderBy('resources.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    //Total resources based on Resource Type
    public function totalResourcesByType()
    {
        $records = DB::table('resources')
                    ->select(
                        'static_learning_resource_types.id',
                        'static_learning_resource_types.name',
                        'resources.language',
                        DB::Raw('count(resources.resourceid) as total')
                    )
                    ->join('resources_learning_resource_types','resources_learning_resource_types.resourceid','=','resources.resourceid')
                    ->join('static_learning_resource_types','static_learning_resource_types.id','=','resources_learning_resource_types.learning_resource_type')
                    ->groupBy(
                        'static_learning_resource_types.id',
                        'static_learning_resource_types.name',
                        'resources.language'
                    )
                    ->orderby('resources.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    //Total resources by attachment type (format)
    public function totalResourcesByFormat()
    {
        $records = DB::table('resources')
                    ->select('resources_attachments.file_mime', 'resources.language')
                    ->selectRaw('count(resources.resourceid) as total')
                    ->join('resources_attachments','resources_attachments.resourceid','=','resources.resourceid')
                    ->groupBy('resources_attachments.file_mime', 'resources.language')
                    ->orderby('resources.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    public function searchResources($searchQuery)
    {
        $records = DB::table('resources')
            ->select(
                '*',
                'resources_attachments.file_mime'
            )
            ->join('resources_attachments','resources_attachments.resourceid','=','resources.resourceid')
            ->where('title','like','%'.$searchQuery.'%')
            ->orwhere('abstract', 'like' , '%'.$searchQuery.'%')
            ->paginate(30);

        return $records;
    }

    public function getRelatedResources($resourceId, $subjectAreas)
    {
        $ids = array();
        foreach($subjectAreas AS $item)
        {
            array_push($ids, $item->id);
        }

        $records = DB::table('resources')
            ->select(
                'resources.resourceid',
                'resources.title',
                'resources.abstract'
                )
            ->join('resources_subject_areas AS rsa','rsa.resourceid','=','resources.resourceid')
            //not to include the record itself in the related items part
            ->where('resources.resourceid','!=', $resourceId)
            ->whereIn('rsa.subject_area',$ids)
            ->limit(5)
            ->get();
        
        return $records;
    }

    public function subjectIconsAndTotal()
    {
        $records = DB::table('resources_subject_areas AS sarea')
            ->select(
                'sticons.file_name', 
                'starea.name', 
                'sarea.subject_area',
                DB::raw('count(sarea.subject_area) AS total')
            )
            ->join('static_subject_areas AS starea','starea.id', '=', 'sarea.subject_area')
            ->join('static_subject_area_icons AS sticons','sticons.said','=','starea.id')
            ->where('starea.language', Config::get('app.locale'))
            ->groupBy('sarea.subject_area', 'sticons.file_name','starea.name')
            ->get();
        return $records;
    }

    public function featuredCollections()
    {
        $records = DB::table('featured_collections AS fcid')
            ->select(
                'fcid.id', 
                'fcid.name', 
                'fcid.icon', 
                'fcid.language', 
                'fu.url', 
                'frt.type_id', 
                'frs.subject_id', 
                'frls.level_id'
            )
            ->leftJoin('featured_resource_levels AS frl','frl.fcid', '=', 'fcid.id')
            ->leftJoin('featured_urls AS fu','fu.fcid', '=' ,'fcid.id')
            ->leftJoin('featured_resource_types AS frt','frt.fcid', '=', 'fcid.id')
            ->leftJoin('featured_resource_subjects AS frs', 'frs.fcid', '=', 'fcid.id')
            ->leftJoin('featured_resource_levels AS frls', 'frls.fcid', '=', 'fcid.id')
            ->where('fcid.language',Config::get('app.locale'))
            ->orderBy('fcid.id')
            ->get();

        return $records;
    }

    public function resourceAttachments($resourceId)
    {
        $records = DB::table('resources_attachments')
            ->select('*')
            ->where('resourceid', $resourceId)
            ->get();
        return $records;
    }
}
