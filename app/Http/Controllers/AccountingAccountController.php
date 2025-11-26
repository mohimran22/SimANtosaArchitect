<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\License;
use App\Models\AccountingAccount;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AccountingAccountController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $query = AccountingAccount::with('license');

    if ($user->hasRole('Super-Admin')) {
        // Lihat semua akun
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses; // ← pakai relasi belongsToMany

        if ($licenses && $licenses->count() > 0) {
            $query->whereIn('license_id', $licenses->pluck('id'));
        } else {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } else {
        abort(403, 'Role Tidak diizinkan');
    }

    $accounts = $query->orderBy('account_code')->get();

    return view('accounting.index', compact('accounts'));
}

    public function create()
{
    $user = Auth::user();

    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->count() === 0) {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } else {
        abort(403, 'Role tidak diizinkan.');
    }

    $parentAccounts = AccountingAccount::where('is_parent', true)->get();

    return view('accounting.create', compact('licenses', 'parentAccounts'));
}


    public function store(Request $request)
    {
        $request->validate([
            'license_id' => 'required|exists:licenses,id',
            'account_code' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string|max:255',
            'balance_type' => 'required|in:Debit,Kredit',
            'initial_balance' => 'nullable|numeric',
            'is_parent' => 'boolean',
            'parent_id' => 'nullable|uuid|exists:accounting_accounts,id',
        ]);

        AccountingAccount::create([
            'id' => Str::uuid(),
            'license_id' => $request->license_id ?? null, // sesuaikan jika ada
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'balance_type' => $request->balance_type,
            'initial_balance' => $request->initial_balance,
            'is_parent' => $request->is_parent ?? false,
            'parent_id' => $request->parent_id,
            'is_active' => true,
        ]);

        return redirect()->route('accounting.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(AccountingAccount $account)
    {
        $user = Auth::user();

    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->count() === 0) {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } else {
        abort(403, 'Role tidak diizinkan.');
    }

    $parentAccounts = AccountingAccount::where('is_parent', true)->get();

        return view('accounting.edit', compact('account', 'licenses', 'parentAccounts'));
    }

    public function update(Request $request, AccountingAccount $account)
    {
        $request->validate([
            'account_code' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string|max:255',
            'balance_type' => 'required|in:Debit,Kredit',
            'initial_balance' => 'nullable|numeric',
            'is_parent' => 'boolean',
            'parent_id' => 'nullable|uuid|exists:accounting_accounts,id',
        ]);

        $account->update([
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'balance_type' => $request->balance_type,
            'initial_balance' => $request->initial_balance,
            'is_parent' => $request->is_parent ?? false,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('accounting.index')->with('success', 'Akun berhasil diubah.');
    }

     public function destroy(AccountingAccount $account)
    {
        $account->delete();
        return redirect()->route('accounting.index')->with('success', 'Akun berhasil dihapus.');
    }

}
