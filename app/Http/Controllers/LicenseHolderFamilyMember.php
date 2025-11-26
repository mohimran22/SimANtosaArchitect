<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LicenseHolder;
use App\Models\LicenseHolderFamilyMembers;

class LicenseHolderFamilyMember extends Controller
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

        return view('license_holder_families.create', compact('license_holder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'license_holder_id' => 'required|exists:license_holders,id',
        'name' => 'required|string|max:50',
        'relationship' => 'required|in:1,2,3,4,5',
        'gender' => 'required|in:1,2',
        'birth_date' => ['required', 'date_format:Y-m-d'],
        'job' => 'nullable',
        'job_phone' => 'nullable',
        'last_education_level' => 'nullable',
        'institution_name' => 'nullable',
    ]);

    LicenseHolderFamilyMembers::create($validated);

    return redirect()
        ->route('license_holders.show', $request->license_holder_id)
        ->with('tab', 'families')
        ->with('success', 'Data keluarga berhasil ditambahkan.');
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
        $families = LicenseHolderFamilyMembers::findOrFail($id);
        $license_holder = $families->license_holder;

        return view('license_holder_families.edit', compact('families', 'license_holder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'license_holder_id' => 'required|exists:license_holders,id',
            'name' => 'required|string|max:50',
            'relationship' => 'required|in:1,2,3,4,5',
            'gender' => 'required|in:1,2',
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'job' => 'nullable',
            'job_phone' => 'nullable',
            'last_education_level' => 'nullable',
            'institution_name' => 'nullable',
        ]);

        $families = LicenseHolderFamilyMembers::findOrFail($id);
        $families->update($validated);

        return redirect()
        ->route('license_holders.show', $families->license_holder_id)
        ->with('success', 'Riwayat pendidikan berhasil diperbarui.')
        ->with('tab', 'families');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $families = LicenseHolderFamilyMembers::findOrFail($id);
        $license_holder_id = $families->license_holder_id;
        $families->delete();

        return redirect()
        ->route('license_holders.show', $families->license_holder_id)
        ->with('success', 'Berhasil hapus data')
        ->with('tab', 'families');
    }
}
