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
                <table id="buildPlanTable" class="table table-bordered build-plan-table">
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
                    <tbody>
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
    <div id="build-plan">
        @include('projects.steps.build-process')
    </div>
@push('js')
<script>

    const table = document.querySelector('.build-plan-table');

    if (!table) return;

    const freezeCount = 6;

    function applyFreeze() {

        // reset
        table.querySelectorAll('.sticky-col,.sticky-last')
            .forEach(el => {

                el.classList.remove(
                    'sticky-col',
                    'sticky-last'
                );

                el.style.left = '';
                el.style.width = '';
            });

        const cols =
            table.querySelectorAll('colgroup col');

        // hitung posisi kiri tiap kolom fixed
        const offsets = [];
        let left = 0;

        for(let i = 0; i < freezeCount; i++){

            offsets.push(left);

            left += parseFloat(
                getComputedStyle(cols[i]).width
            );
        }

        const headerRow1 =
            table.querySelector('thead tr:first-child');

        if(headerRow1){

            const ths =
                headerRow1.children;

            // No
            ths[0].classList.add('sticky-col');
            ths[0].style.left = offsets[0] + 'px';

            // Uraian
            ths[1].classList.add('sticky-col');
            ths[1].style.left = offsets[1] + 'px';

            // TERKONTRAK
            ths[2].classList.add(
                'sticky-col',
                'sticky-last'
            );

            ths[2].style.left =
                offsets[2] + 'px';

            let kontrakWidth = 0;

            for(let i = 2; i < 6; i++){

                kontrakWidth += parseFloat(
                    getComputedStyle(cols[i]).width
                );
            }

            ths[2].style.width =
                kontrakWidth + 'px';
        }

        const headerRow2 =
            table.querySelector('thead tr:last-child');

        if(headerRow2){

            Array.from(headerRow2.children)
                .slice(0,4)
                .forEach((th,index)=>{

                    th.classList.add(
                        'sticky-col'
                    );

                    th.style.left =
                        offsets[index + 2] + 'px';

                    if(index === 3){

                        th.classList.add(
                            'sticky-last'
                        );
                    }
                });
        }

        table.querySelectorAll(
            'tbody tr:not(.row-category):not(.row-uraian)'
        ).forEach(row => {

            const cells = row.children;

            for(let i = 0; i < freezeCount; i++){

                const cell = cells[i];

                if(!cell) continue;

                cell.classList.add(
                    'sticky-col'
                );

                cell.style.left =
                    offsets[i] + 'px';

                if(i === freezeCount - 1){

                    cell.classList.add(
                        'sticky-last'
                    );
                }
            }
        });

        table.querySelectorAll('.row-category,.row-uraian').forEach(row => {

            const td =
                row.querySelector('td');

            if(!td) return;

            let width = 0;

            for(let i = 0; i < freezeCount; i++){

                width += parseFloat(
                    getComputedStyle(cols[i]).width
                );
            }

            td.classList.add(
                'sticky-col',
                'sticky-last'
            );

            td.style.left = '0px';
            td.style.width = width + 'px';
            td.style.zIndex = '40';
        });

        table.querySelectorAll(
            'thead .sticky-col'
        ).forEach(th => {

            th.style.zIndex = '100';
        });
    }

    applyFreeze();

    window.addEventListener(
        'resize',
        applyFreeze
    );

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
    function getWeeklyPlanTotal(week){

        let total = 0;

        document.querySelectorAll(
            `.week-plan[data-week="${week}"]`
        ).forEach(el => {

            total += parseFloat(el.value) || 0;

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
<script>
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
        responsive: true,
        dom: 't',
        columnDefs:[
            {
                targets:0,
                width:"60px"
            },
            {
                targets:1,
                width:"320px"
            },
            {
                targets:[2,3],
                width:"90px"
            },
            {
                targets:4,
                width:"140px"
            },
            {
                targets:5,
                width:"100px"
            }
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
        drawCallback:function(){

            let api = this.api();

            let rows = api.rows({
                page:'current'
            }).nodes();

            let data = api.rows({
                page:'current'
            }).data();

            let lastCategory = null;
            let lastUraian = null;

            let categoryIndex = 0;
            let uraianIndex = 0;
            let itemIndex = 0;

            data.each(function(row,i){

                // CATEGORY
                if(row.category_name !== lastCategory){

                    categoryIndex++;
                    uraianIndex = 0;
                    itemIndex = 0;
                    lastUraian = null;

                    $(rows[i]).before(`
                        <tr class="row-category">
                            <td class="text-center fw-bold">
                                ${alphaIndex(categoryIndex - 1)}
                            </td>
                            <td colspan="${api.columns().count()-1}">
                                <strong>${row.category_name.toUpperCase()}</strong>
                            </td>
                        </tr>
                    `);

                    lastCategory = row.category_name;
                }

                // URAIAN
                if(row.uraian_name !== lastUraian){

                    uraianIndex++;
                    itemIndex = 0;

                    $(rows[i]).before(`
                        <tr class="row-uraian">
                            <td class="text-center">
                                ${uraianIndex}.
                            </td>
                            <td colspan="${api.columns().count()-1}">
                                ${row.uraian_name}
                            </td>
                        </tr>
                    `);

                    lastUraian = row.uraian_name;
                }

                // ITEM
                itemIndex++;

                $('td:eq(0)', rows[i]).html(
                    uraianIndex + '.' + itemIndex
                );

            });

            // total mingguan
            Object.entries(window.weekTotal).forEach(([week,total])=>{
                $('#total-plan-'+week).html(
                    Number(total).toFixed(3)
                );
            });

            Object.entries(window.weekKumulatif).forEach(([week,total])=>{
                $('#kumulatif-plan-'+week).html(
                    Number(total).toFixed(3)
                );
            });
                        $('.week-plan').each(function(){
                if(this.dataset.old === undefined){
                    this.dataset.old =
                        this.value || 0;
                }
            });
            if(!window.chartInitialized){

                initKurvaChart();

                window.chartInitialized=true;

            }
            if(typeof applyFreeze === 'function'){
                applyFreeze();
            }
        }
    });
</script>
@endpush