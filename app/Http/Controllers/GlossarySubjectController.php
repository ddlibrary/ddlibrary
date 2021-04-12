<?php

namespace App\Http\Controllers;

use App\GlossarySubjects;
use Illuminate\Http\Request;

class GlossarySubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \BladeView|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $glossary_subjects = GlossarySubjects::orderBy('id', 'DESC')->paginate(10);
        return view('admin.glossary.glossary_subject_list', compact('glossary_subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \BladeView|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.glossary.glossary_subject_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'english'         => 'required_without_all:farsi,pashto',
            'farsi'           => 'required_without_all:english,pashto',
            'pashto'          => 'required_without_all:farsi,english',
        ]);
        $glossary_subject = new GlossarySubjects();
        $glossary_subject->en = $validatedData['english'];
        $glossary_subject->fa = $validatedData['farsi'];
        $glossary_subject->ps = $validatedData['pashto'];
        $glossary_subject->save();

        return redirect(route('glossary_subjects_list'))->with('status', __('Glossary subject created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \BladeView|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $glossary_subject = GlossarySubjects::findOrFail($id);
        return view('admin.glossary.glossary_subject_edit', compact('glossary_subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'english'         => 'required_without_all:farsi,pashto',
            'farsi'           => 'required_without_all:english,pashto',
            'pashto'          => 'required_without_all:farsi,english',
            'id'              => 'required'
        ]);
        $glossary_subject = GlossarySubjects::findOrFail($validatedData['id']);
        $glossary_subject->en = $validatedData['english'];
        $glossary_subject->fa = $validatedData['farsi'];
        $glossary_subject->ps = $validatedData['pashto'];
        $glossary_subject->save();

        return redirect(route('glossary_subjects_list'))->with('status', __('Glossary subject updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
