<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\TaxonomyTermCreateRequest;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use App\Services\TaxonomyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Requests\Admin\TaxonomyTermListRequest;
use App\Http\Requests\Admin\TaxonomyTermRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TaxonomyController extends Controller
{
    protected $taxonomyService;

    public function __construct(TaxonomyService $taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }
    public function index(TaxonomyTermListRequest $request): View
    {
        $taxonomyVocabularies = TaxonomyVocabulary::all(['vid', 'name']);

        $taxonomyVocabularyId = $request->input('taxonomy_vocabulary_id');
        $name = $request->input('term');
        $language = $request->input('language', 'en');

        $terms = [];
        if ($taxonomyVocabularyId) {
            $terms = TaxonomyTerm::with(['vocabulary', 'translations'])->where([
                'vid' => $taxonomyVocabularyId,
                'language' => $language
            ])
            ->where(function($query) use ($name){
                $query->where('name', 'like', "%$name%");
            })
            ->orderBy('vid', 'desc')->orderBy('weight')->get();
        }

        $languages = LaravelLocalization::getSupportedLocales();

        return view('admin.taxonomy.taxonomy_list', compact('taxonomyVocabularies', 'terms', 'languages', 'taxonomyVocabularyId'));
    }

    public function create(TaxonomyTermCreateRequest $request): View|RedirectResponse
    {
        $selectedVocabulary = $request->input('vid');

        $parents = null;

        if ($selectedVocabulary) {
            if (TaxonomyVocabulary::where('vid', $selectedVocabulary)->doesntExist()) {
                return redirect()->route('taxonomycreate')->with('error', 'This vocabulary does not exist!');
            }

            $parents = TaxonomyTerm::where('vid', $selectedVocabulary)
                ->orderBy('weight')
                ->orderBy('name')
                ->get();
        }

        $taxonomyVocabularies = TaxonomyVocabulary::all(['vid', 'name']);
        $supportedLocales = LaravelLocalization::getSupportedLocales();

        return view('admin.taxonomy.taxonomy_create', compact('taxonomyVocabularies', 'parents', 'supportedLocales', 'selectedVocabulary'));
    }

    public function store(TaxonomyTermRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $vid = $request->input('vid');
            $weight = $request->input('weight');
            $names = $request->input('names', []);
            $parents = $request->input('parents', []);
            $tnid = null;

            foreach ($names as $language => $name) {
                $name = trim($name);
                if ($name === '') {
                    continue;
                }

                $parentId = $parents[$language] ?? 0;
                $term = $this->taxonomyService->saveTranslationTerm($vid, $name, $weight, $language, $tnid, $parentId, null);

                if ($tnid === null) {
                    $tnid = $term->id;
                    $term->update(['tnid' => $tnid]);
                }
            }

            DB::commit();
            return redirect()->route('gettaxonomylist', ['taxonomy_vocabulary_id' => $vid])
                            ->with('success', 'Taxonomy item created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Taxonomy item was not created!');
        }
    }

    public function edit(TaxonomyTermCreateRequest $request, TaxonomyVocabulary $taxonomyVocabulary, $tid): View|RedirectResponse
    {
        $vid = $request->vid ?? $taxonomyVocabulary->vid;

        if ($vid !== $taxonomyVocabulary->vid && TaxonomyVocabulary::where('vid', $vid)->doesntExist()) {
            return redirect()->route('taxonomyedit', [$taxonomyVocabulary->vid, $tid])
                            ->with('error', 'This vocabulary does not exist!');
        }

        $term = TaxonomyTerm::with(['translations','translations.taxonomyHierarchy:parent,tid','vocabulary'])->findOrFail($tid);
        $parents = TaxonomyTerm::where('vid', $vid)->orderBy('weight')->orderBy('name')->get();
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        
        $translationData = [];
        foreach ($supportedLocales as $localeCode => $localeProperties) {
            $translation = $term->translations->where('language', $localeCode)->first();
            
            $translationData[$localeCode] = [
                'name' => $translation->name ?? '',
                'term_id' => $translation->id ?? null,
                'parent_id' => $translation->taxonomyHierarchy->parent ?? null,
            ];
        }

        $taxonomyVocabularies = TaxonomyVocabulary::all(['vid', 'name']);

        return view('admin.taxonomy.taxonomy_edit', compact(
            'taxonomyVocabularies',
            'supportedLocales',
            'translationData',
            'parents',
            'term',
            'vid'
        ));
    }
    

    public function update(TaxonomyTermRequest $request, $vid, $tid): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $term = TaxonomyTerm::findOrFail($tid);

            $tnid = $term->tnid ?: $tid;
            $vid = $request->input('vid');
            $weight = $request->input('weight');
            $names = $request->input('names', []);
            $termIds = $request->input('term_ids', []);
            $parents = $request->input('parents', []);

            foreach ($names as $language => $name) {
                $name = trim($name);
                if (empty($name)) {
                    continue;
                }

                $termId = $termIds[$language] ?? null;
                $parentId = $parents[$language] ?? 0;

                $this->taxonomyService->saveTranslationTerm($vid, $name, $weight, $language, $tnid, $parentId, $termId);
            }

            DB::commit();
            return redirect()->route('gettaxonomylist', ['taxonomy_vocabulary_id' => $vid])
                            ->with('success', 'Taxonomy item updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Taxonomy item was not updated!');
        }
    }

}
