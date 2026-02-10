@php
$offer = $project->offer;
$rab   = $offer?->rab;

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
@if($offer)
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">Detail Penawaran</div>

    <div class="card-body">

        <h4 class="fw-bold mb-4">Detail Penawaran Jasa Desain</h4>

        <div class="row g-4">

            {{-- Informasi utama --}}
            <div class="col-md-4">
                <label class="fw-semibold">Nomor Penawaran</label>
                <input type="text" class="form-control" readonly
                       value="{{ $offer->offer_number }}">
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Tanggal Penawaran</label>
                <input type="text" class="form-control" readonly
                       value="{{ \Carbon\Carbon::parse($offer->offer_date)->format('d/m/Y') }}">
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Nama Customer</label>
                <input type="text" class="form-control" readonly
                       value="{{ $offer->contact_name }}">
            </div>

        </div>

        <div class="row mt-4 g-4">

            <div class="col-md-4">
                <label class="fw-semibold">Pilihan RAB</label>
                <input type="text" class="form-control" readonly
                       value="{{ $rab->project->project_name ?? '-' }}">
            </div>

            {{-- <div class="col-md-2">
                <label class="fw-semibold">Volume</label>
                <input type="text" class="form-control" readonly value="{{ $offer->volume }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Satuan</label>
                <input type="text" class="form-control" readonly value="{{ $offer->satuan }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Harga Satuan</label>
                <input type="text" class="form-control" disabled
                       value="Rp {{ number_format($offer->price_meter, 0, ',', '.') }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Total Harga</label>
                <input type="text" class="form-control" disabled
                       value="Rp {{ number_format($offer->total_price, 0, ',', '.') }}">
            </div> --}}
        </div>

        <h5 class="fw-bold mt-5 mb-3">Rincian Pekerjaan</h5>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Uraian Pekerjaan</th>
                    <th>Volume</th>
                    <th>Satuan</th>
                    <th>Harga Satuan (Rp)</th>
                    <th>Total Harga</th>
                </tr>
            </thead>

                <tbody>

                    @foreach($grouped as $group)

                        {{-- BARIS KATEGORI --}}
                        <tr class="table-secondary fw-bold">
                            <td>{{ $group['kode'] }}</td>
                            <td colspan="5">{{ $group['nama'] }}</td> 
                        </tr>

                        {{-- ITEM DI DALAM KATEGORI --}}
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
                    <th>Rp {{ number_format($rab->subtotal, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>Rp {{ number_format($rab->discount, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                    <th>
                        Rp {{ number_format($rab->total_price - $rab->discount, 0, ',', '.') }}
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TAX RATE (%)</th>
                    <th>{{ $rab->tax_rate }}%</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th>Rp {{ number_format($rab->total_tax, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>Rp {{ number_format($rab->shipping, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">GRAND TOTAL</th>
                    <th class="fw-bold">
                        Rp {{ number_format($rab->grand_total, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        {{-- Notes --}}
        @if($rab->notes)
        <div class="mt-4">
            <h5 class="fw-bold">Keterangan</h5>
            <div class="border p-3">{{ $rab->notes }}</div>
        </div>
        @endif
        <div class="d-flex align-items-center gap-2">
            @if($project->offer?->id)
                @if($project->project_type == 3)
                <a href="{{ route('projects.offers.build.pdf', $project->id) }}"
                    class="btn btn-dark"
                    target="_blank"
                    title="Download PDF">
                        <i class="ti ti-file-text"></i>Download PDF
                </a>
                @endif
            @endif
        </div>
    </div>
</div>
@endif
@endcan