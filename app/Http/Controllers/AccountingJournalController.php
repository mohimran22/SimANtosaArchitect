<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountingJournalRequest;
use App\Http\Requests\UpdateAccountingJournalRequest;
use App\Models\AccountingJournal;
use App\Models\AccountingJournalDetail;
use App\Models\AccountingAccount;
use App\Models\AccountingPeriod;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountingJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $journals = AccountingJournal::with(['license'])
            ->when(Auth::user()->hasRole('Akuntan'), function ($q) {
                $licenses = Auth::user()->employee?->licenses;

                if ($licenses && $licenses->count() > 0) {
                $q->whereIn('license_id', $licenses->pluck('id'));
            } else {
                abort(403, 'Lisensi tidak ditemukan.');
            }
            })
            ->orderByDesc('transaction_date')
            ->get();

        return view('journals.index', compact('journals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('Super-Admin')) {
        // Super Admin bisa lihat semua akun
        $accounts = AccountingAccount::where('is_parent', false)
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $licenses = License::all();
        return view('journals.create', compact('accounts', 'licenses'));
    }

    $licenses = $user->employee?->licenses;

    if (!$licenses || $licenses->count() === 0) {
        abort(403, 'License tidak ditemukan untuk user ini.');
    }

    $accounts = AccountingAccount::where('is_parent', false)
        ->where('is_active', true)
        ->whereIn('license_id', $licenses->pluck('id'))
        ->orderBy('account_code')
        ->get();

    return view('journals.create', compact('accounts', 'licenses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountingJournalRequest $request)
    {

        $user = Auth::user();
        $licenseId = $request->license_id;

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($request->details as $row) {
            $totalDebit += $row['debit'] ?? 0;
            $totalCredit += $row['credit'] ?? 0;
        }

        $journal = AccountingJournal::create([
            'license_id' => $licenseId,
            'journal_code' => 'JRN-' . strtoupper(Str::random(5)),
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->details as $detail) {
            AccountingJournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $detail['account_id'],
                'debit' => $detail['debit'] ?? 0,
                'credit' => $detail['credit'] ?? 0,
                'description' => $detail['description'] ?? null,
            ]);
        }

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountingJournal $journal)
    {
        $journal->load(['details.account', 'creator']);
        return view('journals.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountingJournal $journal)
{
    $user = Auth::user();

    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->isEmpty()) {
            abort(403, 'Lisensi tidak ditemukan.');
        }

        // Cek: jurnal ini milik lisensi user?
        if (!$licenses->pluck('id')->contains($journal->license_id)) {
            abort(403, 'Anda tidak punya akses ke jurnal ini.');
        }
    } else {
        abort(403, 'Role tidak diizinkan.');
    }

    $accounts = AccountingAccount::where('is_active', true)->get();

    return view('journals.edit', compact('journal', 'licenses', 'accounts'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountingJournalRequest $request, AccountingJournal $journal)
{
      $user = Auth::user();

    if ($user->hasRole('Super-Admin')) {
        // Boleh ganti license_id
        $journal->license_id = $request->license_id;
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->isEmpty()) {
            abort(403, 'Lisensi tidak ditemukan.');
        }

        // Harus punya lisensi yang cocok dengan jurnal
        if (!$licenses->pluck('id')->contains($journal->license_id)) {
            abort(403, 'Anda tidak punya akses untuk jurnal ini.');
        }

        // Akuntan tidak boleh ganti license_id, hanya data lain
    }

    $journal->transaction_date = $request->transaction_date;
    $journal->description = $request->description;
    $journal->save();

    // Hapus semua detail lama
    $journal->details()->delete();

    // Buat detail baru
    foreach ($request->details as $detail) {
        $journal->details()->create([
            'account_id' => $detail['account_id'],
            'debit' => $detail['debit'] ?? 0,
            'credit' => $detail['credit'] ?? 0,
            'description' => $detail['description'] ?? null,
        ]);
    }

    return redirect()->route('journals.index')->with('success', 'Jurnal berhasil diperbarui.');

}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $journal = AccountingJournal::findOrFail($id);
        $journal->details()->delete();
        $journal->delete();

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dihapus.');
    }

    public function report(Request $request)
{
    $user = Auth::user();

    $accountsQuery = AccountingAccount::where('is_parent', false)
        ->where('is_active', true);

    $licenses = [];
    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Pemilik Lisensi')) {
        $licenses = $user->licenses;

        if (!$licenses || $licenses->count() === 0) {
            abort(403, 'Lisensi tidak ditemukan.');
        }

        $accountsQuery->whereIn('license_id', $licenses->pluck('id'));


    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses;

        if (!$licenses || $licenses->count() === 0) {
            abort(403, 'Lisensi tidak ditemukan.');
        }

        $accountsQuery->whereIn('license_id', $licenses->pluck('id'));
    } else {
        abort(403, 'Role tidak diizinkan.');
    }


    $accounts = $accountsQuery->orderBy('account_code')->get();

    $accountId = $request->input('account_id');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $licenseFilterId = $request->input('license_id');

    $journals = AccountingJournal::with(['details.account', 'creator'])
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('transaction_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('transaction_date', '<=', $endDate);
        })
        ->when($accountId, function ($q) use ($accountId) {
            $q->whereHas('details', function ($q2) use ($accountId) {
                $q2->where('account_id', $accountId);
            });
        });

    if ($licenseFilterId) {
        if ($user->hasRole('Super-Admin') || $user->hasRole('Pemilik Lisensi')) {
            // Super admin / pemilik bisa filter license mana pun
            $journals->where('license_id', $licenseFilterId);
        } elseif ($user->hasRole('Akuntan')) {
            // Akuntan hanya boleh filter license miliknya
            if (!$licenses->pluck('id')->contains($licenseFilterId)) {
                abort(403, 'Lisensi tidak valid.');
            }
            $journals->where('license_id', $licenseFilterId);
        }
    } elseif ($user->hasRole('Akuntan')) {
        // Kalau tidak ada filter license_id → limit ke license miliknya
        $journals->whereIn('license_id', $licenses->pluck('id'));
    }

    $journals = $journals->orderBy('transaction_date')->get();

    return view('journals.report', compact(
        'accounts',
        'journals',
        'licenses',
        'licenseFilterId',
        'accountId',
        'startDate',
        'endDate'
    ));
}







}
