@php
    $isReadOnly = !$canEdit;
$groups = $project->buildItems
    ->sortBy('jobCategory.nama_group')
    ->groupBy(fn($i) => $i->jobCategory?->nama_group ?? 'Tanpa Group');

$no = 1;
@endphp
@php

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
            </div>
        </div>
        {{-- <button id="saveBobotBtn" class="btn btn-success btn-sm">
            Simpan Bobot
        </button> --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Uraian Pekerjaan</th>
                        <th width="120">Satuan</th>
                        <th width="100">Vol</th>
                        <th width="120">Jumlah Harga</th>
                        <th width="160">Bobot (0%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $namaGroup => $itemsGroup)

                        <tr style="background:#cbd5e1;font-weight:700">
                            <td colspan="6">{{ $namaGroup }}</td>
                        </tr>

                        @php
                        $sub = $itemsGroup->groupBy(fn($i) =>
                            $i->jobCategory?->nama_pekerjaan ?? 'Tanpa Kategori'
                        );
                        @endphp

                        @foreach($sub as $namaPekerjaan => $itemsSub)

                            {{-- <tr style="background:#e5e7eb;font-weight:600">
                                <td></td>
                                <td colspan="5">{{ $namaPekerjaan }}</td>
                            </tr> --}}

                            @foreach($itemsSub as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ $item->uraian }}
                                    </td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>{{ $item->volume }}</td>
                                    <td>{{ number_format($item->total,0,',','.') }}</td>
                                    <td width="120">
                                        <input type="number"
                                            step="0.01"
                                            class="form-control bobot-input"
                                            data-id="{{ $item->id }}"
                                            value="{{ $item->bobot_percent }}">
                                    </td>
                                </tr>
                            @endforeach

                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                <tr class="table-warning">
                    <th colspan="4" class="text-end">TOTAL</th>
                    <th id="totalHarga">{{ number_format($project->buildItems->sum('total'),0,',','.') }}</th>
                    <th id="totalBobot">0</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
{{-- <div class="card shadow-sm border-0">
    <div class="card-body px-5 py-4">

        <h4 class="fw-bold mb-3">Kurva S Progress Proyek</h4>

        <canvas id="kurvaSChart" height="120"></canvas>

    </div>
</div> --}}

@push('js')

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
<script>
let saveTimer = null;

function collectBobot() {
    const rows = [];

    document.querySelectorAll('.bobot-input').forEach(el=>{
        rows.push({
            id: el.dataset.id,
            bobot: parseFloat(el.value || 0)
        });
    });

    return rows;
}

function updateTotalDisplay(total) {
    document.getElementById('totalBobot').innerText = total.toFixed(2);
}

function calcTotal() {
    let t = 0;
    document.querySelectorAll('.bobot-input').forEach(el=>{
        t += parseFloat(el.value || 0);
    });
    updateTotalDisplay(t);
    return t;
}

function autosaveBobot() {

    const items = collectBobot();
    const total = calcTotal();

    if (Math.round(total*100)/100 !== 100) {
        document.getElementById('totalBobot').classList.add('text-danger');
        return; // ❌ JANGAN SIMPAN
    }

    document.getElementById('totalBobot').classList.remove('text-danger');

    fetch("{{ route('build-items.update-bobot') }}", {
        method: "POST",
        headers: {
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({items})
    })
    .then(r=>r.json())
    .then(res=>{
        if(!res.ok){
            alert(res.message);
        }
    });
}

document.querySelectorAll('.bobot-input').forEach(el=>{
    el.addEventListener('input', ()=>{
        calcTotal();

        clearTimeout(saveTimer);
        saveTimer = setTimeout(autosaveBobot, 800); // debounce
    });
});

// init total
calcTotal();
</script>
{{-- <script>
document.getElementById('saveBobotBtn').onclick = function(){

    let items = [];

    document.querySelectorAll('.bobot-input').forEach(el => {
        items.push({
            id: el.dataset.id,
            bobot: el.value
        });
    });

    fetch("{{ route('build-items.update-bobot') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({items})
    })
    .then(r=>r.json())
    .then(()=>{
        alert('Bobot tersimpan');
    });
};
</script> --}}
@endpush
