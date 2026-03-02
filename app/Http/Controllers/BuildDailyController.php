<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildDailyReport;
use App\Models\BuildDailyWorker;
use App\Models\BuildDailyWork;
use App\Models\BuildDailyMaterial;
use App\Models\BuildProcessItem;
use App\Models\Project;
use App\Models\Worker;
use Carbon\Carbon;
use DB;

class BuildDailyController extends Controller
{
    public function store(Request $request)
{
    $request->validate([

        // === MASTER REPORT ===
        'project_id' => 'required|exists:projects,id',
        'tanggal' => 'required|date',
        'jam_mulai' => 'nullable|date_format:H:i',
        'jam_selesai' => 'nullable|date_format:H:i',
        'total_jam' => 'nullable|numeric|min:0',
        'cuaca' => 'nullable|string|max:50',
        'catatan' => 'nullable|string',
        'mk' => 'nullable|string|max:100',
        'kontraktor_ttd' => 'nullable|string|max:100',

        // === TENAGA KERJA ===
        'worker_id.*' => 'nullable',
        'keahlian.*' => 'nullable|string|max:100',
        'jumlah.*' => 'nullable|numeric|min:0',
        'alat.*' => 'nullable|string|max:150',

        // === PEKERJAAN ===
        'rab_process_item_id.*' => 'nullable',
        'uraian_manual.*' => 'nullable|string|max:255',
        'volume.*' => 'nullable|numeric|min:0',
        'satuan.*' => 'nullable|string|max:50',
        'ket.*' => 'nullable|string|max:255',

        // === MATERIAL ===
        'bahan.*' => 'nullable|string|max:150',
        'diterima.*' => 'nullable|numeric|min:0',
        'ditolak.*' => 'nullable|numeric|min:0',

    ]);
    foreach($request->worker_id ?? [] as $i => $worker){

    if($worker === 'manual'){
        if(empty($request->keahlian[$i])){
            return back()
                ->withErrors(['keahlian.'.$i => 'Keahlian manual wajib diisi.'])
                ->withInput();
        }
    }
}
foreach($request->rab_process_item_id ?? [] as $i => $rab){

    if($rab === 'manual'){
        if(empty($request->uraian_manual[$i])){
            return back()
                ->withErrors(['uraian_manual.'.$i => 'Uraian manual wajib diisi.'])
                ->withInput();
        }
    }
}
DB::beginTransaction();

try {
        $project = Project::findOrFail($request->project_id);

        // Hitung jumlah laporan yang sudah ada
        $totalReport = BuildDailyReport::where('project_id', $project->id)->count();

        // Tanggal = start_date + jumlah laporan
        $tanggal = Carbon::parse($project->start_date)
                    ->addDays($totalReport);

    $report = BuildDailyReport::create([
        'project_id' => $request->project_id,
        'pekerjaan' => $request->project_name,
        'tanggal' => $tanggal,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'total_jam' => $request->total_jam,
        'cuaca' => $request->cuaca,
        'catatan' => $request->catatan,
        'mk' => $request->mk,
        'kontraktor_ttd' => $request->kontraktor_ttd
    ]);

    if($request->worker_id){

    foreach($request->worker_id as $i=>$worker){

        if(empty($worker)) continue;

        if($worker == 'manual'){

            BuildDailyWorker::create([

                'daily_report_id'=>$report->id,
                'worker_id'=>null,
                'keahlian'=>$request->keahlian[$i] ?? null,
                'jumlah'=>$request->jumlah[$i] ?? 0,
                'alat'=>$request->alat[$i] ?? null

            ]);

        }else{
            $workerModel = Worker::find($worker);

            if(!$workerModel) continue;

            BuildDailyWorker::create([

                'daily_report_id'=>$report->id,
                'worker_id'=>$workerModel->id,
                'keahlian'=>null,
                'jumlah'=>$request->jumlah[$i] ?? 0,
                'alat'=>$request->alat[$i] ?? null

            ]);

        }

    }
    }

    if($request->rab_process_item_id) {
        foreach($request->rab_process_item_id as $i=>$rab){
            BuildDailyWork::create([
                'build_daily_report_id'=>$report->id,
                'rab_process_item_id'=>
                $rab != 'manual'
                ? $rab
                : null,
                'uraian_manual' =>
                    $rab == 'manual'
                    ? ($request->uraian_manual[$i] ?? null)
                    : null,
                'volume'=>$request->volume[$i] ?? 0,
                'satuan'=>$request->satuan[$i] ?? 0,
                'keterangan'=>$request->ket[$i] ?? null
            ]);
        }
    }

    if($request->bahan){

        foreach($request->bahan as $i=>$bahan){
            if(empty($bahan)) continue;
            BuildDailyMaterial::create([
                'daily_report_id'=>$report->id,
                'nama_bahan'=>$bahan,
                'diterima'=>$request->diterima[$i] ?? 0,
                'ditolak'=>$request->ditolak[$i] ?? 0

            ]);

        }

    }


DB::commit();

return redirect()->back()->with('success','Laporan berhasil disimpan');

}catch(\Exception $e){

DB::rollBack();

\Log::error($e);

return back()->with('error','Terjadi kesalahan saat menyimpan laporan.');

}

}
public function detail($id)
{
    $daily = BuildDailyReport::with([
        'works.rabProcessItem',
        'workers.worker.user',
        'materials'
    ])->findOrFail($id);

    return response()->json($daily);
}
}
