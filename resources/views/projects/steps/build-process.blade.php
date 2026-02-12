@php
    $isReadOnly = !$canEdit;
@endphp

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body px-5 py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Tahap Pelaksanaan Proyek</h3>

            <div class="d-flex gap-2">

                {{-- @can('input laporan harian') --}}
                @can('tambah data rab')
                <a href="{{ route('build.daily.create', $project->id) }}"
                   class="btn btn-sm btn-dark">
                    <i class="ti ti-calendar"></i>
                    Input Laporan Harian
                </a>
                @endcan

                @can('tambah data rab')
                <a href="{{ route('build.weekly.create', $project->id) }}"
                   class="btn btn-sm btn-dark">
                    <i class="ti ti-chart-line"></i>
                    Input Laporan Mingguan
                </a>
                @endcan

            </div>
        </div>

        {{-- =========================
            TABEL ITEM PEKERJAAN
        ========================== --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Uraian Pekerjaan</th>
                        <th width="120">Volume</th>
                        <th width="100">Satuan</th>
                        <th width="120">Bobot (%)</th>
                        <th width="160">Rencana Minggu</th>
                        <th width="140">Progress Aktual</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($project->buildItems as $i => $item)

                    @php
                        $progress = $item->total_progress;
                    @endphp

                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $item->uraian }}</td>
                        <td>{{ $item->volume }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>{{ number_format($item->bobot_percent,2) }}</td>
                        <td>
                            M{{ $item->plan_week_start }}
                            —
                            M{{ $item->plan_week_end }}
                        </td>
                        <td>

                            <div class="progress">
                                <div class="progress-bar"
                                     style="width: {{ $progress }}%">
                                    {{ number_format($progress,1) }}%
                                </div>
                            </div>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada item pelaksanaan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body px-5 py-4">

        <h4 class="fw-bold mb-3">Kurva S Progress Proyek</h4>

        <canvas id="kurvaSChart" height="120"></canvas>

    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const data = @json($project->getKurvaSData());

new Chart(document.getElementById('kurvaSChart'), {
    type: 'line',
    data: {
        labels: data.map(d => 'Minggu ' + d.week),
        datasets: [{
            label: 'Progress (%)',
            data: data.map(d => d.progress),
            tension: 0.3
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>
@endpush