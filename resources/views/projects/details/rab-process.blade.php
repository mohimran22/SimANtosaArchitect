@php
    $rab = $project->rab;
    $grouped = [];

    foreach ($rab->items as $item) {
        $kode = $item->category->kode_group ?? '-';
        $nama = $item->category->nama_group ?? 'Tanpa Kategori';

        if (!isset($grouped[$kode])) {
            $grouped[$kode] = [
                'kode' => $kode,
                'nama' => $nama,
                'items' => [],
                'subtotal' => 0
            ];
        }
        $grouped[$kode]['items'][] = $item;
        $grouped[$kode]['subtotal'] += $item->total;
    }
@endphp

@can('lihat data proyek')
@if($rab)
<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-4">
            <div class="col-md-4">
                <label class="fw-semibold">Nama Customer</label>
                <input type="text" class="form-control" readonly
                       value="{{ $rab->contact_name }}">
            </div>
            <div class="col-md-4">
                <label class="fw-semibold">Lokasi Pekerjaan</label>
                <input type="text" class="form-control" readonly
                       value="{{ $rab->job_location }}">
            </div>
            <div class="col-md-4">
                <label class="fw-semibold">Durasi Pekerjaan</label>
                <input type="text" class="form-control" readonly
                       value="{{ $rab->job_duration }}">
            </div>
        </div>

        {{-- <div class="row mt-4 g-4">

            <div class="col-md-4">
                <label class="fw-semibold">Paket RAB</label>
                <input type="text" class="form-control" readonly
                       value="{{ $rab->rabpackage->name ?? '-' }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Volume</label>
                <input type="text" class="form-control" readonly value="{{ $rab->item->volume }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Satuan</label>
                <input type="text" class="form-control" readonly value="{{ $rab->item->satuan }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Harga Satuan</label>
                <input type="text" class="form-control" disabled
                       value="Rp {{ number_format($rab->item->satuan, 0, ',', '.') }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Total Harga</label>
                <input type="text" class="form-control" disabled
                       value="Rp {{ number_format($rab->item->total_price, 0, ',', '.') }}">
            </div>
        </div> --}}


        <h5 class="fw-bold mt-5 mb-3">Rincian Pekerjaan</h5>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th>Uraian Pekerjaan</th>
                    <th>Volume</th>
                    <th>Satuan</th>
                    <th>Harga Satuan (Rp)</th>
                    <th>Total Harga</th>
                </tr>
            </thead>

            <tbody>
            @foreach($grouped as $group)
                {{-- HEADER GROUP --}}
                <tr class="table-secondary fw-bold">
                    <td>{{ $group['kode'] }}</td>
                    <td colspan="5">{{ $group['nama'] }}</td>
                    
                </tr>

                @foreach($group['items'] as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->job_name }}</td>
                    <td>{{ $item->volume }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                    <td class="text-end">
                        Rp {{ number_format($item->total,0,',','.') }}
                    </td>
                </tr>
                @endforeach

                {{-- SUBTOTAL PER GROUP --}}
                <tr class="fw-bold">
                    <td colspan="5" class="text-end">
                        Subtotal {{ $group['nama'] }}
                    </td>
                    <td class="text-end">
                        Rp {{ number_format($group['subtotal'],0,',','.') }}
                    </td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL</th>
                    <th>Rp {{ number_format($rab->subtotal,0,',','.') }}</th>
                </tr>
 
                <tr>
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>Rp {{ number_format($rab->discount,0,',','.') }}</th>
                    
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                    <th>Rp {{ number_format($rab->subtotal_after_discount,0,',','.') }}</th>
                    
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TAX RATE</th>
                    <th>{{ $rab->tax_rate }}%</th>
                    
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th>Rp {{ number_format($rab->tax_total,0,',','.') }}</th>
                    
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>Rp {{ number_format($rab->shipping,0,',','.') }}</th>
                    
                </tr>

                <tr>
                    <th colspan="5" class="text-end fw-bold">GRAND TOTAL</th>
                    <th class="fw-bold">
                        Rp {{ number_format($rab->grand_total,0,',','.') }}
                    </th>
                    
                </tr>
            </tfoot>
        </table>

        @if($rab->notes)
            <div class="mt-4">
                <h5 class="fw-bold">Keterangan</h5>
                <div class="border p-3">{{ $rab->notes }}</div>
            </div>
        @endif
        <div class="d-flex align-items-center gap-2">
            @if($project->rab?->id)
                
            <a href="{{ route('projects.rab.pdf', $project->id) }}"
                class="btn btn-dark"
                target="_blank"
                title="Download PDF">
                    <i class="ti ti-download"></i>Download PDF
            </a>
                
            @endif
        </div>
        @if(!$ReadOnly)
            <div class="card mt-3">
                <div class="card-body text-muted small">
                    <div>Dibuat oleh: {{ $rab->creator?->fullname ?? '-' }}</div>
                    <div>Dibuat pada: {{ $rab->created_at?->format('d M Y H:i') }}</div>
                    <div>Terakhir diubah: {{ $rab->updated_at?->format('d M Y H:i') }}</div>
                    <div>Diubah oleh: {{ $rab->editor?->fullname ?? '-' }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
@endif
@endcan