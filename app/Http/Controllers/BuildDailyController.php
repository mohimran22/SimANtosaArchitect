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
    $isLibur = $request->has('is_libur');
    
    $request->validate([
        'project_id' => 'required|exists:projects,id',
        'jam_mulai' => 'nullable|date_format:H:i',
        'jam_selesai' => 'nullable|date_format:H:i',
        'total_jam' => 'nullable|numeric|min:0',
        'cuaca' => 'nullable|string|max:50',
        'catatan' => 'nullable|string',
        'mk' => 'nullable|string|max:100',
        'kontraktor_ttd' => 'nullable|string|max:100',
        'worker_id.*' => 'nullable',
        'keahlian.*' => 'nullable|string|max:100',
        'jumlah.*' => 'nullable|numeric|min:0',
        'alat.*' => 'nullable|string|max:150',
        'rab_process_item_id.*' => 'nullable',
        'uraian_manual.*' => 'nullable|string|max:255',
        'daily.volume.*' => 'nullable|numeric|min:0',
        'daily.satuan.*' => 'nullable|string|max:50',
        'ket.*' => 'nullable|string|max:255',
        'bahan.*' => 'nullable|string|max:150',
        'diterima.*' => 'nullable|numeric|min:0',
        'ditolak.*' => 'nullable|numeric|min:0',
        'documentation_tenaga' => 'nullable|array',
        'documentation_tenaga.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        'documentation_pekerjaan' => 'nullable|array',
        'documentation_pekerjaan.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        'documentation_material' => 'nullable|array',
        'documentation_material.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
    foreach($request->rab_process_item_id ?? [] as $i => $rab) {

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

        $lastReport = BuildDailyReport::where('project_id', $project->id)
                        ->orderBy('tanggal', 'desc')
                        ->first();

        $tanggal = $lastReport
                    ? Carbon::parse($lastReport->tanggal)->addDay()
                    : Carbon::parse($project->start_date);

        $exists = BuildDailyReport::where('project_id', $project->id)
                    ->whereDate('tanggal', $tanggal)
                    ->exists();

        if ($exists) {
            return back()
                ->with('error', 'Laporan untuk tanggal ini sudah ada.')
                ->withInput();
        }

        $report = BuildDailyReport::create([
            'project_id' => $request->project_id,
            'pekerjaan' => $project->project_name,
            'tanggal' => $tanggal,
            'is_libur' => $isLibur,
            'jam_mulai' => $isLibur ? null : $request->jam_mulai,
            'jam_selesai' => $isLibur ? null : $request->jam_selesai,
            'total_jam' => $isLibur ? 0 : $request->total_jam,
            'cuaca' => $isLibur ? null : $request->cuaca,
            'catatan' => $isLibur ? 'Tidak ada kegiatan (Hari Libur)' : $request->catatan,
            'mk' => $isLibur ? null : $request->mk,
            'kontraktor_ttd' => $isLibur ? null : $request->kontraktor_ttd,
            'created_by' => auth()->id(),
        ]);

        if ($isLibur) {
            DB::commit();

            return redirect()
                ->route('projects.create', ['project_id' => $report->project_id])
                ->with('success','Laporan hari libur berhasil disimpan');
        }

    if($request->hasFile('documentation_tenaga')){
        foreach($request->file('documentation_tenaga') as $file){

            $path = $file->store('daily/tenaga','public');

            $report->documentations()->create([
                'category' => 'tenaga',
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }
    }

    if($request->hasFile('documentation_pekerjaan')){
        foreach($request->file('documentation_pekerjaan') as $file){

            $path = $file->store('daily/pekerjaan','public');

            $report->documentations()->create([
                'category' => 'pekerjaan',
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }
    }

    if($request->hasFile('documentation_material')){
        foreach($request->file('documentation_material') as $file){

            $path = $file->store('daily/material','public');

            $report->documentations()->create([
                'category' => 'material',
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }
    }

        foreach($request->worker_id ?? [] as $i => $worker){

            if(empty($worker)) continue;

            if($worker == 'manual'){

                BuildDailyWorker::create([
                    'daily_report_id'=>$report->id,
                    'worker_id'=>null,
                    'keahlian'=>$request->keahlian[$i] ?? null,
                    'jumlah'=>$request->jumlah[$i] ?? 0,
                    'alat'=>$request->alat[$i] ?? null
                ]);

            } else {

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

        $volumes = $request->daily['volume'] ?? [];
        $satuans = $request->daily['satuan'] ?? [];

        foreach($request->rab_process_item_id ?? [] as $i => $rab){

            if(
                empty($rab) &&
                empty($request->uraian_manual[$i]) &&
                empty($volumes[$i]) &&
                empty($satuans[$i])
            ){
                continue;
            }

            BuildDailyWork::create([
                'build_daily_report_id' => $report->id,
                'rab_process_item_id'   => $rab != 'manual' ? $rab : null,
                'uraian_manual'         => $rab == 'manual'
                                            ? ($request->uraian_manual[$i] ?? null)
                                            : null,
                'volume'                => $volumes[$i] ?? 0,
                'satuan'                => $satuans[$i] ?? null,
                'keterangan'            => $request->ket[$i] ?? null,
            ]);
        }

        foreach($request->bahan ?? [] as $i => $bahan){

            if(empty($bahan)) continue;

            BuildDailyMaterial::create([
                'daily_report_id'=>$report->id,
                'nama_bahan'=>$bahan,
                'diterima'=>$request->diterima[$i] ?? 0,
                'ditolak'=>$request->ditolak[$i] ?? 0
            ]);
        }

        DB::commit();

        return redirect()
            ->route('projects.create', ['project_id' => $report->project_id])
            ->with('success','Laporan berhasil disimpan');

    } catch(\Exception $e) {

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
        'materials',
        'documentations'
    ])->findOrFail($id);

    return response()->json($daily);
}
public function editPage($id)
{
    $report = BuildDailyReport::with([
        'works.rabProcessItem',
        'workers.worker.user',
        'materials',
        'documentations'
    ])->findOrFail($id);

    return view('projects.edit.daily', compact('report'));
}
public function destroy($id)
{
    $report = BuildDailyReport::findOrFail($id);
    $report->delete();

    return response()->json([
        'success' => true,
        'message' => 'Data berhasil dihapus'
    ]);
}
}
