<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Planning;
use App\Models\ProjectLevel;
use App\Models\Invoice;
use App\Models\Employee;
use App\Notifications\SurveyInvoiceCreatedNotification;
use App\Notifications\PlanAssignedNotification;
use App\Services\InvoiceNumberGenerator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PlanningController extends Controller
{

public function store(Request $request)
{
    abort_if(auth()->user()->cannot('lihat daftar proyek'), 403);

    $project = Project::findOrFail($request->project_id);
    $request->merge([
    'same_address' => $request->boolean('same_address'),
    ]);
    $data = $request->validate([
        'project_id'      => 'required|uuid',
        'employee_id'     => 'required|array',
        'employee_id.*'   => 'uuid',
        'planning_date'   => 'required|date',
        'planning_time'   => 'required',
        'same_address'    => 'required|boolean',
        'survey_address'  => 'nullable|string',
        'province_id'     => 'nullable|integer',
        'city_id'         => 'nullable|integer',
        'district_id'     => 'nullable|integer',
        'sub_district_id' => 'nullable|integer',
        'postal_code_id'  => 'nullable|integer',
        'planning_notes'  => 'nullable|string',
        'survey_fee'      => 'required|string',
    ]);

    // Jika lokasi SAMA dengan proyek
    if ($request->boolean('same_address')) {
        $data['survey_address']  = $project->project_location;
        $data['province_id']     = $project->province_id;
        $data['city_id']         = $project->city_id;
        $data['district_id']     = $project->district_id;
        $data['sub_district_id'] = $project->sub_district_id;
        $data['postal_code_id']  = $project->postal_code_id;
    } else {
        // Lokasi manual → WAJIB lengkap
        validator($data, [
            'survey_address'  => 'required|string',
            'province_id'     => 'required|integer',
            'city_id'         => 'required|integer',
            'district_id'     => 'required|integer',
            'sub_district_id' => 'required|integer',
            'postal_code_id'  => 'required|integer',
        ])->validate();
    }

    $result = DB::transaction(function () use ($data, $project) {

            $surveyFee = (int) preg_replace('/[^0-9]/', '', $data['survey_fee']);

            $planning = Planning::create([
                'project_id'      => $data['project_id'],
                'planning_date'   => $data['planning_date'],
                'planning_time'   => $data['planning_time'],
                'survey_address'  => $data['survey_address'],
                'province_id'     => $data['province_id'],
                'city_id'         => $data['city_id'],
                'district_id'     => $data['district_id'],
                'sub_district_id' => $data['sub_district_id'],
                'postal_code_id'  => $data['postal_code_id'],
                'planning_notes'  => $data['planning_notes'] ?? null,
            ]);

            $projectLevel = ProjectLevel::where('project_id', $planning->project_id)
                ->where('level_order', 2)
                ->first();

            if ($projectLevel) {
                $projectLevel->employees()->sync($data['employee_id']);
            }

            $invoice = null;

            if ($surveyFee === 0) {
                ProjectLevel::where('project_id', $planning->project_id)
                    ->where('level_order', 2)
                    ->update(['is_completed' => true]);

                ProjectLevel::where('project_id', $planning->project_id)
                    ->where('level_order', 3)
                    ->update(['is_started' => true]);
            } else {
                $invoice = Invoice::create([
                    'project_id'   => $planning->project_id,
                    'invoice_type'   => Invoice::TYPE_SURVEY,
                    'invoice_number' => InvoiceNumberGenerator::generate(Invoice::TYPE_SURVEY),
                    'amount'       => $surveyFee,
                    'status'       => Invoice::STATUS_WAITING,
                    'invoice_date' => now(),
                    'approval_token' => Str::uuid(),
                ]);
            }

            return [
                'planning' => $planning,
                'invoice' => $invoice,
            ];
        });

        $creatorUser = auth()->user();
        $planning = $result['planning'];
        $invoice  = $result['invoice'];
        // Ambil level survei (level 2)
        $level = $project->levels()->where('level_order', 2)->first();

        if ($level) {
            $employees = $level->employees()->with('user')->get();

            foreach ($employees as $employee) {
                if (!$employee->user) continue;

                // Jangan kirim ke diri sendiri
                if ($employee->user->id === $creatorUser->id) continue;

                $employee->user->notify(
                    new PlanAssignedNotification($planning, 'assigned_employee')
                );
            }
        }

        // Kirim ke customer kalau ada invoice atau walau gratis
        if ($project->customer?->user) {
            $project->customer->user->notify(
                new PlanAssignedNotification($planning, 'customer')
            );
        }

        if ($invoice && $project->customer?->user) {
            $project->customer->user->notify(
                new SurveyInvoiceCreatedNotification($project, $invoice)
            );
        }

    return redirect()
        ->route('projects.create', ['project_id' => $data['project_id']])
        ->with('success', 'Rencana survei berhasil disimpan.');
}


public function planningSurveyPdf(Project $project)
{
    $planning = $project->planning;
    $surveyInvoice = $project->latestSurveyInvoice();
    $planningEmployees = $planning?->employees;

    abort_if(!$planning, 404);

    return Pdf::loadView('pdf.planning-survey', compact(
        'project',
        'planning',
        'surveyInvoice',
        'planningEmployees'
    ))
    ->setPaper('A4')
    ->stream('Rencana-Survei.pdf');
}

public function update(Request $request, Planning $planning)
{
    abort_if(auth()->user()->cannot('ubah data proyek'), 403);

    $request->validate([
        'planning_date' => 'required|date',
        'planning_time' => 'required',
        'employee_id'   => 'required|array',
        'employee_id.*' => 'exists:employees,id',
        'survey_fee'    => 'required',
    ]);

    DB::transaction(function () use ($request, $planning) {

        $planning->update([
            'planning_date'   => $request->planning_date,
            'planning_time'   => $request->planning_time,
            'planning_notes'  => $request->planning_notes,
            'survey_address'  => $request->survey_address,
            'province_id'     => $request->province_id,
            'city_id'         => $request->city_id,
            'district_id'     => $request->district_id,
            'sub_district_id' => $request->sub_district_id,
            'postal_code_id'  => $request->postal_code_id,
        ]);

        $project = $planning->project;
        $planningLevel = $project->levels->firstWhere('level_order', 2);

        if ($planningLevel) {
            $planningLevel->employees()->sync($request->employee_id);
        }

        $amount = (int) preg_replace('/[^0-9]/', '', $request->survey_fee);

        Invoice::where('project_id', $project->id)
            ->where('invoice_type', 'survey')
            ->whereIn('status', ['waiting_approval', 'rejected'])
            ->update([
                'status' => 'obsolete',
            ]);
        if ($amount > 0) {
        Invoice::create([
            'project_id'     => $project->id,
            'invoice_type'   => 'survey',
            'invoice_number' => InvoiceNumberGenerator::generate(Invoice::TYPE_SURVEY),
            'amount'         => $amount,
            'status'         => $amount > 0 ? 'waiting_approval' : 'approved',
            'approval_token' => $amount > 0 ? Str::uuid() : null,
            'approved_at'    => $amount > 0 ? null : now(),
        ]);
    }

        if ($amount === 0) {

            // Complete Rencana Survei
            ProjectLevel::where('project_id', $project->id)
                ->where('level_order', 2)
                ->update([
                    'is_completed' => true,
                ]);

            // Start Survei
            ProjectLevel::where('project_id', $project->id)
                ->where('level_order', 3)
                ->update([
                    'is_started' => true,
                ]);
        }

    });

    return back()->with('success', 'Rencana survei berhasil diperbarui.');
}

    public function destroy(Student $student)
    {
        if ($student) {
            $student->delete();
            return response()->json(['status' => 'success', 'message' => 'Student deleted successfully']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Unable to delete']);
    }
}
