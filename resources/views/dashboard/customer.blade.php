@extends('tablar::page')

@push('css')
<style>
    /* Semua class diawali "cd-" supaya tidak bergantung pada Tailwind */
    
    .cd-wrap { padding-top: 5.5rem; padding-bottom: 2rem; }
    .cd-card {
        background: #fff; border-radius: 14px; padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
    .cd-title { font-size: 1.5rem; font-weight: 700; color: #000; margin: 0; }
    .cd-muted { color: #6b7280; }
    .cd-empty { text-align: center; padding: 2.5rem 1rem; margin-top: 1rem; }
    .cd-grid {
        display: grid; gap: 1rem; margin-top: 1rem;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
    .cd-project {
        display: block; width: 100%; text-align: left; border: 0;
        background: #fff; border-radius: 14px; padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        color: inherit; text-decoration: none; cursor: pointer;
        transition: box-shadow .15s;
    }
    .cd-project:hover { box-shadow: 0 0 0 2px #000; color: inherit; }
    .cd-type { font-size: .75rem; color: #6b7280; }
    .cd-name { font-weight: 600; color: #000; margin-top: .25rem; }
    .cd-row { display: flex; justify-content: space-between; font-size: .875rem; margin-top: 1rem; }
    .cd-bar { height: 8px; background: #e5e7eb; border-radius: 999px; margin-top: .5rem; }
    .cd-bar > div { height: 8px; background: #000; border-radius: 999px; }

    /* Modal kurva S: tersembunyi sampai dibuka */
    .cd-modal {
        display: none; position: fixed; inset: 0; z-index: 2000;
        background: rgba(0,0,0,.6); align-items: center; justify-content: center; padding: 1rem;
    }
    .cd-modal.is-open { display: flex; }
    .cd-modal-box {
        background: #fff; border-radius: 14px; width: 100%; max-width: 1000px;
        height: 85vh; display: flex; flex-direction: column; padding: 1.5rem;
    }
    .cd-modal-head { display: flex; justify-content: space-between; align-items: flex-start; }
    .cd-close { background: none; border: 0; font-size: 1.75rem; line-height: 1; cursor: pointer; padding: 0 .5rem; }
    .cd-chart { flex: 1; min-height: 0; margin-top: 1rem; position: relative; }
    .cd-chart-empty {
        display: none; position: absolute; inset: 0;
        align-items: center; justify-content: center; color: #6b7280;
    }
    .cd-chart-empty.is-show { display: flex; }
/* HP */
@media (max-width: 767.98px) {
    .page-body.cd-wrap {
        padding-top: 5rem !important;
        padding-bottom: 2rem;
    }
    .cd-modal { padding: .5rem; }
    .cd-modal-box { height: auto; max-height: 92vh; padding: 1rem; }
    .cd-chart { flex: none; height: 320px; }
}

/* Tablet (portrait & landscape) */
@media (min-width: 768px) and (max-width: 1023.98px) {
    .cd-modal-box {
        max-width: 760px;
        height: auto;
        max-height: 90vh;
        padding: 1.25rem;
    }
    /* maksimal 420px, tapi mengecil otomatis di landscape */
    .cd-chart { flex: none; height: min(420px, 55vh); }
    .cd-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')
<div class="page-body cd-wrap">
    <div class="container-xl">
        {{-- Ucapan selamat datang --}}
        <div class="cd-card">
            <h1 class="cd-title">{{ $greeting }}, {{ $user->fullname ?? $user->name }}</h1>
            <p class="cd-muted" style="margin:.25rem 0 0">
                Selamat datang di Antosa Architect. Pilih proyek untuk melihat perkembangannya.
            </p>
        </div>

        {{-- Daftar proyek --}}
        @if ($projects->isEmpty())
            <div class="cd-card cd-empty cd-muted">Anda belum memiliki proyek.</div>
        @else
            <div class="cd-grid">
                @foreach ($projects as $p)
                    @if ($p->type === \App\Models\Project::TYPE_BUILD)
                        {{-- BUILD: klik -> modal kurva S --}}
                        <button type="button" class="cd-project"
                                onclick='openScurve(@json($p->name), @json($p->progress), @json($p->scurve))'>
                            <div class="cd-type">{{ $p->type_label }}</div>
                            <div class="cd-name">{{ $p->name }}</div>
                            <div class="cd-row">
                                <span class="cd-muted">Progres</span>
                                <strong>{{ $p->progress }}%</strong>
                            </div>
                            <div class="cd-bar"><div style="width: {{ min($p->progress, 100) }}%"></div></div>
                        </button>
                    @else
                        {{-- Design / RAB: klik -> detail proyek --}}
                        <a href="{{ $p->url }}" class="cd-project">
                            <div class="cd-type">{{ $p->type_label }}</div>
                            <div class="cd-name">{{ $p->name }}</div>
                            <div class="cd-row" style="justify-content:flex-start;gap:.35rem">
                                <span class="cd-muted">Status:</span>
                                <strong>{{ $p->status }}</strong>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Modal kurva S (maximize) --}}
<div id="scurveModal" class="cd-modal">
    <div class="cd-modal-box">
        <div class="cd-modal-head">
            <div>
                <h2 id="scurveTitle" style="font-size:1.25rem;font-weight:700;margin:0"></h2>
                <p class="cd-muted" style="margin:.25rem 0 0;font-size:.875rem">
                    Progres keseluruhan: <strong id="scurveProgress" style="color:#000"></strong>%
                </p>
            </div>
            <button type="button" class="cd-close" onclick="closeScurve()" aria-label="Tutup">&times;</button>
        </div>
        <div class="cd-chart">
            <canvas id="scurveChart"></canvas>
            <p id="scurveEmpty" class="cd-chart-empty">Data progres mingguan belum tersedia.</p>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script>
    let scurveChart = null;
    function chartSize() {
        const w = window.innerWidth;
        if (w < 768)  return { bw: 2,   pr: 2, font: 11, ticks: 7,  step: 20 };  // HP
        if (w < 1024) return { bw: 2.5, pr: 3, font: 12, ticks: 10, step: 10 };  // tablet
        return               { bw: 3,   pr: 3, font: 13, ticks: 14, step: 10 };  // desktop
    }
    let lastScurve = null;
    function openScurve(name, progress, data) {
        lastScurve = { name, progress, data }
        document.getElementById('scurveTitle').textContent = name;
        document.getElementById('scurveProgress').textContent = progress;
        document.getElementById('scurveModal').classList.add('is-open');

        const hasData = !!(data && data.labels && data.labels.length);
        document.getElementById('scurveEmpty').classList.toggle('is-show', !hasData);
        if (scurveChart) { scurveChart.destroy(); scurveChart = null; }
        if (!hasData) return;

        const s = chartSize();

        scurveChart = new Chart(document.getElementById('scurveChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Realisasi (%)',
                        data: data.values,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderWidth: s.bw,
                        pointRadius: s.pr,
                        fill: false,
                        tension: 0.2,
                        spanGaps: false,
                    },
                    {
                        label: 'Rencana (%)',
                        data: data.plan || [],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderWidth: s.bw,
                        pointRadius: s.pr,
                        fill: false,
                        tension: 0.2,
                        spanGaps: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                elements: { point: { hitRadius: 24, hoverRadius: 6 } },
                plugins: {
                    legend: {
                        labels: { usePointStyle: true, boxWidth: 8, font: { size: s.font } }
                    }
                },
                scales: {
                    x: { ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: s.ticks, font: { size: s.font } } },
                    y: { min: 0, max: 100, ticks: { stepSize: s.step, font: { size: s.font } } }
                }
            }
        });
    }

    function closeScurve() {
        document.getElementById('scurveModal').classList.remove('is-open');
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeScurve(); });
    document.getElementById('scurveModal').addEventListener('click', e => {
        if (e.target.id === 'scurveModal') closeScurve();
    });
    // di bagian bawah script:
    let resizeTimer;
    let lastBreakpoint = chartSize().ticks;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const now = chartSize().ticks;
            if (now === lastBreakpoint) return;      // hanya address bar bergerak: abaikan
            lastBreakpoint = now;
            if (lastScurve && document.getElementById('scurveModal').classList.contains('is-open')) {
                openScurve(lastScurve.name, lastScurve.progress, lastScurve.data);
            }
        }, 250);
    });
</script>
@endpush