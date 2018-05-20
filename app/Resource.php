<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Config;

class Resource extends Model
{
    public function scopeResources()
    {
        $users = DB::table('resources')
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
            ->orderBy('resources.created','desc')
            ->get();

        return $users;
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
            ->orderBy('resources.created','desc')
            ->paginate(30);

        return $users;
    }

    public function resourceAttributes($resourceId, $tableName, $fieldName)
    {
        $records = DB::table($tableName)
                ->select($fieldName)
                ->where('resourceid',$resourceId)
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
                    ->select('resources_subject_areas.subject_area', 'resources.language')
                    ->selectRaw('count(resources.resourceid) as total')
                    ->join('resources_subject_areas','resources_subject_areas.resourceid','=','resources.resourceid')
                    ->groupBy('resources_subject_areas.subject_area', 'resources.language')
                    ->orderby('resources.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    //Total resources based on level
    public function totalResourcesByLevel()
    {
        $records = DB::table('resources')
                    ->select('resources_levels.resource_level', 'resources.language')
                    ->selectRaw('count(resources.resourceid) as total')
                    ->join('resources_levels','resources_levels.resourceid','=','resources.resourceid')
                    ->groupBy('resources_levels.resource_level', 'resources.language')
                    ->orderBy('resources.language')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    //Total resources based on Resource Type
    public function totalResourcesByType()
    {
        $records = DB::table('resources')
                    ->select('resources_learning_resource_types.learning_resource_type', 'resources.language')
                    ->selectRaw('count(resources.resourceid) as total')
                    ->join('resources_learning_resource_types','resources_learning_resource_types.resourceid','=','resources.resourceid')
                    ->groupBy('resources_learning_resource_types.learning_resource_type', 'resources.language')
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
            ->select('*')
            ->where('title','like','%'.$searchQuery.'%')
            ->orwhere('abstract', 'like' , '%'.$searchQuery.'%')
            ->get();

        return $records;
    }

    public function getRelatedResources($resourceId, $subjectAreas)
    {
        $ids = array();
        foreach($subjectAreas AS $item)
        {
            array_push($ids, $item->subject_area);
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
                DB::raw('count(sarea.subject_area) AS total')
            )
            ->join('static_subject_areas AS starea','starea.id', '=', 'sarea.subject_area')
            ->join('static_subject_area_icons AS sticons','sticons.said','=','starea.id')
            ->where('starea.language', Config::get('app.locale'))
            ->groupBy('sarea.subject_area', 'sticons.file_name','starea.name')
            ->get();
        return $records;
    }
}
