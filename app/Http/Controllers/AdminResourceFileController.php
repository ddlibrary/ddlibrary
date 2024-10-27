<?php

namespace App\Http\Controllers;

use App\Enums\TaxonomyVocabularyEnum;
use App\Models\Resource;
use App\Models\ResourceFile;
use App\Models\TaxonomyTerm;
use App\Traits\LanguageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminResourceFileController extends Controller
{
    use LanguageTrait;

    public function index(Request $request){
        $languages = $this->getLanguages();
        $language = $request->language ? $request->language: config('app.locale');
        $myResources = new Resource();
        $subjects = $myResources->resourceAttributesList('taxonomy_term_data', TaxonomyVocabularyEnum::ResourceSubject, $language);
        $query = ResourceFile::query()
            ->where(function ($query) use ($request) {
                if ($request->subject_area_id) {
                    $subjectAreaId = $request->subject_area_id;
                    if($request->language != 'en'){
                        $taxonomyTerm = TaxonomyTerm::find($request->subject_area_id);
                        $subjectAreaId = TaxonomyTerm::where(['language' => $request->language, 'tnid' => $taxonomyTerm->tnid])->value('id');
                    }
                    $resourceFileIds = DB::table('resource_subject_areas')
                    ->join('resources', 'resource_subject_areas.resource_id', '=', 'resources.id')
                        ->where('tid', $subjectAreaId)
                        ->pluck('resource_file_id');
                    $query->whereIn('id', $resourceFileIds);
                }
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%");
                }
            });
        $count = $query->count();
        $images = $query->paginate()->appends($request->except(['page']));

        return view('admin.resource-images.index', compact('subjects', 'images', 'languages', 'totalResourceImages'));
    }
}
