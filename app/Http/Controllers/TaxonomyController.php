<?php

namespace App\Http\Controllers;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TaxonomyController extends Controller
{
    public function index(Request $request): View
    {
        $this->middleware('admin');

        $vocabulary = TaxonomyVocabulary::all('vid AS val', 'name');
        $vocabularyId = $request->input('vocabulary');
        $name = $request->input('term');

        $groupedTerms = [];
        
        if ($vocabularyId) {
            $query = TaxonomyTerm::with('vocabulary');
            
            $query->where('vid', $vocabularyId);
            
            if ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            }

            $terms = $query->orderBy('vid', 'desc')->orderBy('weight')->get();
            
            $groupedByTnid = $terms->groupBy(function($term) {
                return $term->tnid ?: $term->id;
            });
            
            $groupedTerms = $groupedByTnid->map(function($translations) {
                return [
                    'translations' => $translations,
                    'first_term' => $translations->first()
                ];
            })->values()->all();
        }

        // Get supported locales for view
        $laguages = LaravelLocalization::getSupportedLocales();

       

        return view('admin.taxonomy.taxonomy_list', compact('groupedTerms', 'vocabulary', 'laguages', 'vocabularyId'));
    }

    public function edit($vid, $tid): View
    {
        $term = TaxonomyTerm::findOrFail($tid);
        $tnid = $term->tnid;
        
        if ($tnid && $tnid != 0) {
            $translations = TaxonomyTerm::where('tnid', $tnid)->get();
            if (!$translations->contains('id', $tid)) {
                $translations->push($term);
            }
        } else {
            $translations = collect([$term]);
        }
        
        $vocabulary = TaxonomyVocabulary::all();
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        
        $translationData = [];
        foreach ($supportedLocales as $localeCode => $localeProperties) {
            $translation = $translations->where('language', $localeCode)->first();
            
            if (!$translation) {
                $translation = $translations->first(function($t) {
                    $lang = $t->language;
                    return empty($lang) || $lang === 'und';
                });
            }
            
            $parentId = 0;
            if ($translation) {
                $hierarchy = TaxonomyHierarchy::where('tid', $translation->id)->first();
                $parentId = $hierarchy && isset($hierarchy->parent) ? $hierarchy->parent : 0;
            }
            
            $translationData[$localeCode] = [
                'translation' => $translation,
                'name' => $translation ? $translation->name : '',
                'term_id' => $translation ? $translation->id : null,
                'parent_id' => $parentId,
                'has_invalid_lang' => $translation && ($translation->language !== $localeCode),
            ];
        }

        return view('admin.taxonomy.taxonomy_edit', compact(
            'term', 
            'vocabulary', 
            'supportedLocales',
            'translationData',
            'vid'
        ));
    }

    public function update(Request $request, $vid, $tid): RedirectResponse
    {
        $this->validate($request, [
            'vid' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'language' => 'required',
        ]);

        //Saving contact info to the database
        $term = TaxonomyTerm::find($tid);
        $term->vid = $request->input('vid');
        $term->name = $request->input('name');
        $term->weight = $request->input('weight');
        $term->language = $request->input('language');

        if ($term->tnid == 0) {
            $term->tnid = $tid;
        }

        $term->save();

        $parentid = $request->input('parent');

        $parent = TaxonomyHierarchy::firstOrNew(['tid' => $tid], ['parent' => $parentid]);

        if(!isset($parent->id)){
            $latestId = DB::table('taxonomy_term_hierarchy')->max('aux_id');
            $THID = $latestId ? $latestId + 1 : 1;
        }else{
            $THID = $parent->id; // taxonomy_term_hierarchy.id
        }

        $parent->id = $THID;
        $parent->tid = $tid;
        $parent->parent = $parentid;
        $parent->save();

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item updated successfully!');
    }

    public function translate($tid): View
    {
        $tnid = TaxonomyTerm::find($tid)->tnid;
        if ($tnid) {
            $translations = TaxonomyTerm::where('tnid', $tnid)->get();
        } else {
            $translations = null;
        }

        $locals = \LaravelLocalization::getSupportedLocales();
        $supportedLocals = [];

        foreach ($locals as $key => $value) {
            array_push($supportedLocals, $key);
        }

        return view('admin.taxonomy.taxonomy_translate', compact('translations', 'supportedLocals', 'tnid', 'tid'));
    }

    public function create(): View
    {
        $vocabulary = TaxonomyVocabulary::all();

        return view('admin.taxonomy.taxonomy_create', compact('vocabulary'));
    }

    public function getParentTaxonomy(Request $request)
    {
        $vid = $request->input('vid');
        
        if (!$vid) {
            return response()->json([]);
        }
        
        $parents = TaxonomyTerm::where('vid', $vid)
            ->orderBy('weight')
            ->orderBy('name')
            ->get();
        
        $result = [];
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        
        foreach ($parents as $parent) {
            $language = $parent->language;
            
            if (empty($language) || $language === 'und' || !isset($supportedLocales[$language])) {
                foreach ($supportedLocales as $localeCode => $localeProperties) {
                    if (!isset($result[$localeCode])) {
                        $result[$localeCode] = [];
                    }
                    $result[$localeCode][] = [
                        'id' => $parent->id,
                        'name' => $parent->name . ' (' . ($language ?: 'und') . ')',
                        'language' => $language ?: 'und',
                    ];
                }
            } else {
                if (!isset($result[$language])) {
                    $result[$language] = [];
                }
                $result[$language][] = [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'language' => $language,
                ];
            }
        }
        
        foreach ($result as $lang => $terms) {
            usort($result[$lang], function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
        }
        
        return response()->json($result);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'vid' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'language' => 'required',
        ]);

        //Saving contact info to the database
        $term = new TaxonomyTerm;
        $term->vid = $request->input('vid');
        $term->name = $request->input('name');
        $term->weight = $request->input('weight');
        $term->language = $request->input('language');

        $term->save();

        $term->tnid = $term->id;
        //updating with tnid
        $term->save();

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item created successfully!');
    }

    public function createTranslate($tid, $tnid, $lang)
    {
        $vocabulary = TaxonomyVocabulary::all();
        $vid = TaxonomyTerm::where('tnid', $tnid)->first()->vid;
        $weight = TaxonomyTerm::where('tnid', $tnid)->first()->weight;
        $parents = TaxonomyTerm::where('vid', $vid)->get();
        $sourceParent = TaxonomyHierarchy::where('tid', $tid)->first()->parent;
        if ($sourceParent) {
            $parentTermTnid = TaxonomyTerm::where('id', $sourceParent)->first()->tnid;
            $parentTranslation = TaxonomyTerm::where('tnid', $parentTermTnid)->where('language', $lang)->first();
            //If the parent is translated in current language
            if ($parentTranslation) {
                $theParent = $parentTranslation->id;
            } else {
                return 'First translate the parent';
            }
        } else {
            $theParent = 0;
        }

        return view('admin.taxonomy.taxonomy_create_translate', compact(
            'vocabulary',
            'tnid',
            'vid',
            'lang',
            'weight',
            'parents',
            'theParent'
        ));
    }

    public function storeTranslate(Request $request, $tnid): RedirectResponse
    {
        $this->validate($request, [
            'vid' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'language' => 'required',
        ]);

        //Saving contact info to the database
        $term = TaxonomyTerm::create([
            'vid' => $request->input('vid'),
            'name' => $request->input('name'),
            'weight' => $request->input('weight'),
            'language' => $request->input('language'),
            'tnid' => $tnid
        ]);

        TaxonomyHierarchy::insert([
            'id' => (int)(TaxonomyHierarchy::latest()->value('id') + 1),
            'tid' => $term->id,
            'parent' => $request->input('parent') ? $request->input('parent') : 0,
            'aux_id' => $request->input('aux_id') ? $request->input('aux_id') : $term->id,
        ]);

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item added successfully!');
    }
}
