<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\AccountingClosingBalance;
use App\Models\AccountingJournalDetail;
use App\Models\AccountingPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountingClosingController extends Controller
{
    public function showCloseForm()
    {
        return view('accounting.periods.close');
    }

    public function close(Request $request)
    {
        $request->validate([
            'period' => 'required|string|size:6', // format YYYYMM
        ]);

        $period = $request->period;
        $userId = Auth::id();

        $existing = AccountingPeriod::where('period_name', $period)->first();

        if (!$existing) {
            return back()->with('error', 'Periode tidak ditemukan.');
        }

        if ($existing->is_closed) {
            return back()->with('info', 'Periode sudah ditutup.');
        }

        $accounts = AccountingAccount::where('is_active', true)->get();

        foreach ($accounts as $account) {
            $debit = AccountingJournalDetail::whereHas('journal', function ($q) use ($period) {
                $q->where('transaction_date', 'like', "{$period}%");
            })->where('account_id', $account->id)->sum('debit');

            $credit = AccountingJournalDetail::whereHas('journal', function ($q) use ($period) {
                $q->where('transaction_date', 'like', "{$period}%");
            })->where('account_id', $account->id)->sum('credit');

            $closingBalance = $debit - $credit;

            AccountingClosingBalance::create([
                'id' => Str::uuid(),
                'account_id' => $account->id,
                'period' => $period,
                'closing_balance' => $closingBalance,
            ]);
        }

        $existing->update([
            'is_closed' => true,
            'closed_by' => $userId,
        ]);

        return redirect()->route('periods.close.form')->with('success', "Periode {$period} berhasil ditutup.");
    }
}
