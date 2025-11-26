<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeFamilyMember;

class EmployeeFamilyMemberController extends Controller
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

        return view('employee_families.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'name' => 'required|string|max:255',
        'relationship' => 'required|in:1,2,3,4,5',
        'gender' => 'required|in:1,2',
        'birth_date' => ['required', 'date_format:Y-m-d'],
        'job' => 'nullable',
        'job_phone' => 'nullable',
        'last_education_level' => 'nullable',
        'institution_name' => 'nullable',
        'company_name' => 'nullable|string|max:255',

    ]);

    EmployeeFamilyMember::create($validated);

    return redirect()
        ->route('employees.show', $request->employee_id)
        ->with('tab', 'families')
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
        $families = EmployeeFamilyMember::findOrFail($id);
        $employee = $families->employee;

        return view('employee_families.edit', compact('families', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'name' => 'required|string|max:50',
            'relationship' => 'required|in:1,2,3,4,5',
            'gender' => 'required|in:1,2',
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'job' => 'nullable',
            'job_phone' => 'nullable',
            'last_education_level' => 'nullable',
            'institution_name' => 'nullable',
            'company_name' => 'nullable',
        ]);

        $families = EmployeeFamilyMember::findOrFail($id);
        $families->update($validated);

        return redirect()
        ->route('employees.show', $families->employee_id)
        ->with('success', 'Riwayat pendidikan berhasil diperbarui.')
        ->with('tab', 'families');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $families = EmployeeFamilyMember::findOrFail($id);
        $employee_id = $families->employee_id;
        $families->delete();

        return redirect()
        ->route('employees.show', $families->employee_id)
        ->with('success', 'Berhasil hapus data')
        ->with('tab', 'families');
    }
}
