<?php

namespace App\Http\Controllers;

use App\Glossary;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->filled('text')){
            $glossary = Glossary::orderBy('id','desc')    
            ->orWhere('name_en',request('text'))
            ->orWhere('name_fa',request('text'))
            ->orWhere('name_ps',request('text'))
            ->paginate(15);
        }elseif($request->filled('subject') && !$request->filled('text')){
            $glossary = Glossary::orderBy('id','desc')
            ->where('subject', request('subject'))
            ->paginate(15);    
        }else{
            $glossary = Glossary::orderBy('id','desc')->paginate(15);
        }

        $filters = $request;
        return view('glossary.glossary_list', compact('glossary','filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function show(Glossary $glossary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function edit(Glossary $glossary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Glossary $glossary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Glossary $glossary)
    {
        //
    }
}
