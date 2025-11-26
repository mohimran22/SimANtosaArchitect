<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Religion;
use App\Models\License;
use App\Models\LicenseHolder;
use App\Models\Province;
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


class LicenseHoldersController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $license_holders = $this->getJoinedLicenseHolders();

            return DataTables::of($license_holders)
                ->addColumn('birth_date', fn($row) => $row->birth_date ? \Carbon\Carbon::parse($row->birth_date)->format('d/m/Y') : '-')
                ->addColumn('marital_status', fn($row) => $this->readableMaritalStatus($row->marital_status))
                ->addColumn('gender', fn($row) => $this->readableGender($row->gender))
                ->addColumn('married_date', fn($row) => $row->married_date ? \Carbon\Carbon::parse($row->married_date)->format('d/m/Y') : '-')
                 ->editColumn('license_holder_name', function ($row) {
                    $url = route('license_holders.show', $row->license_holder_id); // Ganti dengan route detailmu
                    return '<a href="'.$url.'">'.e($row->license_holder_name).'</a>';
                })
                ->addColumn('indonesian_literacy', fn($row) => $this->readableLanguage($row->indonesian_literacy))
                ->addColumn('indonesian_proficiency', fn($row) => $this->readableLanguage($row->indonesian_proficiency))
                ->addColumn('arabic_literacy', fn($row) => $this->readableLanguage($row->arabic_literacy))
                ->addColumn('arabic_proficiency', fn($row) => $this->readableLanguage($row->arabic_proficiency))
                ->addColumn('english_literacy', fn($row) => $this->readableLanguage($row->english_literacy))
                ->addColumn('english_proficiency', fn($row) => $this->readableLanguage($row->english_proficiency))
                ->addColumn('action', function ($license_holder) {
                    $buttons = '';
                    if (auth()->user()->can('pemilik-lisensi.ubah')) {
                        $buttons .= '<a href="' . route('license_holders.edit', $license_holder->license_holder_id) . '" class="btn btn-success btn-sm">Edit</a> ';
                    }
                    if (auth()->user()->can('pemilik-lisensi.lihat')) {
                        $buttons .= '<a href="' . route('license_holders.show', $license_holder->license_holder_id) . '" class="btn btn-secondary btn-sm">Show</a> ';
                    }
                    if (auth()->user()->can('pemilik-lisensi.hapus')) {
                        $buttons .= '<button data-id="' . $license_holder->license_holder_id . '" class="btn btn-danger btn-sm delete-license_holder">Delete</button>';
                    }
                    return $buttons;
                })
                ->rawColumns(['license_holder_name', 'action'])
                ->make(true);
        }


        return view('license_holders.index');
    }

    private function getJoinedLicenseHolders()
{
    $auth = auth()->user();

    $query = \DB::table('license_holders')
        ->join('users', 'license_holders.user_id', '=', 'users.id')
        ->join('license_user', 'users.id', '=', 'license_user.user_id')
        ->join('licenses', 'license_user.license_id', '=', 'licenses.id')
        ->leftJoin('religions', 'license_holders.religion_id', '=', 'religions.id')
        ->leftJoin('provinces', 'license_holders.province_id', '=', 'provinces.id')
        ->leftJoin('cities', 'license_holders.city_id', '=', 'cities.id')
        ->leftJoin('districts', 'license_holders.district_id', '=', 'districts.id')
        ->leftJoin('sub_districts', 'license_holders.sub_district_id', '=', 'sub_districts.id')
        ->leftJoin('postal_codes', 'license_holders.postal_code_id', '=', 'postal_codes.id')
        ->select(
            'license_holders.id as license_holder_id',
            'license_holders.fullname as license_holder_name',
            'license_holders.nickname',
            'license_holders.gender',
            'licenses.license_id as license_id',
            'licenses.license_type as license_type',
            'licenses.name as license_name',
            'religions.name as religion_name',
            'license_holders.identity_number',
            'license_holders.driver_license_number',
            'license_holders.birth_place',
            'license_holders.birth_date',
            'license_holders.address',
            'provinces.name as province_name',
            'cities.name as city_name',
            'districts.name as district_name',
            'sub_districts.name as sub_district_name',
            'postal_codes.postal_code',
            'license_holders.phone',
            'license_holders.hobby',
            'license_holders.marital_status',
            'license_holders.married_date',
            'license_holders.indonesian_literacy',
            'license_holders.indonesian_proficiency',
            'license_holders.arabic_literacy',
            'license_holders.arabic_proficiency',
            'license_holders.english_literacy',
            'license_holders.english_proficiency'
        );

    if ($auth->hasRole('Pemilik Lisensi')) {
        $query->where('license_holders.user_id', $auth->id);
    }

    return $query;
}


    private function readableLanguage($value)
    {
        return match ((int) $value) {
            1 => 'Lancar',
            2 => 'Tidak Lancar',
            default => '-',
        };
    }

    private function readableMaritalStatus($value)
    {
        return match ((int) $value) {
            1 => 'Belum Menikah',
            2 => 'Sudah Menikah',
            3 => 'Cerai Hidup',
            4 => 'Cerai Mati',
            default => '-',
        };
    }

    private function readableGender($value)
    {
        return match ((int) $value) {
            1 => 'Laki - Laki',
            2 => 'Perempuan',
            default => '-',
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $religions = Religion::all();
        $licenses = License::all(); 
        $provinces = Province::all();
        $owners = \App\Models\User::role('Pemilik Lisensi')->get();
        return view('license_holders.create', compact('religions', 'licenses', 'provinces', 'owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([ 
            'licenses' => 'required|array',
            'licenses.*' => 'exists:licenses,id',
            'fullname' => 'required',
            'nickname' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users,email',
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
            'indonesian_literacy' => 'nullable|in:1,2',
            'indonesian_proficiency' => 'nullable|in:1,2',
            'arabic_literacy' => 'nullable|in:1,2',
            'arabic_proficiency' => 'nullable|in:1,2',
            'english_literacy' => 'nullable|in:1,2',
            'english_proficiency' => 'nullable|in:1,2',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $filename, 'public');
            $validated['photo'] = $filename;
        }

        $user = \App\Models\User::create([
        'name' => $validated['fullname'],
        'email' => $validated['email'],
        'password' => bcrypt('password123'), // atau gunakan password generator
        ]);

        $user->assignRole('Pemilik Lisensi');

        $user->licenses()->sync($validated['licenses']);

        $validated['user_id'] = $user->id;
        LicenseHolder::create(collect($validated)->except(['licenses', 'email'])->toArray());
             

        return redirect()->route('license_holders.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(LicenseHolder $license_holder)
    {

        $license_holder->load(['religion', 'user.licenses', 'province', 'city', 'district', 'subDistrict', 'postalCode']);
        return view('license_holders.show', compact('license_holder'));
    }

        public function showLicense($id)
        {
            $license_holder = LicenseHolder::with('user.licenses', 'user.licenses.province', 'user.licenses.city', 'user.licenses.district', 'user.licenses.subDistrict', 'user.licenses.postalCode')->findOrFail($id);

            return view('license_holders.tab.licenses', compact('license_holder'));
        }

    public function showProfile($id)
    {
    $license_holder = LicenseHolder::with(['user.licenses', 'religion'])->findOrFail($id);
    return view('license_holders.tab.profile', compact('license_holder'));
    }

    public function showTab($id)
{
    $license_holder = LicenseHolder::with('educations')->findOrFail($id);
    return view('license_holders.tab.educations', compact('license_holder'));
}

    public function showWorks($id)
{
    $license_holder = LicenseHolder::with('workers')->findOrFail($id);
    return view('license_holders.tab.workers', compact('license_holder'));
}

    public function showFams($id)
{
    $license_holder = LicenseHolder::with('families')->findOrFail($id);
    return view('license_holders.tab.families', compact('license_holder'));
}

    public function edit(LicenseHolder $license_holder)
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
    public function update(Request $request, LicenseHolder $license_holder)
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
    public function destroy(LicenseHolder $license_holder)
    {
         if ($license_holder) {
            $license_holder->delete();
            return response()->json(['status' => 'success', 'message' => 'License deleted successfully']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Unable to delete']);
    }
}
