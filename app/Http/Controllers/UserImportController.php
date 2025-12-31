<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class UpahImportController extends Controller
{

public function importUpah(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xls,xlsx'
    ]);

    DB::beginTransaction();

    try {
        $spreadsheet = IOFactory::load($request->file('file')->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $currentCategory = null;

        foreach ($rows as $index => $row) {
            if ($index < 6) continue; // skip header atas

            $colB = trim($row[1] ?? '');
            $colC = trim($row[2] ?? '');
            $colD = trim($row[3] ?? '');
            $colE = trim($row[4] ?? '');
            $colF = trim($row[5] ?? '');

            // =========================
            // CATEGORY (PP, PT, dll)
            // =========================
            if ($colB && !$colF) {
                $currentCategory = \App\Models\JobCategory::updateOrCreate(
                    ['code' => $colB],
                    ['name' => $colD, 'is_active' => true]
                );
                continue;
            }

            // =========================
            // JOB PRICE
            // =========================
            if ($currentCategory && $colF && $colC) {
                \App\Models\JobPrice::updateOrCreate(
                    [
                        'category_id' => $currentCategory->id,
                        'code' => $colC
                    ],
                    [
                        'name'  => $colD,
                        'unit'  => $colE,
                        'price' => (float) str_replace('.', '', $colF)
                    ]
                );
            }
        }

        DB::commit();
        return back()->with('success', 'Import UPH berhasil');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
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

            