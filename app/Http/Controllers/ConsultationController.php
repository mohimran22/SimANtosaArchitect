<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultationRequest;
use App\Models\Consultation;
use App\Models\ConsultationItem;
use App\Models\Project;
use App\Models\ProjectLevel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ConsultationController extends Controller
{
    public function create(Project $project)
    {
        // eager load project -> customer.user
        $project->load('customer.user');
        return view('projects.consultations.create', compact('project'));
    }

    public function store(ConsultationRequest $request)
{
    $data = $request->validated();

    // ============================================================
    // 1. Ambil project + relasi employee & customer (untuk TTD default)
    // ============================================================
    $project = Project::with(['employee', 'customer'])
        ->findOrFail($data['project_id']);


    // ============================================================
    // 2. Tentukan tanda tangan digital (checkbox signed)
    // ============================================================
    $consultantSigned = $request->boolean('consultant_signed');
    $clientSigned     = $request->boolean('client_signed');

    // Jika salah satu tanda tangan dilakukan → simpan timestamp
    $signedAt = ($consultantSigned || $clientSigned) ? now() : null;
    $documentationPath = null;

    if ($request->hasFile('documentation')) {
        $documentationPath = $request->file('documentation')
            ->store('consultations', 'public');
    }

    $consultation = Consultation::create([
        'project_id'        => $data['project_id'],
        'employee_id'       => $data['employee_id'],
        'created_by'        => auth()->id(),
        'contact_name'      => $data['contact_name'] ?? null,
        'contact_phone'     => $data['contact_phone'] ?? null,
        'site_area'         => $data['site_area'] ?? null,
        'building_area'     => $data['building_area'] ?? null,
        'notes'             => $data['notes'] ?? null,
        'documentation'     => $documentationPath,
        'consultant_signed' => $consultantSigned,
        'client_signed'     => $clientSigned,
        'signed_at'         => $signedAt,
    ]);


    // ============================================================
    // 4. SIMPAN ITEM DINAMIS (uraian)
    // ============================================================
    foreach ($data['items'] as $i => $item) {
        ConsultationItem::create([
            'consultation_id' => $consultation->id,
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
            'level_order' => 1,
        ])->first();

        if ($level) {
            $level->update([
                'employee_id'  => $data['employee_id'], // karyawan yg handle
                'is_completed' => true,
            ]);
        }

        // Mulai tahap survei (level 2) otomatis
        ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 2,
        ])->update([
            'is_started' => true,
        ]);
    }


    return redirect()
        ->route('projects.show', $consultation->project_id)
        ->with('success', 'Form konsultasi berhasil disimpan.');
}



    public function show(Consultation $consultation)
    {
        $consultation->load('items', 'project.customer.user', 'creator');
        return view('projects.consultations.show', compact('consultation'));
    }

    public function pdf(Consultation $consultation)
    {
        $consultation->load('items', 'project.customer.user', 'creator');
        $view = view('projects.consultations.pdf', compact('consultation'))->render();

        $pdf = Pdf::loadHTML($view)->setPaper('a4', 'portrait');
        return $pdf->download("consultation-{$consultation->id}.pdf");
    }
}

