<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\BuildPlans;
use App\Models\BuildPlanWeek;
use App\Models\BuildProcessItem;
use DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class BuildWeeklyPlanController22 extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'build_plan_id' => 'required|exists:build_plans,id',
            'week_no'       => 'required|integer',
            'plan_percent'  => 'nullable|numeric|min:0|max:100',
        ]);

        $plan = BuildPlanWeek::updateOrCreate(

            [
                'build_plan_id' => $request->build_plan_id,
                'week_no'       => $request->week_no,

            ],
            [
                'plan_percent'  => $request->plan_percent ?? 0,
            ]
        );
        return response()->json([
            'success' => true,
            'data'    => $plan
        ]);
    }

public function import(Request $request, Project $project)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
        'sheet_name' => 'nullable|string',
    ]);

    $path = $request->file('file')->getRealPath();
    $spreadsheet = IOFactory::load($path);

    /*
    |--------------------------------------------------------------------------
    | HELPER NORMALIZE
    |--------------------------------------------------------------------------
    */
    $normalize = function ($val) {
        $val = (string) $val;
        $val = preg_replace('/\s+/u', ' ', $val);

        return trim($val);
    };

    /*
    |--------------------------------------------------------------------------
    | HELPER COMPOSITE KEY
    |--------------------------------------------------------------------------
    */
    $makeKey = function ($floor, $category, $itemName) use ($normalize) {
        return strtolower(
            $normalize($floor) . '|' .
            $normalize($category) . '|' .
            $normalize($itemName)
        );
    };

    /*
    |--------------------------------------------------------------------------
    | PILIH SHEET
    |--------------------------------------------------------------------------
    */
    $candidateSheets = $request->filled('sheet_name')
        ? [$spreadsheet->getSheetByName($request->sheet_name)]
        : $spreadsheet->getAllSheets();

    $sheet = null;

    $uraianCol = null;
    $bobotCol = null;
    $noCol = null;
    $weekCols = [];
    $labelRow = null;
    $dataStartRow = null;

    /*
    |--------------------------------------------------------------------------
    | DETEKSI HEADER SECARA DINAMIS
    |--------------------------------------------------------------------------
    */
    foreach ($candidateSheets as $candidate) {

        if (!$candidate) {
            continue;
        }

        $highestRow = $candidate->getHighestDataRow();
        $highestColumn = $candidate->getHighestDataColumn();
        $highestColIdx = Coordinate::columnIndexFromString($highestColumn);

        $foundUraianCol = null;
        $foundBobotCol = null;
        $foundNoCol = null;
        $foundLabelRow = null;

        /*
        |--------------------------------------------------------------------------
        | Cari header
        |--------------------------------------------------------------------------
        */
        $scanRows = min($highestRow, 20);

        for ($r = 1; $r <= $scanRows; $r++) {

            for ($c = 1; $c <= $highestColIdx; $c++) {

                $coord = Coordinate::stringFromColumnIndex($c) . $r;

                $val = $normalize(
                    $candidate->getCell($coord)->getValue()
                );

                /*
                |--------------------------------------------------------------------------
                | URAIAN PEKERJAAN
                |--------------------------------------------------------------------------
                */
                if (stripos($val, 'URAIAN PEKERJAAN') !== false) {

                    $foundUraianCol = $c;
                    $foundLabelRow = $r;
                }

                /*
                |--------------------------------------------------------------------------
                | BOBOT
                |--------------------------------------------------------------------------
                */
                if (strcasecmp($val, 'BOBOT') === 0) {

                    $foundBobotCol = $c;
                }

                /*
                |--------------------------------------------------------------------------
                | NO
                |--------------------------------------------------------------------------
                */
                if (
                    strcasecmp($val, 'NO') === 0 ||
                    strcasecmp($val, 'NO.') === 0
                ) {

                    $foundNoCol = $c;
                }
            }

            if ($foundUraianCol && $foundBobotCol) {
                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER TIDAK LENGKAP
        |--------------------------------------------------------------------------
        */
        if (!$foundUraianCol || !$foundBobotCol) {

            Log::debug(
                'BUILD PLAN IMPORT - HEADER TIDAK DITEMUKAN',
                [
                    'sheet' => $candidate->getTitle(),
                    'highest_row' => $highestRow,
                    'highest_column' => $highestColumn,
                ]
            );

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK KOLOM NO
        |--------------------------------------------------------------------------
        */
        if (!$foundNoCol) {

            $foundNoCol = max(
                1,
                $foundUraianCol - 1
            );

            Log::debug(
                'BUILD PLAN IMPORT - KOLOM NO TIDAK TERDETEKSI',
                [
                    'sheet' => $candidate->getTitle(),
                    'fallback_no_col' => $foundNoCol,
                    'uraian_col' => $foundUraianCol,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DETEKSI KOLOM MINGGU
        |--------------------------------------------------------------------------
        |
        | Cari angka minggu pada baris header dan maksimal 3 baris
        | setelah header URAIAN.
        |
        */
        $foundWeekRow = null;
        $foundWeekCols = [];
        $maxCount = 0;

        for (
            $rr = $foundLabelRow;
            $rr <= min($foundLabelRow + 3, $highestRow);
            $rr++
        ) {

            $tempCols = [];

            for (
                $c = $foundBobotCol + 1;
                $c <= $highestColIdx;
                $c++
            ) {

                $coord =
                    Coordinate::stringFromColumnIndex($c) . $rr;

                $val = $normalize(
                    $candidate->getCell($coord)->getValue()
                );

                if ($val !== '' && is_numeric($val)) {

                    $weekNo = (int) $val;

                    if ($weekNo > 0) {
                        $tempCols[$c] = $weekNo;
                    }
                }
            }

            if (count($tempCols) > $maxCount) {

                $maxCount = count($tempCols);

                $foundWeekRow = $rr;
                $foundWeekCols = $tempCols;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA MINGGU
        |--------------------------------------------------------------------------
        */
        if (empty($foundWeekCols)) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | SHEET VALID
        |--------------------------------------------------------------------------
        */
        $sheet = $candidate;

        $uraianCol = $foundUraianCol;
        $bobotCol = $foundBobotCol;
        $noCol = $foundNoCol;

        $weekCols = $foundWeekCols;

        $labelRow = $foundLabelRow;

        $dataStartRow =
            max(
                $foundLabelRow,
                $foundWeekRow
            ) + 1;

        break;
    }

    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA SHEET VALID
    |--------------------------------------------------------------------------
    */
    if (!$sheet) {

        return response()->json([
            'success' => false,
            'message' =>
                'Tidak ada sheet yang memiliki header URAIAN PEKERJAAN, BOBOT, dan nomor minggu.',
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER BACA CELL
    |--------------------------------------------------------------------------
    */
    $cellValue = function (int $col, int $row) use ($sheet) {

        $coord =
            Coordinate::stringFromColumnIndex($col) . $row;

        return $sheet->getCell($coord)->getValue();
    };

    /*
    |--------------------------------------------------------------------------
    | HELPER BACA CALCULATED VALUE
    |--------------------------------------------------------------------------
    */
    $cellCalculated = function (int $col, int $row) use ($sheet) {

        $coord =
            Coordinate::stringFromColumnIndex($col) . $row;

        return $sheet->getCell($coord)->getCalculatedValue();
    };

    /*
    |--------------------------------------------------------------------------
    | HELPER AMBIL TEKS AREA URAIAN
    |--------------------------------------------------------------------------
    |
    | Area:
    |
    | NO | URAIAN PEKERJAAN | BOBOT
    |    | E - sebelum BOBOT
    |
    */
    $getUraianText = function (int $row) use (
        $sheet,
        $normalize,
        $uraianCol,
        $bobotCol
    ) {

        $parts = [];

        for (
            $col = $uraianCol;
            $col < $bobotCol;
            $col++
        ) {

            $coord =
                Coordinate::stringFromColumnIndex($col) . $row;

            $value = $normalize(
                $sheet->getCell($coord)->getValue()
            );

            if ($value === '') {
                continue;
            }

            $parts[] = $value;
        }

        $parts = array_values(
            array_unique($parts)
        );

        return $normalize(
            implode(' ', $parts)
        );
    };

    $detectFloor = function (int $row) use (
        $sheet,
        $normalize,
        $noCol,
        $bobotCol
    ) {

        for (
            $col = $noCol;
            $col < $bobotCol;
            $col++
        ) {

            $coord =
                Coordinate::stringFromColumnIndex($col) . $row;

            $value = $normalize(
                $sheet->getCell($coord)->getValue()
            );

            if ($value === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Deteksi kata LANTAI
            |--------------------------------------------------------------------------
            |
            | Cocok:
            | LANTAI 1
            | Lantai 2
            | LANTAI DASAR
            | LANTAI BASEMENT
            |
            | Tidak cocok:
            | Pekerjaan Lantai Kerja
            |
            */
            if (preg_match('/^\s*LANTAI\b/i', $value)) {

                return $value;
            }
        }

        return null;
    };

    $buildPlans = BuildPlans::where(
        'project_id',
        $project->id
    )->get();

    $indexed = $buildPlans->mapWithKeys(
        function ($p) use ($makeKey) {

            return [
                $makeKey(
                    $p->floor_name,
                    $p->category_name,
                    $p->item_name
                ) => $p
            ];
        }
    );

    /*
    |--------------------------------------------------------------------------
    | COUNTER
    |--------------------------------------------------------------------------
    */
    $imported = 0;
    $rowsWithBobot = 0;
    $rowsWithoutBobot = 0;
    $rowsEmptyUraian = 0;
    $rowsMatched = 0;
    $rowsNotMatched = 0;

    $totalWeekCells = 0;
    $totalWeekImported = 0;
    $totalWeekSkipped = 0;

    $skipped = [];

    $highestRow =
        $sheet->getHighestDataRow();

    /*
    |--------------------------------------------------------------------------
    | STATE KONTEXT
    |--------------------------------------------------------------------------
    */
    $currentFloor = null;
    $currentCategory = null;

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */
    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | SCAN BARIS
        |--------------------------------------------------------------------------
        */
for ($r = $dataStartRow; $r <= $highestRow; $r++) {

    // NO dinamis
    $noVal = $normalize(
        $cellValue($noCol, $r)
    );

    // URAIAN dinamis
    $itemNameRaw = $getUraianText($r);

    // BOBOT dinamis
    $bobotVal = $cellCalculated(
        $bobotCol,
        $r
    );

    $hasBobot =
        $bobotVal !== null &&
        $bobotVal !== '' &&
        is_numeric($bobotVal);


    /*
    |--------------------------------------------------------------------------
    | DETEKSI LANTAI
    |--------------------------------------------------------------------------
    */
    if (!$hasBobot) {

        $floorFound = $detectFloor($r);

        if ($floorFound !== null) {

            $currentFloor = $floorFound;
            $currentCategory = null;

            Log::debug(
                'BUILD PLAN IMPORT - LANTAI TERDETEKSI',
                [
                    'row' => $r,
                    'floor' => $currentFloor,
                ]
            );

            continue;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DETEKSI KATEGORI
    |--------------------------------------------------------------------------
    */
    if (
        !$hasBobot &&
        preg_match('/^[A-Z]$/', $noVal) &&
        $itemNameRaw !== ''
    ) {

        $currentCategory = $itemNameRaw;

        Log::debug(
            'BUILD PLAN IMPORT - KATEGORI TERDETEKSI',
            [
                'row' => $r,
                'floor' => $currentFloor,
                'category' => $currentCategory,
            ]
        );

        continue;
    }


    /*
    |--------------------------------------------------------------------------
    | BARIS TANPA BOBOT
    |--------------------------------------------------------------------------
    */
    if (!$hasBobot) {

        $rowsWithoutBobot++;

        continue;
    }


    /*
    |--------------------------------------------------------------------------
    | ITEM
    |--------------------------------------------------------------------------
    */
    $rowsWithBobot++;

    if ($itemNameRaw === '') {

        $rowsEmptyUraian++;

        continue;
    }


    $key = $makeKey(
        $currentFloor,
        $currentCategory,
        $itemNameRaw
    );

    $plan = $indexed->get($key);


            /*
            |--------------------------------------------------------------------------
            | BUILD PLAN TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */
            if (!$plan) {

                $rowsNotMatched++;

                $skipped[] =
                    "Baris {$r}: \"{$itemNameRaw}\" " .
                    "(Lantai: {$currentFloor}, " .
                    "Kategori: {$currentCategory}) " .
                    "tidak ditemukan di build plan project ini.";

                Log::debug(
                    'BUILD PLAN IMPORT - ITEM TIDAK DITEMUKAN',
                    [
                        'row' => $r,
                        'floor' => $currentFloor,
                        'category' => $currentCategory,
                        'item_name' => $itemNameRaw,
                        'bobot' => $bobotVal,
                    ]
                );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | MATCHED
            |--------------------------------------------------------------------------
            */
            $rowsMatched++;


            /*
            |--------------------------------------------------------------------------
            | IMPORT WEEK
            |--------------------------------------------------------------------------
            */
            foreach (
                $weekCols as $colIdx => $weekNo
            ) {

                $totalWeekCells++;

                $coord =
                    Coordinate::stringFromColumnIndex(
                        $colIdx
                    ) . $r;

                $cell =
                    $sheet->getCell($coord);

                $raw =
                    $cell->getCalculatedValue();


                /*
                |--------------------------------------------------------------------------
                | KOSONG
                |--------------------------------------------------------------------------
                */
                if (
                    $raw === null ||
                    $raw === ''
                ) {

                    $totalWeekSkipped++;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | NORMALISASI STRING
                |--------------------------------------------------------------------------
                */
                if (is_string($raw)) {

                    $raw = str_replace(
                        ['%', ','],
                        ['', '.'],
                        $raw
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | BUKAN NUMERIC
                |--------------------------------------------------------------------------
                */
                if (!is_numeric($raw)) {

                    $totalWeekSkipped++;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | CEK FORMAT PERCENT
                |--------------------------------------------------------------------------
                */
                $formatCode =
                    $cell
                        ->getStyle()
                        ->getNumberFormat()
                        ->getFormatCode();

                $isPercentFormat =
                    strpos($formatCode, '%') !== false;


                /*
                |--------------------------------------------------------------------------
                | KONVERSI
                |--------------------------------------------------------------------------
                */
                $percent = (float) $raw;

                if ($isPercentFormat) {

                    /*
                    | Excel:
                    |
                    | 0.0032
                    |
                    | tampil:
                    |
                    | 0.32%
                    |
                    | Database kita:
                    |
                    | 0.32
                    */
                    $percent *= 100;
                }


                /*
                |--------------------------------------------------------------------------
                | SIMPAN
                |--------------------------------------------------------------------------
                */
                BuildPlanWeek::updateOrCreate(
                    [
                        'build_plan_id' => $plan->id,
                        'week_no' => $weekNo,
                    ],
                    [
                        'plan_percent' =>
                            round($percent, 6),
                    ]
                );

                $totalWeekImported++;
            }

            $imported++;
        }

        DB::commit();

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error(
            'BUILD PLAN IMPORT - ERROR',
            [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
        );

        return response()->json([
            'success' => false,
            'message' =>
                'Import gagal: ' .
                $e->getMessage(),
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' =>
            'Import Build Plan berhasil.',

        'sheet' =>
            $sheet->getTitle(),

        'imported' =>
            $imported,

        'rows_with_bobot' =>
            $rowsWithBobot,

        'rows_without_bobot' =>
            $rowsWithoutBobot,

        'rows_empty_uraian' =>
            $rowsEmptyUraian,

        'rows_matched' =>
            $rowsMatched,

        'rows_not_matched' =>
            $rowsNotMatched,

        'week_count' =>
            count($weekCols),

        'total_week_cells' =>
            $totalWeekCells,

        'week_imported' =>
            $totalWeekImported,

        'week_skipped' =>
            $totalWeekSkipped,

        'skipped' =>
            $skipped,
    ]);
}
}