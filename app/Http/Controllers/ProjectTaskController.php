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
            'uploaded_by'  => $file->uploaded_by_name,
            'uploaded_at'  => $file->created_at->format('d-m-Y H:i'),
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
        return response()->json(['message' => 'Task belum punya PIC'], 403);
    }

    if ($task->files()->count() === 0) {
        return response()->json(['message' => 'File belum diupload'], 403);
    }

    if ($task->status !== 'konfirmasi') {
        return response()->json(['message' => 'Status belum konfirmasi'], 403);
    }


    $approvedAt = now();

    $task->update([
        'status'          => 'selesai',
        'approved_at'     => $approvedAt,
        'approved_by' => auth()->id(),
        'reject_note'     => null,
    ]);

    $this->checkAutoNextLevel($task);

        return response()->json([
        'status' => 'ok',
    'approved_by' => auth()->user()->fullname,
    'approved_at' => $approvedAt->format('d-m-Y H:i'),
        'task_status' => 'selesai',
    ]);
}

// protected function checkAutoNextLevel(ProjectTask $task)
// {
//     if (
//         ProjectTask::where('project_id', $task->project_id)
//             ->where('status', '!=', 'selesai')
//             ->exists()
//     ) {
//         return;
//     }

//     ProjectLevel::where([
//         'project_id'  => $task->project_id,
//         'level_order' => 7,
//     ])->update(['is_completed' => true]);

//     ProjectLevel::where([
//         'project_id'  => $task->project_id,
//         'level_order' => 8,
//     ])->update(['is_started' => true]);
// }
protected function checkAutoNextLevel(ProjectTask $task)
{
    $projectId = $task->project_id;

    $activeTaskIds = ProjectTask::where('project_id', $projectId)
        ->where(function ($q) {
            $q->whereNull('parent_task_id') // task utama
              ->orWhereIn('revision_number', function ($sub) {
                  $sub->selectRaw('MAX(revision_number)')
                      ->from('project_tasks')
                      ->whereNotNull('parent_task_id')
                      ->groupBy('parent_task_id');
              });
        })
        ->pluck('revision_number');

    $hasUnfinished = ProjectTask::whereIn('id', $activeTaskIds)
        ->where('status', '!=', 'selesai')
        ->exists();

    if ($hasUnfinished) {
        return;
    }

    ProjectLevel::where([
        'project_id'  => $projectId,
        'level_order' => 7,
    ])->update([
        'is_completed' => true,
    ]);

    ProjectLevel::where([
        'project_id'  => $projectId,
        'level_order' => 8,
    ])->update([
        'is_started' => true,
    ]);
}

public function reject(Request $request, ProjectTask $task)
{
    // ambil task aktif (revisi terakhir)
    $activeTask = ProjectTask::where(function ($q) use ($task) {
            $q->where('id', $task->id)
              ->orWhere('parent_task_id', $task->id);
        })
        ->orderByDesc('revision_number')
        ->first();

    abort_if($activeTask->status !== 'konfirmasi', 403);

    $request->validate([
        'reject_note' => 'required|string|max:1000',
    ]);

    // update task lama (yang direject)
    $activeTask->update([
        'status'        => 'revisi',
        'reject_note'   => $request->reject_note,
        'rejected_by'   => auth()->id(),
        'rejected_at'   => now(),
    ]);

    $parentId = $activeTask->parent_task_id ?? $activeTask->id;

    $revisionNumber = ProjectTask::where(
        'parent_task_id',
        $parentId
    )->max('revision_number') + 1;

    // buat task revisi baru
    $newTask = ProjectTask::create([
        'project_id'      => $activeTask->project_id,
        'offer_id'        => $activeTask->offer_id,
        'parent_task_id'  => $parentId,
        'revision_number' => $revisionNumber,
        'task_name'       => "Revisi {$revisionNumber} - {$activeTask->task_name}",
        'employee_id'     => $activeTask->employee_id,
        'category'        => $activeTask->category,
        'status'          => 'proses',
        'started_at'      => now(),
    ]);

    return response()->json([
        'status' => 'ok',

        // info task lama (yang ditolak)
        'rejected' => [
            'task_id'     => $activeTask->id,
            'rejected_by' => auth()->user()->fullname,
            'rejected_at' => now()->format('d-m-Y H:i'),
            'reject_note' => $activeTask->reject_note,
        ],

        // task revisi baru
        'revision' => [
            'id'           => $newTask->id,
            'name'         => $newTask->task_name,
            'category'     => $newTask->category,
            'category_key' => Str::slug($newTask->category),
            'employee'     => optional($newTask->employee?->user)->fullname,
            'status'       => $newTask->status,
            'revision'     => $newTask->revision_number,
            'reject_note'  => $activeTask->reject_note,
            'rejected_by'  => auth()->user()->fullname,
            'rejected_at'  => now()->format('d-m-Y H:i'),
        ],
    ]);
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
    $task = $file->task;

    // OPTIONAL: authorization
    // abort_unless(auth()->user()->can('delete', $file), 403);

    // hapus fisik
    Storage::disk('public')->delete($file->file_path);

    // hapus DB
    $file->delete();

    // update status task
    if ($task->files()->count() === 0) {
        $task->update([
            'status' => $task->employee_id ? 'proses' : 'tunda',
        ]);
    }

    return response()->json([
        'status' => 'ok',
        'task_status' => $task->status,
    ]);
}



}

