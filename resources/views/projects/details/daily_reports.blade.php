@php
    $dailies = $project->dailyReports;
@endphp

@if($dailies->count())
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header fw-bold">
        Riwayat Laporan Harian
        </div>

        <div class="card-body">
            {{-- @foreach($dailies as $daily) --}}

                <div class="border rounded p-3 mb-4">
                    <table class="table table-bordered">
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Minggu</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

@foreach($dailies as $i=>$daily)

<tr>
<td>{{ $i+1 }}</td>
<td>{{ $daily->tanggal }}</td>
<td>{{ $daily->week }}</td>
<td>
{{-- <a href="{{ route('build.daily.show',$daily->id) }}"
class="btn btn-sm btn-primary">
Detail
</a> --}}
</td>

</tr>

@endforeach

</tbody>
</table>
                    {{-- <h4 class="fw-bold mb-4">{{ $daily->project->project_name }}</h4>
                    <h5 class="mb-3">

                    📅 {{ \Carbon\Carbon::parse($daily->tanggal)->format('d m Y') }}

                    </h5>
                    <h6>Pekerjaan Hari Ini</h6>

                    <table class="table table-bordered mb-4">

                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Uraian</th>
                                <th width="100">Volume</th>
                                <th width="100">Satuan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($daily->works as $i=>$work)

                                <tr>

                                    <td>{{ $i+1 }}</td>

                                    <td>

                                        @if($work->rabProcessItem)
                                        {{ $work->rabProcessItem->job_name }}
                                        @else
                                        {{ $work->uraian_manual }}
                                        @endif

                                    </td>

                                    <td>{{ $work->volume }}</td>

                                    <td>{{ $work->satuan }}</td>

                                    <td>{{ $work->keterangan }}</td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                    <h6>Tenaga Kerja</h6>

                    <table class="table table-bordered mb-4">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama / Keahlian</th>
                                <th width="100">Jumlah</th>
                                <th>Alat</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($daily->workers as $i=>$worker)

                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>
                                        @if($worker->worker)
                                        {{ $worker->worker->user->fullname }}
                                        @else
                                        {{ $worker->keahlian }}
                                        @endif

                                    </td>

                                    <td>{{ $worker->jumlah }}</td>

                                    <td>{{ $worker->alat }}</td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>

                    <h6>Bahan Masuk</h6>

                    <table class="table table-bordered">

                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Bahan</th>
                                <th width="120">Diterima</th>
                                <th width="120">Ditolak</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($daily->materials as $i=>$m)

                                <tr>

                                    <td>{{ $i+1 }}</td>

                                    <td>{{ $m->nama_bahan }}</td>

                                    <td>{{ $m->diterima }}</td>

                                    <td>{{ $m->ditolak }}</td>

                                </tr>

                            @endforeach
                        </tbody>
                    </table> --}}

                    <div class="col-md-12 mt-3">
                        <label class="fw-semibold">Catatan / Perintah Konsultan MK</label>
                        <textarea class="form-control" rows="3" readonly>{{ $daily->catatan }}</textarea>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-6">
                            Side Manager
                            <br><br><br><br>
                            <input name="mk" class="form-control text-center" value="{{ $daily->mk }}">
                        </div>

                        <div class="col-md-6">

                            Kontraktor

                                <br><br><br><br>

                            <input name="kontraktor_ttd" class="form-control text-center" placeholder="Nama Project Manager">
                        </div>
                    </div>
                </div>
            {{-- @endforeach --}}

        </div>
    </div>
@endif