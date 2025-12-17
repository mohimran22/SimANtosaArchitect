<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyRequest;
use App\Models\Survey;
use App\Models\SurveyItem;
use App\Models\SurveyImage;
use App\Models\SurveyDocumentation;
use App\Models\Project;
use App\Models\ProjectLevel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class SurveyController extends Controller
{

    public function store(SurveyRequest $request)
{
    $data = $request->validated();

    $project = Project::with(['employee', 'customer'])
        ->findOrFail($data['project_id']);

    $consultantSigned = $request->boolean('consultant_signed');
    $clientSigned     = $request->boolean('client_signed');

    // Jika salah satu tanda tangan dilakukan → simpan timestamp
    $signedAt = ($consultantSigned || $clientSigned) ? now() : null;
    $documentationPath = null;

    // 1. SIMPAN FOTO DOKUMENTASI


    $survey = Survey::create([
        'project_id'        => $data['project_id'],
        'created_by'        => auth()->id(),
        'survey_date'     => $data['survey_date'] ?? null,
        'survey_time'     => $data['survey_time'] ?? null,
        'contact_name'      => $data['contact_name'] ?? null,
        'contact_phone'     => $data['contact_phone'] ?? null,
        'site_area'    => $data['site_area'] ?? null,
        'building_area'=> $data['building_area'] ?? null,
        'notes'             => $data['notes'] ?? null,
        'consultant_signed' => $consultantSigned,
        'client_signed'     => $clientSigned,
        'signed_at'         => $signedAt,
    ]);

    if ($request->hasFile('documentation')) {
    foreach ($request->file('documentation') as $file) {
        $path = $file->store('surveys/documentations', 'public');

        SurveyDocumentation::create([
            'survey_id' => $survey->id,
            'file_path' => $path
        ]);
    }
}

// 2. SIMPAN FOTO HASIL SURVEI
if ($request->hasFile('result_images')) {
    foreach ($request->file('result_images') as $file) {
        $path = $file->store('surveys/result-images', 'public');

        SurveyImage::create([
            'survey_id' => $survey->id,
            'file_path' => $path
        ]);
    }
}



    // ============================================================
    // 4. SIMPAN ITEM DINAMIS (uraian)
    // ============================================================
    foreach ($data['items'] as $i => $item) {
        SurveyItem::create([
            'survey_id' => $survey->id,
            'order_no'        => $i + 1,
            'description'     => $item['description'],
            'remark'          => $item['remark'] ?? null,
        ]);
    }


    // ============================================================
    // 5. Jika client sudah tanda tangan → nyatakan tahap selesai
    // ============================================================
    if ($clientSigned) {

        // Tandai level konsultasi selesai
        $level = ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 3,
        ])->first();

        if ($level) {
            $level->update([
                'is_completed' => true,
            ]);
            $level->employees()->sync($data['employee_id']);
        }

        // Mulai tahap survei (level 2) otomatis
        ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 4,
        ])->update([
            'is_started' => true,
        ]);
    }

    return redirect()
    ->route('projects.create', ['project_id' => $survey->project_id])
    ->with('success', 'Form konsultasi berhasil disimpan.');
}

    public function pdf(Survey $Survey)
    {
        $Survey->load('items', 'project.customer.user', 'creator');
        $view = view('projects.Surveys.pdf', compact('Survey'))->render();

        $pdf = Pdf::loadHTML($view)->setPaper('a4', 'portrait');
        return $pdf->download("Survey-{$Survey->id}.pdf");
    }

    public function update(Request $request, Survey $survey)
{
    $validated = $request->validate([
        'survey_date' => 'required|date',
        'survey_time' => 'required',
        'employee_id'   => 'required|array',
        'employee_id.*'  => 'uuid',
        'project_id' => 'required|uuid|exists:projects,id',
        'contact_name' => 'nullable|string|max:255',
        'site_area' => 'nullable|string|max:255',
        'building_area' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    // ===========================
    // UPDATE DATA SURVEY
    // ===========================
    $survey->update([
        'survey_date'   => $request->survey_date,
        'survey_time'   => $request->survey_time,
        'notes'         => $request->notes,
        'project_id'    => $request->project_id,
        'contact_name'  => $request->contact_name,
        'site_area'     => $request->site_area,
        'building_area' => $request->building_area,
        'consultant_signed' => $request->has('consultant_signed') ? 1 : 0,
        'client_signed'     => $request->has('client_signed') ? 1 : 0,
    ]);

    $survey->items()->delete();

    if ($request->has('items')) {
        foreach ($request->items as $i => $item) {

            if (
                (!isset($item['description']) || trim($item['description']) === '') &&
                (!isset($item['remark']) || trim($item['remark']) === '')
            ) {
                continue;
            }

            $survey->items()->create([
                'order_no'    => $i + 1,
                'description' => $item['description'] ?? '',
                'remark'      => $item['remark'] ?? '',
            ]);
        }
    }


    // ===========================
    // UPDATE FOTO DOKUMENTASI (MULTIPLE)
    // ===========================
    if ($request->hasFile('documentation')) {

        // hapus semua dokumentasi lama
        foreach ($survey->documentations as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        // simpan baru
        foreach ($request->file('documentation') as $file) {
            $path = $file->store('surveys/documentations', 'public');

            SurveyDocumentation::create([
                'survey_id' => $survey->id,
                'file_path' => $path
            ]);
        }
    }


    // ===========================
    // UPDATE FOTO HASIL SURVEY (MULTIPLE)
    // ===========================
    if ($request->hasFile('result_images')) {

        // hapus lama
        foreach ($survey->surveyimages as $img) {
            Storage::disk('public')->delete($img->file_path);
            $img->delete();
        }

        // simpan baru
        foreach ($request->file('result_images') as $file) {
            $path = $file->store('surveys/result-images', 'public');

            SurveyImage::create([
                'survey_id' => $survey->id,
                'file_path' => $path
            ]);
        }
    }


    // ===========================
    // UPDATE PETUGAS SURVEI DI LEVEL
    // ===========================
    $project = $survey->project;
    $surveyLevel = $project->levels->firstWhere('level_order', 3);

    if ($surveyLevel) {
        $surveyLevel->employees()->sync($request->employee_id);
    }

    return back()->with('success', 'Survey berhasil diperbarui.');
}


}

