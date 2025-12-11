<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use App\Models\Project;
use App\Models\ProjectLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       public function index(Request $request)
{
    $auth = auth()->user();

    $query = Project::with([
        'customer.user:id,fullname',
        'employee.user:id,fullname',
        'affiliator.user:id,fullname',
        'province:id,name',
        'city:id,name',
        'district:id,name',
        'subDistrict:id,name',
        'postalCode:id,postal_code',
        'levels:id,project_id,level_order,level_name,is_completed'
    ]);

    // Jika ada hak akses untuk membatasi data
    if ($auth->can('lihat data proyek') && !$auth->can('lihat daftar proyek')) {
        $query->where('user_id', $auth->id);
    }

    if ($request->ajax()) {
        $projects = $query->get();

        $statusLabel = [
            1 => 'Proses',
            2 => 'Revisi',
            3 => 'Butuh Persetujuan',
            4 => 'Selesai'
        ];

        return DataTables::of($projects)
        ->addIndexColumn()

        ->addColumn('province_name', fn($row) => $row->province->name ?? '-')
        ->addColumn('city_name', fn($row) => $row->city->name ?? '-')
        ->addColumn('district_name', fn($row) => $row->district->name ?? '-')
        ->addColumn('sub_district_name', fn($row) => $row->subDistrict->name ?? '-')
        ->addColumn('postal_code', fn($row) => $row->postalCode->postal_code ?? '-')
        ->addColumn('customer', fn($row) => $row->customer?->user?->fullname ?? '-')
        ->addColumn('employee', fn($row) => $row->employee?->user?->fullname ?? '-')
        ->addColumn('affiliator', fn($row) => $row->affiliator?->user?->fullname ?? '-')
        ->addColumn('project_type', fn($row) => $this->readableProjectType($row->project_type))
        ->addColumn('start_date', fn($row) => $row->start_date ? Carbon::parse($row->start_date)->format('d/m/Y') : '-')

        ->addColumn('project_status', function ($row) use ($statusLabel) {

            $label = $statusLabel[$row->project_status] ?? 'Tidak Diketahui';

            $color = match ($row->project_status) {
                1 => 'success',
                2 => 'warning',
                3 => 'danger',
                4 => 'info',
                default => 'secondary'
            };

            return '<span class="badge bg-' . $color . '">' . $label . '</span>';
        })

        ->addColumn('current_level', function ($row) {
    $current = $row->levels
        ->where('is_completed', false)
        ->sortBy('level_order')
        ->first();

    // Jika semua selesai
    if (!$current) {
        return '<span class="badge bg-success">Selesai Semua Tahapan</span>';
    }

    $url = route('projects.continue', $row->id);

    return '<a href="'.$url.'" class="badge bg-primary" style="cursor:pointer;">
                '.$current->level_name.'
            </a>';
})
        ->editColumn('project_name', function ($row) {
                    $url = route('projects.continue', $row->id);
                    $name = Str::title($row->project_name ?? '-');
                    return '<a href="'.$url.'">'.e($name).'</a>';
                })

        // Tombol Aksi
        ->addColumn('action', function ($project) {
            $buttons = '';

            if (auth()->user()->can('ubah data proyek')) {
                $buttons .= '<a href="' . route('projects.edit', $project->id) . '" 
                            class="btn btn-icon btn-sm btn-dark me-1">
                            <i class="ti ti-edit"></i></a>';
            }
            //  if (auth()->user()->can('lihat data proyek')) {
            //             $buttons .= '<a href="' . route('projects.show', $project->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Lihat">
            //                             <i class="ti ti-eye"></i>
            //                         </a>';

            // }
            if (auth()->user()->can('hapus data proyek')) {
                $buttons .= '<button data-id="' . $project->id . '" 
                            class="btn btn-icon btn-sm btn-dark delete-projects">
                            <i class="ti ti-trash"></i></button>';
            }

            return $buttons;
        })

        ->rawColumns(['current_level', 'action', 'project_status', 'project_name'])
        ->make(true);
    }

    return view('projects.index');
}

    private function readableProjectType($value)
    {
        return match ((int) $value) {
            1 => 'Desain',
            2 => 'Build',
            default => '-',
        };
    }

public function create(Request $request)
    {
        

        $project = null;

        if ($request->has('project_id')) {
            $project = $this->loadFullProject($request->project_id);
        }

        $activeStep = $this->getCurrentStep($project);

        return view('projects.create', array_merge(
            $this->formData(),
            compact('project', 'activeStep')
        ));
    }
public function store(ProjectRequest $request)
    {
        $project = DB::transaction(function () use ($request) {

            $project = Project::create($request->validated());

            $project->levels()->createMany([
                ['level_order' => 1, 'level_name' => 'Konsultasi'],
                ['level_order' => 2, 'level_name' => 'Rencana Survei'],
                ['level_order' => 3, 'level_name' => 'Survei'],
                ['level_order' => 4, 'level_name' => 'Penawaran Jasa Desain'],
                ['level_order' => 5, 'level_name' => 'Kontrak Desain'],
                ['level_order' => 6, 'level_name' => 'Invoice Desain DP'],
                ['level_order' => 7, 'level_name' => 'Form SPK Desain Denah'],
                ['level_order' => 8, 'level_name' => 'Pengerjaan Desain Denah'],
                ['level_order' => 9, 'level_name' => 'Revisi Desain Denah'],
                ['level_order' => 10, 'level_name' => 'Form SPK 3D'],
                ['level_order' => 11, 'level_name' => 'Pengerjaan 3D'],
                ['level_order' => 12, 'level_name' => 'Revisi 3D'],
                ['level_order' => 13, 'level_name' => 'Form SPK DED'],
                ['level_order' => 14, 'level_name' => 'Pengerjaan DED'],
                ['level_order' => 15, 'level_name' => 'Revisi DED'],
                ['level_order' => 16, 'level_name' => 'Form SPK RAB'],
                ['level_order' => 17, 'level_name' => 'Pengerjaan RAB'],
                ['level_order' => 18, 'level_name' => 'Revisi RAB'],
                ['level_order' => 19, 'level_name' => 'Invoice Pelunasan Desain'],
                ['level_order' => 20, 'level_name' => 'Cetak & Softcopy'],
            ]);

            return $project;
        });

        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with('success', 'Project berhasil dibuat, lanjut ke form konsultasi.');
    }


    /**
     * Hitung step aktif berdasarkan ProjectLevel
     */
    private function getCurrentStep($project)
    {
        if (!$project) return 1;

        $current = $project->levels
            ->where('is_completed', false)
            ->sortBy('level_order')
            ->first();

        return $current ? $current->level_order + 1 : 99;
    }


    /**
     * Load project lengkap + semua relasi untuk multi-step
     */
    private function loadFullProject($projectId)
    {
        return Project::with([
         'customer.user',
        'employee',

        // LEVEL + EMPLOYEE
        'levels.employees',
        'consultations.items',
        // PLANNING LENGKAP
        'plannings',
        'surveys.items',
        'offer.items',
        ])->findOrFail($projectId);
    }

    public function continue(Project $project, Request $request)
{
    $project->load([
        'customer.user',
        'employee',
        'levels.employees',
        'consultations.items',
        'plannings',
        'surveys.items',
        'offer.items',
    ]);

    $activeStep = $this->computeActiveStep($project);

    return redirect()->route('projects.create', [
        'project_id' => $project->id,
        'step'       => $activeStep,
    ]);
}

/**
 * Dapatkan step aktif untuk project.
 */
private function computeActiveStep($project, $request = null)
{
    // Jika URL membawa ?step= → pakai itu
    if ($request && $request->filled('step')) {
        return (int) $request->step;
    }

    // Jika project belum ada → step 1
    if (!$project) {
        return 1;
    }

    // Cari level yang sedang berjalan
    $current = $project->levels
        ->where('is_started', true)
        ->where('is_completed', false)
        ->sortBy('level_order')
        ->first();

    // Jika tidak ada → berarti semua selesai → step terakhir
    if (!$current) {
        return 20;
    }

    // Step aktif adalah level_order
    return $current->level_order;
}



public function show(Project $project)
{
    $project->load([
        'customer.user',
        'employee.user',
        'affiliator.user',
        'levels',
        'consultations.items',
        'plannings',
        'surveys.items'
    ]);

    $currentLevel = $project->levels()
        ->where('is_completed', false)
        ->orderBy('level_order')
        ->first();

    $consultation = $project->consultations->first();
    $planning = $project->plannings->first();

    return view('projects.show', compact(
        'project',
        'currentLevel',
        'consultation',
        'planning'
    ));
}

    public function edit(Project $project)
{
    $project->load([
        'customer.user',
        'employee.user',
        'affiliator.user',
        'levels',
        'consultations.items',
        // 'surveys.items',
        // 'designs',
        // 'rabs',
        // 'spks',
    ]);

    $consultation = $project->consultations->first();
    // $survey       = $project->surveys->first();
    // $design       = $project->designs->first();
    // $rab          = $project->rabs->first();
    // $spk          = $project->spks->first();

    $employees = Employee::with('user')->get();
    $customers = Customer::with('user')->get();
    $provinces = Province::all();
    $cities = City::where('province_id', $project->province_id)->get();
    $districts = District::where('city_id', $project->city_id)->get();
    $subDistricts = SubDistrict::where('district_id', $project->district_id)->get();
    $postalCodes = PostalCode::where('sub_district_id', $project->sub_district_id)->get();

    return view('projects.edit', compact(
        'project',
        'consultation',
        // 'survey',
        // 'design',
        // 'rab',
        'customers',
        'employees',
        'provinces', 'cities', 'districts', 'subDistricts', 'postalCodes'
    ));
}

    public function update(ProjectRequest $request, Project $project)
{
    DB::transaction(function() use ($request, $project) {

        // UPDATE PROJECT
        $project->update($request->validated());

        // === JIKA ADA KONSULTASI, UPDATE ===
        if ($project->consultations->isNotEmpty()) {

            $consultation = $project->consultations->first();

            $consultation->update([
                'contact_name'      => $request->contact_name,
                'contact_phone'     => $request->contact_phone,
                'site_area'         => $request->site_area,
                'building_area'     => $request->building_area,
                'notes'             => $request->notes,
                'employee_id'       => $request->employee_id,
            ]);

            // UPDATE ITEMS
            $consultation->items()->delete();
            foreach ($request->items as $i => $item) {
                $consultation->items()->create([
                    'order_no'    => $i + 1,
                    'description' => $item['description'],
                    'remark'      => $item['remark'] ?? null,
                ]);
            }
        }

        // Tahap lain nanti bisa dilanjutkan di sini...
    });

    return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'Produk berhasil diperbarui.');
}


     public function destroy(Project $project) 
    {
    
        if ($project) {
            $project->delete();
            return response()->json(['status' => 'success', 'message' => 'Project deleted successfully']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Unable to delete']);
    }

    private function formData($merge = [])
    {
        return array_merge([
            'employees'     => \App\Models\Employee::with('user:id,fullname')->get(['id','user_id']),
            'customers'     => \App\Models\Customer::with('user:id,fullname')->get(['id','user_id']),
            'affiliators'   => \App\Models\Affiliator::with('user:id,fullname')->get(['id','user_id']),
            'provinces'     => \App\Models\Province::all(),
'designPackages' => \App\Models\DesignPackage::orderBy('name')->orderBy('price_meter')->get(),

            'projectStatus' => [
                1 => 'Proses',
                2 => 'Revisi',
                3 => 'Butuh Persetujuan',
                4 => 'Selesai'
            ]
        ], $merge);
    }
}
