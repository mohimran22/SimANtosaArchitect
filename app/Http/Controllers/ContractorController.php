<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Models\User;
use App\Models\Religion;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = auth()->user();

        $query = Contractor::with(['user.roles']);

        if ($auth->can('lihat data kontraktor') && !$auth->can('lihat daftar kontraktor')) {
            $query->where('user_id', $auth->id);
        }

        if ($request->ajax()) {
            $contractors = $query->get();

        return DataTables::of($contractors)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    return $row->user->fullname ?? '-';
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email ?? '-';
                })
                // ->addColumn('membership', function ($row) {
                //     $level = ucfirst($row->readableMembership);
                //     $color = match ($row->readableMembership) {
                //         '1' => 'secondary',
                //         '2' => 'warning',
                //         '3' => 'info',
                //         default => 'secondary',
                //     };
                //     return '<span class="badge bg-' . $color . '">' . $level . '</span>';
                // })
                ->editColumn('fullname', function ($row) {
                    $url = route('contractors.show', $row->id);
                    $name = Str::title($row->user->fullname ?? '-');
                    return '<a href="'.$url.'">'.e($name).'</a>';
                })
                
                ->addColumn('action', function ($contractor) {
                    $buttons = '';
                    if (auth()->user()->can('ubah data kontraktor')) {
                        $buttons .= '<a href="' . route('contractors.edit', $contractor->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Ubah">
                                        <i class="ti ti-edit"></i>
                                    </a>';
                    }
                    if (auth()->user()->can('lihat data kontraktor')) {
                        $buttons .= '<a href="' . route('contractors.show', $contractor->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Lihat">
                                        <i class="ti ti-eye"></i>
                                    </a>';

                    }
                    if (auth()->user()->can('hapus data kontraktor')) {
                        $buttons .= '<button data-id="' . $contractor->id . '" class="btn btn-icon btn-sm btn-dark delete-kontraktor" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }
                    return $buttons;
                })
                ->rawColumns(['fullname', 'action', 'membership'])
                ->make(true);
        }

        return view('contractors.index');
    }
    
        public function create()
    {
        $user = auth()->user();
        $religions = Religion::all();
        $provinces = Province::all();
        $roles = Role::all();
        return view('contractors.create', compact('user', 'roles', 'religions', 'provinces'));
    }

    public function store(Request $request)
{
    $normalizePhone = function ($value) {
        $n = preg_replace('/\D/', '', $value ?? '');
        if ($n !== '' && str_starts_with($n, '0')) {
            $n = '62' . ltrim($n, '0');
        }
        return $n !== '' ? $n : null;
    };

    $request->merge([
        'phone'            => $normalizePhone($request->phone),
        'contractor_phone' => $normalizePhone($request->contractor_phone),
    ]);

    $validated = $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'fullname' => 'required|string|max:255',
        'nickname' => 'nullable|string|max:100',
        'gender' => 'nullable|in:1,2',
        'email' => 'required|email|unique:users,email',
        'birth_place' => 'nullable|string|max:100',
        'birth_date' => 'nullable|date_format:Y-m-d',
        'identity_number' => 'nullable|string|max:16',
        'religion_id' => 'nullable|exists:religions,id',
        'npwp' => 'nullable|string|max:30',
        'phone' => ['required', 'regex:/^[1-9][0-9]{7,14}$/'],
        'address' => 'nullable|string|max:255',
        'user_province_id' => 'nullable|exists:provinces,id',
        'user_city_id' => 'nullable|exists:cities,id',
        'user_district_id' => 'nullable|exists:districts,id',
        'user_sub_district_id' => 'nullable|exists:sub_districts,id',
        'user_postal_code_id' => 'nullable|exists:postal_codes,id',
        'bank_id' => 'nullable|uuid|exists:banks,id',
        'account_number' => 'nullable|string|max:50',
        'account_holder' => 'nullable|string|max:50',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        // --- data contractor ---
        'contractor_id' => 'required|unique:contractors,contractor_id',
        'contractor_name' => 'nullable|string|max:255',
        'contractor_phone' => ['nullable', 'regex:/^[1-9][0-9]{7,14}$/'],
        'contractor_address' => 'nullable|string|max:255',
        'province_id' => 'nullable|exists:provinces,id',
        'city_id' => 'nullable|exists:cities,id',
        'district_id' => 'nullable|exists:districts,id',
        'sub_district_id' => 'nullable|exists:sub_districts,id',
        'postal_code_id' => 'nullable|exists:postal_codes,id',
        'role' => 'required|array',
        'role.*' => 'string|exists:roles,name',
    ]);

    if ($request->hasFile('photo')) {
        $filename = Str::uuid().'.'.$request->file('photo')->getClientOriginalExtension();

        $path = $request->file('photo')->storeAs(
            'photos',
            $filename,
            'public'
        );

        // simpan full relative path
        $validated['photo'] = $path;   // → photos/uuid.jpg
    }

    // Jika alamat pengiriman sama dengan domisili user
        if ($request->has('same_address')) {
            $validated['province_id']        = $validated['user_province_id'] ?? null;
            $validated['city_id']            = $validated['user_city_id'] ?? null;
            $validated['district_id']        = $validated['user_district_id'] ?? null;
            $validated['sub_district_id']    = $validated['user_sub_district_id'] ?? null;
            $validated['postal_code_id']     = $validated['user_postal_code_id'] ?? null;
            $validated['contractor_address'] = $validated['address'] ?? null;
            $validated['contractor_name']    = $validated['fullname'];
            $validated['contractor_phone']   = $validated['phone'];
        }

    DB::transaction(function () use ($validated, $request) {

        // 🔹 Cek user atau buat baru
        if (!empty($validated['user_id'])) {
            $user = User::find($validated['user_id']);
        } else {
            $password = '12345678';
            $user = User::create([
                'id' => Str::uuid(),
                'fullname' => $validated['fullname'],
                'nickname' => $validated['nickname'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'identity_number' => $validated['identity_number'],
                'npwp' => $validated['npwp'],
                'address' => $validated['address'],
                'religion_id' => $validated['religion_id'],
                'province_id' => $validated['user_province_id'],
                'city_id' => $validated['user_city_id'],
                'district_id' => $validated['user_district_id'],
                'sub_district_id' => $validated['user_sub_district_id'],
                'postal_code_id' => $validated['user_postal_code_id'],
                'email' => $validated['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'photo' => $validated['photo'] ?? null,
                'bank_id' => $validated['bank_id'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'account_holder' => $validated['account_holder'] ?? null,
            ]);

            session()->flash('new_user_password', $password);
        }

        // 🔹 Assign role tanpa hapus role lama
        if (!empty($validated['role'])) {
            foreach ($validated['role'] as $r) {
                if (!$user->hasRole($r)) {
                    $user->assignRole($r);
                }
            }
        }

        Contractor::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'contractor_id' => $validated['contractor_id'],
            'contractor_name' => $validated['contractor_name'],
            'contractor_phone' => $validated['contractor_phone'],
            'contractor_address' => $validated['contractor_address'],
            'province_id' => $validated['province_id'],
            'city_id' => $validated['city_id'],
            'district_id' => $validated['district_id'],
            'sub_district_id' => $validated['sub_district_id'],
            'postal_code_id' => $validated['postal_code_id'],
        ]);
    });

    return redirect()
        ->route('contractors.index')
        ->with('success', 'Data contractor berhasil ditambahkan.' .
            (session('new_user_password') ? ' Akun user baru dibuat. Password: ' . session('new_user_password') : '')
        );
}


public function generateContractorIdAjax()
{
    $lastNumber = Contractor::selectRaw("MAX(CAST(SUBSTRING(contractor_id, 3) AS INTEGER)) as max_contractor_id")->value('max_contractor_id');
    $newNumber = ($lastNumber ?? 0) + 1;

    $newContractorId = 'K-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    return response()->json([
        'contractor_id' => $newContractorId
    ]);
}
         public function show(Contractor $contractor)
    {
        $contractor->load('user');
        return view('contractors.show', [
            'user' => $contractor->user,
            'contractor' => $contractor
        ]);
    }
  
public function edit($id)
{
    $contractor = Contractor::with(['user.roles', 'user.bank'])->findOrFail($id);
    $user = $contractor->user;
    $religions = Religion::all();
    $provinces = Province::all();
    $roles = Role::all();
    $selectedRoles = $user->roles->pluck('name')->toArray();

    // Wilayah domisili pengguna
    $cities       = City::where('province_id', $user->province_id)->get();
    $districts    = District::where('city_id', $user->city_id)->get();
    $subDistricts = SubDistrict::where('district_id', $user->district_id)->get();
    $postalCodes  = PostalCode::where('sub_district_id', $user->sub_district_id)->get();

    // Wilayah usaha
    $ctrCities       = City::where('province_id', $contractor->province_id)->get();
    $ctrDistricts    = District::where('city_id', $contractor->city_id)->get();
    $ctrSubDistricts = SubDistrict::where('district_id', $contractor->district_id)->get();
    $ctrPostalCodes  = PostalCode::where('sub_district_id', $contractor->sub_district_id)->get();

    return view('contractors.edit', compact(
        'user', 'roles', 'selectedRoles', 'contractor', 'religions', 'provinces',
        'cities', 'districts', 'subDistricts', 'postalCodes',
        'ctrCities', 'ctrDistricts', 'ctrSubDistricts', 'ctrPostalCodes'
    ));
}

public function update(Request $request, Contractor $contractor)
{
    // Normalisasi telepon: buang non-angka, awalan 0 → 62
    $normalizePhone = function ($value) {
        $n = preg_replace('/\D/', '', $value ?? '');
        if ($n !== '' && str_starts_with($n, '0')) {
            $n = '62' . ltrim($n, '0');
        }
        return $n !== '' ? $n : null;
    };

    $request->merge([
        'phone'            => $normalizePhone($request->phone),
        'contractor_phone' => $normalizePhone($request->contractor_phone),
    ]);

    // Data usaha sama dengan pengguna: server mengisi sebelum validasi
    if ($request->has('same_address')) {
        $request->merge([
            'contractor_name'    => $request->fullname,
            'contractor_phone'   => $request->phone,
            'contractor_address' => $request->address,
            'province_id'        => $request->user_province_id,
            'city_id'            => $request->user_city_id,
            'district_id'        => $request->user_district_id,
            'sub_district_id'    => $request->user_sub_district_id,
            'postal_code_id'     => $request->user_postal_code_id,
        ]);
    }

    $validated = $request->validate([
        // --- data user ---
        'fullname' => 'required|string|max:255',
        'nickname' => 'nullable|string|max:100',
        'gender' => 'nullable|in:1,2',
        'email' => 'required|email|unique:users,email,' . $contractor->user_id,
        'birth_place' => 'nullable|string|max:100',
        'birth_date' => 'nullable|date_format:Y-m-d',
        'identity_number' => 'nullable|string|max:16',
        'religion_id' => 'nullable|exists:religions,id',
        'npwp' => 'nullable|string|max:30',
        'phone' => ['required', 'regex:/^[1-9][0-9]{7,14}$/'],
        'address' => 'nullable|string|max:255',
        'user_province_id' => 'nullable|exists:provinces,id',
        'user_city_id' => 'nullable|exists:cities,id',
        'user_district_id' => 'nullable|exists:districts,id',
        'user_sub_district_id' => 'nullable|exists:sub_districts,id',
        'user_postal_code_id' => 'nullable|exists:postal_codes,id',
        'bank_id' => 'nullable|uuid|exists:banks,id',
        'account_number' => 'nullable|string|max:50',
        'account_holder' => 'nullable|string|max:50',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        // --- data contractor ---
        'contractor_id' => 'required|string|max:50|unique:contractors,contractor_id,' . $contractor->id,
        'contractor_name' => 'nullable|string|max:255',
        'contractor_phone' => ['nullable', 'regex:/^[1-9][0-9]{7,14}$/'],
        'contractor_address' => 'nullable|string|max:255',
        'province_id' => 'nullable|exists:provinces,id',
        'city_id' => 'nullable|exists:cities,id',
        'district_id' => 'nullable|exists:districts,id',
        'sub_district_id' => 'nullable|exists:sub_districts,id',
        'postal_code_id' => 'nullable|exists:postal_codes,id',
        'role' => 'nullable|array',
        'role.*' => ['string', Rule::exists('roles', 'name')->where('role_group', 'Eksternal')],
    ]);

    $newPhotoPath = null;   // foto baru yang baru diunggah
    $oldPhotoPath = null;   // foto lama, dihapus setelah transaksi berhasil

    try {
        DB::transaction(function () use ($validated, $contractor, $request, &$newPhotoPath, &$oldPhotoPath) {
            $user = $contractor->user;

            if ($request->hasFile('photo')) {
                $newPhotoPath = $request->file('photo')->storeAs(
                    'photos',
                    Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension(),
                    'public'
                );

                // Data lama bisa berupa nama file saja (tanpa folder)
                $old = $user->photo;
                $oldPhotoPath = $old && !str_contains($old, '/') ? 'photos/' . $old : $old;

                $validated['photo'] = $newPhotoPath;   // → photos/uuid.jpg
            }

            $user->update([
                'fullname' => $validated['fullname'],
                'nickname' => $validated['nickname'] ?? null,
                'birth_place' => $validated['birth_place'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'identity_number' => $validated['identity_number'] ?? null,
                'religion_id' => $validated['religion_id'] ?? null,
                'npwp' => $validated['npwp'] ?? null,
                'address' => $validated['address'] ?? null,
                'province_id' => $validated['user_province_id'] ?? null,
                'city_id' => $validated['user_city_id'] ?? null,
                'district_id' => $validated['user_district_id'] ?? null,
                'sub_district_id' => $validated['user_sub_district_id'] ?? null,
                'postal_code_id' => $validated['user_postal_code_id'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'] ?? null,
                'bank_id' => $validated['bank_id'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'account_holder' => $validated['account_holder'] ?? null,
                'photo' => $validated['photo'] ?? $user->photo,
            ]);

            // Role: multi-role tetap aman
            if (!empty($validated['role'])) {
                foreach ($validated['role'] as $r) {
                    if (!$user->hasRole($r)) {
                        $user->assignRole($r);
                    }
                }
            } elseif (!$user->hasRole('Mitra Kontraktor')) {
                $user->assignRole('Mitra Kontraktor');
            }

            $contractor->update([
                'contractor_id' => $validated['contractor_id'],
                'contractor_name' => $validated['contractor_name'] ?? null,
                'contractor_phone' => $validated['contractor_phone'] ?? null,
                'contractor_address' => $validated['contractor_address'] ?? null,
                'province_id' => $validated['province_id'] ?? null,
                'city_id' => $validated['city_id'] ?? null,
                'district_id' => $validated['district_id'] ?? null,
                'sub_district_id' => $validated['sub_district_id'] ?? null,
                'postal_code_id' => $validated['postal_code_id'] ?? null,
            ]);
        });
    } catch (\Throwable $e) {
        // Transaksi gagal: buang foto baru supaya tidak jadi file yatim
        if ($newPhotoPath && Storage::disk('public')->exists($newPhotoPath)) {
            Storage::disk('public')->delete($newPhotoPath);
        }
        throw $e;
    }

    // Transaksi berhasil: baru hapus foto lama
    if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
        Storage::disk('public')->delete($oldPhotoPath);
    }

    return redirect()
        ->route('contractors.show', $contractor->id)
        ->with('success', 'Data contractor berhasil diperbarui.');
}

       public function destroy(Contractor $contractor): JsonResponse
{
    DB::transaction(function () use ($contractor) {
        $user = $contractor->user;

        // 🔹 Hapus foto contractor dari storage kalau ada
        if ($contractor->photo && Storage::disk('public')->exists('photos/' . $contractor->photo)) {
            Storage::disk('public')->delete('photos/' . $contractor->photo);
        }

        // 🔹 Hapus record contractor
        $contractor->delete();

        // 🔹 Cek user yang terhubung
        if ($user) {
            $roles = $user->roles->pluck('name')->toArray();

            // Kalau hanya punya role "contractor"
            if (count($roles) === 1 && in_array('Mitra Kontraktor', $roles)) {

                // Hapus juga foto user kalau ada
                if ($user->photo && Storage::disk('public')->exists('photos/' . $user->photo)) {
                    Storage::disk('public')->delete('photos/' . $user->photo);
                }

                // Hapus akun user
                $user->delete();

            } else {
                // Kalau user masih punya role lain, hapus hanya role contractor-nya
                $user->removeRole('Mitra Kontraktor');
            }
        }
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Data contractor berhasil dihapus.'
    ]);
}
}
