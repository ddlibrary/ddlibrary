<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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

    public function index(Request $request): View
    {
        //setting the search session empty
        DDLClearSession();

        $menuRecords = Menu::orderBy('weight')
            ->title(request('term'))
            ->location(request('vocabulary'))
            ->language(request('language'))
            ->get();

        $vocabulary = Menu::select('location AS val', 'location AS name')->groupBy('name')->get();

        $args = [
            'route' => 'menulist',
            'filters' => $request,
            'vocabulary' => $vocabulary,
        ];
        //creating search bar
        $createSearchBar = new SearchController();
        $searchBar = $createSearchBar->searchBar($args);

        return view('admin.menu.menu_list', compact('menuRecords', 'searchBar'));
    }

    public function create($id): View
    {
        $menu = Menu::find($id);
        $new_menu = false;
        if (! $menu) {
            $menu = new Menu();
            $new_menu = true;
        }
        $locations = $menu->distinct()->pluck('location');
        $parents = $menu->distinct()->pluck('title', 'id');

        return view('admin.menu.menu_add', compact(
            'menu',
            'new_menu',
            'locations',
            'parents'
        )
        );
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->validate(
                $request, [
                    'title' => 'required',
                    'location' => 'required',
                    'path' => 'required',
                    'parent' => 'nullable',
                    'status' => 'required',
                    'language' => 'required',
                    'weight' => 'required',
                ]
            );
            $menu = new Menu;
            $menu->title = $request->input('title');
            $menu->location = $request->input('location');
            $menu->path = $request->input('path');
            if ($request->filled('parent')) {
                $menu->parent = $request->input('parent');
            }
            $menu->status = (int) $request->input('status');
            $menu->language = $request->input('language');
            $menu->weight = $request->input('weight');
    
            $menu->tnid = ($request->input('tnid')) ? $request->input('tnid') : Menu::max('tnid') + 1;
    
            //inserting
            $menu->save();
        } catch (ValidationException $e) {
            abort(400);
        }


        return redirect('admin/menu')->with('success', 'Menu translation or new menu successfully added!');
    }

    public function edit(Menu $menu, $menuId): View
    {
        $details = $menu->find($menuId);
        $locations = $details->distinct()->pluck('location');
        $parents = $details->distinct()->pluck('title', 'id');

        return view('admin.menu.menu_edit', compact('details', 'locations', 'parents'));
    }

    public function update(Request $request, $menuId): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'location' => 'required',
            'path' => 'required',
            'parent' => 'nullable',
            'status' => 'required',
            'language' => 'required',
            'weight' => 'required',
        ]);

        $menu = Menu::find($menuId);
        $menu->title = $request->input('title');
        $menu->location = $request->input('location');
        $menu->path = $request->input('path');
        if ($request->filled('parent')) {
            $menu->parent = $request->input('parent');
        }
        $menu->status = (int) $request->input('status');
        $menu->language = $request->input('language');
        $menu->weight = $request->input('weight');
        //inserting
        $menu->save();

        return redirect('admin/menu/edit/'.$menuId)->with('success', 'Item successfully updated!');
    }

    public function sort(Request $request)
    {
        $pos = 0;
        $menus = $request->input('data');
        foreach ($menus as $menu) {
            $m = Menu::find($menu['id']);
            $m->weight = ++$pos;
            $m->parent = 0;
            $m->save();
            if (isset($menu['children'])) {
                foreach ($menu['children'] as $sub_menu) {
                    $m = Menu::find($sub_menu['id']);
                    $m->weight = ++$pos;
                    $m->parent = $menu['id'];
                    $m->save();
                    if (isset($sub_menu['children'])) {
                        foreach ($sub_menu['children'] as $sub_menu2) {
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
        $id = $request->input('id');
        $loc = $request->input('loc');
        $lang = $request->input('lang');
        $parents = Menu::where('language', $lang)->where('location', $loc)->get();
        $data = '<option value="">- No Parent -</option>';
        foreach ($parents as $parent) {
            $data .= '<option value="'.$parent->id.'" ';
            $data .= ($parent->id == $id) ? 'selected' : '';
            $data .= '>'.$parent->title.'</option>';
        }
        echo $data;
    }

    public function translate($id = ''): View
    {
        $tnid = Menu::find($id)->tnid;

        if (! $tnid) {
            $menu = Menu::find($id);
            $menu->tnid = Menu::max('tnid') + 1;
            $menu->language = 'en';
            $menu->save();

            $tnid = Menu::find($id)->tnid;
        }

        $translations = ($tnid) ? Menu::where('tnid', $tnid)->get() : null;
        $locals = \LaravelLocalization::getSupportedLocales();

        return view('admin.menu.menu_translate', compact('translations', 'locals', 'tnid', 'id'));
    }

    /**
     * Delete selected menu translations or all translations that share the same tnid
     */
    public function destroy(Request $request, $menuId): RedirectResponse
    {
        $menu = Menu::findOrFail($menuId);
        $tnid = $menu->tnid;



        // Check if specific menu IDs were selected
        $selectedIds = $request->input('selected_ids');
        
        if ($selectedIds) {
            // Delete only selected menu items
            $ids = explode(',', $selectedIds);
            $ids = array_filter($ids); // Remove empty values
            
            if (count($ids) > 0) {
                // Verify all selected IDs belong to the same tnid
                $selectedMenus = Menu::whereIn('id', $ids)
                    ->where('tnid', $tnid)
                    ->get();

                
                if ($selectedMenus->count() > 0) {
                    Menu::whereIn('id', $ids)->delete();
                    $message = count($ids) === 1 
                        ? 'Menu translation deleted successfully!' 
                        : count($ids) . ' menu translations deleted successfully!';
                    return redirect('admin/menu/translate/'.$menu->id)->with('success', $message);
                }
            }
        }

        return back();
    }
}
