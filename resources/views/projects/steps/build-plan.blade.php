@php
    $isReadOnly = !$canEdit;

    $weekCount = count($project->week_labels);
    $colsFixed   = 6;
    $colsPerWeek = 1;
    $colsTotal   = 0;

    $weekCount = count($project->week_labels);

    $totalCols = $colsFixed + ($weekCount * $colsPerWeek);

    $plans = $project->weeklyPlans
        ->keyBy('week_no');
@endphp

    <x-collapse-card title="Tahap Perencanaan Proyek" target="proyek-build-plan-body">
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

                    <div class="col-md-3 d-flex gap-2">
                        @if(!$isReadOnly)
                            <form action="{{ route('projects.sync-build-plan', $project->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Update form kemajuan pekerjaan dengan RAB terbaru?')">

                                @csrf

                                <button type="submit" class="btn btn-secondary">
                                    <i class="ti ti-refresh"></i>
                                    Update Form
                                </button>

                            </form>
                        @endif
                    </div>
                
            <div class="table-responsive">
                <table id="buildPlanTable" class="table table-bordered">
                    <colgroup>
                        <col style="width:60px">
                        <col style="width:320px">
                        <col style="width:80px">
                        <col style="width:90px">
                        <col style="width:140px">
                        <col style="width:100px">

                        @foreach($project->week_labels as $w)
                            <col style="width:95px">
                        @endforeach
                    </colgroup>
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="align-middle text-center">No</th>
                            <th rowspan="2" class="align-middle text-center">Uraian Pekerjaan</th>

                            <th colspan="4" class="align-middle text-center">TERKONTRAK</th>
                            

                            @foreach($project->week_labels as $w)
                                <th class="text-center week-head" data-week="{{ $w['week_no'] }}">
                                        <div>M{{ $w['week_no'] }}</div>
                                        {{-- <small class="text-muted d-block text-nowrap">
                                            {{ $w['start'] }} - {{ $w['end'] }}
                                        </small> --}}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="align-middle">Satuan</th>
                            <th class="align-middle text-center">Vol</th>
                            <th class="align-middle">Jumlah<br>Harga</th>
                            <th class="align-middle text-center">Bobot<br>(%)</th>

                            @foreach($project->week_labels as $w)
                                <th class="align-middle text-center">Bobot<br>(%)</th>
                            @endforeach
                        </tr>
                    </thead>
                     @php
                    function alphaIndex($n) {
                        $result = '';
                        while ($n >= 0) {
                            $result = chr(($n % 26) + 65) . $result;
                            $n = intdiv($n, 26) - 1;
                        }
                        return $result;
                    }
                    @endphp
                    <tbody>
                        @foreach($groupedPlans as $categoryData)

                            @php
                                $categoryNo = alphaIndex($loop->index);
                            @endphp
                            <tr class="row-category">
                                <td colspan="6">
                                    {{ $categoryNo }}. {{ strtoupper($categoryData['category_name']) }}
                                </td>

                                <td colspan="{{ $totalCols - 6 }}"></td>
                            </tr>

                            @foreach($categoryData['uraians'] as $uraianData)
                                @php
                                    $uraianNo = $loop->iteration;
                                @endphp
                                <tr class="row-uraian">
                                    <td colspan="6">
                                        {{ $uraianNo }}. {{ ucwords($uraianData['uraian_name']) }}
                                    </td>

                                    <td colspan="{{ $totalCols - 6 }}"></td>
                                </tr>

                                @foreach($uraianData['items'] as $item)
                                            @php
                                                $itemNo = $loop->iteration;
                                            @endphp
                                            <tr
                                                data-item-id="{{ $item->id }}"
                                                data-item-vol="{{ $item->volume }}"
                                                data-item-bobot="{{ $item->bobot_percent }}">                              
                                                <td>
                                                    {{ $uraianNo }}.{{ $itemNo }}
                                                </td>
                                                <td class="uraian-pekerjaan">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            {{ $item->item_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->satuan }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item->volume,2) }}
                                                </td>

                                                <td class="text-end">
                                                    Rp {{ number_format($item->total,0,',','.') }}
                                                </td>
                                                <td width="120">
                                                    <input type="number"
                                                        step="0.001"
                                                        class="form-control"
                                                        data-id="{{ $item->id }}"
                                                        value="{{ number_format($item->bobot_percent, 3, '.', '') }}"
                                                        readonly>
                                                </td>
                                                    @foreach($project->week_labels as $w)
                                                        @php
                                                            $prog = $item->progress_map[$w['week_no']] ?? null;
                                                        @endphp
                                                        <td class="week-col">
                                                            @if(!$isReadOnly)
                                                            <input type="number"
                                                                step="0.01"
                                                                min="0"
                                                                max="{{ $item->bobot_percent }}"
                                                                class="form-control week-plan"
                                                                data-item="{{ $item->id }}"
                                                                data-week="{{ $w['week_no'] }}"
                                                                data-bobot="{{ $item->bobot_percent }}"   {{-- ← tambahkan ini --}}
                                                                value="{{ $prog->plan_percent ?? '' }}">
                                                            @else
                                                            <div class="form-control bg-light">
                                                                {{ $prog->plan_percent ?? '' }}
                                                            </div>
                                                            @endif
                                                        </td>                           
                                                    @endforeach
                                            </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">
                                Total Pekerjaan (%)
                            </th>
                            <th>
                                {{ $isReadOnly ? '' : 'Rp '.number_format($buildPlans->sum('total'),0,',','.') }}
                            </th>
                            <th class="text-center">
                                100.0
                            </th>

                            @foreach($project->week_labels as $w)
                                <th class="week-foot text-center fw-bold"
                                    data-week="{{ $w['week_no'] }}"
                                    id="total-plan-{{ $w['week_no'] }}">
                                    0
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">
                                Kumulatif (%)
                            </th>
                            <th>
                                {{ $isReadOnly ? '' : 'Rp '.number_format($buildPlans->sum('total'),0,',','.') }}
                            </th>
                            <th class="text-center">
                                100.0
                            </th>
                            @foreach($project->week_labels as $w)
                                <th class="week-foot text-center fw-bold"
                                    id="kumulatif-plan-{{ $w['week_no'] }}">

                                    0
                                </th>
                            @endforeach
                        </tr>
                    
                    </tfoot>
                </table>
            </div>
        </div>
    </x-collapse-card>
    <div id="build-process">
        @include('projects.steps.build-process')
    </div>
@push('js')
<script>

    const buildPlanTable = document.querySelector("#buildPlanTable");
    const cols = buildPlanTable?.querySelectorAll("colgroup col") || [];
    const freezeCounts = 6;
    function applyFreeze() {
        if (!buildPlanTable) return;

        buildPlanTable.querySelectorAll(".sticky-col, .sticky-last").forEach(cell => {
            cell.classList.remove("sticky-col", "sticky-last");
            cell.style.left = "";
            cell.style.width = "";
        });

        if (window.innerWidth < 576) {
            return;
        }
        const offsets = [];
        let left = 0;
        for (let i = 0; i < freezeCounts; i++) {
            offsets.push(left);
            left += Math.round(
                parseFloat(getComputedStyle(cols[i]).width)
            );
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

                if (
                        colIndex < freezeCounts ||
                        cell.classList.contains('freeze-col')
                    ) {
                    cell.classList.add("sticky-col");
                    if (colIndex === freezeCounts - 1) {
                        cell.classList.add("sticky-last");
                    }
                    cell.style.left = Math.round(offsets[colIndex]) + "px";
                    // batasi width jika colspan > 1
                    let width = 0;
                    for (let i = 0; i < colspan && (colIndex + i) < freezeCounts; i++) {
                        width += Math.round(parseFloat(
                            getComputedStyle(cols[colIndex + i]).width)
                        );
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

        // row-category: freeze td pertama
        buildPlanTable.querySelectorAll("tr.row-category, tr.row-uraian").forEach(row => {
            const cell = row.querySelector("td");
            if (!cell) return;
            const width = Array.from(cols).slice(0, freezeCounts).reduce((sum, c) => {
                return sum + (parseFloat(getComputedStyle(c).width) || 0);
            }, 0);
            cell.classList.add("sticky-col");
            cell.classList.add("sticky-last");

            cell.style.left = "0px";
            cell.style.width = Math.round(width) + "px";
        });
        // row tambahan (kuning)
        buildPlanTable.querySelectorAll("tr.row-tambahan-item").forEach(row => {

            let left = 0;

            Array.from(row.children).forEach((cell, index) => {

                if (index < freezeCounts) {

                    cell.classList.add("sticky-col");

                    if (index === freezeCounts - 1) {
                        cell.classList.add("sticky-last");
                    }

                    cell.style.left = Math.round(left) + "px";

                    cell.style.zIndex = 55;

                    cell.style.background = "#fff3cd";

                    left += Math.round(parseFloat(
                        getComputedStyle(cols[index]).width)
                    );
                }
            });
        });
    }
    applyFreeze();

</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        $(document).on('focus', '.week-plan', function(){
            this.dataset.old = this.value || 0;
        });

        $(document).on('input', '.week-plan', function(){

            const target = this;

            const itemId = target.dataset.item;

            const isValid = validateItemPlan(itemId);

            if(!isValid){

                target.value = target.dataset.old || '';

                return;

            }

            autosavePlan(
                itemId,
                target.dataset.week,
                parseFloat(target.value) || 0
            );

            calculateFooterPlan();
            updateKurvaPlanRealtime();
        });
        calculateFooterPlan();
        initKurvaChart();
    });

    function initKurvaChart(){
        const ctx = document.getElementById('kurvaSChart');
        ctx.width = Math.max({{ $weekCount }} * 50, 900);
        if(!ctx || typeof Chart === 'undefined') {

            console.error('Chart.js belum load');
            return;

        }
        const dataAwal = @json($project->getKurvaSData() ?? []);
        const labels = []; for(let i = 1; i <= {{ $weekCount }}; i++){ labels.push('M' + i); }
        const realisasi = []; for(let i = 1; i <= {{ $weekCount }}; i++){ const found = dataAwal.find(d => d.week == i); realisasi.push( found ? found.progress : 0 ); }

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
                }
            }

        });
    }
    function getWeeklyPlanTotal(week) {
        let total = 0;
        document.querySelectorAll(`.week-plan[data-week="${week}"]`).forEach(el => {
            // Handle baik <input> maupun <div>
            const val = el.tagName === 'INPUT' ? el.value : el.innerText;
            total += parseFloat(val) || 0;
        });
        return total;
    }

    function getPlanKumulatif(){

        let weekCount = {{ $weekCount }};
        let jalan = 0;
        let data = [];

        for(let w = 1; w <= weekCount; w++){

            jalan += getWeeklyPlanTotal(w);

            data.push(jalan);

        }

        return data;

    }

    function calculateFooterPlan(){

        let weekCount = {{ $weekCount }};

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
            getPlanKumulatif();

        window.kurvaChart.update();

    }

    function rebuildKurvaFromTable(){

        let weekCount = {{ $weekCount }};
        let kumulatif = [];
        let jalan = 0;

        for(let w = 1; w <= weekCount; w++){

            let sumBobot = 0;

            document.querySelectorAll(
                `.week-bobot[data-week="${w}"]`
            ).forEach(el => {

                sumBobot += parseFloat(el.innerText) || 0;

            });

            jalan += sumBobot;

            kumulatif.push(jalan);

        }

        return kumulatif;

    }

    function updateKurvaChartRealtime(){

        if(!window.kurvaChart) return;

        window.kurvaChart.data.datasets[0].data =
            rebuildKurvaFromTable();

        window.kurvaChart.update();

    }

    function validatePlanTotal(){

        let total = 0;

        let weekCount = {{ $weekCount }};

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

        let total = 0;

        let bobot =
            parseFloat(
                document.querySelector(
                    `.week-plan[data-item="${itemId}"]`
                )
                ?.dataset
                .bobot
            ) || 0;


        document.querySelectorAll(`.week-plan[data-item="${itemId}"]`)
        .forEach(el=>{
            total += parseFloat(el.value)||0;
        });
        if(total > bobot + 0.001){
            alert(
                `Total plan item melebihi bobot ${bobot}%`
            );
            return false;
        }
        return true;
    }
</script>
{{-- <script>
    let weeks = @json($project->week_labels);

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
            data:'satuan',
            name:'satuan',
        },

        {
            data:'volume',
            name:'volume',
        },

        {
            data:'total_format',
            name:'total_format',
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
                return `
                <input type="number" step="0.001" class="form-control week-plan"
                    data-item="${row.id}"
                    data-week="${w.week_no}"
                    data-bobot="${row.bobot_percent}"
                    value="${data ?? 0}"
                >
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

    $('#buildPlanTable').DataTable({
        processing:true,
        serverSide:true,
        searching: false,
        lengthChange: false,
        paging: false,
        info: false,
        ordering: false,
        autoWidth: false,
        dom: 't',

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

            let lastCategory = null;
            let lastUraian = null;

            let categoryIndex = 0;
            let uraianIndex = 0;
            let itemIndex = 0;

            data.each(function (row, i) {

                // CATEGORY ROW (SAFE: ONLY INSERT OUTSIDE TABLE BODY LOGIC)
                if (row.category_name !== lastCategory) {
                    categoryIndex++;
                    uraianIndex = 0;
                    itemIndex = 0;
                    lastUraian = null;

                    $(rows[i]).before(`
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">${alphaIndex(categoryIndex - 1)}</td>
                            <td colspan="999">
                                ${row.category_name.toUpperCase()}
                            </td>
                        </tr>
                    `);

                    lastCategory = row.category_name;
                }

                // URAIAN ROW
                if (row.uraian_name !== lastUraian) {
                    uraianIndex++;
                    itemIndex = 0;

                    $(rows[i]).before(`
                        <tr class="table-light">
                            <td class="text-center">${uraianIndex}.</td>
                            <td colspan="999">
                                ${row.uraian_name}
                            </td>
                        </tr>
                    `);

                    lastUraian = row.uraian_name;
                }

                itemIndex++;

                $('td:eq(0)', rows[i]).html(
                    uraianIndex + '.' + itemIndex
                );
            });

            // update totals (OK tetap)
            Object.entries(window.weekTotal || {}).forEach(([week, total]) => {
                $('#total-plan-' + week).html(Number(total).toFixed(3));
            });

            Object.entries(window.weekKumulatif || {}).forEach(([week, total]) => {
                $('#kumulatif-plan-' + week).html(Number(total).toFixed(3));
            });

            // chart init tetap aman
            if (!window.chartInitialized) {
                initKurvaChart();
                window.chartInitialized = true;
            }
        }
    });
</script> --}}
@endpush