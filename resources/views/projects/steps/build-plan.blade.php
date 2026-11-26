@php
    $isContractor = auth()->user()?->isContractorContext() ?? false;
    // kontraktor mengisi progres pelaksanaan, jadi tidak read-only
    $isReadOnly = !$canEdit && !$isContractor;

    $weekCount = count($project->week_labels);          // total (termasuk Justek), dipakai Kurva S
    $planWeeks = collect($project->week_labels)->where('is_justek', false)->values();
    $planWeekCount = $planWeeks->count();               // hanya minggu kontrak

    $colsFixed   = 6;
    $colsPerWeek = 1;
    $totalCols   = $colsFixed + ($planWeekCount * $colsPerWeek);

    $plans = $project->weeklyPlans->keyBy('week_no');
@endphp

    @unless($isContractor)
    <x-collapse-card title="Tahap Perencanaan Proyek" target="proyek-build-plan-body">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="row mb-2">
                            <div class="col-4 col-md-2 fw-semibold">
                                PEKERJAAN
                            </div>
                            <div class="col-8 col-md-10">
                                : {{ $project->project_name ?? '-' }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 col-md-2 fw-semibold">
                                LOKASI
                            </div>
                            <div class="col-8 col-md-10">
                                : {{ $project->city->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-md-3 d-flex gap-2">
                        @if(!$isReadOnly)
                            {{-- <form action="{{ route('projects.sync-build-plan', $project->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Update form kemajuan pekerjaan dengan RAB terbaru?')">

                                @csrf

                                <button type="submit" class="btn btn-secondary">
                                    <i class="ti ti-refresh"></i>
                                    Impor dari Excel
                                </button>

                            </form> --}}
                                 <form action="{{ route('build-plan.import', $project->id) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    id="importPlanForm"
                                    class="d-inline d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="file" name="file" accept=".xlsx,.xls" class="form-control form-control-sm" required style="max-width:220px">
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        <i class="ti ti-upload"></i> Import Excel
                                    </button>
                                </form>
                        @endif
                    </div>
                    <div class="table-scroll-top">
                        <div></div>
                    </div>
                    <div class="table-plan">
                        <table id="buildPlanTable" class="table card-table table-vcenter text-nowrap">
                            <colgroup>
                                <col style="width:50px"> 
                                <col style="width:260px"> 
                                <col style="width:80px">  

                                @foreach($planWeeks as $w)
                                    <col style="width:95px">
                                @endforeach
                            </colgroup>
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">No</th>
                                    <th rowspan="2" class="align-middle text-center">Uraian Pekerjaan</th>

                                    <th rowspan="2" class="align-middle text-center">BOBOT</th>
                                    @foreach($planWeeks as $w)
                                        <th class="text-center" data-week="{{ $w['week_no'] }}">
                                            <div>M{{ $w['week_no'] }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>

                                    @foreach($planWeeks as $w)
                                        <th class="align-middle text-center">Bobot<br>(%)</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">
                                        Total Pekerjaan (%)
                                    </th>
                                    <th class="text-center">
                                        {{ number_format($buildPlans->sum('bobot_percent'),2) }}
                                    </th>

                                    @foreach($planWeeks as $w)
                                        <th class="week-foot text-center fw-bold"
                                            data-week="{{ $w['week_no'] }}"
                                            id="total-plan-{{ $w['week_no'] }}">
                                            0
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">
                                        Kumulatif (%)
                                    </th>
                                    <th class="text-center">
                                        {{ number_format($buildPlans->sum('bobot_percent'),2) }}
                                    </th>
                                    @foreach($planWeeks as $w)
                                        <th class="week-foot text-center fw-bold"
                                            id="kumulatif-plan-{{ $w['week_no'] }}">
                                            0
                                        </th>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if(!$isReadOnly)
                    <div class="modal fade"
                        id="importPlanModal"
                        tabindex="-1"
                        aria-labelledby="importPlanModalLabel"
                        aria-hidden="true">

                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">

                                <form action="{{ route('build-plan.import', $project->id) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    id="importPlanForm">

                                    @csrf

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importPlanModalLabel">
                                            <i class="ti ti-file-spreadsheet me-1"></i>
                                            Import Build Plan dari Excel
                                        </h5>

                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        {{-- FILE --}}
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <label class="form-label">
                                                    File Excel
                                                </label>

                                                <input type="file"
                                                    name="file"
                                                    id="importPlanFile"
                                                    class="form-control"
                                                    accept=".xlsx,.xls"
                                                    required>
                                            </div>

                                            <div class="col-md-4 d-flex align-items-end">
                                                <div id="importFileInfo"
                                                    class="text-muted small d-none">
                                                    <i class="ti ti-file-spreadsheet"></i>
                                                    <span id="importFileName"></span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- LOADING --}}
                                        <div id="importPreviewLoading"
                                            class="text-center py-5 d-none">

                                            <div class="spinner-border mb-3"
                                                role="status">
                                            </div>

                                            <div>
                                                Membaca file Excel...
                                            </div>
                                        </div>

                                        {{-- ERROR --}}
                                        <div id="importPreviewError"
                                            class="alert alert-danger d-none">
                                        </div>

                                        {{-- SUMMARY --}}
                                        <div id="importPreviewSummary"
                                            class="row g-2 mb-3 d-none">

                                            <div class="col-md-3">
                                                <div class="card card-sm">
                                                    <div class="card-body">
                                                        <div class="text-muted">
                                                            Lantai
                                                        </div>
                                                        <div class="h3 mb-0"
                                                            id="previewFloorCount">
                                                            0
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card card-sm">
                                                    <div class="card-body">
                                                        <div class="text-muted">
                                                            Kategori
                                                        </div>
                                                        <div class="h3 mb-0"
                                                            id="previewCategoryCount">
                                                            0
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card card-sm">
                                                    <div class="card-body">
                                                        <div class="text-muted">
                                                            Pekerjaan
                                                        </div>
                                                        <div class="h3 mb-0"
                                                            id="previewItemCount">
                                                            0
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card card-sm">
                                                    <div class="card-body">
                                                        <div class="text-muted">
                                                            Total Bobot
                                                        </div>
                                                        <div class="h3 mb-0"
                                                            id="previewTotalWeight">
                                                            0%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- PREVIEW TABLE --}}
                                        <div id="importPreviewWrapper"
                                            class="d-none">

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">
                                                    Preview Data
                                                </h5>

                                                <span class="text-muted small">
                                                    Data belum disimpan ke database
                                                </span>
                                            </div>

                                            <div class="table-responsive"
                                                style="max-height:500px; overflow:auto;">

                                                <table class="table table-sm table-bordered table-vcenter"
                                                    id="importPreviewTable">

                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th>Uraian Pekerjaan</th>
                                                            <th>Floor</th>
                                                            <th>Kategori</th>
                                                            <th class="text-end">Bobot</th>

                                                            @for($i = 1; $i <= 25; $i++)
                                                                <th class="text-center">
                                                                    M{{ $i }}
                                                                </th>
                                                            @endfor
                                                        </tr>
                                                    </thead>

                                                    <tbody id="importPreviewBody">
                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <button type="submit"
                                                class="btn btn-primary"
                                                id="btnImportPlan"
                                                disabled>
                                            <i class="ti ti-upload me-1"></i>
                                            Import Data
                                        </button>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    @endif
            </div>
    </x-collapse-card>
    @endunless
    <div id="build-process">
        @include('projects.steps.build-process')
    </div>
    @vite('resources/js/pages/justekrab.js')
    @vite('resources/js/pages/justekedit.js')
@push('js')
@if($isContractor)
{{-- Kontraktor: tabel perencanaan tidak ditampilkan, jadi Kurva S dibangun dari data server --}}
<script>
    const TOTAL_WEEKS = {{ $weekCount }};

    // realisasi kumulatif per minggu dari input volume di tabel pelaksanaan
    // (rumus sama dengan Project::getKurvaSData); null setelah minggu terakhir yang berprogres
    function rebuildKurvaFromTable() {
        const perWeek = {};
        let lastWeek = 0;

        document.querySelectorAll('tr[data-item-id]').forEach(tr => {
            const vol   = parseFloat(tr.dataset.itemVol)   || 0;
            const bobot = parseFloat(tr.dataset.itemBobot) || 0;

            tr.querySelectorAll('.week-vol').forEach(inp => {
                const w = parseInt(inp.dataset.week, 10);
                const v = parseFloat(inp.value) || 0;

                if (vol > 0 && v > 0) {
                    perWeek[w] = (perWeek[w] || 0) + (v / vol) * bobot;
                    if (w > lastWeek) lastWeek = w;
                }
            });
        });

        const out = [];
        let run = 0;
        for (let w = 1; w <= TOTAL_WEEKS; w++) {
            run += perWeek[w] || 0;
            out.push(w <= lastWeek ? Math.min(Number(run.toFixed(2)), 100) : null);
        }
        return out;
    }

    function initKurvaChart() {
        const ctx = document.getElementById('kurvaSChart');
        if (!ctx || typeof Chart === 'undefined') return;

        const rencana = @json(collect($project->getKurvaRencanaData())->pluck('progress')->values());
        const labels = [];
        for (let i = 1; i <= TOTAL_WEEKS; i++) labels.push('M' + i);

        window.kurvaChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Realisasi (%)', data: rebuildKurvaFromTable(), tension: 0.3, spanGaps: false },
                    { label: 'Rencana (%)',   data: rencana,                 tension: 0.3, spanGaps: false }
                ]
            },
            options: {
                animation: false,
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                elements: { point: { hitRadius: 24, hoverRadius: 6 } },
                scales: {
                    x: { ticks: { autoSkip: true, maxTicksLimit: 5 } },
                    y: { beginAtZero: true, max: 100 }
                },
                plugins: { legend: { position: window.innerWidth < 576 ? 'bottom' : 'top' } }
            }
        });
    }

    // dipanggil oleh script tahap pelaksanaan setiap volume diubah
    function updateKurvaChartRealtime() {
        if (!window.kurvaChart) return;
        window.kurvaChart.data.datasets[0].data = rebuildKurvaFromTable();
        window.kurvaChart.update();
    }

    document.addEventListener('DOMContentLoaded', initKurvaChart);
</script>
@else
<script>

    const buildPlanTable = document.querySelector("#buildPlanTable");

    const freezeCounts = 3;
    function applyFreeze() {
        if (!buildPlanTable) return;
            const firstColgroup = buildPlanTable.querySelector('colgroup:first-of-type');
            const allColgroups = buildPlanTable.querySelectorAll('colgroup');
            if (allColgroups.length > 1 && firstColgroup) {
                for (let i = 1; i < allColgroups.length; i++) {
                    const secondCols = allColgroups[i].querySelectorAll('col');
                    const firstCols = firstColgroup.querySelectorAll('col');
                    firstCols.forEach((col, idx) => {
                        if (secondCols[idx]) {
                            secondCols[idx].style.width = col.style.width;
                        }
                    });
                }
            }
            const cols =
                buildPlanTable.querySelectorAll(
                    'colgroup:last-of-type col'
                );
            buildPlanTable.style.width = "";
            buildPlanTable.style.minWidth = "";

            const totalColWidth = Array.from(cols).reduce((sum, col) => {
                return sum + (parseFloat(col.style.width) || 0);
            }, 0);

            buildPlanTable.style.width = totalColWidth + "px";
            buildPlanTable.style.minWidth = totalColWidth + "px";

            buildPlanTable.querySelectorAll(".sticky-col").forEach(cell => {
                cell.classList.remove("sticky-col");
                cell.style.left = "";
                cell.style.width = "";
            });

        const isMobile = window.innerWidth < 576;
        if (!isMobile) {
            const offsets = [];
            let left = 0;
            for (let i = 0; i < freezeCounts; i++) {
                offsets.push(left);
                left += Math.round(parseFloat(getComputedStyle(cols[i]).width));
            }

            const rowspanMap = [];
            buildPlanTable.querySelectorAll("tr").forEach(row => {
                let colIndex = 0;
                Array.from(row.children).forEach(cell => {
                    while (rowspanMap[colIndex] && rowspanMap[colIndex] > 0) {
                        rowspanMap[colIndex]--;
                        colIndex++;
                    }

                    const colspan = parseInt(cell.getAttribute("colspan")) || 1;
                    const rowspan = parseInt(cell.getAttribute("rowspan")) || 1;

                    if (colIndex < freezeCounts || cell.classList.contains('freeze-col')) {
                        cell.classList.add("sticky-col");
                        cell.style.left = Math.round(offsets[colIndex]) + "px";

                        let width = 0;
                        for (let i = 0; i < colspan && (colIndex + i) < freezeCounts; i++) {
                            width += Math.round(parseFloat(getComputedStyle(cols[colIndex + i]).width));
                        }
                        cell.style.width = width + "px";
                    }

                    if (rowspan > 1) {
                        for (let i = 0; i < colspan; i++) {
                            rowspanMap[colIndex + i] = rowspan - 1;
                        }
                    }
                    colIndex += colspan;
                });
            });

            // row-category & row-uraian
            buildPlanTable.querySelectorAll("tr.row-category, tr.row-uraian").forEach(row => {
                const cells = row.querySelectorAll("td");

                cells.forEach(cell => {
                    cell.classList.remove("sticky-col");
                    cell.style.left = "";
                    cell.style.width = "";
                    cell.style.zIndex = "";
                    cell.style.background = "";
                });

                const firstCell = cells[0];
                if (!firstCell) return;

                const width = Array.from(cols).slice(0, freezeCounts).reduce((sum, c) => {
                    return sum + (parseFloat(c.style.width) || 0); // pakai style.width bukan getComputedStyle
                }, 0);

                firstCell.classList.add("sticky-col");
                firstCell.style.left = "0px";
                firstCell.style.width = width + "px";
                firstCell.style.zIndex = "20";
                firstCell.style.background = "#fff";
            });
        }
        const HEADER_ROW_HEIGHT = 45;

        const headerRows = buildPlanTable.querySelectorAll("thead tr");
        const firstRow = headerRows[0];
        const secondRow = headerRows[1];

        Array.from(firstRow.children).forEach(th => {
            th.style.position = "sticky";
            th.style.top = "0px";
            th.style.zIndex = th.classList.contains("sticky-col") ? "155" : "102";
            th.style.background = "#f8f9fa";
        });

        Array.from(secondRow.children).forEach(th => {
            th.style.position = "sticky";
            th.style.top = HEADER_ROW_HEIGHT + "px";
            th.style.zIndex = th.classList.contains("sticky-col") ? "155" : "101";
            th.style.background = "#f8f9fa";
        });
    }
</script>
<script>
    const TOTAL_WEEKS = {{ $weekCount }};
    const PLAN_WEEKS  = {{ $planWeekCount }};
    document.addEventListener('DOMContentLoaded', () => {
        $(document).on('focus', '.week-plan', function(){
            this.dataset.old = this.value || 0;
        });

        $(document).on('input', '.week-plan', function(){

            const target = this;

            const itemId = target.dataset.item;
            const week = target.dataset.week;

            const persen = parseFloat(target.value) || 0;

            const isValid = validateItemPlan(itemId);

            if (!isValid) {
                target.value = target.dataset.old || '';
                return;
            }

            // Simpan nilai plan APA ADANYA
            autosavePlan(itemId, week, persen);

            // Hitung total plan berdasarkan nilai plan langsung
            calculateFooterPlan();

            // Update kurva berdasarkan nilai plan langsung
            updateKurvaPlanRealtime();
        });
    });

    function initKurvaChart(){
        const ctx = document.getElementById('kurvaSChart');

        if(!ctx || typeof Chart === 'undefined') {

            console.error('Chart.js belum load');
            return;

        }
        const dataAwal = @json($project->getKurvaSData() ?? []);
        const labels = [];
        for (let i = 1; i <= {{ $weekCount }}; i++) { labels.push('M' + i); }

        // minggu terakhir di mana progres kumulatif benar-benar naik
        let lastWeek = 0, prev = 0;
        dataAwal.forEach(d => {
            if (d.progress > prev + 0.0001) lastWeek = d.week;
            prev = d.progress;
        });
        const realisasi = [];
        for (let i = 1; i <= {{ $weekCount }}; i++) {
            const found = dataAwal.find(d => d.week == i);
            realisasi.push(found && i <= lastWeek ? found.progress : null);
        }

        window.kurvaChart = new Chart(ctx, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [

                    {
                        label:'Realisasi (%)',
                        data: realisasi,
                        tension:0.3
                    },

                    {
                        label:'Rencana (%)',
                        data:getPlanKumulatif(),
                        tension:0.3
                    }

                ]

            },

            options:{
                animation:false,
                responsive:true,
                maintainAspectRatio:false,

                scales:{
                    x:{
                        ticks:{
                            autoSkip:true,
                            maxTicksLimit:5
                        }
                    },

                    y:{
                        beginAtZero:true,
                        max:100
                    }
                },
                plugins:{
                    legend:{
                        position: window.innerWidth < 576 ? 'bottom' : 'top'
                    }
                },
            }

        });
    }
    function getWeeklyPlanTotal(week) {

        let total = 0;

        document
            .querySelectorAll(`.week-plan[data-week="${week}"]`)
            .forEach(el => {

                const persen = parseFloat(el.value) || 0;

                total += persen;
            });

        return total;
    }

    function getPlanKumulatif(){
        return rebuildKurvaPlanFromTable();
    }

    function calculateFooterPlan(){

        let weekCount = PLAN_WEEKS;

        let kumulatif = 0;

        for(let w = 1; w <= weekCount; w++){

            let total = getWeeklyPlanTotal(w);

            kumulatif += total;

            // mingguan
            const mingguanEl =
                document.getElementById(`total-plan-${w}`);

            if(mingguanEl){

                mingguanEl.innerText =
                    total.toFixed(3);

            }

            // kumulatif
            const kumulatifEl =
                document.getElementById(`kumulatif-plan-${w}`);

            if(kumulatifEl){

                kumulatifEl.innerText =
                    kumulatif.toFixed(3);

            }

        }

        validatePlanTotal();

    }

function updateKurvaPlanRealtime(){

    if(!window.kurvaChart) return;

    window.kurvaChart.data.datasets[1].data =
        rebuildKurvaPlanFromTable();

    window.kurvaChart.update();
}
    function rebuildKurvaPlanFromTable(){
        let jalan = 0;
        const data = [];

        for (let w = 1; w <= TOTAL_WEEKS; w++) {
            if (w > PLAN_WEEKS) {
                data.push(null);          // minggu Justek: garis berhenti
                continue;
            }
            jalan += getWeeklyPlanTotal(w);
            data.push(jalan);
        }
        return data;
    }
    function updateKurvaChartRealtime(){
        if(!window.kurvaChart) return;
        window.kurvaChart.data.datasets[0].data = rebuildKurvaFromTable();
        window.kurvaChart.update();
    }
    function validatePlanTotal(){
        let total = 0;
        let weekCount = PLAN_WEEKS;
        for(let w = 1; w <= weekCount; w++){
            total += getWeeklyPlanTotal(w);
        }
        console.log('Total Plan:', total);
        if(Math.abs(total - 100) > 0.01){
            console.warn("Total rencana ≠ 100%");
        }
    }
    function autosavePlan(itemId, week, val){
        console.log({
            item: itemId,
            week: week,
            val: val
        });
        fetch("{{ route('build-week-plan.update') }}", {

            method: "POST",

            headers: {

                "Content-Type":"application/json",

                "X-CSRF-TOKEN":
                    document.querySelector(
                        'meta[name=csrf-token]'
                    ).content

            },

            body: JSON.stringify({

                project_id: "{{ $project->id }}",
                build_plan_id: itemId,
                week_no: week,
                plan_percent: val

            })

        })
        .then(async r => {

            const res = await r.json();

            console.log(res);

            if(!r.ok){

                console.error(res);

            }

        })
        .catch(err => {

            console.error(err);

        });

    }
    function validateItemPlan(itemId)
    {
        let totalPersen = 0;
        let bobotItem = 0;

        document.querySelectorAll(`.week-plan[data-item="${itemId}"]`)
            .forEach(el => {
                totalPersen += parseFloat(el.value) || 0;
                bobotItem = parseFloat(el.dataset.bobot) || 0; // sama utk semua baris item ini
            });

        // Total plan mingguan untuk 1 item tidak boleh melebihi bobot item itu sendiri
        if (totalPersen > bobotItem + 0.001) {
            alert(
                `Total plan item melebihi bobot maksimal (${bobotItem.toFixed(3)}%). ` +
                `Sekarang: ${totalPersen.toFixed(3)}%`
            );
            return false;
        }

        return true;
    }
</script>
<script>
    let weeks = @json($planWeeks);

    let columns = [
        {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false,
        },
        {
            data:'item_name',
            name:'item_name',
        },
        {
            data:'bobot_format',
            name:'bobot_format',
        }
    ];

    weeks.forEach(function(w){
        columns.push({
            data:'week_values.'+w.week_no,
            width:"95px",
            className:"text-center",
            defaultContent:'',
            orderable:false,
            render:function(data,type,row){
                const persen = parseFloat(data) || 0;
                return `
                <div class="week-plan-wrapper">
                    <input type="text" step="any" class="form-control week-plan"
                        data-item="${row.id}"
                        data-week="${w.week_no}"
                        data-bobot="${row.bobot_percent}"
                        value="${persen}"
                        placeholder="%"
                    >
                </div>
                `;
            }
        });
    });
    function alphaIndex(n) {
        let result = '';

        while (n >= 0) {
            result = String.fromCharCode((n % 26) + 65) + result;
            n = Math.floor(n / 26) - 1;
        }

        return result;
    }
    document.addEventListener('DOMContentLoaded', function () {
        const topScroll = document.querySelector('.table-scroll-top');
        const topContent = topScroll.querySelector('div');
        const bottomScroll = document.querySelector('.table-plan');

        // Sync scroll
        topScroll.addEventListener('scroll', () => {
            bottomScroll.scrollLeft = topScroll.scrollLeft;
        });
        bottomScroll.addEventListener('scroll', () => {
            topScroll.scrollLeft = bottomScroll.scrollLeft;
        });
    });
    document.getElementById('importPlanForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if(res.success){
                alert(`Berhasil import ${res.imported} item.` +
                    (res.skipped.length ? `\n\nDilewati:\n- ${res.skipped.join('\n- ')}` : ''));
                $('#buildPlanTable').DataTable().ajax.reload();
            } else {
                alert('Gagal: ' + res.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat import.');
        });
    });
    $('#buildPlanTable').DataTable({
        processing:true,
        serverSide:true,
        searching: false,
        lengthChange: false,
        paging: false,
        info: false,
        ordering: false,
        autoWidth: false,
        scrollX: false,
        dom: 't',
        columnDefs: [
            { targets: 0, width: '50px' },
            { targets: 1, width: '260px' },
            { targets: 2, width: '80px' },
        ],
        ajax:{
            url:"{{ route('build-plan.data',$project->id) }}",
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':
                '{{ csrf_token() }}'
            },
            dataSrc:function(json){
                window.weekTotal = json.week_total ?? {};
                window.weekKumulatif = json.week_kumulatif ?? {};
                return json.data;
            }
        },
        columns:columns,
        drawCallback: function () {

            let api = this.api();
            let data = api.rows({ page: 'current' }).data();
            let rows = api.rows({ page: 'current' }).nodes();

            let lastFloor = null;
            let lastCategory = null;
            let lastDescription = null;

            let categoryIndex = 0;
            let itemIndex = 0;

            data.each(function (row, i) {

                // FLOOR ROW
                if (row.floor_name !== lastFloor) {
                    $(rows[i]).before(`
                        <tr class="table-secondary fw-bold row-floor">
                            <td colspan="${columns.length}" style="background:#343a40; color:#000;">
                                ${row.floor_name ?? '-'}
                            </td>
                        </tr>
                    `);
                    lastFloor = row.floor_name;
                    lastCategory = null;   // reset kategori tiap ganti lantai
                    lastDescription = null;
                }

                // CATEGORY ROW
                if (row.category_name !== lastCategory) {
                    categoryIndex++;
                    itemIndex = 0;

                    $(rows[i]).before(`
                        <tr class="table-secondary fw-bold row-category">
                            <td colspan="${columns.length}">
                                ${alphaIndex(categoryIndex - 1)}. ${row.category_name.toUpperCase()}
                            </td>
                        </tr>
                    `);

                    lastCategory = row.category_name;
                    lastDescription = null;  // reset grup deskripsi tiap ganti kategori
                }

                // NOMOR: hanya increment & tampil kalau description beda dari baris sebelumnya
                let noCell = '';
                if (!row.description || row.description !== lastDescription) {
                    itemIndex++;
                    noCell = itemIndex;
                }
                lastDescription = row.description;
                $('td:eq(0)', rows[i]).html(noCell);
            });

            Object.entries(window.weekTotal || {}).forEach(([week, total]) => {
                $('#total-plan-' + week).html(Number(total).toFixed(3));
            });

            Object.entries(window.weekKumulatif || {}).forEach(([week, total]) => {
                $('#kumulatif-plan-' + week).html(Number(total).toFixed(3));
            });

            if (!window.chartInitialized) {
                initKurvaChart();
                window.chartInitialized = true;
            }
            setTimeout(() => {
                applyFreeze();

                const cols = document.querySelectorAll('#buildPlanTable colgroup:first-of-type col');
                const totalWidth = Array.from(cols).reduce((sum, col) => {
                    return sum + (parseFloat(col.style.width) || 0);
                }, 0);

                const topContent = document.querySelector('.table-scroll-top div');
                if (topContent) {
                    topContent.style.width = Math.ceil(totalWidth) + 'px';
                }
                const observer = new MutationObserver(() => {
                    const tbl = document.querySelector('#buildPlanTable');
                    if (tbl && Math.round(parseFloat(tbl.style.width)) !== totalWidth) {
                        tbl.style.width = totalWidth + "px";
                        tbl.style.minWidth = totalWidth + "px";
                    }
                });

                observer.observe(buildPlanTable, {
                    attributes: true,
                    attributeFilter: ['style']
                });

                setTimeout(() => observer.disconnect(), 2000);
            }, 200);
        }
    });
</script>
@endif
@endpush