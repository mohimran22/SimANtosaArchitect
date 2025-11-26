<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use App\Models\Religion;
use App\Models\Role;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CustomersController extends Controller
{
    
    private function readableGender($value)
    {
        return match ((int) $value) {
            1 => 'Laki - Laki',
            2 => 'Perempuan',
            default => '-',
        };
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
        $Customers = Customer::query()
        ->leftJoin('licenses', 'students.license_id', '=', 'licenses.id')
        ->leftJoin('provinces', 'students.province_id', '=', 'provinces.id')
        ->leftJoin('cities', 'students.city_id', '=', 'cities.id')
        ->leftJoin('districts', 'students.district_id', '=', 'districts.id')
        ->leftJoin('sub_districts', 'students.sub_district_id', '=', 'sub_districts.id')
        ->leftJoin('postal_codes', 'students.postal_code_id', '=', 'postal_codes.id')
        ->leftJoin('religions', 'students.religion_id', '=', 'religions.id')
        ->select(
            'students.*',
            'licenses.license_type as license_type',
            'licenses.name as license_name',
            'provinces.name as province_name',
            'cities.name as city_name',
            'districts.name as district_name',
            'sub_districts.name as sub_district_name',
            'postal_codes.postal_code as postal_code',
            'religions.name as religion_name'
        );

         if (Auth::user()->hasRole('Siswa')) {
            $students->where('students.user_id', Auth::id());
        }


        return DataTables::of($students)
        ->addColumn('license_type', fn ($s) => $s->license_type ?? '-')
        ->addColumn('license_name', fn ($s) => $s->license_name ?? '-')
        ->addColumn('religion_name', fn ($s) => $s->religion_name ?? '-')
        ->addColumn('provinsi', fn ($s) => $s->province_name ?? '-')
        ->addColumn('kabupaten_kota', fn ($s) => $s->city_name ?? '-')
        ->addColumn('kecamatan', fn ($s) => $s->district_name ?? '-')
        ->addColumn('kelurahan', fn ($s) => $s->sub_district_name ?? '-')
        ->addColumn('kode_pos', fn ($s) => $s->postal_code ?? '-')
        ->addColumn('gender', fn($row) => $this->readableGender($row->gender))
        ->addColumn('birth_date', fn($row) => $row->birth_date ? Carbon::parse($row->birth_date)->format('d/m/Y') : '-')
        ->addColumn('action', function ($s) {
            return '
                <a href="'.route('students.edit', $s->id).'" class="btn btn-sm btn-primary">Edit</a>
                <button data-id="'.$s->id.'" class="btn btn-danger btn-sm delete-student">Delete</button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);


    }

        return view('students.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $religions = Religion::all();
        $licenses = License::all(); 
        $provinces = Province::all();
        
        return view('students.create', compact('religions', 'licenses', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'nis' => 'required|string|unique:students',
        'license_id' => 'required|exists:licenses,id',
        'fullname' => 'required|string',
        'nickname' => 'nullable|string',
        'gender' => 'required|in:1,2',
        'birth_place' => 'nullable|string',
        'birth_date' => 'required|date',
        'age' => 'nullable|integer|min:0',
        'religion_id' => 'required|exists:religions,id',
        'email' => 'nullable|email|unique:students,email',
        'address' => 'nullable|string',
        'province_id' => 'nullable|exists:provinces,id',
        'city_id' => 'nullable|exists:cities,id',
        'district_id' => 'nullable|exists:districts,id',
        'sub_district_id' => 'nullable|exists:sub_districts,id',
        'postal_code_id' => 'nullable|exists:postal_codes,id',
        'father_name' => 'nullable|string',
        'father_phone' => 'nullable|string',
        'mother_name' => 'nullable|string',
        'mother_phone' => 'nullable|string',
        'student_phone' => 'nullable|string',
        'previous_school' => 'nullable|string',
        'grade' => 'nullable|string',
        'status' => 'nullable|string',
        'photo' => ['nullable|image|mimes:jpeg,png,jpg,gif|max:2048'],
    ]);

     if ($request->birth_date) {
        $validated['age'] = Carbon::parse($request->birth_date)->age;
    }

    if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $filename, 'public');
            $validated['photo'] = $filename;
        }

        // Buat akun user
    $user = User::create([
        'name' => $validated['fullname'],
        'email' => $validated['email'] ?: Str::slug($validated['fullname']).'@example.com',
        'password' => Hash::make('password123'), // default password
    ]);

    // Assign role Student (jika pakai Spatie)
    $user->syncRoles('Student');

    // Buat student & hubungkan ke user
    $student = Student::create(array_merge(
        $validated,
        ['user_id' => $user->id]
    ));

    return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan');
}

    /**
     * Display the specified resource.
     */
    public function show(Students $students)
    {
        //
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
