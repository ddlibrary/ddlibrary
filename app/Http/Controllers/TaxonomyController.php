<?php

namespace App\Http\Controllers;
use App\TaxonomyTerm;
use App\TaxonomyVocabulary;

use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
    public function index(Request $request)
    {
        $this->middleware('admin');
        
        $terms = TaxonomyTerm::orderBy('vid','desc')->orderBy('weight')
        ->name(request('term'))
        ->vocabulary(request('vocabulary'))
        ->language(request('language'))
        ->paginate(10);

        $vocabulary = TaxonomyVocabulary::all('vid AS val','name');

        $args = array(
            'route'         => 'taxonomylist',
            'filters'       => $request,
            'vocabulary'    => $vocabulary
        );
        //creating search bar
        $createSearchBar = new SearchController();
        $searchBar = $createSearchBar->searchBar($args);
        return view('admin.taxonomy.taxonomy_list', compact('terms', 'searchBar'));
    }

    public function edit($tid)
    {
        $term = TaxonomyTerm::find($tid);
        $vocabulary = TaxonomyVocabulary::all();
        return view('admin.taxonomy.taxonomy_edit', compact('term', 'vocabulary'));
    }

    public function update(Request $request, $tid)
    {
        $this->validate($request, [
            'vid'           => 'required',
            'name'          => 'required',
            'weight'        => 'required',
            'language'      => 'required'
        ]);

        //Saving contact info to the database
        $term = TaxonomyTerm::find($tid);
        $term->vid = $request->input('vid');
        $term->name = $request->input('name');
        $term->weight = $request->input('weight');
        $term->language = $request->input('language');

        if($term->tnid == 0){
            $term->tnid = $tid;
        }

        $term->save();

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item updated successfully!');
    }

    public function translate($tid)
    {
        $tnid = TaxonomyTerm::find($tid)->tnid;
        if($tnid){
            $translations = TaxonomyTerm::where('tnid', $tnid)->get();
        }else{
            $translations = NULL;
        }

        $locals = \LaravelLocalization::getSupportedLocales();
        $supportedLocals = array();

        foreach($locals as $key=>$value){
            array_push($supportedLocals, $key);
        }

        return view('admin.taxonomy.taxonomy_translate', compact('translations','supportedLocals','tnid'));      
    }

    public function create()
    {
        $vocabulary = TaxonomyVocabulary::all();
        return view('admin.taxonomy.taxonomy_create',compact('vocabulary'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'vid'           => 'required',
            'name'          => 'required',
            'weight'        => 'required',
            'language'      => 'required'
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

    public function createTranslate($tnid, $lang)
    {
        $vocabulary = TaxonomyVocabulary::all();
        $vid = TaxonomyTerm::where('tnid',$tnid)->first()->vid;
        $weight = TaxonomyTerm::where('tnid', $tnid)->first()->weight;
        return view('admin.taxonomy.taxonomy_create_translate',compact('vocabulary','tnid','lang', 'vid', 'weight'));
    }

    public function storeTranslate(Request $request, $tnid)
    {
        $this->validate($request, [
            'vid'           => 'required',
            'name'          => 'required',
            'weight'        => 'required',
            'language'      => 'required'
        ]);

        

        //Saving contact info to the database
        $term = new TaxonomyTerm;
        $term->vid = $request->input('vid');
        $term->name = $request->input('name');
        $term->weight = $request->input('weight');
        $term->language = $request->input('language');
        $term->tnid = $tnid;
        $term->save();

        return redirect('/admin/taxonomy')->with('success', 'Taxonomy item added successfully!');
    }
}
