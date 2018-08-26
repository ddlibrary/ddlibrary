<?php

namespace App\Http\Controllers;
use App\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    function index ()
    {
        //setting the search session empty
        session()->forget(['resource1','resource2','resource3','search']);
        session()->save();
        
        $menuRecords = Menu::paginate(10);
        return view('admin.menu.menu_list', compact('menuRecords'));
    }

    function edit(Menu $menu, $menuId)
    {
        $details = $menu->find($menuId);
        $locations = $details->distinct()->pluck('location');
        $parents = $details->distinct()->pluck('title','id');
        return view('admin.menu.menu_edit', compact('details','locations','parents'));
    }

    public function update(Request $request, $menuId)
    {
        $this->validate($request, [
            'title'      => 'required',
            'location'   => 'required',
            'path'    => 'required',
            'parent'       => 'nullable',
            'language'  => 'required',
            'weight'  => 'required'
        ]);

        $menu = Menu::find($menuId);
        $menu->title = $request->input('title');
        $menu->location = $request->input('location');
        $menu->path = $request->input('path');
        if($request->filled('parent')){
            $menu->parent = $request->input('parent');
        }
        $menu->language = $request->input('language');
        $menu->weight = $request->input('weight');
        //inserting
        $menu->save();

        return redirect('admin/menu/edit/'.$menuId)->with('success', 'Item successfully updated!');
    }
}
