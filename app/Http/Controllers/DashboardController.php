<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\AccountingJournalDetail;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\Employee;
use App\Models\Religion;
use App\Models\Project;
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
use Carbon\Carbon;

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
        $hour = now()->hour;

        $greeting = match (true) {
            $hour >= 4 && $hour < 10  => 'Selamat Pagi',
            $hour >= 10 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default                   => 'Selamat Malam',
        };
        $attendanceToday = null;

        if (auth()->user()->isInternal() && auth()->user()->employee) {
            $attendanceToday = Attendance::with('overtime')
                ->where('employee_id', auth()->user()->employee->id)
                ->whereDate('attendance_date', today())
                ->first();
        }
        if ($user->isContractorContext()) {
            return $this->contractorDashboard($user, $greeting);
        }
        if (! $user->isInternal()) {
            return $this->customerDashboard($user, $greeting);
        }
        $attendances = Attendance::with([
                'employee.user',
                'overtime'
            ])
            ->whereDate('attendance_date', today())
            ->orderBy('check_in')
            ->get();

        $hadir = $attendances->count();

        $terlambat = $attendances->filter(function ($item) {
            return in_array($item->attendance_code, ['TL A', 'TL B', 'TL C']);
        })->count();

        $totalKaryawan = Employee::count();

        $belumHadir = $totalKaryawan - $hadir;
        $awalBulan = now()->startOfMonth();
        $akhirBulan = now()->endOfMonth();
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
        $totalCashBank = $cashAccounts->sum('balance');

        $cashAccountIds = AccountingAccount::where('is_parent', false)
            ->where('category', 'AKTIVA')
            ->where('sub_category', 'Aset Lancar - Kas & Bank')
            ->pluck('id');
        $cashInThisMonth = AccountingJournalDetail::query()
            ->whereIn('account_id', $cashAccountIds)
            ->whereHas('journal', function ($q) {
                $q->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year);
            })
            ->sum('debit');
        $cashOutThisMonth = AccountingJournalDetail::query()
            ->whereIn('account_id', $cashAccountIds)
            ->whereHas('journal', function ($q) {
                $q->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year);
            })
            ->sum('credit');
        $monthlyRevenue = AccountingJournalDetail::whereHas('account', function($q) {
                $q->where('account_code', 'like', '4%');
            })
            ->whereHas('journal', function($q) use ($awalBulan, $akhirBulan) {
                $q->whereBetween('transaction_date', [$awalBulan, $akhirBulan]);
            })
            ->sum('credit');
        $totalProject = Project::count();

        $totalDesign = Project::where('project_type', Project::TYPE_DESIGN)->count();

        $totalRab = Project::where('project_type', Project::TYPE_RAB)->count();

        $totalBuild = Project::where('project_type', Project::TYPE_BUILD)->count();

        $buildProjects = Project::with([
            'buildItems.weeklyProgresses'
        ])
        ->where('project_type', Project::TYPE_BUILD)
        ->get();

        // Rumus sama dengan customerDashboard() dan Project::getKurvaSData():
        // progress_percent per item dikali bobot_percent item / 100
        $buildProjects = $buildProjects->map(function ($project) {

            $progress = $project->buildItems->sum(
                fn ($bi) => $bi->weeklyProgresses->sum('progress_percent') * ($bi->bobot_percent / 100)
            );

            $project->progress = min(round($progress, 1), 100);

            return $project;
        });

        $completedBuild = $buildProjects
            ->where('progress', '>=', 100)
            ->count();

        $runningBuild = $buildProjects
            ->where('progress', '>', 0)
            ->where('progress', '<', 100)
            ->count();

        $topBuildProjects = $buildProjects
            ->sortByDesc('progress')
            ->take(5)
            ->values();
        $employee = auth()->user()->employee;

        $todayRequest = null;

        if ($employee) {
            $todayRequest = AttendanceRequest::where('employee_id', $employee->id)
                ->whereDate('attendance_date', today())
                ->latest()
                ->first();
        }
        $attendanceClosed = now()->gte(today()->setTime(10, 0));

    $projectStages = Project::with('levels')
    ->get()
    ->map(function ($project) {
        $levels  = $project->levels->sortBy('level_order')->values();
        $current = $levels->firstWhere('is_completed', false);

        // semua tahap sudah selesai -> tidak perlu dipantau
        if (!$current) {
            return null;
        }

        $lastDone = $levels->where('is_completed', true)->last();

        // ⬇ GANTI 'completed_at' kalau nama kolom tanggalnya berbeda
        $since = $lastDone?->completed_at ?? $project->start_date;
        $since = $since ? Carbon::parse($since)->startOfDay() : null;

        return (object) [
            'id'          => $project->id,   
            'name'        => $project->project_name,
            'type'        => match ((int) $project->project_type) {
                Project::TYPE_DESIGN => 'Desain',
                Project::TYPE_RAB    => 'RAB',
                Project::TYPE_BUILD  => 'Build',
                default              => '-',
            },
            'stage'       => $current->level_name,
            'stage_no'    => $current->level_order,
            'stage_total' => $levels->count(),
            'since'       => $since,
            'days_idle'   => $since ? (int) $since->diffInDays(today(), true) : null,
        ];
    })
    ->filter()
    ->sortByDesc('days_idle')   // paling lama diam di atas
    ->values();
        return view('dashboard.index', compact('user', 'incompleteProfile', 'incompleteAffiliator', 'attendanceToday', 'greeting', 'attendances',
        'hadir',
        'terlambat',
        'totalKaryawan',
        'belumHadir', 'projectStages',
        'cashAccounts', 'totalProject', 'totalDesign', 'totalRab', 'totalBuild',
        'runningBuild', 'attendanceClosed', 'todayRequest',
        'completedBuild',
        'topBuildProjects', 'totalCashBank',
        'cashInThisMonth',
        'cashOutThisMonth','monthlyRevenue'
        ));

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
                'account_type' => $account->account_type,
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
private function customerDashboard($user, string $greeting)
{
    // projects.customer_id berisi customers.id (bukan users.id).
    // array_filter: kalau user belum punya record customer -> whereIn([]) -> tidak ada proyek
    // (hindari where('customer_id', null) yang justru cocok dengan proyek tanpa customer).
    $projects = $this->portalProjectsQuery()
        ->whereIn('customer_id', array_filter([$user->customer?->id]))
        ->orderBy('project_name')
        ->get()
        ->map(fn ($project) => $this->portalProjectItem($project));

    $incompleteProfile = collect([
        'fullname', 'gender', 'birth_place', 'birth_date', 'religion_id',
        'identity_number', 'phone', 'address', 'province_id', 'city_id',
        'district_id', 'sub_district_id', 'postal_code_id', 'photo',
    ])->contains(fn ($field) => empty($user->$field));

    return view('dashboard.customer', compact(
        'user', 'greeting', 'projects', 'incompleteProfile'
    ));
}

/**
 * Kontraktor: hanya proyek BUILD yang ditugaskan kepadanya, tanpa data keuangan.
 * Memakai view yang sama dengan customer (kartu nama + progres, klik -> kurva S).
 */
private function contractorDashboard($user, string $greeting)
{
    $contractorIds = array_filter([$user->contractor?->id]);

    $projects = $this->portalProjectsQuery()
        ->where('project_type', Project::TYPE_BUILD)
        ->whereHas('contractors', fn ($q) => $q->whereIn('contractors.id', $contractorIds))
        ->orderBy('project_name')
        ->get()
        ->map(fn ($project) => $this->portalProjectItem($project));

    $incompleteProfile = false;

    return view('dashboard.customer', compact(
        'user', 'greeting', 'projects', 'incompleteProfile'
    ));
}

private function portalProjectsQuery()
{
    return Project::with([
        'levels',
        'buildItems.weeklyProgresses',
        'weeklyPlans.weeks',
        'technicalJustifications',
    ]);
}

/**
 * Satu kartu proyek untuk dashboard customer/kontraktor.
 */
private function portalProjectItem(Project $project): object
{
            $type = (int) $project->project_type;

    $item = (object) [
        'id'         => $project->id,
        'name'       => $project->project_name,
        'type'       => $type,
        'type_label' => match ($type) {
            Project::TYPE_DESIGN => 'Desain',
            Project::TYPE_RAB    => 'RAB',
            Project::TYPE_BUILD  => 'Build',
            default              => '-',
        },
        // sesuaikan nama route detail proyek untuk customer
        'url'      => route('projects.show', $project->id),
        'status'   => null,
        'progress' => null,
        'scurve'   => null,
    ];

    // Design & RAB: nama proyek + status (tahap yang sedang berjalan)
    if ($type !== Project::TYPE_BUILD) {
        $current = $project->levels
            ->sortBy('level_order')
            ->firstWhere('is_completed', false);

        $item->status = $current ? $current->level_name : 'Selesai';

        return $item;
    }

    // BUILD: pakai rumus yang sama dengan Project::getKurvaSData()
    // (progress_percent per item dikali bobot_percent item / 100)
    $progress = $project->buildItems->sum(
        fn ($bi) => $bi->weeklyProgresses->sum('progress_percent') * ($bi->bobot_percent / 100)
    );
    $item->progress = min(round($progress, 1), 100);

    // minggu terakhir yang benar-benar ada progres (> 0); setelah itu garis realisasi
    // berhenti. Baris laporan bernilai 0 untuk minggu yang belum terisi diabaikan.
    $lastWeek = (int) $project->buildItems
        ->flatMap->weeklyProgresses
        ->filter(fn ($p) => (float) $p->progress_percent > 0)
        ->max('week_no');

    $labels = collect($project->week_labels)
        ->map(fn ($l) => 'M' . $l['week_no'])
        ->values()
        ->all();

    $realisasi = collect($project->getKurvaSData())
        ->map(fn ($r) => $r['week'] <= $lastWeek ? min($r['progress'], 100) : null)
        ->values()
        ->all();

    // Rencana kumulatif per minggu (sumber: BuildPlanWeek, lihat model Project)
    $rencanaRaw = collect($project->getKurvaRencanaData())->pluck('progress');

    // Berakhir di 100%: normalisasi terhadap total rencana (kalau total > 0)
    $totalRencana = (float) $rencanaRaw->filter(fn ($v) => $v !== null)->max();

    $rencana = $rencanaRaw
        ->map(fn ($v) => $v === null
            ? null
            : ($totalRencana > 0 ? min(round($v / $totalRencana * 100, 2), 100) : $v))
        ->values()
        ->all();

    $item->scurve = [
        'labels' => $labels,
        'values' => $realisasi,
        'plan'   => $rencana,
    ];

    return $item;
}
}