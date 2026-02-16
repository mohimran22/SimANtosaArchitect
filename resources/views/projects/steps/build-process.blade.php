@php
    $isReadOnly = !$canEdit;
$groups = $project->buildItems
    ->sortBy('jobCategory.nama_group')
    ->groupBy(fn($i) => $i->jobCategory?->nama_group ?? 'Tanpa Group');

$no = 1;
$weekCount = count($project->week_labels);
$totalCols = 6 + ($weekCount * 3);
@endphp

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Tahap Pelaksanaan Proyek
    </div>
    <div class="card-body">
        
            <table width="100%" style="margin-bottom:20px; margin-left:20px;">
            <tr>
                <td width="20%">PEKERJAAN</td>
                <td>: {{ $project->project_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>LOKASI</td>
                <td>: {{ $project->city->name ?? '-' }}</td>
            </tr>
            </table>
        
        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                @can('tambah data rab')
                <a href="{{ route('build.daily.create', $project->id) }}"
                   class="btn btn-sm btn-dark">
                    <i class="ti ti-calendar"></i>
                    Input Laporan Harian
                </a>
                @endcan
            </div>
        </div> --}}

        <div class="table-responsive">
            <table class="table table-bordered align-middle" style="table-layout: fixed;">
                    <colgroup>
                        <col style="width:60px;">   
                        <col style="width:250px;">  
                        <col style="width:80px;">   
                        <col style="width:80px;">   
                        <col style="width:140px;">  
                        <col style="width:120px;">  
                        
                        @foreach($project->week_labels as $w)
                            <col style="width:100px;"> 
                            <col style="width:100px;">   
                            <col style="width:100px;">   
                        @endforeach
                    </colgroup>
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle text-center">No</th>
                    <th rowspan="2" class="align-middle">Uraian Pekerjaan</th>

                    <th colspan="3" class="text-center">TERKONTRAK</th>

                    <th rowspan="2" class="align-middle">Bobot (%)</th>

                    @foreach($project->week_labels as $w)
                        <th colspan="3" class="text-center">
                            M{{ $w['week_no'] }}
                        </th>
                    @endforeach
                </tr>

                <tr>
                    <th class="align-middle">Satuan</th>
                    <th class="align-middle text-center">Vol</th>
                    <th class="align-middle">Jumlah Harga</th>

                    @foreach($project->week_labels as $w)
                        <th class="align-middle text-center">Vol</th>
                        <th class="text-center">Progres<br>(%)</th>
                        <th class="text-center">Bobot<br>(%)</th>
                    @endforeach
                </tr>
            </thead>
                <tbody>
                    @foreach($groups as $namaGroup => $itemsGroup)

                        <tr style="background:#cbd5e1;font-weight:700">
                            <td colspan="{{ $totalCols }}">
                                {{ $namaGroup }}
                            </td>
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
                                <tr
                                    data-item-vol="{{ $item->volume }}"
                                    data-item-bobot="{{ $item->bobot_percent }}"
                                >
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ $item->uraian }}
                                    </td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>{{ $item->volume }}</td>
                                    <td>{{ number_format($item->total,0,',','.') }}</td>
                                    <td width="120">
                                        <input type="number"
                                            step="0.001"
                                            class="form-control bobot-input"
                                            data-id="{{ $item->id }}"
                                            value="{{ $item->bobot_percent }}"
                                            @if($project->bobot_locked) disabled @endif>
                                    </td>
                                        @foreach($project->week_labels as $w)
                                            @php
                                                $prog = $item->weeklyProgresses
                                                    ->firstWhere('week_no', $w['week_no']);
                                            @endphp
                                            <td>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control week-vol"
                                                    data-item="{{ $item->id }}"
                                                    data-week="{{ $w['week_no'] }}"
                                                    value="{{ $prog->volume ?? 0 }}">
                                            </td>
                                            <td class="week-progress"
                                                data-week="{{ $w['week_no'] }}"
                                                id="prog-{{ $item->id }}-{{ $w['week_no'] }}">
                                            </td>

                                            <td class="week-bobot"
                                                data-week="{{ $w['week_no'] }}"
                                                id="bobot-{{ $item->id }}-{{ $w['week_no'] }}">
                                            </td>
                                        @endforeach
                                </tr>
                            @endforeach

                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                <tr class="table-warning">
                    <th colspan="4" class="text-end">Realisai Pekerjaan</th>

                    <th id="totalHarga">
                        {{ number_format($project->buildItems->sum('total'),0,',','.') }}
                    </th>

                    <th id="totalBobotKontrak">0</th>

                    @foreach($project->week_labels as $w)
                        <th id="sum-vol-{{ $w['week_no'] }}">0</th>
                        <th id="sum-prog-{{ $w['week_no'] }}">0</th>
                        <th id="sum-bobot-{{ $w['week_no'] }}">0</th>
                    @endforeach
                </tr>
                </tfoot>
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

<script>
document.addEventListener('DOMContentLoaded', () => {

    const ctx = document.getElementById('kurvaSChart');
    if(!ctx || typeof Chart === 'undefined') {
        console.error('Chart.js belum load');
        return;
    }

    const dataAwal = @json($project->getKurvaSData());

    window.kurvaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dataAwal.map(d=>'Minggu '+d.week),
            datasets:[{
                label:'Progress (%)',
                data:dataAwal.map(d=>d.progress),
                tension:0.3
            }]
        },
        options:{
            animation:false,
            scales:{ y:{ beginAtZero:true, max:100 }}
        }
    });

});

function rebuildKurvaFromTable() {

    let weekCount = {{ $weekCount }};
    let kumulatif = [];
    let jalan = 0;

    for (let w = 1; w <= weekCount; w++) {

        let sumBobot = 0;

        document.querySelectorAll(`.week-bobot[data-week="${w}"]`)
        .forEach(el => {
            sumBobot += parseFloat(el.innerText || 0);
        });

        jalan += sumBobot;
        kumulatif.push(jalan);
    }

    return kumulatif;
}

function updateKurvaChartRealtime() {
    if(!window.kurvaChart) return;
    const dataBaru = rebuildKurvaFromTable();
    window.kurvaChart.data.datasets[0].data = dataBaru;
    window.kurvaChart.update();
}
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
    document.getElementById('totalBobotKontrak').innerText = total.toFixed(2);
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
        document.getElementById('totalBobotKontrak').classList.add('text-danger');
        return; 
    }

    document.getElementById('totalBobotKontrak').classList.remove('text-danger');

    fetch("{{ route('build-items.update-bobot') }}", {
        method: "POST",
        headers: {
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({
            project_id: "{{ $project->id }}",
            items
        })
    })
    .then(r=>{
        if(!r.ok) throw new Error('HTTP '+r.status);
        return r.json();
    })
    .then(res=>{
        if(res.locked){
            document.querySelectorAll('.bobot-input')
                .forEach(el=>el.disabled = true);
            alert('Bobot sudah 100% dan dikunci');
        }
    })
    .catch(err=>{
        console.error("autosaveBobot gagal:", err);
    });
}

document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.bobot-input').forEach(el=>{
        el.addEventListener('input', ()=>{
            console.log("INPUT CHANGED");
            calcTotal();
            clearTimeout(saveTimer);
            saveTimer = setTimeout(autosaveBobot, 800);
        });
    });

    calcTotal();
});

// init total
// calcTotal();
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    function recalcWeek(itemId, weekNo, itemVol, itemBobot)
    {
        itemVol = parseFloat(itemVol || 0);
        itemBobot = parseFloat(itemBobot || 0);

        const el = document.querySelector(
            `.week-vol[data-item="${itemId}"][data-week="${weekNo}"]`
        );

        if (!el) return;

        const vol = parseFloat(el.value || 0);

        const progress = itemVol > 0
            ? (vol / itemVol) * 100
            : 0;

        const bobot = progress * itemBobot / 100;

        document.getElementById(`prog-${itemId}-${weekNo}`)
            .innerText = progress.toFixed(2);

        document.getElementById(`bobot-${itemId}-${weekNo}`)
            .innerText = bobot.toFixed(3);
    }

    document.querySelectorAll('.week-vol').forEach(el => {

        el.addEventListener('input', e => {

            const tr = e.target.closest('tr');

            const item = e.target.dataset.item;
            const week = e.target.dataset.week;

            const itemVol = tr.dataset.itemVol || 0;
            const itemBobot = tr.dataset.itemBobot || 0;

            recalcWeek(item, week, itemVol, itemBobot);
            autosaveWeek(item, week);
            hitungFooter();
            updateKurvaChartRealtime();
        });
    });

    document.querySelectorAll('.week-vol').forEach(el => {

        const tr = el.closest('tr');

        recalcWeek(
            el.dataset.item,
            el.dataset.week,
            tr.dataset.itemVol || 0,
            tr.dataset.itemBobot || 0
        );
    });

    hitungFooter();
    function autosaveWeek(item, week)
{
    const vol = parseFloat(document.querySelector(
        `.week-vol[data-item="${item}"][data-week="${week}"]`
    ).value || 0);

    fetch("{{ route('build-weekly.update') }}", {
        method: "POST",
        headers: {
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({
            item_id: item,
            week_no: week,
            volume: vol
        })
    });
}

function hitungFooter() {

    let weekCount = {{ $weekCount }};

    for (let w=1; w<=weekCount; w++) {

        let sumVol = 0;
        let sumProg = 0;
        let sumBobot = 0;

        // total volume minggu
        document.querySelectorAll(`.week-vol[data-week="${w}"]`)
        .forEach(el => {
            sumVol += parseFloat(el.value || 0);
        });

        // total progress minggu
        document.querySelectorAll(`.week-progress[data-week="${w}"]`)
        .forEach(el => {
            sumProg += parseFloat(el.innerText || 0);
        });

        // total bobot minggu
        document.querySelectorAll(`.week-bobot[data-week="${w}"]`)
        .forEach(el => {
            sumBobot += parseFloat(el.innerText || 0);
        });

        document.getElementById(`sum-vol-${w}`).innerText = sumVol.toFixed(2);
        document.getElementById(`sum-prog-${w}`).innerText = sumProg.toFixed(2);
        document.getElementById(`sum-bobot-${w}`).innerText = sumBobot.toFixed(2);
    }
}
hitungFooter();
updateKurvaChartRealtime();
    });


</script>
@endpush
