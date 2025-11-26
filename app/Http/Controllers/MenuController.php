<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['parent'])
            ->orderBy('order')
            ->paginate(15);

        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->get();
        $permissions = Permission::all();

        return view('menus.create', compact('parents', 'permissions'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            'text' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'in:route,url,label',
            'key' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'integer',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);


        $menu = Menu::create($data);

        cache()->forget('menus_for_user_*');

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        $permissions = Permission::orderBy('name')->get();

        return view('menus.edit', compact('menu', 'parents', 'permissions'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'text' => 'required|string|max:255',
        'url' => 'nullable|string|max:255',
        'type' => 'required|string|max:50',
        'parent_id' => 'nullable|exists:menus,id',
        'order' => 'integer',
        'icon' => 'nullable|string|max:255',
        'is_active' => 'boolean',
        'permission_name' => 'nullable|string|max:255',
    ]);

    $menu = Menu::findOrFail($id);

    $menu->update($validated);

    return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
}


    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu deleted successfully.');
    }
}
