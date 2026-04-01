<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingAccount;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AccountingAccountController extends Controller
{

public function index(Request $request)
{
    $user = Auth::user();

    $query = AccountingAccount::with(['parent']);

    if ($user->hasRole('Super-Admin')) {
        // semua data
    } elseif ($user->hasRole('Pemilik Lisensi')) {

        $licenses = optional($user->licenses);

        if ($licenses?->isNotEmpty()) {
            $query->whereIn('license_id', $licenses->pluck('id'));
        } else {
            abort(403, 'Lisensi tidak ditemukan untuk pemilik lisensi.');
        }

    } elseif ($user->hasRole('Akuntan')) {

        $licenses = optional($user->employee)->licenses;

        if ($licenses && $licenses->count() > 0) {
            $query->whereIn('license_id', $licenses->pluck('id'));
        } else {
            abort(403, 'Lisensi tidak ditemukan.');
        }

    } else {
        abort(403, 'Role Tidak diizinkan');
    }

    if (!$user->hasRole('Super-Admin')) {

        $activeLicenseId = session('active_license_id'); // ⬅️ biasanya ini

        if (!$activeLicenseId) {
            abort(403, 'Silakan pilih lisensi aktif terlebih dahulu.');
        }

        $query->where('license_id', $activeLicenseId);
    }

    if ($request->ajax()) {

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('parent_name', function ($row) {
                return optional($row->parent)->account_name;
            })

            ->addColumn('is_parent', function ($row) {
                return $row->is_parent ? 'Ya' : 'Tidak';
            })

            ->addColumn('status', function ($row) {
                return $row->is_active ? 'Aktif' : 'Nonaktif';
            })

            ->addColumn('aksi', function ($row) {
                $buttons = '';

                if (auth()->user()->can('ubah akun-akuntansi')) {
                    $buttons .= '<a href="' . route('accounting.edit', $row->id) . '" 
                                class="btn btn-icon btn-sm btn-dark me-1">
                                <i class="ti ti-edit"></i></a>';
                }
                //  if (auth()->user()->can('lihat data proyek')) {
                //             $buttons .= '<a href="' . route('projects.show', $row->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Lihat">
                //                             <i class="ti ti-eye"></i>
                //                         </a>';

                // }
                if (auth()->user()->can('hapus akun-akuntansi')) {
                    $buttons .= '<button data-id="' . $row->id . '" 
                                class="btn btn-icon btn-sm btn-dark delete-accounts">
                                <i class="ti ti-trash"></i></button>';
                }

                return $buttons;
            })

            ->rawColumns(['aksi'])
            ->orderColumn('account_code', 'account_code $1') // biar sorting jalan

            ->make(true);
    }

    return view('accounting.index');
}


    public function create()
{
    $user = Auth::user();

    if ($user->hasRole('Super-Admin')) {
        // $licenses = License::all();
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->count() === 0) {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } else {
        abort(403, 'Role tidak diizinkan.');
    }

    $parentAccounts = AccountingAccount::where('is_parent', true)->get();

    return view('accounting.create', compact('parentAccounts'));
}


    public function store(Request $request)
    {
        $request->validate([
            'account_code' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'required|string|max:255',
            'initial_balance' => 'nullable|numeric',
            'is_parent' => 'boolean',
            'parent_id' => 'nullable|uuid|exists:accounting_accounts,id',
        ]);

        AccountingAccount::create([
            'id' => Str::uuid(),
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'category' => $request->category,
            'sub_category' => $request->sub_category,
            'initial_balance' => $request->initial_balance,
            'is_parent' => $request->is_parent ?? false,
            'parent_id' => $request->parent_id,
            'is_active' => true,
        ]);

        return redirect()->route('accounting.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(AccountingAccount $accounting)
    {
        $user = Auth::user();

    if ($user->hasRole('Super-Admin')) {
        // $licenses = License::all();
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->count() === 0) {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } else {
        abort(403, 'Role tidak diizinkan.');
    }

    $parentAccounts = AccountingAccount::where('is_parent', true)->get();

    return view('accounting.edit', [
        'account' => $accounting,
        'parentAccounts' => $parentAccounts
    ]);
    }

    public function update(Request $request, AccountingAccount $account)
    {
        $request->validate([
            'account_code' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'required|in:Debit,Kredit',
            'initial_balance' => 'nullable|numeric',
            'is_parent' => 'nullable|boolean',
            'parent_id' => 'nullable|uuid|exists:accounting_accounts,id',
        ]);

        // 🔥 cegah parent ke diri sendiri
        if ($request->parent_id == $account->id) {
            return back()->withErrors([
                'parent_id' => 'Tidak boleh memilih diri sendiri sebagai parent.'
            ]);
        }

        $account->update([
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'category' => $request->category,
            'sub_category' => $request->sub_category,
            'initial_balance' => $request->initial_balance,
            'is_parent' => $request->boolean('is_parent'),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()
            ->route('accounting.index')
            ->with('success', 'Akun berhasil diubah.');
    }

    public function destroy($id)
    {
        $account = AccountingAccount::findOrFail($id);
        $account->delete();

        return response()->json(['status' => 'success']);
    }

}
