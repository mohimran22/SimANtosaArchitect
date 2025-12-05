@extends('tablar::page')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold">Detail Proyek: {{ $project->project_name }}</h3>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif


{{-- ============================================================
    SECTION 1: INFORMASI PROYEK
============================================================ --}}
<div class="card mb-4">
    <div class="card-header fw-bold">Informasi Proyek</div>
    <div class="card-body">
        <div class="row mb-2">

            <div class="col-md-4"><strong>Kode Proyek</strong></div>
            <div class="col-md-8">{{ $project->project_code }}</div>

            <div class="col-md-4"><strong>Customer</strong></div>
            <div class="col-md-8">{{ $project->customer->user->fullname }}</div>

            <div class="col-md-4"><strong>Karyawan Utama</strong></div>
            <div class="col-md-8">{{ $project->employee->user->fullname }}</div>

            <div class="col-md-4"><strong>Tanggal Mulai</strong></div>
            <div class="col-md-8">{{ $project->start_date }}</div>

            <div class="col-md-4"><strong>Lokasi</strong></div>
            <div class="col-md-8">{{ $project->project_location }}</div>

        </div>
    </div>
</div>



{{-- ============================================================
    SECTION 2: PROGRESS TAHAPAN PROYEK
============================================================ --}}
@php
    $levels = $project->levels->sortBy('level_order');
    $currentLevel = $project->levels->where('is_started', true)->where('is_completed', false)->first();
    $consultation = $project->consultation ?? null;
@endphp

<div class="card mb-4">
    <div class="card-header fw-bold">Progress Tahapan Proyek</div>
    <div class="card-body">

        <ul class="list-group">
            @foreach($levels as $level)
                <li class="list-group-item">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $level->level_order }}. {{ $level->level_name }}</strong>
                        </div>

                        <div>
                            @if($level->is_completed)
                                <span class="badge bg-success">Selesai</span>
                            @elseif($level->is_started)
                                <span class="badge bg-warning text-dark">Sedang Berjalan</span>
                            @else
                                <span class="badge bg-secondary">Belum Dimulai</span>
                            @endif
                        </div>
                    </div>

                    {{-- Karyawan yang menangani tahapan --}}
                    <div class="mt-2 ms-2">
                        <small class="text-muted">Karyawan bertugas:</small><br>

                        @php
                            $emp = $level->employee?->user?->fullname;
                        @endphp

                        @if($emp)
                            <span class="fw-semibold">{{ $emp }}</span>
                        @else
                            <span class="text-muted fst-italic">Belum ditentukan</span>
                        @endif
                    </div>

                </li>
            @endforeach
        </ul>

    </div>
</div>



{{-- ============================================================
    SECTION 3: FORM KONSULTASI
============================================================ --}}
{{-- <div class="card mb-4">
    <div class="card-header fw-bold">Tahap 1: Konsultasi</div>
    <div class="card-body">

        @if(!$consultation)
            <p class="text-muted">
                Anda belum mengisi Form Konsultasi. Klik tombol di bawah ini untuk memulai.
            </p>

            <a href="{{ route('projects.consultations.create', $project->id) }}"
                class="btn btn-dark btn-lg">
                📝 Isi Form Konsultasi
            </a>

        @else
            <p><strong>Form Konsultasi sudah diisi.</strong></p>

            <a href="{{ route('consultations.show', $consultation->id) }}"
                class="btn btn-success me-2">
                ✔ Lihat Form Konsultasi
            </a>

            <a href="{{ route('consultations.pdf', $consultation->id) }}"
                class="btn btn-outline-primary" target="_blank">
                🖨 Cetak PDF
            </a>
        @endif

    </div>
</div> --}}

{{-- ==================== SECTION: FORM KONSULTASI ==================== --}}
<div class="card mb-4">
    <div class="card-header fw-bold">Tahap 1: Konsultasi</div>
    <div class="card-body">

        @if(!$consultation)
            {{-- Belum isi form konsultasi --}}
            <p class="text-muted">
                Anda belum mengisi Form Konsultasi. Klik tombol di bawah ini untuk memulai.
            </p>

            <a href="{{ route('projects.create', $project->id) }}"
               class="btn btn-dark btn-lg">
                📝 Isi Form Konsultasi
            </a>

        @else
            {{-- Sudah ada data konsultasi --}}
            <p><strong>Form Konsultasi sudah diisi.</strong></p>

            

            <a href="{{ route('consultations.show', $consultation->id) }}"
               class="btn btn-success me-2">
                ✔ Lihat Form Konsultasi
            </a>

            <a href="{{ route('consultations.pdf', $consultation->id) }}"
               class="btn btn-outline-primary" target="_blank">
                🖨 Cetak PDF
            </a>
        @endif

    </div>
</div>




{{-- ============================================================
    SECTION 4: AKSI TAHAP BERIKUTNYA
============================================================ --}}
@if($consultation && $consultation->client_signed)

    @php
        $next = $project->levels
            ->where('is_completed', false)
            ->sortBy('level_order')
            ->skip(1) 
            ->first();
    @endphp

    @if($next)
        <div class="card mb-4">
            <div class="card-header fw-bold">Aksi Tahap Selanjutnya</div>
            <div class="card-body">

                <h5>Tahap berikutnya: <strong>{{ $next->level_name }}</strong></h5>

                <a href="{{ route('project-level.start', $next->id) }}"
                    class="btn btn-primary mt-3">
                    → Mulai Tahap {{ $next->level_name }}
                </a>

            </div>
        </div>
    @endif

@endif


@endsection
