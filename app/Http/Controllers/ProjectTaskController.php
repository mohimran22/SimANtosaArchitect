<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\ProjectLevel;
use App\Models\ProjectTask;
use App\Models\ProjectTaskFile;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProjectTaskController extends Controller
{
public function assign(Request $request, ProjectTask $task)
{
    $request->validate([
        'employee_id' => 'required|uuid|exists:employees,id',
    ]);

    $task->update([
        'employee_id' => $request->employee_id,
        'status'      => 'proses',
        'started_at'  => $task->started_at ?? now(),
    ]);

    return response()->json([
        'status'  => 'ok',
        'message' => 'PIC berhasil ditetapkan',
        'task'    => [
            'employee' => $task->employee->user->fullname,
            'status'   => $task->status,
        ]
    ]);
}


    public function uploadFile(Request $request, ProjectTask $task)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        abort_if(!$task->employee_id, 403, 'PIC belum ditentukan');
        $uploadedFile = $request->file('file');

        $path = $uploadedFile->store('project-tasks', 'public');

        $file = $task->files()->create([
            'file_path'   => $path,
            'file_name'   => $request->file('file')->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
            'is_revision' => $task->status === 'revisi',
        ]);

        $task->update([
            'status' => 'konfirmasi',
        ]);

    return response()->json([
        'status' => 'ok',
        'file'   => [
            'id'   => $file->id,
            'name' => $file->file_name,
            'url'  => route('tasks.files.view', $file),
        ],
        'task_status' => $task->status,
    ]);
    }

//     public function complete(ProjectTask $task)
// {
//     abort_unless(auth()->user()->hasRole('Direktur'), 403);

//     $task->update([
//         'status'       => 'selesai',
//         'completed_at' => now(),
//     ]);

//     // AUTO LANJUT LEVEL JIKA SEMUA TASK SELESAI
//     if ($task->project->tasks()->where('status', '!=', 'selesai')->count() === 0) {

//         ProjectLevel::where([
//             'project_id'  => $task->project_id,
//             'level_order' => 7,
//         ])->update(['is_completed' => true]);

//         ProjectLevel::where([
//             'project_id'  => $task->project_id,
//             'level_order' => 8,
//         ])->update(['is_started' => true]);
//     }

//     return back()->with('success', 'Task diselesaikan.');
// }

public function approve(ProjectTask $task)
{
    if (!$task->employee_id) {
        return back()->with('error', 'Tugas belum memiliki PIC.');
    }

    // ❌ BELUM UPLOAD FILE
    if ($task->files()->count() === 0) {
        return back()->with('error', 'Belum ada dokumen yang diupload.');
    }

    // ❌ STATUS TIDAK VALID
    if ($task->status !== 'konfirmasi') {
        return back()->with('error', 'Tugas belum dalam proses.');
    }

    $task->update([
        'status'          => 'selesai',
        'approved_at'     => now(),
        'reject_note'     => null,
    ]);

    $this->checkAutoNextLevel($task);

    return back()->with('success', 'Tugas disetujui.');
}

protected function checkAutoNextLevel(ProjectTask $task)
{
    if (
        ProjectTask::where('project_id', $task->project_id)
            ->where('status', '!=', 'selesai')
            ->exists()
    ) {
        return;
    }

    ProjectLevel::where([
        'project_id'  => $task->project_id,
        'level_order' => 7,
    ])->update(['is_completed' => true]);

    ProjectLevel::where([
        'project_id'  => $task->project_id,
        'level_order' => 8,
    ])->update(['is_started' => true]);
}


public function reject(Request $request, ProjectTask $task)
{
    abort_if($task->status !== 'konfirmasi', 403);

    $request->validate([
        'reject_note' => 'required|string|max:1000',
    ]);

    // tandai task lama
    $task->update([
        'status'      => 'revisi',
        'reject_note' => $request->reject_note,
    ]);

    // $tasks = ProjectTask::where('project_id', $project->id)
    //     ->orderBy('parent_task_id')
    //     ->orderBy('revision_number')
    //     ->get();

    $revisionNumber = ProjectTask::where('parent_task_id', $task->parent_task_id ?? $task->id)
        ->max('revision_number') + 1;

    // task baru (revisi)
    ProjectTask::create([
        'project_id'       => $task->project_id,
        'offer_id'         => $task->offer_id,
        'parent_task_id'   => $task->parent_task_id ?? $task->id,
        'revision_number'  => $revisionNumber,
        'task_name'        => "Revisi {$revisionNumber} - {$task->task_name}",
        'employee_id'      => $task->employee_id,
        'category'         => $task->category,
        'status'           => 'proses',
        'started_at'       => now(),
    ]);

    return back()->with('success', 'Revisi dibuat.');
}

public function viewFile(ProjectTaskFile $file)
{
    // OPTIONAL: cek hak akses
    // abort_unless(auth()->user()->can('view', $file), 403);
    // abort_unless($file->task->project_id === request()->route('project')->id, 403);

    return Storage::disk('public')->response($file->file_path);
}

public function deleteFile(ProjectTaskFile $file)
{
    // Optional: cek permission
    // abort_unless(auth()->user()->can('update', $file->task), 403);

    Storage::disk('public')->delete($file->file_path);

    $task = $file->task;

    $file->delete();

    // Kalau file dihapus, kembalikan ke tunda approval
    $task->update([
        'approval_status' => 'tunda',
    ]);

    return response()->json([
        'status' => 'ok',
        'file'   => [
            'id'   => $file->id,
            'name' => $file->file_name,
            'url'  => route('tasks.files.delete', $file),
        ],
        'task_status' => $task->status,
    ]);
}


}

