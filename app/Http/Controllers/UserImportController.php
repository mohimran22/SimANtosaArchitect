<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\License;
use App\Models\LicenseHolderFamilyMembers;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Helpers\FormatHelper as F;

class UserImportController extends Controller
{
    public function showForm()
    {
        return view('users.import');
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    $spreadsheet = IOFactory::load($request->file('file'));
    $sheet = $spreadsheet->getSheetByName('Data Keluarga');
    $totalInserted = 0;

    if ($sheet) {
            $rows = $sheet->toArray();

            foreach ($rows as $i => $row) {
                if ($i === 0) continue;

                 // Ambil license
                $license = License::whereRaw('TRIM(license_id) = ?', [trim($row[1])])->first();
                if (!$license) {
                    logger('License not found: '.$row[1]);
                    continue;
                }

                // Ambil user (owner) pertama dari pivot license_user
                $user = $license->owners()->first();
                if (!$user) {
                    logger('User owner not found for license: '.$license->id);
                    continue;
                }

                // Ambil license_holder dari user
                $holder = $user->licenseHolder;
                if (!$holder) {
                    logger('License holder not found for user: '.$user->id);
                    continue;
                }

                // === SUAMI/ISTRI ===
                if (strtolower(trim($row[4])) === 'married' && !empty($row[5])) {
                    LicenseHolderFamilyMembers::create([
                        'id' => Str::uuid(),
                        'license_holder_id' => $holder->id,
                        'name' => trim($row[5]),
                        'relationship' => 2, // Istri, 4 kalau Suami
                        'gender' => 2, // Istri = perempuan
                        'birth_date' => F::parseIndoDate($row[25]) ?? '1980-01-01', // Tidak ada di header
                        'job' => trim($row[8] ?? '') ?: null,
                        'job_phone' => trim($row[9] ?? '') ?: null,
                        'last_education_level' => null,
                        'institution_name' => trim($row[22] ?? '') ?: null,
                    ]);
                    $totalInserted++;
                }

                // === ANAK 1 ===
                if (!empty($row[10])) {
                    LicenseHolderFamilyMembers::create([
                        'id' => Str::uuid(),
                        'license_holder_id' => $holder->id,
                        'name' => trim($row[10]),
                        'relationship' => 3, // Anak
                        'gender' => strtolower(trim($row[12])) == 'laki-laki' ? 1 : 2,
                        'birth_date' => F::parseIndoDate($row[11]) ?? '1990-01-01',
                        'job' => null,
                        'job_phone' => null,
                        'last_education_level' => null,
                        'institution_name' => trim($row[13] ?? '') ?: null,
                    ]);
                    $totalInserted++;
                }

                // === ANAK 2 ===
                if (!empty($row[14])) {
                    LicenseHolderFamilyMembers::create([
                        'id' => Str::uuid(),
                        'license_holder_id' => $holder->id,
                        'name' => trim($row[14]),
                        'relationship' => 3,
                        'gender' => strtolower(trim($row[16])) == 'laki-laki' ? 1 : 2,
                        'birth_date' => F::parseIndoDate($row[15]) ?? '1990-01-01',
                        'job' => null,
                        'job_phone' => null,
                        'last_education_level' => null,
                        'institution_name' => trim($row[17] ?? '') ?: null,
                    ]);
                    $totalInserted++;
                }

                // === ANAK 3 ===
                if (!empty($row[18])) {
                    LicenseHolderFamilyMembers::create([
                        'id' => Str::uuid(),
                        'license_holder_id' => $holder->id,
                        'name' => trim($row[18]),
                        'relationship' => 3,
                        'gender' => strtolower(trim($row[20])) == 'laki-laki' ? 1 : 2,
                        'birth_date' => F::parseIndoDate($row[19]) ?? '1990-01-01',
                        'job' => null,
                        'job_phone' => null,
                        'last_education_level' => null,
                        'institution_name' => trim($row[21] ?? '') ?: null,
                    ]);
                    $totalInserted++;
                }
            }




        return back()->with('success', "Import Riwayat Pekerjaan selesai. Total: {$totalInserted}");
    }


}

}


// if ($sheet) {
    //     $rows = $sheet->toArray();

    //     foreach ($rows as $i => $row) {
    //         if ($i === 0) continue; // header

    //          $nik = trim($row[1]);
    //             $employee = Employee::where('nik', $nik)->first();
    //             if (!$employee) {
    //                 logger('SKIP: Employee not found for NIK: ' . $nik);
    //                 continue;
    //             }

    //         // Tanggal mulai
    //         $startYear = null;
    //         if (!empty($row[7])) {
    //             if (is_numeric($row[7])) {
    //                 $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]);
    //                 $startYear = $date->format('Y');
    //             } else {
    //                 try {
    //                     $startYear = \Carbon\Carbon::parse($row[7])->format('Y');
    //                 } catch (\Exception $e) {
    //                     logger('Parse failed: ' . $row[7]);
    //                 }
    //             }
    //         }

    //         // Tanggal lulus ➜ tahun
    //         $endYear = null;
    //         if (!empty($row[8])) {
    //             if (is_numeric($row[8])) {
    //                 $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8]);
    //                 $endYear = $date->format('Y');
    //             } else {
    //                 try {
    //                     $endYear = \Carbon\Carbon::parse($row[8])->format('Y');
    //                 } catch (\Exception $e) {
    //                     logger('Parse failed: ' . $row[8]);
    //                 }
    //             }
    //         }

    //         // is_graduated TRUE kalau ada tahun lulus
    //         $isGraduated = $endYear ? true : false;


    //         EmployeeEducation::create([
    //             'id' => Str::uuid(),
    //             'employee_id' => $employee->id,
    //             'education_level' => trim($row[5] ?? ''),
    //             'institution_name' =>  F::parseTextOrDefault($row[6], 'Tidak Diketahui'),
    //             'start_year' => $startYear,
    //             'end_year' => $endYear,
    //             'is_graduated' => $isGraduated,
    //         ]);

    //         $totalInserted++;
    //     }
    // }


        // foreach ($rows as $i => $row) {
        //     if ($i === 0) continue; // skip header

        //     $nik = trim($row[1] ?? '');
        //     $employee = Employee::where('nik', $nik)->first();
        //     if (!$employee) {
        //         logger('SKIP: Employee not found for NIK: ' . $nik);
        //         continue;
        //     }

        //     // ORANG TUA
        //     if (!empty($row[5])) {
        //         EmployeeFamilyMember::create([
        //             'id' => Str::uuid(),
        //             'employee_id' => $employee->id,
        //             'name' => trim($row[5]),
        //             'relationship' => 4, // Ibu misalnya
        //             'gender' => 2, // Perempuan
        //             'birth_date' => F::parseIndoDate($row[6]) ?? '1960-01-01',
        //             'job' => trim($row[7] ?? '') ?: null,
        //             'company_name' => trim($row[8] ?? '') ?: null,
        //         ]);
        //         $totalInserted++;
        //     }

        //     // SUAMI / ISTRI
        //     if (!empty($row[9])) {
        //         EmployeeFamilyMember::create([
        //             'id' => Str::uuid(),
        //             'employee_id' => $employee->id,
        //             'name' => trim($row[9]),
        //             'relationship' => 2, // Istri default
        //             'gender' => 2,
        //             'birth_date' => F::parseIndoDate($row[10]) ?? '1970-01-01',
        //             'job' => trim($row[11] ?? '') ?: null,
        //             'company_name' => trim($row[12] ?? '') ?: null,
        //         ]);
        //         $totalInserted++;
        //     }

        //     // ANAK 1
        //     if (!empty($row[13])) {
        //         EmployeeFamilyMember::create([
        //             'id' => Str::uuid(),
        //             'employee_id' => $employee->id,
        //             'name' => trim($row[13]),
        //             'relationship' => 3, // Anak
        //             'gender' => 1, // Default laki-laki, kalau ga tau
        //             'birth_date' => F::parseIndoDate($row[14]) ?? '1990-01-01',
        //         ]);
        //         $totalInserted++;
        //     }

        //     // ANAK 2
        //     if (!empty($row[15])) {
        //         EmployeeFamilyMember::create([
        //             'id' => Str::uuid(),
        //             'employee_id' => $employee->id,
        //             'name' => trim($row[15]),
        //             'relationship' => 3,
        //             'gender' => 1,
        //             'birth_date' => F::parseIndoDate($row[16]) ?? '1990-01-01',
        //         ]);
        //         $totalInserted++;
        //     }

        //     // ANAK 3
        //     if (!empty($row[17])) {
        //         EmployeeFamilyMember::create([
        //             'id' => Str::uuid(),
        //             'employee_id' => $employee->id,
        //             'name' => trim($row[17]),
        //             'relationship' => 3,
        //             'gender' => 1,
        //             'birth_date' => F::parseIndoDate($row[18]) ?? '1990-01-01',
        //         ]);
        //         $totalInserted++;
        //     }
        // }

            