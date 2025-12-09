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
    public function show(Planning $planning)
    {
        $planning->load('project.customer.user');
        return view('projects.plannings.show', compact('planning'));
    }

        public function pdf(Planning $planning)
    {
        $planning->load('project.customer.user');
        $view = view('projects.plannings.pdf', compact('planning'))->render();

        $pdf = Pdf::loadHTML($view)->setPaper('a4', 'portrait');
        return $pdf->download("planning-{$planning->id}.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $licenses = License::all();
        $religions = Religion::all();
        $provinces = Province::all();
        $cities = City::where('province_id', $student->province_id)->get();
        $districts = District::where('city_id', $student->city_id)->get();
        $subDistricts = SubDistrict::where('district_id', $student->district_id)->get();
        $postalCodes = PostalCode::where('sub_district_id', $student->sub_district_id)->get();

        return view('students.edit', compact('student', 'licenses', 'religions', 'provinces', 'cities', 'districts', 'subDistricts', 'postalCodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
        'nis' => [
            'required',
            'string',
            'digits:5',
            Rule::unique('students')->ignore($student->id),
        ],
        'license_id' => 'required|exists:licenses,id',
        'fullname' => 'required|string',
        'nickname' => 'nullable|string',
        'gender' => 'required|in:1,2',
        'birth_place' => 'nullable|string',
        'birth_date' => 'required|date',
        'age' => 'nullable|integer|min:0',
        'religion_id' => 'required|exists:religions,id',
        'email' => [
            'nullable',
            'email',
             Rule::unique('students')->ignore($student->id),
            ], 
        'address' => 'nullable|string',
        'province_id' => 'nullable|exists:provinces,id',
        'city_id' => 'nullable|exists:cities,id',
        'district_id' => 'nullable|exists:districts,id',
        'sub_district_id' => 'nullable|exists:sub_districts,id',
        'postal_code_id' => 'nullable|exists:postal_codes,id',
        'father_name' => 'required|string',
        'father_phone' => 'required|string',
        'mother_name' => 'required|string',
        'mother_phone' => 'required|string',
        'student_phone' => 'nullable|string',
        'previous_school' => 'nullable|string',
        'grade' => 'nullable|string',
        'status' => 'nullable|string',
        'photo' => ['nullable|image|mimes:jpeg,png,jpg,gif|max:2048'],
    ]);

    if ($request->birth_date) {
        $validated['age'] = Carbon::parse($request->birth_date)->age;
    }

    // Jika ada file baru
            if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
                if ($student->photo && Storage::disk('public')->exists('photos/' . $student->photo)) {
                Storage::disk('public')->delete('photos/' . $student->photo);
                }

            // Simpan file baru
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $filename, 'public');
            $validated['photo'] = $filename;
        }

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diupdate.');
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
