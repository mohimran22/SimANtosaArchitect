@if ($technicalJustification)
<div class="card-body">

    <div class="row g-4">
        <div class="col-md-4">
            <label class="fw-semibold">Nomor Penawaran</label>
            <input type="text" class="form-control" readonly
                   value="{{ $technicalJustification->justek_number }}">
        </div>
        <div class="col-md-4">
            <label class="fw-semibold">Tanggal Penawaran</label>
            <input type="text" class="form-control" readonly
                   value="{{ \Carbon\Carbon::parse($technicalJustification->offer_date)->format('d/m/Y') }}">
        </div>
        <div class="col-md-4">
            <label class="fw-semibold">Nama Customer</label>
            <input type="text" class="form-control" readonly
                   value="{{ $technicalJustification->contact_name }}">
        </div>
    </div>

    <h5 class="fw-bold mt-5 mb-3">Rincian Pekerjaan</h5>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>URAIAN PEKERJAAN</th>
                    <th>SAT</th>
                    <th>VOL</th>
                    <th>HARGA SATUAN</th>
                    <th>JUMLAH HARGA</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $items = $technicalJustification->items->sortBy('order_no')->values();
                    $floorGroups = $items->groupBy('floor_name');
                    $categoryIndex = 0;
                @endphp

                @foreach($floorGroups as $floorName => $floorItems)
                    <tr class="table-secondary">
                        <th colspan="6">{{ strtoupper($floorName ?: 'Tanpa Lantai') }}</th>
                    </tr>

                    @php $categoryGroups = $floorItems->groupBy('category_name'); @endphp

                    @foreach($categoryGroups as $categoryName => $categoryItems)
                        @php
                            $categoryLetter = number_to_letters($categoryIndex);
                            $categoryTotal = $categoryItems->sum('total');
                            $displayNo = 0;
                            $previousDescription = null;
                        @endphp

                        <tr class="table-secondary fw-bold">
                            <td>{{ $categoryLetter }}</td>
                            <td colspan="4">{{ strtoupper($categoryName ?: 'Tanpa Kategori') }}</td>
                            <td class="text-end">Rp {{ number_format($categoryTotal, 2, ',', '.') }}</td>
                        </tr>

                        @foreach($categoryItems as $item)
                            @php
                                $currentDescription = $item->description ?? '';
                                $isNewGroup = empty($currentDescription) || $currentDescription !== $previousDescription;
                                if ($isNewGroup) { $displayNo++; }
                                $previousDescription = $currentDescription;
                            @endphp

                            <tr>
                                <td>{{ $displayNo }}</td>
                                <td>
                                    {{ $item->job_name }}
                                    @if(!empty($item->description))
                                        <div class="small text-muted">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ rtrim(rtrim(number_format($item->volume, 5, '.', ''), '0'), '.') }}</td>
                                <td>Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        @php $categoryIndex++; @endphp
                    @endforeach
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL</th>
                    <th>Rp {{ number_format($technicalJustification->subtotal,3,',','.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>Rp {{ number_format($technicalJustification->discount,3,',','.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                    <th>Rp {{ number_format($technicalJustification->subtotal_after_discount,3,',','.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">TAX RATE</th>
                    <th>{{ $technicalJustification->tax_rate }}%</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th>Rp {{ number_format($technicalJustification->tax_total,2,',','.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>Rp {{ number_format($technicalJustification->shipping,2,',','.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end fw-bold">GRAND TOTAL</th>
                    <th class="fw-bold">Rp {{ number_format($technicalJustification->grand_total,3,',','.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end fw-bold">DIBULATKAN</th>
                    <th class="fw-bold">
                        Rp {{ number_format(floor($technicalJustification->grand_total / 100000) * 100000,0,',','.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($technicalJustification->notes)
        <div class="mt-4">
            <h5 class="fw-bold">Keterangan</h5>
            <div class="border p-3">{{ $technicalJustification->notes }}</div>
        </div>
    @endif

    <div class="d-flex align-items-center gap-2 mt-4">
        <a href="{{ route('projects.justek.pdf', $technicalJustification->id) }}"
           target="_blank" class="btn btn-dark">
            <i class="ti ti-file-type-pdf me-1"></i> Download PDF
        </a>
    </div>

    @if(!($ReadOnly ?? false))
        <div class="card mt-3">
            <div class="card-body text-muted small">
                <div>Dibuat oleh: {{ $technicalJustification->creator?->fullname ?? '-' }}</div>
                <div>Dibuat pada: {{ $technicalJustification->created_at?->format('d M Y H:i') }}</div>
                <div>Terakhir diubah: {{ $technicalJustification->updated_at?->format('d M Y H:i') }}</div>
                <div>Diubah oleh: {{ $technicalJustification->editor?->fullname ?? '-' }}</div>
            </div>
        </div>
    @endif
</div>
@endif