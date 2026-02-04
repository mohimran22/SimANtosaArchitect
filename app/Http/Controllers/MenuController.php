<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
public function index(Request $request)
{
    if ($request->ajax()) {
        $query = Menu::with('parent')->orderBy('order');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('parent_name', function ($row) {
                return $row->parent?->text ?? '-';
            })

            ->addColumn('active_badge', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })

            ->addColumn('actions', function ($row) {
                $edit = route('menus.edit', $row->id);
                $delete = route('menus.destroy', $row->id);

                return "
                    <a href='{$edit}' class='btn btn-sm btn-warning'>Edit</a>
                    <button data-id='{$row->id}' class='btn btn-sm btn-danger btn-delete'>Delete</button>
                ";
            })

            ->rawColumns(['active_badge','actions'])
            ->make(true);
    }

    return view('menus.index');
}

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->get();
        $permissions = Permission::all();

        return view('menus.create', compact('parents', 'permissions'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'text' => 'required|string|max:255',
        'url' => 'nullable|string|max:255',
        'type' => 'in:route,url,label',
        'key' => 'nullable|string|max:255',
        'parent_id' => 'nullable|exists:menus,id',
        'order' => 'integer',
        'icon' => 'nullable|string|max:255',
        'is_active' => 'boolean',

        // ✅ UNTUK MULTIPLE
        'permission_name'   => 'nullable|array',
        'permission_name.*' => 'string|max:255',
    ]);

    // ✅ SIMPAN SEBAGAI "a|b|c"
    if ($request->filled('permission_name')) {
        $data['permission_name'] = implode('|', $request->permission_name);
    } else {
        $data['permission_name'] = null;
    }

    Menu::create($data);

    return redirect()
        ->route('menus.index')
        ->with('success', 'Menu created successfully.');
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
        'permission_name'   => 'nullable|array',
        'permission_name.*' => 'string|max:255',
    ]);

    // ✅ Gabungkan menjadi string "a|b|c"
    if ($request->filled('permission_name')) {
        $validated['permission_name'] = implode('|', $request->permission_name);
    } else {
        $validated['permission_name'] = null;
    }

    $menu = Menu::findOrFail($id);
    $menu->update($validated);

    return redirect()
        ->route('menus.index')
        ->with('success', 'Menu updated successfully.');
}



    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu deleted successfully.');
    }
}
