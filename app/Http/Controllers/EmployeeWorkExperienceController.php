<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeWorkExperience;

class EmployeeWorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee_id = $request->query('employee_id');

        $employee = Employee::findOrFail($employee_id);

        return view('employee_workers.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'company_name' => 'required|string|max:255',
        'last_position' => 'required',
        'start_date' => ['required', 'date_format:Y-m-d'],
        'end_date' => ['nullable', 'date_format:Y-m-d'],
        'last_salary' => 'nullable|numeric|min:0',
        'reason_for_leaving' => 'nullable',
    ]);

    EmployeeWorkExperience::create($validated);

    return redirect()
        ->route('employees.show', $request->employee_id)
        ->with('tab', 'workers')
        ->with('success', 'Riwayat pekerjaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $workers = EmployeeWorkExperience::findOrFail($id);
        $employee = $workers->employee;

        return view('employee_workers.edit', compact('workers', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_name' => 'required|string|max:255',
            'last_position' => 'nullable',
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
            'last_salary' => ['required', 'numeric', 'min:0'],
            'reason_for_leaving' => 'nullable',
        ]);

        $workers = EmployeeWorkExperience::findOrFail($id);
        $workers->update($validated);

        return redirect()
        ->route('employees.show', $workers->employee_id)
        ->with('success', 'Riwayat pendidikan berhasil diperbarui.')
        ->with('tab', 'workers');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $workers = EmployeeWorkExperience::findOrFail($id);
        $employee_id = $workers->employee_id;
        $workers->delete();

        return redirect()
        ->route('employees.show', $workers->employee_id)
        ->with('success', 'Berhasil hapus riwayat kerja')
        ->with('tab', 'workers');
    }
}
