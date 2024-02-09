<?php

namespace App\Http\Controllers;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxonomyController extends Controller
{
    public function index(Request $request): View
    {
        $this->middleware('admin');

        $terms = TaxonomyTerm::orderBy('vid', 'desc')->orderBy('weight')
            ->name(request('term'))
            ->vocabulary(request('vocabulary'))
            ->language(request('language'))
            ->paginate(10);

        $vocabulary = TaxonomyVocabulary::all('vid AS val', 'name');

        $args = [
            'route' => 'gettaxonomylist',
            'filters' => $request,
            'vocabulary' => $vocabulary,
        ];
        //creating search bar
        $createSearchBar = new SearchController();
        $searchBar = $createSearchBar->searchBar($args);

        return view('admin.taxonomy.taxonomy_list', compact('terms', 'searchBar'));
    }

    public function edit($vid, $tid): View
    {
        $term = TaxonomyTerm::find($tid);
        $vocabulary = TaxonomyVocabulary::all();
        $parents = TaxonomyTerm::where('vid', $vid)->get();
        $theParent = TaxonomyHierarchy::where('tid', $tid)->first();
        if (isset($theParent->parent)) {
            $theParent = $theParent->parent;
        } else {
            $theParent = 0;
        }

        return view('admin.taxonomy.taxonomy_edit', compact('term', 'vocabulary', 'parents', 'theParent'));
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
        $term = new TaxonomyTerm;
        $term->vid = $request->input('vid');
        $term->name = $request->input('name');
        $term->weight = $request->input('weight');
        $term->language = $request->input('language');
        $term->tnid = $tnid;
        $term->save();

        $parent = new TaxonomyHierarchy();
        $parent->tid = $term->id;
        $parent->parent = $request->input('parent');
        $parent->save();

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item added successfully!');
    }
}
