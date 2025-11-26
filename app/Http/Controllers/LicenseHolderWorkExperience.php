<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LicenseHolder;
use App\Models\LicenseHolderWorkExperiences;

class LicenseHolderWorkExperience extends Controller
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
        $license_holder_id = $request->query('license_holder_id');

        $license_holder = LicenseHolder::findOrFail($license_holder_id);

        return view('license_holder_workers.create', compact('license_holder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'license_holder_id' => 'required|exists:license_holders,id',
        'company_name' => 'required|string|max:255',
        'city' => 'required|string|max:50',
        'phone' => 'required',
        'position' => 'nullable',
        'employment_type' => 'required|in:1,2,3,4',
        'start_date' => ['required', 'date_format:Y-m-d'],
        'end_date' => ['nullable', 'date_format:Y-m-d'],
        'is_current' => 'required|boolean',
        'skills_used' => 'nullable',
        'job_description' => 'nullable',
    ]);

    LicenseHolderWorkExperiences::create($validated);

    return redirect()
        ->route('license_holders.show', $request->license_holder_id)
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
    public function edit($id)
    {
        $workers = LicenseHolderWorkExperiences::findOrFail($id);
        $license_holder = $workers->license_holder;

        return view('license_holder_workers.edit', compact('workers', 'license_holder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'license_holder_id' => 'required|exists:license_holders,id',
            'company_name' => 'required|string|max:255',
            'city' => 'required|string|max:50',
            'phone' => 'required',
            'position' => 'nullable',
            'employment_type' => 'required|integer',
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
            'is_current' => 'required|boolean',
            'skills_used' => 'nullable',
            'job_description' => 'nullable',
        ]);

        $workers = LicenseHolderWorkExperiences::findOrFail($id);
        $workers->update($validated);

        return redirect()
        ->route('license_holders.show', $workers->license_holder_id)
        ->with('success', 'Riwayat pendidikan berhasil diperbarui.')
        ->with('tab', 'workers');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $workers = LicenseHolderWorkExperiences::findOrFail($id);
        $license_holder_id = $workers->license_holder_id;
        $workers->delete();

        return redirect()
        ->route('license_holders.show', $workers->license_holder_id)
        ->with('success', 'Berhasil hapus riwayat kerja')
        ->with('tab', 'workers');
    }
}
