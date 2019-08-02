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
    
    function index (Request $request)
    {
        //setting the search session empty
        DDLClearSession();
        
        $menuRecords = Menu::orderBy('weight')
        ->title(request('term'))
        ->location(request('vocabulary'))
        ->language(request('language'))
        ->get();

        $vocabulary = Menu::select('location AS val','location AS name')->groupBy('name')->get();

        $args = array(
            'route'         => 'menulist',
            'filters'       => $request,
            'vocabulary'    => $vocabulary
        );
        //creating search bar
        $createSearchBar = new SearchController();
        $searchBar = $createSearchBar->searchBar($args);

        return view('admin.menu.menu_list', compact('menuRecords','searchBar'));
    }

    function create(Menu $menu, $id='')
    {
        $details = Menu::find($id);
        $locations = $details->distinct()->pluck('location');
        $parents = $details->distinct()->pluck('title','id');
        return view('admin.menu.menu_add', compact('details','locations','parents'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'title'     => 'required',
            'location'  => 'required',
            'path'      => 'required',
            'parent'    => 'nullable',
            'language'  => 'required',
            'weight'    => 'required'
        ]);

        $menu = new Menu;
        $menu->title = $request->input('title');
        $menu->location = $request->input('location');
        $menu->path = $request->input('path');
        if($request->filled('parent')){
            $menu->parent = $request->input('parent');
        }
        $menu->language = $request->input('language');
        $menu->weight = $request->input('weight');

        $menu->tnid = ($request->input('tnid')) ? $request->input('tnid') : Menu::max('tnid') + 1;
        
        //inserting
        $menu->save();

        return redirect('admin/menu')->with('success', 'Menu translation successfully added!');
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
    
    public function sort(Request $request)
    {
        $pos        = 0;
        $menus      = $request->input('data');
        foreach ($menus as $menu) 
        {
            $m = Menu::find($menu['id']);
            $m->weight = ++$pos;
            $m->parent = 0;
            $m->save();
            if(isset($menu['children']))
            {
                foreach($menu['children'] as $sub_menu)
                {
                    $m = Menu::find($sub_menu['id']);
                    $m->weight = ++$pos;
                    $m->parent = $menu['id'];
                    $m->save();
                    if(isset($sub_menu['children']))
                    {
                        foreach($sub_menu['children'] as $sub_menu2)
                        {
                            $m = Menu::find($sub_menu2['id']);
                            $m->weight = ++$pos;
                            $m->save();                        
                        }
                    }
                }
            }
        }

        echo true;
    }
    
    public function ajax_get_parents(Request $request)
    {
        $id      = $request->input('id');
        $loc     = $request->input('loc');
        $lang    = $request->input('lang');
        $parents = Menu::where("language", $lang)->where("location", $loc)->get();
        $data    = '<option value="">- No Parent -</option>';
        foreach($parents as $parent)
        {
            $data .= '<option value="' . $parent->id . '" ';
            $data .= ($parent->id == $id) ? 'selected' : '';
            $data .= '>' . $parent->title . '</option>';
        }
        echo ($data);
    }

    public function translate($id='')
    {
        $tnid = Menu::find($id)->tnid;

        if(!$tnid)
        {
            $menu = Menu::find($id);
            $menu->tnid = Menu::max('tnid') + 1;
            $menu->language = 'en';
            $menu->save();

            $tnid = Menu::find($id)->tnid;
        }

        $translations = ($tnid) ? Menu::where('tnid', $tnid)->get() : NULL;
        $locals       = \LaravelLocalization::getSupportedLocales();

        return view('admin.menu.menu_translate', compact('translations','locals','tnid', 'id'));
    }

    public function translate_menu(Request $request, $tnid='')
    {
        echo 'ID: ' . $tnid . '<br>';

        foreach(\LaravelLocalization::getSupportedLocales() as $key=>$value)
        {
            if($request->input($key))
            {
                $menues = Menu::where('tnid', $tnid)->where('language', $key)->first();

                if(count($menues))
                {
                    
                }

                echo count($menues);
                echo $request->input($key) . ' ' . $key . '<br>';
            }
        }

        dd($request->all());
        //return redirect('admin/menu/translate/' . $menuId);
    }

}
