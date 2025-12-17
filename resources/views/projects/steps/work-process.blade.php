@php
$groupedTasks = $project->tasks->groupBy('category');
@endphp

@foreach($groupedTasks as $category => $tasks)

<h5 class="fw-bold mt-4">{{ $category }}</h5>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Tugas</th>
            <th>PIC</th>
            <th>Status</th>
            {{-- <th>Progress</th> --}}
            <th>Dokumen</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
        <tr>
            <td>{{ $task->task_name }}</td>

            <td>
                <form method="POST"
                    action="{{ route('projects.tasks.assign', $task) }}">
                    @csrf
                    <select name="employee_id"
                        class="form-select form-select-md"
                        onchange="this.form.submit()">
                        <option value="">-- Pilih --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                @selected($task->employee_id == $emp->id)>
                                {{ $emp->user->fullname }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </td>

            <td>
                <span class="badge bg-{{ 
                    $task->status == 'done' ? 'success' :
                    ($task->status == 'in_progress' ? 'warning' : 'secondary')
                }}">
                    {{ strtoupper($task->status) }}
                </span>
            </td>

            {{-- <td>{{ $task->progress }}%</td> --}}

            <td>
                <form method="POST"
                    action="{{ route('projects.tasks.upload', $task) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" required>
                    <button class="btn btn-sm btn-dark mt-1">
                        Upload
                    </button>
                </form>
            </td>
            <td>
                {{-- <span class="badge bg-{{ 
                    $task->status == 'done' ? 'success' :
                    ($task->status == 'in_progress' ? 'warning' : 'secondary')
                }}">
                    {{ strtoupper($task->status) }}
                </span> --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endforeach
