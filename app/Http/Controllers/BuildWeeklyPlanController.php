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

class BuildWeeklyPlanController extends Controller
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

        $normalize = function ($val) {
            $val = (string) $val;
            $val = preg_replace('/\s+/u', ' ', $val);
            return trim($val);
        };

        $makeKey = function ($floor, $category, $itemName) use ($normalize) {
            return strtolower($normalize($floor) . '|' . $normalize($category) . '|' . $normalize($itemName));
        };

        $candidateSheets = $request->filled('sheet_name')
            ? [$spreadsheet->getSheetByName($request->sheet_name)]
            : $spreadsheet->getAllSheets();

        $sheet = null;
        $uraianCol = null;
        $bobotCol = null;
        $weekCols = [];
        $dataStartRow = null;

        foreach ($candidateSheets as $candidate) {

            if (!$candidate) continue;

            $highestRow = $candidate->getHighestDataRow();
            $highestColumn = $candidate->getHighestDataColumn();
            $highestColIdx = Coordinate::columnIndexFromString($highestColumn);

            $foundUraianCol = null;
            $foundBobotCol = null;
            $foundNoCol = null;   
            $foundLabelRow = null;

            $scanRows = min($highestRow, 20);

            for ($r = 1; $r <= $scanRows; $r++) {
                for ($c = 1; $c <= $highestColIdx; $c++) {
                    $coord = Coordinate::stringFromColumnIndex($c) . $r;
                    $val = $normalize($candidate->getCell($coord)->getValue());

                    if (stripos($val, 'URAIAN PEKERJAAN') !== false) {
                        $foundUraianCol = $c;
                        $foundLabelRow = $r;
                    }

                    if (strcasecmp($val, 'BOBOT') === 0) {
                        $foundBobotCol = $c;
                    }

                    // Deteksi kolom "NO" -> harus exact match, jangan pakai stripos
                    // supaya tidak ke-trigger oleh kata lain yang mengandung "no"
                    if (strcasecmp($val, 'NO') === 0 || strcasecmp($val, 'NO.') === 0) {
                        $foundNoCol = $c;
                    }
                }

                if ($foundUraianCol && $foundBobotCol) break;
            }

            if (!$foundUraianCol || !$foundBobotCol) {
                Log::debug('BUILD PLAN IMPORT - HEADER TIDAK DITEMUKAN', [
                    'sheet' => $candidate->getTitle(),
                    'highest_row' => $highestRow,
                    'highest_column' => $highestColumn,
                ]);
                continue;
            }

            // Fallback: kalau kolom "NO" tidak ketemu (misal headernya cuma kosong / beda tulisan),
            // asumsikan kolom NO ada tepat 1 kolom sebelum URAIAN PEKERJAAN
            if (!$foundNoCol) {
                $foundNoCol = max(1, $foundUraianCol - 1);

                Log::debug('BUILD PLAN IMPORT - KOLOM NO TIDAK TERDETEKSI, PAKAI FALLBACK', [
                    'sheet' => $candidate->getTitle(),
                    'fallback_no_col' => $foundNoCol,
                    'uraian_col' => $foundUraianCol,
                ]);
            }

            $foundWeekRow = null;
            $foundWeekCols = [];
            $maxCount = 0;

            for ($rr = $foundLabelRow; $rr <= min($foundLabelRow + 3, $highestRow); $rr++) {
                $tempCols = [];
                for ($c = $foundBobotCol + 1; $c <= $highestColIdx; $c++) {
                    $coord = Coordinate::stringFromColumnIndex($c) . $rr;
                    $val = $normalize($candidate->getCell($coord)->getValue());
                    if ($val !== '' && is_numeric($val)) {
                        $tempCols[$c] = (int) $val;
                    }
                }
                if (count($tempCols) > $maxCount) {
                    $maxCount = count($tempCols);
                    $foundWeekRow = $rr;
                    $foundWeekCols = $tempCols;
                }
            }

            if (empty($foundWeekCols)) continue;
            $foundFirstFloorRow = null;

            for ($rr = 1; $rr <= $highestRow; $rr++) {
                $noValue = $normalize(
                    $candidate->getCell(
                        Coordinate::stringFromColumnIndex($foundNoCol) . $rr
                    )->getValue()
                );

                if (
                    $noValue !== '' &&
                    preg_match('/^LANTAI(?:\s+.*)?$/iu', $noValue)
                ) {
                    $foundFirstFloorRow = $rr;
                    break;
                }
            }
                $sheet = $candidate;
                $uraianCol = $foundUraianCol;
                $bobotCol = $foundBobotCol;
                $noCol = $foundNoCol;
                $weekCols = $foundWeekCols;

                $dataStartRow = $foundFirstFloorRow
                    ? $foundFirstFloorRow
                    : max(1, $foundLabelRow - 2);

                break;
            break;
        }

        if (!$sheet) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sheet yang memiliki header URAIAN PEKERJAAN, BOBOT, dan nomor minggu.',
            ], 422);
        }

        $cellValue = function (int $col, int $row) use ($sheet) {
            $coord = Coordinate::stringFromColumnIndex($col) . $row;
            return $sheet->getCell($coord)->getValue();
        };

        $cellCalculated = function (int $col, int $row) use ($sheet) {
            $coord = Coordinate::stringFromColumnIndex($col) . $row;
            return $sheet->getCell($coord)->getCalculatedValue();
        };

        // === INDEX DATA BUILD PLAN DARI DATABASE, PAKAI COMPOSITE KEY ===
        $buildPlans = BuildPlans::where('project_id', $project->id)->get();

        $indexed = $buildPlans->mapWithKeys(function ($p) use ($makeKey) {
            return [
                $makeKey($p->floor_name, $p->category_name, $p->item_name) => $p
            ];
        });

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

        $highestRow = $sheet->getHighestDataRow();

        // State pelacak konteks saat scanning baris demi baris
        $currentFloor = null;
        $currentCategory = null;

        DB::beginTransaction();

        try {

        $parentName = null;

        for ($r = $dataStartRow; $r <= $highestRow; $r++) {

            $noVal      = $normalize($cellValue($noCol, $r));
            $uraianE    = $normalize($cellValue($uraianCol, $r));
            $uraianF    = $normalize($cellValue($uraianCol + 1, $r));
            $bobotVal   = $cellCalculated($bobotCol, $r);

            $hasBobot =
                $bobotVal !== null &&
                $bobotVal !== '' &&
                is_numeric($bobotVal);


            /*
            * ==========================
            * FLOOR
            * ==========================
            */
            if (
                !$hasBobot &&
                $noVal !== '' &&
                preg_match('/^LANTAI(?:\s+.*)?$/iu', $noVal)
            ) {
                $currentFloor = $noVal;
                $currentCategory = null;
                $parentName = null;

                continue;
            }


            /*
            * ==========================
            * CATEGORY
            * ==========================
            *
            * A | PEKERJAAN PERSIAPAN
            * B | PEKERJAAN TANAH
            */
            if (
                !$hasBobot &&
                $uraianE !== '' &&
                preg_match('/^[A-Z]$/i', $noVal)
            ) {
                $currentCategory = $uraianE;
                $parentName = null;

                continue;
            }


            /*
            * ==========================
            * TIDAK ADA BOBOT
            * ==========================
            *
            * Kalau E terisi dan bobot kosong,
            * anggap sebagai parent/subkategori.
            */
            if (!$hasBobot) {

                if ($uraianE !== '') {
                    $parentName = $uraianE;

                    Log::debug('BUILD PLAN IMPORT - PARENT TERDETEKSI', [
                        'row' => $r,
                        'floor' => $currentFloor,
                        'category' => $currentCategory,
                        'parent' => $parentName,
                    ]);
                }

                $rowsWithoutBobot++;
                continue;
            }

            $rowsWithBobot++;


            /*
            * POLA 1:
            *
            * E terisi + bobot ada
            *
            * Contoh:
            *
            * E = Pekerjaan Pembersihan Lapangan
            * H = 0.003219
            *
            * Ini adalah ITEM LANGSUNG.
            */
            if ($uraianE !== '') {

                $itemNameRaw = $uraianE;

                /*
                * Karena item langsung dimulai dari E,
                * parent sebelumnya tidak boleh dianggap
                * sebagai parent item ini.
                */
                $itemParent = null;

            }

            /*
            * POLA 2:
            *
            * E kosong + F terisi + bobot ada
            *
            * Contoh:
            *
            * E = kosong
            * F = Bekisting Kolom
            * H = 0.002241
            *
            * Ini adalah ITEM DETAIL dari parent E sebelumnya.
            */
            elseif ($uraianF !== '') {

                $itemNameRaw = $uraianF;

                $itemParent = $parentName;

            }

            /*
            * Tidak ada nama pekerjaan
            */
            else {

                $rowsEmptyUraian++;

                Log::debug('BUILD PLAN IMPORT - NAMA ITEM KOSONG', [
                    'row' => $r,
                    'floor' => $currentFloor,
                    'category' => $currentCategory,
                    'parent' => $parentName,
                    'bobot' => $bobotVal,
                ]);

                continue;
            }


            /*
            * ==========================
            * VALIDASI FLOOR
            * ==========================
            */

            if (!$currentFloor) {

                $rowsNotMatched++;

                $skipped[] =
                    "Baris {$r}: \"{$itemNameRaw}\" tidak memiliki lantai.";

                continue;
            }


            /*
            * ==========================
            * VALIDASI CATEGORY
            * ==========================
            */

            if (!$currentCategory) {

                $rowsNotMatched++;

                $skipped[] =
                    "Baris {$r}: \"{$itemNameRaw}\" tidak memiliki kategori.";

                continue;
            }


            /*
            * ==========================
            * MATCH BUILD PLAN
            * ==========================
            */

            $key = $makeKey(
                $currentFloor,
                $currentCategory,
                $itemNameRaw
            );

            $plan = $indexed->get($key);


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
                        'parent' => $itemParent,
                        'item_name' => $itemNameRaw,
                        'key' => $key,
                        'bobot' => $bobotVal,
                    ]
                );

                continue;
            }


            $rowsMatched++;


            /*
            * ==========================
            * IMPORT 25 MINGGU
            * ==========================
            */

            foreach ($weekCols as $colIdx => $weekNo) {

                $totalWeekCells++;

                $coord =
                    Coordinate::stringFromColumnIndex($colIdx) . $r;

                $cell = $sheet->getCell($coord);

                $raw = $cell->getCalculatedValue();

                if ($raw === null || $raw === '') {
                    $totalWeekSkipped++;
                    continue;
                }

                if (is_string($raw)) {
                    $raw = trim($raw);
                    $raw = str_replace('%', '', $raw);
                    $raw = str_replace(',', '.', $raw);
                }

                if (!is_numeric($raw)) {

                    $totalWeekSkipped++;

                    continue;
                }

                $formatCode =
                    $cell->getStyle()
                        ->getNumberFormat()
                        ->getFormatCode();

                $isPercentFormat =
                    strpos($formatCode, '%') !== false;

                $percent = (float) $raw;

                if ($isPercentFormat) {
                    $percent *= 100;
                }

                BuildPlanWeek::updateOrCreate(
                    [
                        'build_plan_id' => $plan->id,
                        'week_no' => $weekNo,
                    ],
                    [
                        'plan_percent' => round($percent, 6),
                    ]
                );

                $totalWeekImported++;
            }


            $imported++;
        }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BUILD PLAN IMPORT - ERROR', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage(),
            ], 500);
        }

        Log::info('BUILD PLAN IMPORT - SELESAI', [
            'project_id' => $project->id,
            'sheet' => $sheet->getTitle(),
            'data_start_row' => $dataStartRow,
            'week_count' => count($weekCols),
            'rows_with_bobot' => $rowsWithBobot,
            'rows_without_bobot' => $rowsWithoutBobot,
            'rows_empty_uraian' => $rowsEmptyUraian,
            'rows_matched' => $rowsMatched,
            'rows_not_matched' => $rowsNotMatched,
            'total_week_cells' => $totalWeekCells,
            'week_imported' => $totalWeekImported,
            'week_skipped' => $totalWeekSkipped,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Import Build Plan berhasil.',
            'sheet' => $sheet->getTitle(),
            'imported' => $imported,
            'rows_with_bobot' => $rowsWithBobot,
            'rows_without_bobot' => $rowsWithoutBobot,
            'rows_empty_uraian' => $rowsEmptyUraian,
            'rows_matched' => $rowsMatched,
            'rows_not_matched' => $rowsNotMatched,
            'week_count' => count($weekCols),
            'total_week_cells' => $totalWeekCells,
            'week_imported' => $totalWeekImported,
            'week_skipped' => $totalWeekSkipped,
            'skipped' => $skipped,
        ]);
    }
}

// foreach ($candidateSheets as $candidate) {

//     if (!$candidate) {
//         continue;
//     }

//     $highestRow = $candidate->getHighestDataRow();
//     $highestColumn = $candidate->getHighestDataColumn();
//     $highestColIdx = Coordinate::columnIndexFromString($highestColumn);

//     $foundUraianCol = null;
//     $foundBobotCol = null;
//     $foundNoCol = null;
//     $foundLabelRow = null;

//     $scanRows = min($highestRow, 20);

//     for ($r = 1; $r <= $scanRows; $r++) {

//         for ($c = 1; $c <= $highestColIdx; $c++) {

//             $coord = Coordinate::stringFromColumnIndex($c) . $r;

//             $val = $normalize(
//                 $candidate->getCell($coord)->getValue()
//             );

//             /*
//              * URAIAN PEKERJAAN
//              */
//             if (
//                 stripos($val, 'URAIAN PEKERJAAN') !== false
//             ) {
//                 $foundUraianCol = $c;
//                 $foundLabelRow = $r;
//             }

//             /*
//              * BOBOT
//              */
//             if (
//                 strcasecmp($val, 'BOBOT') === 0
//             ) {
//                 $foundBobotCol = $c;
//             }

//             /*
//              * NO / NO.
//              */
//             if (
//                 strcasecmp($val, 'NO') === 0 ||
//                 strcasecmp($val, 'NO.') === 0
//             ) {
//                 $foundNoCol = $c;
//             }
//         }

//         if (
//             $foundUraianCol &&
//             $foundBobotCol
//         ) {
//             break;
//         }
//     }


//     if (
//         !$foundUraianCol ||
//         !$foundBobotCol
//     ) {

//         Log::debug(
//             'BUILD PLAN IMPORT - HEADER TIDAK DITEMUKAN',
//             [
//                 'sheet' => $candidate->getTitle(),
//                 'highest_row' => $highestRow,
//                 'highest_column' => $highestColumn,
//             ]
//         );

//         continue;
//     }

//     if (!$foundNoCol) {

//         $foundNoCol = max(
//             1,
//             $foundUraianCol - 1
//         );

//         Log::debug(
//             'BUILD PLAN IMPORT - KOLOM NO FALLBACK',
//             [
//                 'sheet' => $candidate->getTitle(),
//                 'fallback_no_col' => $foundNoCol,
//                 'uraian_col' => $foundUraianCol,
//             ]
//         );
//     }

//     $foundWeekRow = null;
//     $foundWeekCols = [];
//     $maxCount = 0;

//     for (
//         $rr = $foundLabelRow;
//         $rr <= min($foundLabelRow + 3, $highestRow);
//         $rr++
//     ) {

//         $tempCols = [];

//         for (
//             $c = $foundBobotCol + 1;
//             $c <= $highestColIdx;
//             $c++
//         ) {

//             $coord =
//                 Coordinate::stringFromColumnIndex($c) . $rr;

//             $val = $normalize(
//                 $candidate->getCell($coord)->getValue()
//             );

//             if (
//                 $val !== '' &&
//                 is_numeric($val)
//             ) {

//                 $tempCols[$c] = (int) $val;
//             }
//         }

//         if (
//             count($tempCols) > $maxCount
//         ) {

//             $maxCount = count($tempCols);

//             $foundWeekRow = $rr;
//             $foundWeekCols = $tempCols;
//         }
//     }

//     if (empty($foundWeekCols)) {

//         Log::debug(
//             'BUILD PLAN IMPORT - NOMOR MINGGU TIDAK DITEMUKAN',
//             [
//                 'sheet' => $candidate->getTitle(),
//                 'bobot_col' => $foundBobotCol,
//                 'label_row' => $foundLabelRow,
//             ]
//         );

//         continue;
//     }

//     $foundFirstFloorRow = null;

//     for (
//         $rr = 1;
//         $rr <= $highestRow;
//         $rr++
//     ) {

//         $noValue = $normalize(
//             $candidate->getCell(
//                 Coordinate::stringFromColumnIndex(
//                     $foundNoCol
//                 ) . $rr
//             )->getValue()
//         );

//         if (
//             $noValue !== '' &&
//             preg_match(
//                 '/^LANTAI(?:\s+.*)?$/iu',
//                 $noValue
//             )
//         ) {

//             $foundFirstFloorRow = $rr;

//             Log::debug(
//                 'BUILD PLAN IMPORT - LANTAI PERTAMA DITEMUKAN',
//                 [
//                     'sheet' => $candidate->getTitle(),
//                     'row' => $rr,
//                     'floor' => $noValue,
//                     'no_col' => $foundNoCol,
//                 ]
//             );

//             break;
//         }
//     }

//     $sheet = $candidate;

//     $uraianCol = $foundUraianCol;
//     $bobotCol = $foundBobotCol;
//     $noCol = $foundNoCol;

//     $weekCols = $foundWeekCols;

//     $dataStartRow =
//         $foundFirstFloorRow
//             ? $foundFirstFloorRow
//             : max(
//                 1,
//                 max(
//                     $foundLabelRow,
//                     $foundWeekRow
//                 ) + 1
//             );


//     Log::info(
//         'BUILD PLAN IMPORT - HEADER TERDETEKSI',
//         [
//             'sheet' => $candidate->getTitle(),

//             'no_col' => $noCol,

//             'uraian_col' => $uraianCol,

//             /*
//              * Kolom detail pekerjaan.
//              *
//              * Misalnya:
//              * E = URAIAN
//              * F = DETAIL
//              */
//             'detail_col' => $uraianCol + 1,

//             'bobot_col' => $bobotCol,

//             'week_row' => $foundWeekRow,

//             'week_count' => count($weekCols),

//             'first_floor_row' => $foundFirstFloorRow,

//             'data_start_row' => $dataStartRow,
//         ]
//     );


//     break;
// }