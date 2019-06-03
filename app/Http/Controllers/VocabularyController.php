<?php

namespace App\Http\Controllers;
use App\TaxonomyVocabulary;

use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    //Index Function
    public function index()
    {
        $vocabularies = TaxonomyVocabulary::all();
        return view('admin.vocabulary.vocabulary_list', compact('vocabularies'));
    }

    //Vocabulary Create Function
    public function create()
    {
        return view('admin.vocabulary.vocabulary_create');
    }

    //Vocabulary Store Function
    public function store(Request $request)
    {
        $attr = $this->validate($request, [
            'name'          => 'required',
            'weight'        => 'required',
            'language'      => 'required'
        ]);

        $row = new TaxonomyVocabulary();

        $row->name      = $request->name;
        $row->weight    = $request->weight;
        $row->language  = $request->language;

        $row->save();

        return redirect('/admin/vocabulary')->with('success', 'Vocabulary item created successfully!');
    }

    //Vocabulary Edit Function
    public function edit($vid)
    {
        $vocabulary = TaxonomyVocabulary::find($vid);
        return view('admin.vocabulary.vocabulary_edit', compact('vocabulary'));
    }

    //Vocabulary Update Function
    public function update(Request $request, $vid)
    {
        $this->validate($request, [
            'name'          => 'required',
            'weight'        => 'required',
            'language'      => 'required'
        ]);

        //Updating vocabulary info to the database
        $vocabulary = TaxonomyVocabulary::find($vid);
        $vocabulary->name = $request->input('name');
        $vocabulary->weight = $request->input('weight');
        $vocabulary->language = $request->input('language');

        $vocabulary->save();

        return redirect('/admin/vocabulary')->with('success', 'Vocabulary item updated successfully!');
    }
}
