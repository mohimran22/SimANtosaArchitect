<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\ProjectLevel;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use Illuminate\Support\Carbon;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class OfferController extends Controller
{


    public function show(Package $license_holder)
    {

        $license_holder->load(['religion', 'user.licenses', 'province', 'city', 'district', 'subDistrict', 'postalCode']);
        return view('license_holders.show', compact('license_holder'));
    }

        public function showLicense($id)
        {
            $license_holder = Package::with('user.licenses', 'user.licenses.province', 'user.licenses.city', 'user.licenses.district', 'user.licenses.subDistrict', 'user.licenses.postalCode')->findOrFail($id);

            return view('license_holders.tab.licenses', compact('license_holder'));
        }

    public function showProfile($id)
    {
    $license_holder = Package::with(['user.licenses', 'religion'])->findOrFail($id);
    return view('license_holders.tab.profile', compact('license_holder'));
    }

    public function showTab($id)
{
    $license_holder = Package::with('educations')->findOrFail($id);
    return view('license_holders.tab.educations', compact('license_holder'));
}

    public function showWorks($id)
{
    $license_holder = Package::with('workers')->findOrFail($id);
    return view('license_holders.tab.workers', compact('license_holder'));
}

    public function showFams($id)
{
    $license_holder = Package::with('families')->findOrFail($id);
    return view('license_holders.tab.families', compact('license_holder'));
}

    public function edit(Package $license_holder)
    {
        $auth = auth()->user();

        // Batas akses: kalau user punya role Pemilik Lisensi, hanya bisa edit miliknya sendiri
        if ($auth->hasRole('Pemilik Lisensi') && $license_holder->user_id !== $auth->id) {
            abort(403); // atau redirect()->back()->with('error', 'Tidak diizinkan.');
        }

        $religions = Religion::all();
        $provinces = Province::all();
        $allLicenses = License::all(); 

         // Ambil salah satu license kalau mau load data wilayah
        $license = $license_holder;

        $cities = collect();
        $districts = collect();
        $subDistricts = collect();
        $postalCodes = collect();

        if ($license) {
        $cities = City::where('province_id', $license->province_id)->get();
        $districts = District::where('city_id', $license->city_id)->get();
        $subDistricts = SubDistrict::where('district_id', $license->district_id)->get();
        $postalCodes = PostalCode::where('sub_district_id', $license->sub_district_id)->get();
        }

        return view('license_holders.edit', compact('license_holder','religions', 'license', 'provinces', 'allLicenses', 'cities', 'districts', 'subDistricts', 'postalCodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $license_holder)
    {
            $auth = auth()->user();

            
            if ($auth->hasRole('Pemilik Lisensi') && $license_holder->user_id !== $auth->id) {
                abort(403); // Forbidden
            }
        $validated = $request->validate([
            'licenses' => 'required|array',
            'licenses.*' => 'exists:licenses,id',
            'fullname' => 'required',
            'nickname' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users,email,' . $license_holder->user_id,
            'religion_id' => 'required|exists:religions,id',
            'identity_number' => 'required|digits:16',
            'driver_license_number' => 'nullable|string|max:20',
            'birth_place' => 'required',
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'address' => 'required',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'sub_district_id' => 'required|exists:sub_districts,id',
            'postal_code_id' => 'required|exists:postal_codes,id',
            'phone' => 'required',
            'hobby' => 'required',
            'marital_status' => 'required|in:1,2,3',
            'married_date' => ['nullable', 'date_format:Y-m-d'],
            'indonesian_literacy' => 'nullable|in:1,2,3',
            'indonesian_proficiency' => 'nullable|in:1,2,3',
            'arabic_literacy' => 'nullable|in:1,2,3',
            'arabic_proficiency' => 'nullable|in:1,2,3',
            'english_literacy' => 'nullable|in:1,2,3',
            'english_proficiency' => 'nullable|in:1,2,3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

            // Jika ada file baru
            if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
                if ($license_holder->photo && Storage::disk('public')->exists('photos/' . $license_holder->photo)) {
                Storage::disk('public')->delete('photos/' . $license_holder->photo);
                }

            // Simpan file baru
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $filename, 'public');
            $validated['photo'] = $filename;
        }

            $license_holder->update(collect($validated)->except(['licenses'])->toArray());

            if ($license_holder->user) {
                $license_holder->user->update([
                    'name' => $validated['fullname'],
                    'email' => $validated['email'],
                ]);
                $license_holder->user->licenses()->sync($validated['licenses']);
            }

            return redirect()->route('license_holders.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $license_holder)
    {
         if ($license_holder) {
            $license_holder->delete();
            return response()->json(['status' => 'success', 'message' => 'License deleted successfully']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Unable to delete']);
    }
}
