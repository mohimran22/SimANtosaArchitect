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

        $goToDesain = $request->boolean('go_to_desain');

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

        if (!$goToDesain) {
        return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'Konsultasi berhasil disimpan.');
    }

    return redirect()
    ->route('projects.create', ['project_id' => $survey->project_id])
    ->with('success', 'Form konsultasi berhasil disimpan.');
}



    public function show(Survey $Survey)
    {
        $Survey->load('items', 'project.customer.user', 'creator');
        return view('projects.Surveys.show', compact('Survey'));
    }

    public function pdf(Survey $Survey)
    {
        $Survey->load('items', 'project.customer.user', 'creator');
        $view = view('projects.Surveys.pdf', compact('Survey'))->render();

        $pdf = Pdf::loadHTML($view)->setPaper('a4', 'portrait');
        return $pdf->download("Survey-{$Survey->id}.pdf");
    }
}

