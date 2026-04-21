<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    // public function index()
    // {
    //         if (request()->ajax()) {
    //             $roles = Role::withCount('permissions')->get();
    //             $permissions = Permission::all();                

    //             return datatables()->of($roles)
    //                 ->addIndexColumn()
    //                 ->addColumn('status', fn($row) => '<span class="badge bg-dark">Active</span>')
    //                 ->addColumn('action', function ($role) {
    //                 $buttons = '';
    //                 {
    //                     $buttons .= '<button data-role-id="' . $role->id . '" class="btn btn-icon btn-sm btn-dark me-1 edit permissions" title="Ubah">
    //                                     <i class="ti ti-edit"></i>
    //                                 </button>';
    //                 }
                    
    //                 {
    //                     $buttons .= '<button data-id="' . $role->id . '" class="btn btn-icon btn-sm btn-dark delete-role" title="Hapus">
    //                                     <i class="ti ti-trash"></i>
    //                                 </button>';
    //                 }
    //                 return $buttons;
    //             })
    //                 ->rawColumns(['status', 'action'])
    //                 ->make(true);
    //         }

    //         return view('roles.index', compact('permissions'));
    // }

    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));
    }

    public function updatePermissions(Request $request, $id)
{
    $role = Role::findOrFail($id);

    // 🧩 Filter hanya UUID valid (hindari angka 0, null, dll)
    $permissionIds = collect($request->input('permissions', []))
        ->filter(fn($id) => is_string($id) && preg_match('/^[0-9a-fA-F-]{36}$/', $id))
        ->values()
        ->toArray();

    if (empty($permissionIds)) {
        $role->syncPermissions([]); // Hapus semua jika kosong
    } else {
        // 🔹 Ambil nama permission berdasarkan UUID valid
        $permissionNames = Permission::whereIn('id', $permissionIds)
            ->pluck('name')
            ->toArray();

        $role->syncPermissions($permissionNames);
    }

    return response()->json([
        'success' => true,
        'message' => 'Permissions berhasil diperbarui.'
    ]);
}



   

//     public function show(Role $role)
// {
//     if (request()->ajax()) {
//         $permissions = $role->permissions()->select('id', 'name', 'modules')->get();
//         return response()->json(['permissions' => $permissions]);
//     }

//     return abort(404);
// }



    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        return redirect()->route('roles.index')->with('success', 'Role diperbarui.');
    }

    public function destroy(Role $role)
    {
        try {

            \DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->delete();

            $role->delete();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Role berhasil dihapus.');

        } catch (\Exception $e) {

            return redirect()
                ->route('roles.index')
                ->with('error', 'Terjadi kesalahan saat menghapus.');

        }
    }
}
