@php
    $isReadOnly = !$canEdit;
$groups = $project->buildItems
    ->sortBy('jobCategory.nama_group')
    ->groupBy(fn($i) => $i->jobCategory?->nama_group ?? 'Tanpa Group');

$no = 1;
$weekCount = count($project->week_labels);
$colsFixed   = 6;
$colsNormal  = 3;
$colsJustek  = 3;
$colsPerWeek = $colsNormal + $colsJustek; // = 6
$colsTotal   = 3;

$weekCount = count($project->week_labels);

$totalCols = $colsFixed + ($weekCount * $colsPerWeek) + $colsTotal;

$plans = $project->weeklyPlans
    ->keyBy('week_no');
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
                            <col class="just-col" data-week="{{ $w['week_no'] }}" style="width:90px;">
                            <col class="just-col" data-week="{{ $w['week_no'] }}" style="width:90px;">
                            <col class="just-col" data-week="{{ $w['week_no'] }}" style="width:110px;">
                        @endforeach
                        <col style="width:140px;">
                        <col style="width:140px;">
                        <col style="width:140px;">
                    </colgroup>
                <thead class="table-light">
                    <tr>
                        <th rowspan="2" class="align-middle text-center">No</th>
                        <th rowspan="2" class="align-middle">Uraian Pekerjaan</th>

                        <th colspan="4" class="text-center">TERKONTRAK</th>

                        @foreach($project->week_labels as $w)
                            <th colspan="3" class="text-center">
                                M{{ $w['week_no'] }}
                                <button type="button"
                                    class="btn btn-sm btn-outline-dark ms-1 btn-just-toggle"
                                    data-week="{{ $w['week_no'] }}">
                                    +
                                </button>
                            </th>

                            <th colspan="3"
                                class="text-center bg-warning-subtle just-head"
                                data-week="{{ $w['week_no'] }}">
                                Justek Volume M{{ $w['week_no'] }}
                            </th>
                        @endforeach

                        <th colspan="3" class="text-center">Total</th>
                    </tr>

                    <tr>
                        <th class="align-middle">Satuan</th>
                        <th class="align-middle text-center">Vol</th>
                        <th class="align-middle">Jumlah Harga</th>
                        <th class="align-middle text-center">Bobot (%)</th>

                        @foreach($project->week_labels as $w)
                            <th class="align-middle text-center">Vol</th>
                            <th class="text-center">Progres<br>(%)</th>
                            <th class="text-center">Bobot<br>(%)</th>

                            <th class="just-col" data-week="{{ $w['week_no'] }}">Kurang</th>
                            <th class="just-col" data-week="{{ $w['week_no'] }}">Tambah</th>
                            <th class="just-col" data-week="{{ $w['week_no'] }}">Pek.Baru</th>
                        @endforeach

                        <th class="text-center">Vol<br>Pelaksanaan</th>
                        <th class="text-center">Nilai<br>Kontrak</th>
                        <th class="text-center">Nilai<br>Pelaksanaan</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($groups as $namaGroup => $itemsGroup)
                        @php
                            $cleanName = preg_replace('/^HARGA SATUAN\s*/i', '', $namaGroup);
                        @endphp
                        <tr style="background:#c4c4c4;font-weight:700">
                            <td colspan="{{ $totalCols }}">
                                {{ $cleanName }}
                            </td>
                        </tr>

                        @php
                        $sub = $itemsGroup->groupBy(fn($i) =>
                            $i->jobCategory?->nama_pekerjaan ?? 'Tanpa Kategori'
                        );
                        @endphp

                        @foreach($sub as $namaPekerjaan => $itemsSub)
                            @foreach($itemsSub as $item)
                            @php
                                $volKontrak = $item->volume;

                                $volTerpakai = $item->weeklyProgresses->sum('volume');

                                $isFull = $volTerpakai >= $volKontrak;
                            @endphp
                                <tr
                                    data-item-id="{{ $item->id }}"
                                    data-item-vol="{{ $item->volume }}"
                                    data-item-bobot="{{ $item->bobot_percent }}"
                                    data-full="{{ $isFull ? 1 : 0 }}">                              
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
                                                    data-last="{{ $prog->volume ?? 0 }}"
                                                    value="{{ $prog->volume ?? '' }}"
                                                    {{ $isFull ? 'disabled' : '' }}>
                                            </td>
                                            <td class="week-progress"
                                                data-week="{{ $w['week_no'] }}"
                                                id="prog-{{ $item->id }}-{{ $w['week_no'] }}">
                                            </td>
                                            <td class="week-bobot"
                                                data-week="{{ $w['week_no'] }}"
                                                id="bobot-{{ $item->id }}-{{ $w['week_no'] }}">
                                            </td>
                                            <td class="just-col" data-week="{{ $w['week_no'] }}">
                                                <input class="form-control just-kurang"
                                                        placeholder="Kurang"
                                                        data-item="{{ $item->id }}"
                                                        data-week="{{ $w['week_no'] }}"></td>
                                            <td class="just-col" data-week="{{ $w['week_no'] }}">
                                                <input class="form-control just-tambah"
                                                        placeholder="Tambah"
                                                        data-item="{{ $item->id }}"
                                                        data-week="{{ $w['week_no'] }}"></td>
                                            <td class="just-col" data-week="{{ $w['week_no'] }}">
                                                <input class="form-control just-baru"
                                                        placeholder="Pek.Baru"
                                                        data-item="{{ $item->id }}"
                                                        data-week="{{ $w['week_no'] }}"></td>
                                        @endforeach
                                        <td class="total-pelaksanaan"
                                            data-item="{{ $item->id }}"
                                            data-vol-kontrak="{{ $item->volume }}">
                                            {{ number_format($item->volume, 3) }}
                                        </td>
                                        <td>{{ number_format($item->total,0,',','.') }}</td>
                                        <td class="nilai-pelaksanaan">0</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th colspan="4" class="text-end">
                            Total Pekerjaan Kumulatif (%)
                        </th>
                        <th>{{ number_format($project->buildItems->sum('total'),0,',','.') }}</th>
                        <th class="totalBobotKontrak">0</th>

                        @foreach($project->week_labels as $w)
                            <th></th>
                            <th></th>
                            <th>
                                <input type="number"
                                    step="0.001"
                                    class="form-control plan-bobot"
                                    data-week="{{ $w['week_no'] }}"
                                    value="{{ $plans[$w['week_no']]->bobot_percent ?? 0 }}">
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        @endforeach
                        <th></th>
                        <th></th>
                        <th id="grand-total-pelaksanaan">0</th>
                    </tr>

                    <tr class="table-warning">
                        <th colspan="4" class="text-end">
                            Realisasi kumulatif kemajuan Pekerjaan
                        </th>
                        <th>{{ number_format($project->buildItems->sum('total'),0,',','.') }}</th>
                        <th class="totalBobotKontrak">0</th>

                        @foreach($project->week_labels as $w)
                            <th></th>
                            <th></th>
                            <th id="sum-bobot-{{ $w['week_no'] }}">0</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        @endforeach
                        <th></th>
                        <th></th>
                        <th id="grand-total-pelaksanaan">0</th>
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
document.querySelectorAll('.plan-bobot')
.forEach(el => {
    el.addEventListener('input', e => {
        const target = e.target;

        autosavePlan(
            target.dataset.week,
            parseFloat(target.value) || 0
        );
        updateKurvaPlanRealtime();
    });
});
    const ctx = document.getElementById('kurvaSChart');
    if(!ctx || typeof Chart === 'undefined') {
        console.error('Chart.js belum load');
        return;
    }

    const dataAwal = @json($project->getKurvaSData());
    const dataPlan = @json($project->getKurvaRencanaData());

    window.kurvaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dataAwal.map(d=>'Minggu '+d.week),
            datasets:[
            {
                label:'Realisasi (%)',
                data:dataAwal.map(d=>d.progress),
                tension:0.3
            },
            {
                label:'Rencana (%)',
                // data:getPlanKumulatif(),
                data:dataPlan.map(d=>d.progress),
                tension:0.3,
                // borderDash:[5,5]
            }]
        },
        options:{
            animation:false,
            scales:{ y:{ beginAtZero:true, max:100 }}
        }
    });
    updateKurvaPlanRealtime();
});

function getPlanKumulatif() {

    let weekCount = {{ $weekCount }};
    let jalan = 0;
    let data = [];

    for (let w=1; w<=weekCount; w++) {

        const el = document.querySelector(
            `.plan-bobot[data-week="${w}"]`
        );

        const val = el ? (parseFloat(el.value) || 0) : 0;

        jalan += val;
        data.push(jalan);
    }

    return data;
}


function updateKurvaPlanRealtime() {

    if(!window.kurvaChart) return;

    window.kurvaChart.data.datasets[1].data =
        getPlanKumulatif();

    window.kurvaChart.update();
    validatePlanTotal();
}
function rebuildKurvaFromTable() {

    let weekCount = {{ $weekCount }};
    let kumulatif = [];
    let jalan = 0;

    for (let w = 1; w <= weekCount; w++) {

        let sumBobot = 0;

        document.querySelectorAll(`.week-bobot[data-week="${w}"]`)
        .forEach(el => {
            sumBobot += parseFloat(el.innerText) || 0;
        });

        jalan += sumBobot;
        kumulatif.push(jalan);
    }

    return kumulatif;
}

function updateKurvaChartRealtime() {

    if(!window.kurvaChart) return;

    window.kurvaChart.data.datasets[0].data =
        rebuildKurvaFromTable();

    window.kurvaChart.update();
}
function validatePlanTotal() {

    let t = 0;

    document.querySelectorAll('.plan-bobot')
    .forEach(el => t += parseFloat(el.value||0));
    if (Math.abs(t - 100) > 0.01) {
        console.warn("Total rencana ≠ 100%");
    }
}
function autosavePlan(week, val)
{
    fetch("{{ route('build-weekly-plan.update') }}", {
        method: "POST",
        headers: {
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":
                document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({
            project_id: "{{ $project->id }}",
            week_no: week,
            bobot: val
        })
    })
    .then(r=>{
        if(!r.ok) console.warn("Plan autosave failed");
    });
}
validatePlanTotal();
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
    document.querySelectorAll('.totalBobotKontrak')
        .forEach(el => {
            el.innerText = total.toFixed(2);
        });
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

    if (Math.abs(total - 100) > 0.01) {
        document.querySelectorAll('.totalBobotKontrak')
            .forEach(el => el.classList.add('text-danger'));
        return; // stop autosave
    } else {
        document.querySelectorAll('.totalBobotKontrak')
            .forEach(el => el.classList.remove('text-danger'));
    }

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

</script>
<script>
document.addEventListener('click', function(e) {

    const btn = e.target.closest('.btn-just-toggle');
    if (!btn) return;

    const week = btn.dataset.week;

    const targets = document.querySelectorAll(
        `.just-col[data-week="${week}"],
         .just-head[data-week="${week}"]`
    );

    const cols = document.querySelectorAll(
        `col.just-col[data-week="${week}"]`
    );

    const isHidden = targets[0].classList.contains('just-hidden');

    targets.forEach(el => el.classList.toggle('just-hidden', !isHidden));
    cols.forEach(el => el.classList.toggle('just-hidden', !isHidden));

    btn.textContent = isHidden ? '−' : '+';
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.just-col, .just-head, col.just-col')
        .forEach(el => el.classList.add('just-hidden'));
});
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

let autosaveTimer = {};

function autosaveWeek(item, week)
{
    const key = item + '-' + week;

    clearTimeout(autosaveTimer[key]);

    autosaveTimer[key] = setTimeout(() => {

        const input = document.querySelector(
            `.week-vol[data-item="${item}"][data-week="${week}"]`
        );

        const oldVal = input.dataset.last || 0;
        const vol = parseFloat(input.value) || 0;

        fetch("{{ route('build-weekly.update') }}", {
            method:"POST",
            headers:{
                "Content-Type":"application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({
                item_id: item,
                week_no: week,
                volume: vol
            })
        })
        .then(async r => {

            const data = await r.json();

            if (!r.ok) {
                input.value = oldVal; // rollback
                showToast(data.error || 'Volume melebihi kontrak');
                throw new Error("422");
            }
            recalcWeek(item, week, 
                input.closest('tr').dataset.itemVol,
                input.closest('tr').dataset.itemBobot
            );

            hitungFooter();
            updateKurvaChartRealtime();

            return data;
        })
        .then(res => {

            input.dataset.last = vol;

            if (res.full) {
                lockItemRow(item);
            }

        })
        .catch(e => {
            console.log("autosave rejected:", e.message);
        });

    }, 400);
}

function hitungFooter() {

    let weekCount = {{ $weekCount }};

    for (let w=1; w<=weekCount; w++) {

        let sumBobot = 0;

        document.querySelectorAll(`.week-bobot[data-week="${w}"]`)
            .forEach(el => {
                sumBobot += parseFloat(el.innerText || 0);
            });

        const bobotCell = document.getElementById(`sum-bobot-${w}`);
        if (bobotCell) {
            bobotCell.innerText = sumBobot.toFixed(2);
        }
    }
}
function lockItemRow(itemId)
{
    document.querySelectorAll(
        `.week-vol[data-item="${itemId}"]`
    ).forEach(el => {
        el.disabled = true;
        el.classList.add('bg-light');
    });
}
hitungFooter();
updateKurvaChartRealtime();
    });
function hitungTotalPelaksanaan() {
    let grandTotalVolume = 0;
    let grandTotalHarga = 0;

    document.querySelectorAll('tr[data-item-id]').forEach(row => {
        const volKontrak = parseFloat(row.dataset.itemVol) || 0;

        const hargaKontrakText = row.children[4].innerText
            .replace(/\./g, '')
            .replace(',', '.');

        const hargaKontrak = parseFloat(hargaKontrakText) || 0;

        let totalTambah = 0;
        let totalKurang = 0;
        let totalBaru = 0;

        row.querySelectorAll('.just-tambah').forEach(i => {
            totalTambah += parseFloat(i.value) || 0;
        });

        row.querySelectorAll('.just-kurang').forEach(i => {
            totalKurang += parseFloat(i.value) || 0;
        });

        row.querySelectorAll('.just-baru').forEach(i => {
            totalBaru += parseFloat(i.value) || 0;
        });

        const volPelaksanaan =
            volKontrak + totalTambah - totalKurang + totalBaru;

        let hargaPelaksanaan = 0;
        if (volKontrak > 0) {
            hargaPelaksanaan =
                (volPelaksanaan / volKontrak) * hargaKontrak;
        }

        // isi kolom total
const cells = row.querySelectorAll('td');

const colVolPelaksanaan   = cells[cells.length - 3];
const colNilaiKontrak     = cells[cells.length - 2]; // biarkan
const colNilaiPelaksanaan = cells[cells.length - 1]; // update di sini

colVolPelaksanaan.textContent = volPelaksanaan.toFixed(3);

colNilaiPelaksanaan.textContent =
    Math.round(hargaPelaksanaan).toLocaleString('id-ID');

        grandTotalVolume += volPelaksanaan;
        grandTotalHarga += hargaPelaksanaan;
    });

    const grandVol = document.getElementById('grand-total-pelaksanaan');
    if (grandVol) {
        grandVol.textContent = grandTotalVolume.toFixed(3);
    }
}

document.addEventListener('input', e => {
    if (
        e.target.classList.contains('just-kurang') ||
        e.target.classList.contains('just-tambah') ||
        e.target.classList.contains('just-baru')
    ) {
        hitungTotalPelaksanaan();
    }
});

document.addEventListener('DOMContentLoaded', hitungTotalPelaksanaan);


document.addEventListener('input', e => {
    if (
        e.target.classList.contains('just-kurang') ||
        e.target.classList.contains('just-tambah') ||
        e.target.classList.contains('just-baru')
    ) {
        hitungTotalPelaksanaan();
    }
});

document.addEventListener('input', e => {
    if (
        e.target.classList.contains('just-kurang') ||
        e.target.classList.contains('just-tambah') ||
        e.target.classList.contains('just-baru')
    ) {
        hitungTotalPelaksanaan();
    }
});
</script>

@endpush
@push('css')
<style>
.just-hidden {
    visibility: collapse !important;
}
col.just-hidden {
    visibility: collapse;
}
</style>
@endpush