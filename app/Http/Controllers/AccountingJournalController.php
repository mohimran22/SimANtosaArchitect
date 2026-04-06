<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountingJournalRequest;
use App\Http\Requests\UpdateAccountingJournalRequest;
use App\Models\AccountingJournal;
use App\Models\AccountingJournalDetail;
use App\Models\AccountingAccount;
use App\Models\AccountingPeriod;
use App\Models\License;
use App\Models\Student;
use App\Models\Employee;
use App\Models\LicenseHolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\ReportService;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;


class AccountingJournalController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {

        $licenseId = config('app.license_id');

        $journals = AccountingJournal::query()
            ->select(
                'accounting_journals.id',
                'accounting_journals.journal_code',
                'accounting_journals.transaction_date',
                'users.fullname as creator_name'
            )
            ->leftJoin('users', 'accounting_journals.created_by', '=', 'users.id')
            ->where('accounting_journals.license_id', $licenseId)
            ->orderByDesc('accounting_journals.transaction_date');

        return DataTables::of($journals)
            ->addIndexColumn()

            ->editColumn('transaction_date', function ($row) {
                return \Carbon\Carbon::parse($row->transaction_date)->format('d/m/Y');
            })

            ->addColumn('journal_code', function ($row) {
                return '<a href="' . route('journals.show', $row->id) . '" 
                        class="fw-bold text-primary">'
                        . $row->journal_code . '</a>';
            })

            ->addColumn('creator', function ($row) {
                return $row->creator_name 
                    ? e($row->creator_name)
                    : '<small class="fst-italic text-muted">dibuat oleh sistem</small>';
            })

            ->addColumn('action', function ($journal) {
                $buttons = '';

                if (auth()->user()->can('ubah jurnal')) {
                    $buttons .= '<a href="' . route('journals.edit', $journal->id) . '" class="btn btn-sm btn-dark me-1">
                                    <i class="ti ti-edit"></i>
                                </a>';
                }

                if (auth()->user()->can('lihat jurnal')) {
                    $buttons .= '<a href="' . route('journals.show', $journal->id) . '" class="btn btn-sm btn-dark me-1">
                                    <i class="ti ti-eye"></i>
                                </a>';
                }

                if (auth()->user()->can('hapus jurnal')) {
                    $buttons .= '<button data-id="' . $journal->id . '" class="btn btn-sm btn-dark delete-journal">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                return $buttons;
            })

            ->rawColumns(['journal_code', 'creator', 'action'])
            ->make(true);
    }

    return view('journals.index');
}

public function create()
{
    $licenseId = config('app.license_id');

    // Accounts
    $accounts = AccountingAccount::where('is_parent', false)
        ->where('is_active', true)
        ->where('license_id', $licenseId)
        ->orderBy('account_code')
        ->get();

    // Employees
    $employees = User::select('id', 'fullname as name')->get();


    $journalCode = $this->generateNextJournalCode();

    return view('journals.create', compact(
        'accounts',
        'employees',
        'journalCode'
    ));
}

private function generateNextJournalCode()
{
    $licenseId = config('app.license_id');

    $lastJournalNumber = AccountingJournal::where('license_id', $licenseId)
        ->where('journal_code', 'LIKE', 'IJ-%')
        ->selectRaw("
            MAX(
                CAST(
                    REGEXP_REPLACE(journal_code, '^.*-', '') AS INTEGER
                )
            ) as last_number
        ")
        ->value('last_number');

    $lastJournalNumber = $lastJournalNumber ?? 0;

    do {
        $nextNumber = str_pad($lastJournalNumber + 1, 4, '0', STR_PAD_LEFT);
        $journalCode = 'IJ-' . $nextNumber;

        $exists = AccountingJournal::where('journal_code', $journalCode)->exists();
        $lastJournalNumber++;
    } while ($exists);

    return $journalCode;
}

public function getNextCode()
{
    $nextCode = $this->generateNextJournalCode();

    return response()->json([
        'next_code' => $nextCode,
    ]);
}

public function store(StoreAccountingJournalRequest $request)
{
    $user = Auth::user();
    $licenseId = config('app.license_id');

    // ✅ Validasi balance
    $totalDebit  = collect($request->details)->sum('debit');
    $totalCredit = collect($request->details)->sum('credit');

    if ($totalDebit != $totalCredit) {
        return back()->withErrors('Debit dan Credit harus balance.');
    }

    // Upload file
    $enclosurePath = null;
    if ($request->hasFile('enclosure')) {
        $file = $request->file('enclosure');
        $enclosurePath = $file->storeAs(
            'attachments',
            Str::uuid().'.'.$file->getClientOriginalExtension(),
            'public'
        );
    }

    // ✅ Pakai auto generate (tidak dari request)
    $journalCode = $this->generateNextJournalCode();

    // Simpan jurnal
    $journal = AccountingJournal::create([
        'license_id'       => $licenseId,
        'journal_code'     => $journalCode,
        'transaction_date' => $request->transaction_date,
        'description'      => $request->description,
        'created_by'       => $user->id,
        'enclosure'        => $enclosurePath,
    ]);

    foreach ($request->details as $detail) {
        AccountingJournalDetail::create([
            'journal_id'  => $journal->id,
            'account_id'  => $detail['account_id'],
            'person'      => $detail['person'] ?? null,
            'debit'       => $detail['debit'] ?? 0,
            'credit'      => $detail['credit'] ?? 0,
            'description' => $detail['description'] ?? null,
        ]);
    }

    return redirect()->route('journals.index')
        ->with('success', 'Jurnal berhasil dibuat.');
}

public function show(AccountingJournal $journal)
{
    if ($journal->license_id !== config('app.license_id')) {
        abort(403);
    }

    $journal->load(['details.account', 'creator']);

    return view('journals.show', compact('journal'));
}

public function edit(AccountingJournal $journal)
{
    // 🔒 optional security (kalau masih pakai license di DB)
    if ($journal->license_id !== config('app.license_id')) {
        abort(403);
    }

    // akun
    $accounts = AccountingAccount::where('is_active', true)
        ->orderBy('account_code')
        ->get();

    // employee saja (kalau masih dipakai)
    $employees = Employee::with('user')
        ->get()
        ->map(fn($emp) => [
            'id'   => $emp->id,
            'name' => $emp->user?->fullname ?? '-',
        ]);

    $journal->load(['details.account']);

    return view('journals.edit', compact('journal', 'accounts', 'employees'));
}


public function update(UpdateAccountingJournalRequest $request, AccountingJournal $journal)
{
    // 🔐 Optional: lock ke license config
    if ($journal->license_id !== config('app.license_id')) {
        abort(403, 'Akses tidak valid.');
    }

    // ✅ Validasi balance
    $totalDebit = collect($request->details)->sum('debit');
    $totalCredit = collect($request->details)->sum('credit');

    if ($totalDebit != $totalCredit) {
        return back()->withErrors([
            'details' => 'Debit dan kredit harus seimbang.'
        ]);
    }

    // 📎 File
    $enclosurePath = $journal->enclosure;

    if ($request->hasFile('enclosure')) {
        if ($journal->enclosure && Storage::disk('public')->exists($journal->enclosure)) {
            Storage::disk('public')->delete($journal->enclosure);
        }

        $file = $request->file('enclosure');

        $enclosurePath = $file->storeAs(
            'attachments',
            Str::uuid().'.'.$file->getClientOriginalExtension(),
            'public'
        );
    }

    // 🔹 Update jurnal
    $journal->update([
        'transaction_date' => $request->transaction_date,
        'description' => $request->description,
        'enclosure' => $enclosurePath,
    ]);

    // 🔹 Reset detail
    $journal->details()->delete();

    foreach ($request->details as $detail) {
        $journal->details()->create([
            'account_id' => $detail['account_id'],
            'person' => $detail['person'] ?? null,
            'debit' => $detail['debit'] ?? 0,
            'credit' => $detail['credit'] ?? 0,
            'description' => $detail['description'] ?? null,
        ]);
    }

    return redirect()->route('journals.index')
        ->with('success', 'Jurnal berhasil diperbarui.');
}

    public function destroy($id)
    {
        $journal = AccountingJournal::findOrFail($id);
         if ($journal->enclosure && Storage::disk('public')->exists($journal->enclosure)) {
            Storage::disk('public')->delete($journal->enclosure);
        }
        $journal->details()->delete();
        $journal->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }


    public function report(Request $request)
{
    $user = Auth::user();
    
    $accountId = $request->input('account_id');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $licenseFilterId = $request->input('license_id');

    
    $licenses = collect();
    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Pemilik Lisensi')) {
        $licenses = $user->licenses ?? collect();
        if ($licenses->isEmpty()) {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } elseif ($user->hasRole('Akuntan')) {
        $licenses = $user->employee?->licenses ?? collect();
        if ($licenses->isEmpty()) {
            abort(403, 'Lisensi tidak ditemukan.');
        }
    } else {
        abort(403, 'Role tidak diizinkan.');
    }

    // Filter akun
    $accountsQuery = AccountingAccount::where('is_parent', false)->where('is_active', true);
    if ($licenseFilterId) {
        $accountsQuery->where('license_id', $licenseFilterId);
    } else {
        $accountsQuery->whereIn('license_id', $licenses->pluck('id'));
    }
    $accounts = $accountsQuery->orderBy('account_code')->get();

    $journalsQuery = AccountingJournal::query()
        ->with('creator')
        ->with(['details' => function ($q) use ($accountId) {
            // jika ada filter akun, eager-load hanya details untuk account tersebut
            if ($accountId) {
                $q->where('account_id', $accountId)->with('account');
            } else {
                $q->with('account');
            }
        }])
        ->when($startDate, fn($q) => $q->whereDate('transaction_date', '>=', $startDate))
        ->when($endDate, fn($q) => $q->whereDate('transaction_date', '<=', $endDate))
        // pastikan jurnal memiliki detail yang sesuai (biar jurnal tanpa akun itu tidak muncul)
        ->when($accountId, fn($q) => $q->whereHas('details', fn($q2) => $q2->where('account_id', $accountId)));


    if ($licenseFilterId) {
        if (
            $user->hasRole('Super-Admin') ||
            ($user->hasRole('Pemilik Lisensi') && $licenses->pluck('id')->contains($licenseFilterId)) ||
            ($user->hasRole('Akuntan') && $licenses->pluck('id')->contains($licenseFilterId))
        ) {
            $journalsQuery->where('license_id', $licenseFilterId);
        } else {
            abort(403, 'Lisensi tidak valid.');
        }
    } else {
        $journalsQuery->whereIn('license_id', $licenses->pluck('id'));
    }

    $journals = $journalsQuery->orderBy('transaction_date')->get();

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

   public function generalJournal(Request $request)
{
    $user = Auth::user();

    // 🔹 Default filter tanggal: bulan berjalan
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

    // 🔹 Base query
    $journals = AccountingJournal::with(['details.account']);

    // 🔹 Filter role user
    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } else {     
        $licenses = $user->hasRole('Pemilik Lisensi')
            ? $user->licenses
            : $user->employee?->licenses;

        abort_if(!$licenses || $licenses->isEmpty(), 403, 'Lisensi tidak ditemukan.');
    }

    $activeLicenseId = $request->get('license_id') ?? session('active_license_id');

    if ($activeLicenseId) {
        $journals->where('license_id', $activeLicenseId);
    } else {
        $journals->whereIn('license_id', $licenses->pluck('id'));
    }

    // 🔹 Filter tanggal
    $journals = $journals->whereBetween('transaction_date', [$startDate, $endDate])
        ->orderBy('transaction_date')
        ->get();

    // 🔹 Hitung total debit & kredit
    $totalDebit = 0;
    $totalCredit = 0;

    foreach ($journals as $journal) {
        foreach ($journal->details as $detail) {
            $totalDebit += $detail->debit;
            $totalCredit += $detail->credit;
        }
    }

    return view('journals.general', compact('journals', 'licenses', 'activeLicenseId', 'startDate', 'endDate', 'totalDebit', 'totalCredit'));
}

public function exportPDF(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate = $request->end_date ?? now()->endOfMonth()->toDateString();
    $activeLicenseId = $request->license_id ?? auth()->user()->license_id;

    // Ambil data sesuai filter
    $journals = AccountingJournal::with(['details.account'])
        ->whereBetween('transaction_date', [$startDate, $endDate])
        ->where('license_id', $activeLicenseId)
        ->orderBy('transaction_date', 'asc')
        ->get();

    $totalDebit = $journals->sum(fn($j) => $j->details->sum('debit'));
    $totalCredit = $journals->sum(fn($j) => $j->details->sum('credit'));

    // Load view khusus PDF
    $pdf = Pdf::loadView('journals.export-pdf', compact(
        'journals',
        'startDate',
        'endDate',
        'totalDebit',
        'totalCredit'
    ))
    ->setPaper('a4', 'landscape');

    return $pdf->stream('general.pdf');
}

public function ledger(Request $request)
{
    $user = Auth::user();

    // 🔹 Default filter tanggal: bulan berjalan
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } else {
        $licenses = $user->hasRole('Pemilik Lisensi')
            ? $user->licenses
            : $user->employee?->licenses;

        abort_if(!$licenses || $licenses->isEmpty(), 403, 'Lisensi tidak ditemukan.');
    }

    $activeLicenseId = $request->get('license_id') ?? session('active_license_id');

    [$ledger, $licenses] = $this->getLedgerData($startDate, $endDate, $licenses, $activeLicenseId);

    return view('journals.ledger', compact(
        'ledger', 'licenses', 'activeLicenseId', 'startDate', 'endDate'
    ));
}

private function getLedgerData($startDate, $endDate, $licenses, $licenseId = null)
{
    $query = AccountingJournalDetail::with(['journal', 'account']);
    $user = Auth::user();

    // 🔹 Filter lisensi
    if ($user->hasRole('Super-Admin')) {
        if ($licenseId) {
            $licenses = License::where('id', $licenseId)->get();
        } else {
            $licenses = License::all();
        }
    } else {
        if ($user->hasRole('Pemilik Lisensi')) {
            $licenses = $user->licenses;
        } elseif ($user->employee) {
            $licenses = $user->employee->licenses;
        } else {
            $licenses = collect();
        }

        if ($licenseId) {
            $licenses = $licenses->where('id', $licenseId);
        }
    }

    // 🔹 Filter periode
    $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
        $q->whereBetween('transaction_date', [$startDate, $endDate]);
    });

    // 🔹 Filter lisensi
    if ($licenses->isNotEmpty()) {
        $query->whereHas('journal', function ($q) use ($licenses) {
            $q->whereIn('license_id', $licenses->pluck('id'));
        });
    }

    $details = $query
        ->join('accounting_accounts', 'accounting_accounts.id', '=', 'accounting_journal_details.account_id')
        ->orderByRaw('CAST(accounting_accounts.account_code AS INTEGER) ASC')
        ->orderBy(
            AccountingJournal::select('transaction_date')
                ->whereColumn('id', 'accounting_journal_details.journal_id')
        )
        ->select('accounting_journal_details.*')
        ->get();

    // 🔹 Kelompokkan per akun
    $ledger = [];
    foreach ($details->groupBy('account_id') as $accountId => $items) {
        $balance = 0;
        $rows = [];

        foreach ($items as $detail) {
            $balance += ($detail->debit - $detail->credit);

            $rows[] = [
                'journal_id'       => $detail->journal_id,
                'transaction_date' => $detail->journal->transaction_date,
                'journal_code'     => $detail->journal->journal_code,
                'description'      => $detail->description,
                'debit'            => $detail->debit,
                'credit'           => $detail->credit,
                'balance'          => $balance,
            ];
        }

        $ledger[$accountId] = [
            'account' => $items->first()->account,
            'rows'    => $rows,
        ];
    }

    return [$ledger, $licenses];
}

public function exportLedgerPdf(Request $request)
{
    $startDate = $request->get('start_date');
    $endDate   = $request->get('end_date');
    $licenseId = $request->get('license_id');

    $user = auth()->user();

    // 🔹 Tentukan nama lisensi
    if ($user->hasRole('Super-Admin')) {
        if ($licenseId) {
            $license = License::find($licenseId);
            $licenseName = $license?->name ?? '-';
        } else {
            $licenseName = 'Semua Lisensi';
        }
    } else {
        if ($user->hasRole('Pemilik Lisensi')) {
            $licenseName = $user->licenses->pluck('name')->join(', ');
        } elseif ($user->employee) {
            $licenseName = $user->employee->licenses->pluck('name')->join(', ');
        } else {
            $licenseName = '-';
        }
    }

    // 🔹 Ambil data ledger sesuai filter
    [$ledger, $licenses] = $this->getLedgerData(
        $startDate,
        $endDate,
        $user->hasRole('Super-Admin') ? License::all() : ($user->hasRole('Pemilik Lisensi') ? $user->licenses : $user->employee?->licenses),
        $licenseId
    );

    // 🔹 Load view PDF
    $pdf = Pdf::loadView('journals.ledgerpdf', compact(
        'ledger', 'licenseName', 'startDate', 'endDate'
    ))->setPaper('a4', 'landscape');

    return $pdf->stream('ledger_'.$startDate.'_to_'.$endDate.'.pdf');
}


public function trialBalance(Request $request)
{
    $user = auth()->user();

    // Default periode (bulan berjalan)
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

    // 🔹 Filter lisensi sesuai role
    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Pemilik Lisensi')) {
        $licenses = $user->licenses;
    } else {
        $licenses = $user->employee?->licenses ?? collect();
    }

    // 🔹 Lisensi aktif → default ambil yang pertama
    $activeLicenseId = $request->get('license_id') ?? session('active_license_id');

    // 🔹 Ambil akun yang sudah dikelompokkan
    $groupedAccounts = $this->getGroupedAccounts($startDate, $endDate, $activeLicenseId);

    // 🔹 Hitung total debit & kredit
    $totalDebit  = collect($groupedAccounts)->sum(fn($cat) => collect($cat)->sum(fn($sub) => $sub['subtotalDebit']));
    $totalCredit = collect($groupedAccounts)->sum(fn($cat) => collect($cat)->sum(fn($sub) => $sub['subtotalCredit']));

    return view('journals.trialbalance', compact(
        'groupedAccounts',
        'totalDebit',
        'totalCredit',
        'startDate',
        'endDate',
        'licenses',
        'activeLicenseId'
    ));
}

/**
 * Ambil akun + group berdasarkan kategori dan sub-kategori
 */
    private function getGroupedAccounts($startDate, $endDate, $licenseId)
{
    $accounts = AccountingAccount::where('license_id', $licenseId)
        ->with(['details.journal' => function ($q) use ($startDate, $endDate, $licenseId) {
                $q->whereBetween('transaction_date', [$startDate, $endDate])
                   ->where('license_id', $licenseId);
        }])
        ->get()
        ->map(function ($account) {
            $debit   = $account->details->sum('debit');
            $credit  = $account->details->sum('credit');
            $balance = $debit - $credit;

            return [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'category'     => $account->category,
                'sub_category' => $account->sub_category,
                'parent_id'    => $account->parent_id,
                'is_parent'    => $account->is_parent,
                'debit'        => $balance > 0 ? $balance : 0,
                'credit'       => $balance < 0 ? abs($balance) : 0,
            ];
        })

        ->filter(function ($acc) {
            return !$acc['is_parent'] && $acc['category'] !== '-';
        });

    // 🔹 Kelompokkan berdasarkan kategori & sub kategori
    return $accounts
        ->groupBy('category')
        ->map(function ($catGroup) {
            return $catGroup->groupBy('sub_category')->map(function ($subGroup) {
                return [
                    'accounts'       => $subGroup,
                    'subtotalDebit'  => $subGroup->sum('debit'),
                    'subtotalCredit' => $subGroup->sum('credit'),
                ];
            });
        });
}

public function exportTrial(Request $request)
{
    $user = auth()->user();

    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

    // 🔹 Filter lisensi sesuai role → sama seperti trialBalance()
    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Pemilik Lisensi')) {
        $licenses = $user->licenses;
    } else {
        $licenses = $user->employee?->licenses ?? collect();
    }

   $activeLicenseId = $request->get('license_id') ?? session('active_license_id');

    // 🔹 Ambil data yang sama seperti di view trial balance
    $groupedAccounts = $this->getGroupedAccounts($startDate, $endDate, $activeLicenseId);

    $totalDebit  = collect($groupedAccounts)->sum(fn($cat) => collect($cat)->sum(fn($sub) => $sub['subtotalDebit']));
    $totalCredit = collect($groupedAccounts)->sum(fn($cat) => collect($cat)->sum(fn($sub) => $sub['subtotalCredit']));

    // 🔹 Generate PDF
    $pdf = Pdf::loadView('journals.trialbalance-pdf', [
        'groupedAccounts' => $groupedAccounts,
        'startDate'       => $startDate,
        'endDate'         => $endDate,
        'licenses'        => $licenses,
        'activeLicenseId' => $activeLicenseId,
        'totalDebit'      => $totalDebit,
        'totalCredit'     => $totalCredit,
        'request'         => $request,
    ]);

    return $pdf->stream('trial-balance.pdf');
}

public function print(AccountingJournal $journal)
{
    $pdf = Pdf::loadView('journals.print', compact('journal'))
        ->setPaper('a4', 'landscape'); // bisa juga landscape

    return $pdf->stream('journal-'.$journal->journal_code.'.pdf');
}

public function balanceSheet(Request $request)
{
    $user = auth()->user();

    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

    if ($user->hasRole('Super-Admin')) {
        $licenses = License::all();
    } elseif ($user->hasRole('Pemilik Lisensi')) {
        $licenses = $user->licenses;
    } else {
        $licenses = $user->employee?->licenses ?? collect();
    }

    $activeLicenseId = $request->get('license_id') ?? session('active_license_id');

    $viewType = $request->get('view', 'default'); // 🔹 default | skontro

    $groupedAccounts = $this->getGroupedAccounts($startDate, $endDate, $activeLicenseId);

    $totals = ReportService::calculateBalanceSheet($groupedAccounts);

    $totalDebit  = collect($groupedAccounts)->sum(fn($cat) => collect($cat)->sum(fn($sub) => $sub['subtotalDebit']));
    $totalCredit = collect($groupedAccounts)->sum(fn($cat) => collect($cat)->sum(fn($sub) => $sub['subtotalCredit']));

    return view('reports.balance_sheet', array_merge([
        'startDate'       => $startDate,
        'endDate'         => $endDate,
        'licenses'        => $licenses,
        'activeLicenseId' => $activeLicenseId,
        'groupedAccounts' => $groupedAccounts,
        'viewType'        => $viewType,
        'totalDebit'      => $totalDebit,
        'totalCredit'     => $totalCredit,
    ], $totals));
}

}



