@extends('tablar::page')

@section('content')
    <div class="page-body contractor-dashboard">
        <div class="container-xl">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body px-3 py-3 px-md-4 py-md-4">
                    <h2 class="cd-greeting fw-bold mb-1 text-break">
                        {{ $greeting }}, {{ $user->fullname }}
                    </h2>
                    <div class="text-muted">
                        Selamat datang di Antosa Architect. Pilih proyek untuk melihat perkembangannya.
                    </div>
                </div>
            </div>

            @if($projects->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center text-muted py-5">
                        Belum ada proyek yang ditugaskan kepada Anda.
                    </div>
                </div>
            @else
                {{-- HP: 1 kolom | tablet: 2 kolom | layar sangat lebar: 3 kolom --}}
                <div class="row g-3">
                    @foreach($projects as $project)
                        <div class="col-12 col-md-6 col-xxl-4">
                            <a href="{{ $project->url }}"
                               class="cd-card card card-link card-link-pop border-0 shadow-sm h-100 text-reset text-decoration-none">
                                <div class="card-body">
                                    <div class="text-muted small mb-1">{{ $project->type_label }}</div>
                                    <div class="fw-bold mb-3 mb-md-4 text-break">{{ $project->name }}</div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Progres</span>
                                        <span class="fw-bold">{{ $project->progress ?? 0 }}%</span>
                                    </div>
                                    <div class="progress progress-sm"
                                         role="progressbar"
                                         aria-valuenow="{{ $project->progress ?? 0 }}"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-dark"
                                             style="width: {{ min($project->progress ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
<style>
    /*
     * Jarak atas supaya kartu salam tidak tertutup topbar.
     * Kalau masih menempel/terlalu jauh, ubah nilai --cd-top-offset saja.
     */
    .contractor-dashboard {
        --cd-top-offset: 5rem;
        padding-top: var(--cd-top-offset);
    }

    /* judul mengecil di layar kecil, tidak meluber */
    .contractor-dashboard .cd-greeting {
        font-size: clamp(1.15rem, 1rem + 1.6vw, 1.75rem);
        line-height: 1.3;
    }

    /* area sentuh nyaman di HP + efek tekan */
    .contractor-dashboard .cd-card {
        min-height: 7.5rem;
        transition: transform .12s ease, box-shadow .12s ease;
    }
    .contractor-dashboard .cd-card:active {
        transform: scale(.99);
    }
    @media (hover: hover) {
        .contractor-dashboard .cd-card:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12) !important;
        }
    }

    @media (max-width: 575.98px) {
        .contractor-dashboard { --cd-top-offset: 5.5rem; }
        .contractor-dashboard .container-xl { padding-left: .75rem; padding-right: .75rem; }
    }
</style>
@endsection