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
    public function store(ConsultationRequest $request)
{
    $data = $request->validated();

    $project = Project::with(['employee', 'customer'])
        ->findOrFail($data['project_id']);

    $consultantSigned = $request->boolean('consultant_signed');
    $clientSigned     = $request->boolean('client_signed');

    $signedAt = ($consultantSigned || $clientSigned) ? now() : null;

    $consultation = Consultation::create([
        'project_id'        => $data['project_id'],
        'employee_id'       => $data['employee_id'],
        'created_by'        => auth()->id(),
        'contact_name'      => $data['contact_name'] ?? null,
        'contact_phone'     => $data['contact_phone'] ?? null,
        'site_area'         => $data['site_area'] ?? null,
        'building_area'     => $data['building_area'] ?? null,
        'notes'             => $data['notes'] ?? null,
        'consultant_signed' => $consultantSigned,
        'client_signed'     => $clientSigned,
        'signed_at'         => $signedAt,
    ]);

    if ($request->hasFile('documentation')) {
        foreach ($request->file('documentation') as $file) {
            $consultation->documentations()->create([
                'file_path' => $file->store('consultations/documentations', 'public')
            ]);
        }
    }

    foreach ($data['items'] as $i => $item) {
        ConsultationItem::create([
            'consultation_id' => $consultation->id,
            'order_no'        => $i + 1,
            'description'     => $item['description'],
            'remark'          => $item['remark'] ?? null,
        ]);
    }

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
    ->route('projects.create', ['project_id' => $consultation->project_id])
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

    public function update(Request $request, Consultation $consultation)
{
    $consultation->update($request->only([
        'contact_name',
        'contact_phone',
        'employee_id',
        'site_area',
        'building_area',
        'consultant_signed',
        'client_signed',
        'notes',
    ]));

    $consultation->items()->delete(); // hapus item lama

    if ($request->has('items')) {
        foreach ($request->items as $i => $item) {

            if (
                (!isset($item['description']) || trim($item['description']) === '') &&
                (!isset($item['remark']) || trim($item['remark']) === '')
            ) {
                continue;
            }

            $consultation->items()->create([
                'order_no'    => $i + 1,
                'description' => $item['description'] ?? '',
                'remark'      => $item['remark'] ?? '',
            ]);
        }
    }

    if ($request->hasFile('documentation')) {

        if ($consultation->documentation) {
            Storage::delete('public/'.$consultation->documentation);
        }

        $path = $request->file('documentation')->store('consultations', 'public');

        $consultation->update(['documentation' => $path]);
    }

    return back()->with('success', 'Data konsultasi berhasil diperbarui!');
}
}

