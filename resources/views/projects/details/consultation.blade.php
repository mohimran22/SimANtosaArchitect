@php
    $consultation = $project->consultation;
@endphp


@if(isset($consultation))
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Detail Konsultasi
    </div>

    <div class="card-body">
        <div class="row g-4">

            <div class="col-md-4">
                <label class="fw-semibold">Nama Customer</label>
                <input type="text" class="form-control"
                       value="{{ $consultation->contact_name }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">No HP</label>
                <input type="text" class="form-control"
                       value="{{ $consultation->contact_phone }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Karyawan Konsultan</label>
                <input type="text" class="form-control"
                       value="{{ $consultation->employee->display_name ?? '-' }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Ukuran Tanah</label>
                <input type="text" class="form-control"
                       value="{{ $consultation->site_area }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Ukuran Bangunan</label>
                <input type="text" class="form-control"
                       value="{{ $consultation->building_area }}" readonly>
            </div>

            <div class="col-md-12 mt-3">
                <label class="fw-semibold mb-2">Daftar Uraian</label>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Uraian</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultation->items as $item)
                        <tr>
                            <td class="text-center">{{ $item->order_no }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->remark }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($consultation->documentation)
            <div class="col-md-4 mt-3">
                <label class="fw-semibold">Foto Dokumentasi</label><br>
                <img src="{{ asset('storage/'.$consultation->documentation) }}"
                     alt="Dokumentasi"
                     class="img-thumbnail"
                     style="width: 200px; height: auto;">
            </div>
            @endif

            <div class="col-md-12 mt-3">
                <label class="fw-semibold">Catatan Tambahan</label>
                <textarea class="form-control" rows="3" readonly>{{ $consultation->notes }}</textarea>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-around text-center">
                <div>
                    <label class="form-label fw-bold d-block">Persetujuan Konsultan</label>
                    <i class="ti {{ $consultation->consultant_signed ? 'ti-check text-success' : 'ti-x text-danger' }}"
                    style="font-size: 28px"></i>
                </div>

                <div>
                    <label class="form-label fw-bold d-block">Persetujuan Customer</label>
                    <i class="ti {{ $consultation->client_signed ? 'ti-check text-success' : 'ti-x text-danger' }}"
                    style="font-size: 28px"></i>
                </div>
            </div>

        </div>
    </div>
</div>
@endif
