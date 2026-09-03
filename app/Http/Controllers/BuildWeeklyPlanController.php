<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    $candidateSheets = $request->filled('sheet_name')
        ? [$spreadsheet->getSheetByName($request->sheet_name)]
        : $spreadsheet->getAllSheets();

    $sheet        = null;
    $uraianCol    = null;
    $bobotCol     = null;
    $weekCols     = [];
    $dataStartRow = null;

    foreach ($candidateSheets as $candidate) {
        if (!$candidate) continue;

        $highestRow    = $candidate->getHighestDataRow();
        $highestColumn = $candidate->getHighestDataColumn();
        $highestColIdx = Coordinate::columnIndexFromString($highestColumn);

        $foundUraianCol = null;
        $foundBobotCol  = null;
        $foundLabelRow  = null;

        $scanRows = min($highestRow, 20);

        for ($r = 1; $r <= $scanRows; $r++) {
            for ($c = 1; $c <= $highestColIdx; $c++) {
                $coord = Coordinate::stringFromColumnIndex($c) . $r;
                $val = $normalize($candidate->getCell($coord)->getValue());

                if (stripos($val, 'URAIAN PEKERJAAN') !== false) {
                    $foundUraianCol = $c;
                    $foundLabelRow  = $r;
                }
                if (strcasecmp($val, 'BOBOT') === 0) {
                    $foundBobotCol = $c;
                }
            }
            if ($foundUraianCol && $foundBobotCol) break;
        }

        if (!$foundUraianCol || !$foundBobotCol) {
            continue;
        }

        $foundWeekRow  = null;
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
                $foundWeekRow  = $rr;
                $foundWeekCols = $tempCols;
            }
        }

        if (empty($foundWeekCols)) {
            continue;
        }

        $sheet        = $candidate;
        $uraianCol    = $foundUraianCol;
        $bobotCol     = $foundBobotCol;
        $weekCols     = $foundWeekCols;
        $dataStartRow = max($foundLabelRow, $foundWeekRow) + 1;
        break;
    }

    if (!$sheet) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada sheet yang punya header "URAIAN PEKERJAAN"/"BOBOT" sekaligus baris nomor minggu (1,2,3,...) di file ini.',
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

    $highestRow = $sheet->getHighestDataRow();

    // 3. Index build_plans by nama item (lowercase trim)
    $buildPlans = BuildPlans::where('project_id', $project->id)->get();
    $indexed = $buildPlans->mapWithKeys(function ($p) {
        return [strtolower(trim($p->item_name)) => $p];
    });

    $imported = 0;
    $skipped  = [];

    DB::beginTransaction();
    try {
        for ($r = $dataStartRow; $r <= $highestRow; $r++) {
            $bobotVal = $cellCalculated($bobotCol, $r);

            if ($bobotVal === null || $bobotVal === '' || !is_numeric($bobotVal)) {
                continue; // baris kategori/lantai
            }

            $itemName = $normalize($cellValue($uraianCol, $r));
            if ($itemName === '') {
                continue;
            }

            $key = strtolower($itemName);
            $plan = $indexed->get($key);

            if (!$plan) {
                $skipped[] = "Baris {$r}: \"{$itemName}\" tidak ditemukan di data build plan project ini.";
                continue;
            }

            foreach ($weekCols as $colIdx => $weekNo) {
                $raw = $cellCalculated($colIdx, $r);

                if ($raw === null || $raw === '') {
                    continue;
                }

                if (is_string($raw)) {
                    $raw = str_replace(['%', ','], ['', '.'], $raw);
                }
                $percent = (float) $raw;

                if ($percent > 0 && $percent < 1) {
                    $percent *= 100;
                }

                BuildPlanWeek::updateOrCreate(
                    [
                        'build_plan_id' => $plan->id,
                        'week_no'       => $weekNo,
                    ],
                    [
                        'plan_percent'  => round($percent, 3),
                    ]
                );
            }

            $imported++;
        }

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Import gagal: ' . $e->getMessage(),
        ], 500);
    }

    return response()->json([
        'success'  => true,
        'imported' => $imported,
        'skipped'  => $skipped,
    ]);
}
}