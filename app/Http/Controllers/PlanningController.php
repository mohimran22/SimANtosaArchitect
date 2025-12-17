<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Planning;
use App\Models\ProjectLevel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PlanningController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $data = $request->validate([
        'project_id'     => 'required|uuid',
        'employee_id'    => 'required|array',
        'employee_id.*'  => 'uuid',
        'planning_date'    => 'required|date',
        'planning_time'    => 'required',
        'survey_address' => 'required|string',
        'province_id'    => 'required|integer',
        'city_id'        => 'required|integer',
        'district_id'    => 'required|integer',
        'sub_district_id'=> 'required|integer',
        'postal_code_id' => 'required|integer',
        'planning_notes'   => 'nullable|string',
    ]);

    $planning = Planning::create([
        'project_id'       => $data['project_id'],
        'planning_date'      => $data['planning_date'],
        'planning_time'      => $data['planning_time'],
        'survey_address' => $data['survey_address'],
        'province_id'      => $data['province_id'],
        'city_id'          => $data['city_id'],
        'district_id'      => $data['district_id'],
        'sub_district_id'  => $data['sub_district_id'],
        'postal_code_id'   => $data['postal_code_id'],
        'planning_notes'     => $data['planning_notes'],
    ]);

    $projectLevel = ProjectLevel::where('project_id', $planning->project_id)
        ->where('level_order', 2) // level survei
        ->first();

    if ($projectLevel) {
        $projectLevel->employees()->sync($data['employee_id']);
    }

    // update level project (level 3 selesai, level 4 mulai)
    ProjectLevel::where('project_id', $planning->project_id)
        ->where('level_order', 2)
        ->update(['is_completed' => true]);

    ProjectLevel::where('project_id', $planning->project_id)
        ->where('level_order', 3)
        ->update(['is_started' => true]);

    return redirect()
        ->route('projects.create', ['project_id' => $data['project_id']])
        ->with('success', 'Form survei berhasil disimpan.');
}



    /**
     * Display the specified resource.
     */
    // public function show(Planning $planning)
    // {
    //     $planning->load('project.customer.user');
    //     return view('projects.plannings.show', compact('planning'));
    // }

        public function pdf(Planning $planning)
    {
        $planning->load('project.customer.user');
        $view = view('projects.plannings.pdf', compact('planning'))->render();

        $pdf = Pdf::loadHTML($view)->setPaper('a4', 'portrait');
        return $pdf->download("planning-{$planning->id}.pdf");
    }

    public function update(Request $request, Planning $planning)
    {
        $validated = $request->validate([
            'planning_date' => 'required|date',
            'planning_time' => 'required',
            'employee_id'   => 'required|array',
            'employee_id.*'  => 'uuid',
        ]);

        // 🔹 UPDATE DATA PLANNING
        $planning->update([
            'planning_date'  => $request->planning_date,
            'planning_time'  => $request->planning_time,
            'planning_notes' => $request->planning_notes,
            'survey_address' => $request->survey_address,
            'province_id'    => $request->province_id,
            'city_id'        => $request->city_id,
            'district_id'    => $request->district_id,
            'sub_district_id'=> $request->sub_district_id,
            'postal_code_id' => $request->postal_code_id,
        ]);

        /**
         * 🔹 UPDATE PETUGAS SURVEI DI LEVEL PROJECT
         * Petugas TIDAK disimpan di tabel planning.
         */
        $project = $planning->project;
        $planningLevel = $project->levels->firstWhere('level_order', 2);

        if ($planningLevel) {
            $planningLevel->employees()->sync($request->employee_id);
        }

        return back()->with('success', 'Planning berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if ($student) {
            $student->delete();
            return response()->json(['status' => 'success', 'message' => 'Student deleted successfully']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Unable to delete']);
    }
}
