@php
// $groupedTasks = $project->tasks->groupBy('category');
$tasks = \App\Models\ProjectTask::where('project_id', $project->id)
    ->orderBy('parent_task_id')
    ->orderBy('revision_number')
    ->get();
$colors = [
    'tunda'       => 'secondary',
    'proses'      => 'warning',
    'konfirmasi'  => 'info',
    'revisi'      => 'danger',
    'selesai'     => 'success',
];
@endphp

@foreach($tasks->groupBy('category') as $category => $tasks)

<h3 class="fw-bold mt-4">{{ $category }}</h3>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Uraian Pekerjaan</th>
            <th>PIC</th>
            {{-- <th>Progress</th> --}}
            <th>Dokumen</th>
            <th>Keterangan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
            <tr>
                <td>
                    {{ $task->task_name }}
                    @if($task->revision_number > 0)
                        <span class="badge bg-danger ms-1">
                            Revisi {{ $task->revision_number }}
                        </span>
                    @endif
                </td>


                <td>
                            <select class="form-select assign-employee"
                                    data-task="{{ $task->id }}">
                                <option value="">-- Pilih --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        @selected($task->employee_id == $emp->id)>
                                        {{ $emp->user->fullname }}
                                    </option>
                                @endforeach
                            </select>
                </td>



                {{-- <td>{{ $task->progress }}%</td> --}}
                <td>
                    @if($task->files->count() === 0)

                        <input type="file"
                            class="upload-file"
                            data-task="{{ $task->id }}"
                            hidden>

                        <button type="button"
                                class="btn btn-sm btn-dark btn-upload"
                                data-task="{{ $task->id }}">
                            <i class="ti ti-upload"></i> Upload
                        </button>

                        <div class="file-result mt-1"></div>

                    @else
                        @foreach($task->files as $file)
                            <a href="{{ route('tasks.files.view', $file) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary mb-1">
                                <i class="ti ti-eye"></i> Lihat File
                            </a>
                        @endforeach

                        <input type="file"
                            class="upload-file"
                            data-task="{{ $task->id }}"
                            hidden>
                        <button type="button"
                                class="btn btn-sm btn-warning btn-upload"
                                data-task="{{ $task->id }}"
                                title="Ganti File">
                            <i class="ti ti-pencil"></i>
                        </button>
                                    <a href="{{ route('tasks.files.delete', $task) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </a>
                    @endif
                </td>

                <td class="text-center"> 


                    {{-- Keterangan --}}
                                    @if(
                                        $task->approval_status === 'pending' &&
                                        $task->employee_id &&
                                        $task->files->count() > 0 &&
                                        $task->status === 'konfirmasi'
                                    )
                        {{-- APPROVE --}}
                        <form method="POST"
                            action="{{ route('tasks.approve', $task) }}"
                            class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success"
                                    title="Setujui hasil pekerjaan">
                                <i class="ti ti-check"></i>
                            </button>
                        </form>

                        {{-- REJECT --}}
                        <button class="btn btn-sm btn-danger"
                                title="Tolak & minta revisi"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectTask{{ $task->id }}">
                            <i class="ti ti-x"></i>
                        </button>
                    @endif

                    {{-- APPROVED --}}
                    {{-- @if($task->approval_status === 'approved')
                        <span class="badge bg-success"
                            title="Disetujui">
                            <i class="ti ti-circle-check"></i>
                        </span>
                    @endif --}}

                    {{-- REJECTED --}}
                    {{-- @if($task->approval_status === 'rejected')
                        <span class="badge bg-danger"
                            title="Revisi diminta">
                            <i class="ti ti-circle-x"></i>
                        </span>

                        @if($task->reject_note)
                            <div class="text-muted small mt-1">
                                {{ $task->reject_note }}
                            </div>
                        @endif
                    @endif --}}
                </td>
                <td class="task-status">
                    <span class="badge bg-secondary">
                        {{ strtoupper($task->status) }}
                    </span>
                </td>
            </tr>

            <div class="modal fade" id="rejectTask{{ $task->id }}">
            <div class="modal-dialog">
                <form method="POST"
                    action="{{ route('tasks.reject', $task) }}">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Tolak Hasil Pekerjaan</h5>
                        </div>

                        <div class="modal-body">
                            <textarea name="reject_note"
                                    class="form-control"
                                    required
                                    placeholder="Catatan revisi..."></textarea>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button class="btn btn-danger">
                                Tolak & Minta Revisi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        @endforeach
    </tbody>
</table>


@endforeach

@push('js')
<script>
document.querySelectorAll('.assign-employee').forEach(select => {
    select.addEventListener('change', function () {
        const taskId = this.dataset.task;
        const employeeId = this.value;

        fetch(`/tasks/${taskId}/assign`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ employee_id: employeeId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok') {
                this.closest('tr')
                    .querySelector('.task-status')
                    .innerHTML = badge('proses');
            }
        });
    });
});

function badge(status) {
    const map = {
        proses: 'warning',
        konfirmasi: 'info',
        selesai: 'success',
        revisi: 'danger'
    };
    return `<span class="badge bg-${map[status]}">${status.toUpperCase()}</span>`;
}
</script>


<script>
document.querySelectorAll('.btn-upload').forEach(btn => {
    btn.addEventListener('click', function () {
        const td = this.closest('td');
        td.querySelector('.upload-file').click();
    });
});

document.querySelectorAll('.upload-file').forEach(input => {
    input.addEventListener('change', function () {
        const taskId = this.dataset.task;
        const formData = new FormData();
        formData.append('file', this.files[0]);

        fetch(`/tasks/${taskId}/upload`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok') {
                const td = this.closest('td');

                td.innerHTML = `
                    <a href="${data.file.url}"
                       target="_blank"
                       class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-eye"></i> Lihat File
                    </a>
                    <button class="btn btn-sm btn-warning ms-1 btn-upload"
                            data-task="${taskId}">
                        <i class="ti ti-pencil"></i>
                    </button>
                `;

                this.closest('tr')
                    .querySelector('.task-status')
                    .innerHTML = badge('konfirmasi');
            }
        });
    });
});

// function badge(status) {
//     const map = {
//         tunda: 'secondary',
//         proses: 'warning',
//         konfirmasi: 'info',
//         revisi: 'danger',
//         selesai: 'success',
//     };
//     return `<span class="badge bg-${map[status]}">${status.toUpperCase()}</span>`;
// }
</script>

@endpush


