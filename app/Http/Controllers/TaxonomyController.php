<?php

namespace App\Http\Controllers;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use App\Services\TaxonomyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TaxonomyController extends Controller
{
    protected TaxonomyService $taxonomyService;

    public function __construct(TaxonomyService $taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }

    public function index(Request $request): View
    {
        $this->middleware('admin');

        $vocabulary = TaxonomyVocabulary::all('vid AS val', 'name');
        $vocabularyId = $request->input('vocabulary');
        $termName = $request->input('term');

        // Require vocabulary filter - don't show records if no vocabulary selected
        if (!$vocabularyId) {
            $supportedLocales = LaravelLocalization::getSupportedLocales();
            return view('admin.taxonomy.taxonomy_list', compact('vocabulary', 'supportedLocales'));
        }

        // Use service to get grouped terms with hierarchy
        $result = $this->taxonomyService->getGroupedTermsWithHierarchy($vocabularyId, $termName);
        $groupedTerms = $result['groupedTerms'];
        $parentInfo = $result['parentInfo'];

        $supportedLocales = LaravelLocalization::getSupportedLocales();

        return view('admin.taxonomy.taxonomy_list', compact('groupedTerms', 'vocabulary', 'supportedLocales', 'parentInfo'));
    }

    public function edit($vid, $tid): View
    {
        $term = TaxonomyTerm::findOrFail($tid);
        $tnid = $term->tnid;
        
        // Get all translations with the same tnid
        // If tnid exists and is not 0, get all terms with that tnid
        if ($tnid && $tnid != 0) {
            $translations = TaxonomyTerm::where('tnid', $tnid)->get();
            // Ensure we include the term itself if it's not in the collection
            if (!$translations->contains('id', $tid)) {
                $translations->push($term);
            }
        } else {
            // No tnid set yet, just use this term
            $translations = collect([$term]);
        }
        
        $vocabulary = TaxonomyVocabulary::all();
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        
        // Get current parent IDs for each translation
        $currentParents = [];
        foreach ($translations as $trans) {
            $hierarchy = TaxonomyHierarchy::where('tid', $trans->id)->first();
            $lang = $trans->language ?? 'und';
            $currentParents[$lang] = $hierarchy && isset($hierarchy->parent) ? $hierarchy->parent : 0;
            // Also store by term id for lookup
            $currentParents['tid_' . $trans->id] = $hierarchy && isset($hierarchy->parent) ? $hierarchy->parent : 0;
        }
        
        // Pre-load parents for existing translations only (will be loaded via AJAX for others)
        $allParents = TaxonomyTerm::where('vid', $vid)->get();

        return view('admin.taxonomy.taxonomy_edit', compact(
            'term', 
            'translations', 
            'vocabulary', 
            'allParents', 
            'currentParents',
            'supportedLocales',
            'tnid',
            'vid'
        ));
    }

    public function update(Request $request, $vid, $tid): RedirectResponse
    {
        $this->validate($request, [
            'vid' => 'required',
            'weight' => 'required',
            'names' => 'required|array',
        ]);

        $term = TaxonomyTerm::findOrFail($tid);
        $tnid = $term->tnid;
        
        // If tnid is not set or is 0, use the term's id as tnid
        // But if tnid equals tid, that's actually correct - it means this is the first term
        // and all other terms should have this tnid
        if (!$tnid || $tnid == 0) {
            $tnid = $tid;
        }
        // If tnid equals tid, that's fine - it means this term is the root of the translation group
        
        $newVid = $request->input('vid');
        $weight = $request->input('weight');
        $names = $request->input('names', []);
        $termIds = $request->input('term_ids', []);
        $parents = $request->input('parents', []);

        // Update or create translations for each language
        foreach ($names as $language => $name) {
            $name = trim($name);
            if (empty($name)) {
                continue; // Skip empty names
            }

            $termId = $termIds[$language] ?? null;
            
            if ($termId) {
                // Update existing translation
                $translation = TaxonomyTerm::find($termId);
                if ($translation) {
                    $translation->vid = $newVid;
                    $translation->name = $name;
                    $translation->weight = $weight;
                    $translation->tnid = $tnid;
                    $translation->save();
                }
            } else {
                // Create new translation
                $translation = new TaxonomyTerm();
                $translation->vid = $newVid;
                $translation->name = $name;
                $translation->weight = $weight;
                $translation->language = $language;
                $translation->tnid = $tnid;
                $translation->save();
                $termId = $translation->id;
            }

            // Update parent hierarchy
            $parentId = $parents[$language] ?? 0;
            $hierarchy = TaxonomyHierarchy::where('tid', $termId)->first();
            
            if (!$hierarchy) {
                $latestId = DB::table('taxonomy_term_hierarchy')->max('aux_id');
                $THID = $latestId ? $latestId + 1 : 1;
                $hierarchy = new TaxonomyHierarchy();
                $hierarchy->id = $THID;
                $hierarchy->tid = $termId;
                $hierarchy->aux_id = $termId;
            }
            
            $hierarchy->parent = $parentId;
            $hierarchy->save();
        }

        // Update the main term's tnid and other fields
        $term->tnid = $tnid;
        $term->vid = $newVid;
        $term->weight = $weight;
        $term->save();

        $redirectUrl = '/admin/taxonomy';
        if ($request->has('vocabulary')) {
            $redirectUrl .= '?vocabulary=' . $request->input('vocabulary');
        }

        return redirect($redirectUrl)->with('success', 'Taxonomy item updated successfully!');
    }

    public function translate($tid): View
    {
        $tnid = TaxonomyTerm::find($tid)->tnid;
        if ($tnid) {
            $translations = TaxonomyTerm::where('tnid', $tnid)->get();
        } else {
            $translations = null;
        }

        $locals = LaravelLocalization::getSupportedLocales();
        $supportedLocals = [];

        foreach ($locals as $key => $value) {
            array_push($supportedLocals, $key);
        }

        return view('admin.taxonomy.taxonomy_translate', compact('translations', 'supportedLocals', 'tnid', 'tid'));
    }

    public function create(Request $request): View
    {
        $vocabulary = TaxonomyVocabulary::all();
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        $selectedVocabulary = $request->input('vocabulary'); // Get vocabulary from query parameter

        return view('admin.taxonomy.taxonomy_create', compact('vocabulary', 'supportedLocales', 'selectedVocabulary'));
    }
    
    public function getParents(Request $request)
    {
        $vid = $request->input('vid');
        
        if (!$vid) {
            return response()->json([]);
        }
        
        // Get all parents for the vocabulary, grouped by language
        $parents = TaxonomyTerm::where('vid', $vid)
            ->orderBy('weight')
            ->orderBy('name')
            ->get();
        
        // Format response: { 'en': [...], 'fa': [...], ... }
        // Also handle records with NULL, empty, or 'und' language
        $result = [];
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        
        foreach ($parents as $parent) {
            $language = $parent->language;
            
            // Normalize invalid language values
            if (empty($language) || $language === 'und' || !isset($supportedLocales[$language])) {
                // For invalid languages, add to all language dropdowns so they can be selected
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
                // Valid language - add to its specific language group
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
        
        // Sort each language group
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
            'weight' => 'required',
            'names' => 'required|array',
        ]);

        $vid = $request->input('vid');
        $weight = $request->input('weight');
        $names = $request->input('names', []);
        $parents = $request->input('parents', []);
        
        $firstTermId = null;
        $tnid = null;

        // Create translations for each language
        foreach ($names as $language => $name) {
            $name = trim($name);
            if (empty($name)) {
                continue; // Skip empty names
            }

            $term = new TaxonomyTerm();
            $term->vid = $vid;
            $term->name = $name;
            $term->weight = $weight;
            $term->language = $language;
            $term->save();

            // Set tnid to the first term's id
            if (!$firstTermId) {
                $firstTermId = $term->id;
                $tnid = $term->id;
            }

            $term->tnid = $tnid;
            $term->save();

            // Create parent hierarchy
            $parentId = $parents[$language] ?? 0;
            $latestId = DB::table('taxonomy_term_hierarchy')->max('aux_id');
            $THID = $latestId ? $latestId + 1 : 1;
            
            $hierarchy = new TaxonomyHierarchy();
            $hierarchy->id = $THID;
            $hierarchy->tid = $term->id;
            $hierarchy->parent = $parentId;
            $hierarchy->aux_id = $term->id;
            $hierarchy->save();
        }

        $redirectUrl = '/admin/taxonomy';
        if ($request->has('vid')) {
            $redirectUrl .= '?vocabulary=' . $request->input('vid');
        }

        return redirect($redirectUrl)->with('success', 'Taxonomy item created successfully!');
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

        $latestId = DB::table('taxonomy_term_hierarchy')->max('aux_id');
        $THID = $latestId ? $latestId + 1 : 1;
        
        $hierarchy = new TaxonomyHierarchy();
        $hierarchy->id = $THID;
        $hierarchy->tid = $term->id;
        $hierarchy->parent = $request->input('parent') ? $request->input('parent') : 0;
        $hierarchy->aux_id = $request->input('aux_id') ? $request->input('aux_id') : $term->id;
        $hierarchy->save();

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item added successfully!');
    }
}
