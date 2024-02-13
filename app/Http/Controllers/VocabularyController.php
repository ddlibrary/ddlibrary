<?php

namespace App\Http\Controllers;

use App\Models\TaxonomyVocabulary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;

class VocabularyController extends Controller
{
    //Index Function
    public function index(): View
    {
        return view('admin.vocabulary.vocabulary_list');
    }

    //Ajax get vocabularies Function
    public function getVocabularies()
    {
        //return dataTables::of(TaxonomyVocabulary::query())->make(true);
        $vocs = TaxonomyVocabulary::select(['vid', 'name', 'weight', 'language']);

        return Datatables::of($vocs)
            ->addColumn('action', function ($vocs) {
                return '<a href="'.URL('admin/vocabulary/edit/'.$vocs->vid).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
            })
            ->editColumn('language', '{{fixLanguage($language)}}')
            ->make(true);
    }

    //Vocabulary Create Function
    public function create(): View
    {
        return view('admin.vocabulary.vocabulary_create');
    }

    //Vocabulary Store Function
    public function store(Request $request): RedirectResponse
    {
        $attr = $this->validate($request, [
            'name' => 'required',
            'weight' => 'required',
            'language' => 'required',
        ]);
        $row = new TaxonomyVocabulary();
        $row->name = $request->name;
        $row->weight = $request->weight;
        $row->language = $request->language;
        $row->save();

        return redirect('/admin/vocabulary')->with('success', 'Vocabulary item created successfully!');
    }

    //Vocabulary Edit Function
    public function edit($vid): View
    {
        $vocabulary = TaxonomyVocabulary::find($vid);

        return view('admin.vocabulary.vocabulary_edit', compact('vocabulary'));
    }

    //Vocabulary Update Function
    public function update(Request $request, $vid): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'weight' => 'required',
            'language' => 'required',
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
