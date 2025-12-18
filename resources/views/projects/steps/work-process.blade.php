@php
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
@php
    $categoryKey = \Illuminate\Support\Str::slug($category);
@endphp

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
    <tbody data-category="{{ $categoryKey }}">
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

                <td class="task-document" data-task="{{ $task->id }}">

                    @if($task->files->count())
                        @foreach($task->files as $file)
                            <div class="doc-cell">

                                <div class="doc-actions">
                                    <a href="{{ route('tasks.files.view', $file) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye"></i> Lihat File
                                    </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-delete-file"
                                                data-file="{{ $file->id }}">
                                            <i class="ti ti-x"></i>
                                        </button>

                                </div>

                                <div class="doc-meta">
                                    <strong>{{ $file->uploader_name }}</strong><br>
                                    {{ $file->created_at->format('d-m-Y H:i') }}
                                </div>

                            </div>
                        @endforeach
                    @else
                        <button class="btn btn-sm btn-dark btn-upload"
                                data-task="{{ $task->id }}">
                            <i class="ti ti-upload"></i> Upload
                        </button>

                        <input type="file"
                            class="d-none upload-input"
                            data-task="{{ $task->id }}">
                    @endif

                </td>
                <td class="task-action" data-task="{{ $task->id }}">

                    @if($task->status === 'selesai')
                        <div class="action-cell">
                            <span class="text-success">
                                <i class="ti ti-check" style="font-size:18px"></i>
                            </span>

                            <div class="action-meta text-muted small">
                                Disetujui oleh <strong>{{ optional($task->approvedBy)->fullname ?? 'System' }}</strong><br>
                                {{ optional($task->approved_at)->format('d-m-Y H:i') }}
                            </div>
                        </div>

                    @else
                        <div class="action-cell">
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-success btn-approve-task"
                                        data-task="{{ $task->id }}">
                                    <i class="ti ti-check"></i>
                                </button>

                                <button class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectTask{{ $task->id }}">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </td>

                <td class="task-status" data-task="{{ $task->id }}">
                    <span class="badge bg-{{ $colors[$task->status] }}">
                        {{ strtoupper($task->status) }}
                    </span>
                </td>
            </tr>

            <div class="modal fade" id="rejectTask{{ $task->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" data-task="{{ $task->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Tolak Hasil Pekerjaan</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <textarea name="reject_note"
                                    class="form-control reject-note"
                                    placeholder="Catatan revisi..."
                                    required></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="button"
                                    class="btn btn-danger btn-submit-reject"
                                    data-task="{{ $task->id }}"
                                    data-bs-dismiss="modal">
                                Tolak & Minta Revisi
                            </button>
                        </div>

                    </div>
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
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-upload');
        if (!btn) return;

        e.preventDefault();

        const taskId = btn.dataset.task;
        const input = document.querySelector(
            `.upload-input[data-task="${taskId}"]`
        );

        if (input) input.click();
    });

    document.addEventListener('change', function (e) {

        const input = e.target.closest('.upload-input');
        if (!input) return;

        const taskId = input.dataset.task;
        const file   = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        fetch(`/tasks/${taskId}/upload`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('Upload gagal');
            return res.json();
        })
        .then(data => {

            /* ===============================
               UPDATE DOKUMEN
            =============================== */
            const docCell = document.querySelector(
                `.task-document[data-task="${taskId}"]`
            );

            if (docCell) {
                docCell.innerHTML = `
                    <div class="doc-cell">
                        <div class="doc-actions">
                            <a href="${data.file.url}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye"></i> Lihat File
                            </a>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-file"
                                    data-file="${data.file.id}">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>

                        <div class="doc-meta">
                            Di-upload oleh <strong>${data.file.uploaded_by ?? 'System'}</strong><br>
                            ${data.file.uploaded_at ?? '-'}
                        </div>
                    </div>

                    <input type="file"
                           class="d-none upload-input"
                           data-task="${taskId}">
                `;
            }

            /* ===============================
               UPDATE STATUS
            =============================== */
            const statusCell = document.querySelector(
                `.task-status[data-task="${taskId}"] span`
            );

            if (statusCell) {
                statusCell.className = 'badge bg-info';
                statusCell.innerText = 'KONFIRMASI';
            }

            /* ===============================
               🔥 UPDATE KETERANGAN (ACTION)
            =============================== */
            const actionCell = document.querySelector(
                `.task-action[data-task="${taskId}"]`
            );

            if (actionCell) {
                actionCell.innerHTML = `
                    <div class="action-cell">
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-success btn-approve-task"
                                    data-task="${taskId}">
                                <i class="ti ti-check"></i>
                            </button>

                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectTask${taskId}">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                `;
            }
        })
        .catch(err => alert(err.message));
    });
});
</script>


<script>
document.addEventListener('click', function (e) {

    const deleteBtn = e.target.closest('.btn-delete-file');
    if (!deleteBtn) return;

    e.preventDefault();

    const fileId = deleteBtn.dataset.file;
    const wrapper = deleteBtn.closest('.doc-cell');
    const taskDocument = deleteBtn.closest('.task-document');

    if (!fileId || !wrapper || !taskDocument) return;

    if (!confirm('Hapus file ini?')) return;

    fetch(`/tasks/files/${fileId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => {
        if (!res.ok) throw new Error();
        return res.json();
    })
    .then(() => {

        wrapper.remove();

        const taskId = taskDocument.dataset.task;
        const badge = document.querySelector(
            `.task-status[data-task="${taskId}"] span`
        );

        if (badge) {
            badge.className = 'badge bg-warning';
            badge.innerText = 'PROSES';
        }

        taskDocument.innerHTML = `
            <button class="btn btn-sm btn-dark btn-upload"
                    data-task="${taskId}">
                <i class="ti ti-upload"></i> Upload
            </button>

            <input type="file"
                   class="d-none upload-input"
                   data-task="${taskId}">
        `;
    })
    .catch(() => alert('Gagal menghapus file'));
});
</script>

<script>
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.btn-approve-task');
    if (!btn) return;

    e.preventDefault();

    const taskId = btn.dataset.task;

    fetch(`/tasks/${taskId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => {
                throw new Error(err.message || 'Gagal menyetujui tugas');
            });
        }
        return res.json(); // ⬅️ DI SINI data didapat
    })
    .then(data => {

        // update status badge
        const badge = document.querySelector(
            `.task-status[data-task="${taskId}"] span`
        );
        if (badge) {
            badge.className = 'badge bg-success';
            badge.innerText = 'SELESAI';
        }

        // update kolom aksi + footnote
        btn.closest('.task-action').innerHTML = `
            <div class="action-cell">
                <span class="text-success">
                    <i class="ti ti-check" style="font-size:18px"></i>
                </span>

                <div class="action-meta text-muted small">
                    Disetujui oleh <strong>${data.approved_by ?? 'System'}</strong><br>
                    ${data.approved_at ?? '-'}
                </div>
            </div>
        `;
    })
    .catch(err => alert(err.message));
});
</script>

<script>
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.btn-submit-reject');
    if (!btn) return;

    const modal  = btn.closest('.modal-content');
    const taskId = btn.dataset.task;
    const noteEl = modal.querySelector('textarea[name="reject_note"]');

    if (!noteEl || !noteEl.value.trim()) {
        alert('Catatan revisi wajib diisi');
        return;
    }

    fetch(`/tasks/${taskId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            reject_note: noteEl.value.trim()
        })
    })
    .then(res => {
        if (!res.ok) throw new Error();
        return res.json();
    })
    .then(data => {
        console.log('RESPONSE:', data);

        // update badge lama
        const oldBadge = document.querySelector(
            `.task-status[data-task="${taskId}"] span`
        );
        if (oldBadge) {
            oldBadge.className = 'badge bg-danger';
            oldBadge.innerText = 'REVISI';
        }

        // append revisi baru
        const tbody = document.querySelector(
            `tbody[data-category="${data.revision.category_key}"]`
        );
        if (tbody) {
            tbody.insertAdjacentHTML(
                'beforeend',
                renderRevisionRow(data.revision)
            );
        }

        noteEl.value = '';
    })
    .catch(err => {
    console.error(err);
    alert('Gagal meminta revisi');
});

});
</script>

<script>
function renderRevisionRow(task) {
    return `
    <tr data-task-row="${task.id}" class="table-warning">
        <td>
            ${task.name}
            <span class="badge bg-danger ms-1">
                Revisi ${task.revision}
            </span>
        </td>

        <td>
            <span class="text-muted">
                ${task.employee ?? '-'}
            </span>
        </td>

        <!-- ⬇️ INI YANG PENTING -->
        <td class="task-document" data-task="${task.id}">
            <button class="btn btn-sm btn-dark btn-upload"
                    data-task="${task.id}">
                <i class="ti ti-upload"></i> Upload
            </button>

            <input type="file"
                   class="d-none upload-input"
                   data-task="${task.id}">
        </td>

        <td class="task-action" data-task="${task.id}">
            <div class="action-cell">
                <div class="action-buttons">
                    <button class="btn btn-sm btn-success btn-approve-task"
                            data-task="${task.id}">
                        <i class="ti ti-check"></i>
                    </button>

                    <button class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectTask${task.id}">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        </td>


        <td class="task-status" data-task="${task.id}">
            <span class="badge bg-warning">
                PROSES
            </span>
        </td>
    </tr>`;
}
</script>
@endpush