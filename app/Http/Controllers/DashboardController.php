<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\AccountingJournalDetail;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Religion;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $requiredCustomerFields = [
            'fullname',
            'gender',
            'birth_place',
            'birth_date',
            'religion_id',
            'identity_number',
            'phone',
            'address',
            'province_id',
            'city_id',
            'district_id',
            'sub_district_id',
            'postal_code_id',
            'photo',
        ];

        // 🔍 Daftar field penting untuk affiliator (kalau user juga punya role affiliator)
        $requiredAffiliatorFields = [
            'bank_id',
            'account_number',
            'account_holder',
        ];


        $incompleteProfile = collect($requiredCustomerFields)->contains(fn($field) => empty($user->$field));
        $incompleteAffiliator = collect($requiredAffiliatorFields)->contains(fn($field) => empty($user->$field));

        $profileComplete = !$incompleteProfile && !$incompleteAffiliator;
        $attendanceToday = null;

        if (auth()->user()->isInternal() && auth()->user()->employee) {
            $attendanceToday = Attendance::with('overtime')
                ->where('employee_id', auth()->user()->employee->id)
                ->whereDate('attendance_date', today())
                ->first();
        }
        $hour = now()->hour;

        $greeting = match (true) {
            $hour >= 4 && $hour < 10  => 'Selamat Pagi',
            $hour >= 10 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default                   => 'Selamat Malam',
        };
        $attendances = Attendance::with([
                'employee.user',
                'overtime'
            ])
            ->whereDate('attendance_date', today())
            ->orderBy('check_in')
            ->get();

        // Statistik sederhana
        $hadir = $attendances->count();

        // Misalnya status terlambat disimpan di attendance_code
        $terlambat = $attendances->filter(function ($item) {
            return in_array($item->attendance_code, ['TL A', 'TL B', 'TL C']);
        })->count();

        // Total seluruh karyawan aktif
        $totalKaryawan = Employee::count();

        $belumHadir = $totalKaryawan - $hadir;
        $groupedAccounts = $this->buildGroupedAccounts(function ($q) {
            $q->whereDate('transaction_date', '<=', today());
        });
        $cashAccounts = collect(
            data_get(
                $groupedAccounts,
                'AKTIVA.Aset Lancar - Kas & Bank.accounts',
                []
            )
        );
        return view('dashboard.index', compact('user', 'incompleteProfile', 'incompleteAffiliator', 'attendanceToday', 'greeting', 'attendances',
        'hadir',
        'terlambat',
        'totalKaryawan',
        'belumHadir',
        'cashAccounts'));

    }

    public function edit()
    {
        $user = auth()->user()->load('bank');
    
        $religions = Religion::all();
        $provinces = Province::all();
        $cities = City::where('province_id', $user->province_id)->get();
        $districts = District::where('city_id', $user->city_id)->get();
        $subDistricts = SubDistrict::where('district_id', $user->district_id)->get();
        $postalCodes = PostalCode::where('sub_district_id', $user->sub_district_id)->get();
        return view('customers.profile', compact('user', 'religions', 'provinces', 'cities', 'districts', 'subDistricts', 'postalCodes'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:1,2',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'required|date_format:Y-m-d',
            'identity_number' => [
                'required',
                'regex:/^[0-9]{16}$/',
                Rule::unique('users', 'identity_number')->ignore($user->id),
            ],
            'religion_id' => 'required|exists:religions,id',
            'npwp' => 'nullable|string|max:30',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'sub_district_id' => 'required|exists:sub_districts,id',
            'postal_code_id' => 'required|exists:postal_codes,id',
            'bank_id' => 'nullable|uuid|exists:banks,id',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|max:50',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $newPhotoPath = null;
        // Upload photos jika ada
        if ($request->hasFile('photo')) {
            $newPhotoPath = $request->file('photo')->storeAs(
                'photos',
                Str::uuid().'.'.$request->file('photo')->getClientOriginalExtension(),
                'public'
            );

            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $validated['photo'] = $newPhotoPath;
        }

        // 🔁 Update data user
        $user->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
    private function buildGroupedAccounts(\Closure $journalFilter)
{
    $accounts = AccountingAccount::where('is_parent', false)
        ->get()
        ->map(function ($account) use ($journalFilter) {

            $debit = AccountingJournalDetail::query()
                ->where('account_id', $account->id)
                ->whereHas('journal', $journalFilter)
                ->sum('debit');

            $credit = AccountingJournalDetail::query()
                ->where('account_id', $account->id)
                ->whereHas('journal', $journalFilter)
                ->sum('credit');

            switch ($account->category) {

                case 'AKTIVA':
                case 'BEBAN':
                    $balance = $debit - $credit;
                    break;

                case 'KEWAJIBAN':
                case 'EKUITAS':
                case 'PENDAPATAN':
                    $balance = $credit - $debit;
                    break;

                default:
                    $balance = $debit - $credit;
            }

            return [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'category'     => $account->category,
                'sub_category' => $account->sub_category,
                'debit'        => $debit,
                'credit'       => $credit,
                'balance'      => $balance,
            ];
        });

    return $accounts
        ->groupBy('category')
        ->map(function ($catGroup) {

            return $catGroup->groupBy('sub_category')->map(function ($subGroup) {

                return [
                    'accounts'         => $subGroup,
                    'subtotalDebit'    => $subGroup->sum('debit'),
                    'subtotalCredit'   => $subGroup->sum('credit'),
                    'subtotalBalance'  => $subGroup->sum('balance'),
                ];

            });

        });
}
}
