<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use App\Models\Project;
use App\Models\ProjectLevel;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ProjectController extends Controller
{
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
                return '<span class="badge bg-success">Selesai</span>';
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

            // if (auth()->user()->can('ubah data proyek')) {
            //     $buttons .= '<a href="' . route('projects.edit', $project->id) . '" 
            //                 class="btn btn-icon btn-sm btn-dark me-1">
            //                 <i class="ti ti-edit"></i></a>';
            // }
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
        $surveyInvoice = Invoice::where('project_id', $project?->id)
            ->where('invoice_type', 'survey')
            ->latest()
            ->first();

        $surveyApproved = $surveyInvoice && $surveyInvoice->status === 'approved';
        $isFreeSurvey = !$surveyInvoice && $project?->levels
            ->firstWhere('level_order', 3)?->is_started;

        $surveyWaiting  = $surveyInvoice && $surveyInvoice->status === 'waiting_approval';
        $surveyRejected = $surveyInvoice && $surveyInvoice->status === 'rejected';
        $invoiceDp = Invoice::where('project_id', $project?->id)
            ->where('invoice_type', Invoice::TYPE_DP)
            ->first();


        // 🔥 OVERRIDE STEP
        if (
            $project &&
            $project->planning &&
            ($isFreeSurvey || $surveyApproved) &&
            $activeStep == 3
        ) {
            $activeStep = 4;
        }

        $timelineSteps = $project
            ? $project->levels
                ->sortBy('level_order')
                ->map(function($level) use ($activeStep) {
                    return [
                        'label' => $level->level_name,
                        'completed' => $level->is_completed,
                        'current' => $activeStep == $level->level_order + 1,
                    ];
                })
                ->values()
            : collect([]);

        return view('projects.create', array_merge(
            $this->formData(),
            compact('project', 'timelineSteps', 'activeStep', 'surveyInvoice',
        'surveyApproved',
        'isFreeSurvey', 'surveyWaiting', 'surveyRejected', 'invoiceDp')
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
                ['level_order' => 7, 'level_name' => 'Proses Pengerjaan'],
                ['level_order' => 8, 'level_name' => 'Invoice Pelunasan Desain'],
                ['level_order' => 9, 'level_name' => 'Cetak & Softcopy'],
            ]);
            return $project;
        });
        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with('success', 'Project berhasil dibuat, lanjut ke form konsultasi.');
    }
                // ['level_order' => 7, 'level_name' => 'Form SPK Desain Denah'],
                // ['level_order' => 8, 'level_name' => 'Pengerjaan Desain Denah'],
                // ['level_order' => 9, 'level_name' => 'Revisi Desain Denah'],
                // ['level_order' => 10, 'level_name' => 'Form SPK 3D'],
                // ['level_order' => 11, 'level_name' => 'Pengerjaan 3D'],
                // ['level_order' => 12, 'level_name' => 'Revisi 3D'],
                // ['level_order' => 13, 'level_name' => 'Form SPK DED'],
                // ['level_order' => 14, 'level_name' => 'Pengerjaan DED'],
                // ['level_order' => 15, 'level_name' => 'Revisi DED'],
                // ['level_order' => 16, 'level_name' => 'Form SPK RAB'],
                
                // ['level_order' => 8, 'level_name' => 'Pengerjaan RAB'],
                // ['level_order' => 9, 'level_name' => 'Revisi RAB'],

    private function getCurrentStep($project)
    {
        if (!$project) return 1;

        $current = $project->levels
            ->where('is_completed', false)
            ->sortBy('level_order')
            ->first();

        return $current ? $current->level_order + 1 : 9;
    }

    private function loadFullProject($projectId)
    {
        return Project::with([
         'customer.user',
        'employee',
        'levels.employees',
        'consultation.items',
        'planning',
        'survey.items',
        'offer.items',
        ])->findOrFail($projectId);
    }

    public function continue(Project $project, Request $request)
{
    $project->load([
        'customer.user',
        'employee',
        'levels.employees',
        'consultation.items',
        'planning',
        'survey.items',
        'offer.items',
    ]);

    $activeStep = $this->computeActiveStep($project);

    return redirect()->route('projects.create', [
        'project_id' => $project->id,
        'step'       => $activeStep,
    ]);
}

private function computeActiveStep($project, $request = null)
{
    if ($request && $request->filled('step')) {
        return (int) $request->step;
    }

    if (!$project) {
        return 1;
    }

    $current = $project->levels
        ->where('is_completed', false)
        ->sortBy('level_order')
        ->first();

    return $current ? $current->level_order + 1 : 9;
}

public function update(Request $request, Project $project)
{
    $project->update($request->all());

    return redirect()
        ->route('projects.index', ['project_id' => $project->id]) 
        ->withFragment('project-' . $project->number)
        ->with('success', 'Updated!');
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
            'provinces'     =>  Province::all(),
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
