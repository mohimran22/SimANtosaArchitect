<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildDailyReport;
use App\Models\BuildDailyWorker;
use App\Models\BuildDailyWork;
use App\Models\BuildDailyMaterial;
use App\Models\BuildProcessItem;
use App\Models\Worker;
use DB;

class BuildDailyController extends Controller
{
    public function store(Request $request)
{

DB::beginTransaction();

try {

    $report = BuildDailyReport::create([
        'project_id' => $request->project_id,
        'pekerjaan' => $request->project_name,
        'tanggal' => $request->tanggal,
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
                'uraian_manual'=>
                $rab == 'manual'
                ? $request->uraian_manual[$i]
                : null,
                'volume'=>$request->volume[$i] ?? 0,
                'satuan'=>$request->satuan[$i] ?? 0,
                'keterangan'=>$request->ket[$i] ?? null
            ]);
        }
    }

    if($request->bahan){

        foreach($request->bahan as $i=>$bahan){

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

dd($e);

}

}
}
