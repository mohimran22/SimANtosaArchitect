<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeEducation;

class EmployeeEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee_id = $request->query('employee_id');

        $employee = Employee::findOrFail($employee_id);

        return view('employee_educations.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'education_level' => 'required|string|max:50',
        'institution_name' => 'required|string|max:255',
        'start_year' => 'required|integer',
        'end_year' => 'nullable|integer',
        'is_graduated' => 'required|boolean',
    ]);

    EmployeeEducation::create($validated);

    return redirect()
        ->route('employees.show', $request->employee_id)
        ->with('tab', 'educations')
        ->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
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
    public function edit($id)
    {
        $education = EmployeeEducation::findOrFail($id);
        $employee = $education->employee;

        return view('employee_educations.edit', compact('education', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'education_level' => 'required|string|max:50',
        'institution_name' => 'required|string|max:255',
        'start_year' => 'required|integer',
        'end_year' => 'nullable|integer',
        'is_graduated' => 'required|boolean',
        ]);

        $education = EmployeeEducation::findOrFail($id);
        $education->update($validated);

        return redirect()
        ->route('employees.show', $education->employee_id)
        ->with('success', 'Riwayat pendidikan berhasil diperbarui.')
        ->with('tab', 'educations');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $education = EmployeeEducation::findOrFail($id);
        $employee_id = $education->employee_id;
        $education->delete();

        return redirect()
            ->route('employees.show', $education->employee_id)
            ->with('success', 'Berhasil hapus data')
            ->with('tab', 'educations');
    }
}
