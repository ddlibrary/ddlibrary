<?php

namespace App\Http\Controllers;

use App\Glossary;
use App\GlossarySubject;
use BladeView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class GlossaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function index(Request $request): View
    {
        $glossary_flagged = null;

        if ($request->filled('text')) {
            $glossary = Glossary::orderBy('id', 'desc')
                ->orWhere('name_en', request('text'))
                ->orWhere('name_fa', request('text'))
                ->orWhere('name_ps', request('text'))
                ->where('flagged_for_review', '!=', true)
                ->paginate(15);
        } elseif ($request->filled('subject') && ! $request->filled('text')) {
            $glossary = Glossary::orderBy('id', 'desc')
                ->where('subject', request('subject'))
                ->where('flagged_for_review', '!=', true)
                ->paginate(15);
        } else {
            $glossary = Glossary::orderBy('id', 'desc')
                ->where('flagged_for_review', '!=', true)
                ->paginate(15);
        }

        if (isAdmin() and (request('flagged') == 'show' or request('flagged') == null)) {
            $glossary_flagged = Glossary::orderBy('id', 'desc')
                ->where('flagged_for_review', '=', true)
                ->paginate(50);
        }

        $locale = app()->getLocale();
        $glossary_subjects = GlossarySubject::orderBy('id')->pluck($locale, 'id');

        $filters = $request;

        return view('glossary.glossary_list', compact('glossary', 'glossary_flagged', 'filters', 'glossary_subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return BladeView|false|Factory|Application|View
     */
    public function create(): View
    {
        $locale = app()->getLocale();
        $glossary_subjects = GlossarySubject::orderBy('id')->pluck($locale, 'id');

        return view('glossary.create', compact('glossary_subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Application|RedirectResponse|Redirector|void
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'english' => 'required_without_all:farsi,pashto',
            'farsi' => 'required_without_all:english,pashto',
            'pashto' => 'required_without_all:farsi,english',
            'subject' => 'required',
        ]);
        $glossary = new Glossary();
        $glossary->name_en = $validatedData['english'];
        $glossary->name_fa = $validatedData['farsi'];
        $glossary->name_ps = $validatedData['pashto'];
        $glossary->subject = $validatedData['subject'];
        if (! isAdmin()) {
            $glossary->flagged_for_review = true;
        }
        $glossary->save();

        return redirect(route('glossary'))->with('status', __('Glossary item added successfully!'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $data = $request['data'];
        $glossary_id = (int) $data[0];  // id of the glossary item
        $type = $data[1];  // possible input - 'glossary'
        $locale = $data[2];  // valid when $type is 'glossary'. Can be 'en', 'fa' or 'ps'
        $string = htmlspecialchars_decode($data[3]);  // the edited string

        if (($glossary_id or $type or $locale or $string) == null) {
            return response()->json(['msg' => 'error'], 400);
        }

        $glossary = Glossary::where('id', $glossary_id)->first();
        if ($type == 'glossary') {
            if ($locale == 'en') {
                $glossary->name_en = $string;
            } elseif ($locale == 'fa') {
                $glossary->name_fa = $string;
            } elseif ($locale == 'ps') {
                $glossary->name_ps = $string;
            }
        }

        if (! isAdmin()) {
            $glossary->flagged_for_review = true;
        }

        $glossary->save();

        return response()->json(['msg' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  null  $glossary_id
     */
    public function destroy($glossary_id = null): JsonResponse
    {
        $glossary = Glossary::where('id', $glossary_id)->first();
        if (! $glossary) {
            return response()->json(['msg' => 'error'], 400);
        }
        $glossary->delete();

        return response()->json(['msg' => 'success'], 200);
    }

    public function approve($glossary_id = null): JsonResponse
    {
        $glossary = Glossary::where('id', $glossary_id)->first();
        if (! $glossary) {
            return response()->json(['msg' => 'error'], 400);
        }
        $glossary->flagged_for_review = false;
        $glossary->save();

        return response()->json(['msg' => 'success'], 200);
    }
}
