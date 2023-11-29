<?php

namespace App\Http\Controllers;

use App\GlossarySubject;
use BladeView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class GlossarySubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BladeView|false|Factory|Application|View
     */
    public function index()
    {
        $glossary_subjects = GlossarySubject::orderBy('id', 'DESC')->paginate(10);

        return view('admin.glossary.glossary_subject_list', compact('glossary_subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return BladeView|false|Factory|Application|View
     */
    public function create()
    {
        $glossary_subject = null;

        return view('admin.glossary.glossary_subject_edit', compact('glossary_subject'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        //
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
     * @return BladeView|false|Factory|Application|View
     */
    public function edit(int $id)
    {
        $glossary_subject = GlossarySubject::findOrFail($id);

        return view('admin.glossary.glossary_subject_edit', compact('glossary_subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'english' => 'required',
            'farsi' => 'required',
            'pashto' => 'required',
            'munji' => 'required',
            'nuristani' => 'required',
            'pashayi' => 'required',
            'shughni' => 'required',
            'swahili' => 'required',
            'uzbek' => 'required',
            'id' => 'required',
        ]);

        if ($validatedData['id'] == 'new') {
            $glossary_subject = new GlossarySubject();
        } else {
            $glossary_subject = GlossarySubject::findOrFail($validatedData['id']);
        }

        $glossary_subject->en = $validatedData['english'];
        $glossary_subject->fa = $validatedData['farsi'];
        $glossary_subject->ps = $validatedData['pashto'];
        $glossary_subject->mj = $validatedData['munji'];
        $glossary_subject->no = $validatedData['nuristani'];
        $glossary_subject->pa = $validatedData['pashayi'];
        $glossary_subject->sh = $validatedData['shughni'];
        $glossary_subject->sw = $validatedData['swahili'];
        $glossary_subject->uz = $validatedData['uzbek'];
        $glossary_subject->save();

        return redirect(route('glossary_subjects_list'))->with('status', __('Glossary subject(s) updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
