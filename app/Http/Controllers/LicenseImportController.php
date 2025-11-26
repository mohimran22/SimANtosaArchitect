<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\License;
use App\Models\Province;
use App\Models\City;
use App\Models\User;
use App\Models\District;
use App\Models\Religion;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Helpers\FormatHelper as F;

class LicenseImportController extends Controller
{
    public function showForm()
    {
        return view('licenses.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $spreadsheet = IOFactory::load($request->file('file'));
        $sheet = $spreadsheet->getSheetByName('DATA SISWA');

        $totalInserted = 0;

        if ($sheet) {
            $rows = $sheet->toArray();

            DB::beginTransaction();

            try {
                foreach ($rows as $index => $row) {
                    if ($index === 0) continue; // skip header

                    $license = License::whereRaw('TRIM(name) = ?', [trim($row[3])])->first();
                    if (!$license) {
                        logger()->warning("SKIP: License not found: {$row[3]}");
                        continue;
                    }

                    $province = Province::whereRaw('TRIM(name) = ?', [trim($row[12])])->first();
                    $city = City::whereRaw('TRIM(name) = ?', [trim($row[13])])->first();
                    $district = District::whereRaw('TRIM(name) = ?', [trim($row[14])])->first();
                    $religion = Religion::whereRaw('TRIM(name) = ?', [trim($row[9])])->first();

                    $gender = strtolower(trim($row[5])) === 'perempuan' ? 2 : 1;

                    // Tanggal lahir
                    $birthDate = null;
                    if (!empty($row[7])) {
                        if (is_numeric($row[7])) {
                            $birthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7])->format('Y-m-d');
                        } else {
                            $birthDate = F::parseIndoDate($row[7]);
                        }
                    } else {
                        $birthDate = '2005-01-01'; // fallback
                    }

                    $email = trim($row[10]) ?: Str::slug(trim($row[4])) . '@example.com';

                    $user = User::firstOrNew(['email' => $email]);
            if (!$user->exists) {
                $user->name = trim($row[4]);
                $user->password = Hash::make('password123');
                $user->save();
            }

            // Optional: assign role
            if ($user->exists && !$user->hasRole('Siswa')) {
                $user->assignRole('Siswa');
            }

            logger('User ID: ' . $user->id);

            // Insert or update student
            $student = Student::updateOrCreate(
                ['nis' => trim($row[1])],
                [
                    'id' => Str::uuid(), // dipakai hanya jika baru
                    'license_id' => $license->id,
                    'user_id' => $user->id,
                    'fullname' => trim($row[4]),
                    'nickname' => trim($row[4]),
                    'gender' => $gender,
                    'birth_place' => F::parseTextOrDefault($row[6]),
                    'birth_date' => $birthDate ?? '2005-01-01',
                    'age' => !empty($row[8]) ? (int) $row[8] : null,
                    'religion_id' => $religion?->id,
                    'address' => trim($row[11]),
                    'email' => $email,
                    'province_id' => $province?->id,
                    'city_id' => $city?->id,
                    'district_id' => $district?->id,
                    'sub_district_id' => null,
                    'postal_code_id' => null,
                    'father_name' => F::parseTextOrDefault($row[15]),
                    'father_phone' => F::parseNumberOrDefault($row[16]),
                    'mother_name' => F::parseTextOrDefault($row[17]),
                    'mother_phone' => F::parseNumberOrDefault($row[18]),
                    'student_phone' => trim($row[19]) ?: null,
                    'previous_school' => trim($row[20]),
                    'grade' => trim($row[21]),
                    'status' => trim($row[22]),
                ]
            );

            logger($student->toArray());

                    $totalInserted++;
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                logger()->error('IMPORT ERROR: ' . $e->getMessage());
                return back()->with('error', 'Gagal import: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Import selesai. Total siswa diimport: {$totalInserted}");
    }


    
}

        //  if  ($sheet1) {
        //     $rows = $sheet1->toArray();

        //     foreach ($rows as $index => $row) {
        //         if ($index === 0) continue;

        //         $province = Province::whereRaw("TRIM(name) = ?", [F::cleanFk($row[6])])->first();
        //         $city = City::whereRaw("TRIM(name) = ?", [F::cleanFk($row[7])])->first();
        //         $district = District::whereRaw("TRIM(name) = ?", [F::cleanFk($row[8])])->first();
        //         $sub_district = SubDistrict::whereRaw("TRIM(name) = ?", [F::cleanFk($row[9])])->first();
        //         $postal_code = PostalCode::whereRaw("TRIM(postal_code) = ?", [F::cleanFk($row[10])])->first();


        //         logger([
        //             'Province' => $province?->name,
        //             'City' => $city?->name,
        //             'District' => $district?->name,
        //             'SubDistrict' => $sub_district?->name,
        //             'PostalCode' => $postal_code?->postal_code
        //         ]);

        //         // Parse join_date
        //         $joinDate = null;
        //         if (isset($row[12]) && $row[12] !== '') {
        //             if (is_numeric($row[12])) {
        //                 $joinDate = ExcelDate::excelToDateTimeObject($row[12])->format('Y-m-d');
        //             } else {
        //                 try {
        //                     $joinDate = Carbon::createFromFormat('d/m/Y', $row[12])->format('Y-m-d');
        //                 } catch (\Exception $e) {
        //                     try {
        //                         $joinDate = Carbon::parse($row[12])->format('Y-m-d');
        //                     } catch (\Exception $e2) {
        //                         logger('Join Date Parse FAILED: ' . $row[12]);
        //                     }
        //                 }
        //             }
        //         }

        //         // Parse expired_date
        //         $expiredDate = null;
        //         if (isset($row[13]) && $row[13] !== '') {
        //             if (is_numeric($row[13])) {
        //                 $expiredDate = ExcelDate::excelToDateTimeObject($row[13])->format('Y-m-d');
        //             } else {
        //                 try {
        //                     $expiredDate = Carbon::createFromFormat('d/m/Y', $row[13])->format('Y-m-d');
        //                 } catch (\Exception $e) {
        //                     try {
        //                         $expiredDate = Carbon::parse($row[13])->format('Y-m-d');
        //                     } catch (\Exception $e2) {
        //                         logger('Expired Date Parse FAILED: ' . $row[13]);
        //                     }
        //                 }
        //             }
        //         }

        //         try {
        //             License::create([
        //                 'id' => Str::uuid(),
        //                 'license_id' => $row[1] ?? null,
        //                 'license_type' => $row[2] ?? null,
        //                 'name' => $row[3] ?? null,
        //                 'email' => $row[4] ?? null,
        //                 'address' => $row[5] ?? null,
        //                 'province_id' => $province?->id,
        //                 'city_id' => $city?->id,
        //                 'district_id' => $district?->id,
        //                 'sub_district_id' => $sub_district?->id,
        //                 'postal_codes' => $postal_code?->id,
        //                 'phone' => $row[11] ?? null,
        //                 'join_date' => $joinDate,
        //                 'expired_date' => $expiredDate,
        //                 'contract_agreement_number' => $row[14] ?? null,
        //                 'status' => $row[15] ?? null,
        //             ]);
        //             $totalInserted++;
        //         } catch (\Exception $e) {
        //             logger('INSERT ERROR SHEET1: ' . $e->getMessage());
        //         }
        //     }
        // }

    

        // $sheet2 = $spreadsheet->getSheetByName('Akun Sosial Media');
        // if ($sheet2) {
        //         $rows = $sheet2->toArray();

        //         foreach ($rows as $index => $row) {
        //             if ($index === 0) continue;

        //             $license = License::where('license_id', trim($row[1] ?? ''))->first();
        //             if ($license) {
        //                 $license->update([
        //                     'instagram' => $row[5] ?? null,
        //                     'facebook_page' => $row[7] ?? null,
        //                     'tiktok' => $row[9] ?? null,
        //                     'youtube' => $row[11] ?? null,
        //                     'landig_page_student_registration' => $row[13] ?? null,
        //                     'google_maps' => $row[17] ?? null,
        //                 ]);
        //                 $totalInserted++;
        //             }
        //         }
        //     }
 
