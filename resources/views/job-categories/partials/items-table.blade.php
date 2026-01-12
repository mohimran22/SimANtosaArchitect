<table class="table table-bordered table-sm align-middle">
    <thead class="table-light text-center">
        <tr>
            <th width="40">No</th>
            <th>Uraian</th>
            <th width="80">Supplier</th>
            <th width="80">Kode</th>
            <th width="80">Satuan</th>
            <th width="90">Koefisien</th>
            <th width="120">Harga Satuan</th>
            <th width="140">Jumlah Harga</th>
            <th width="60">Aksi</th>
        </tr>
    </thead>

<tbody>
@php
    $groups = [
        'labor'     => 'TENAGA KERJA',
        'product'   => 'HARGA BAHAN',
        'equipment' => 'HARGA ALAT',
    ];
@endphp

@foreach($groups as $key => $label)

    {{-- HEADER GROUP --}}
    <tr class="table-secondary fw-bold">
        <td colspan="9">{{ $label }}</td>
    </tr>

    @php
        $no = 1;
        $subtotal = 0;
    @endphp

    @foreach($items->where('category', $key) as $item)
        @php $subtotal += $item->total_price; @endphp
        <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->supplier }}</td>
            <td class="text-center">{{ $item->code }}</td>
            <td class="text-center">{{ $item->unit }}</td>
            <td class="text-end">{{ number_format($item->coefisien, 4) }}</td>
            <td class="text-end">
                Rp {{ number_format($item->base_unit_price, 0, ',', '.') }}
            </td>
            <td class="text-end">
                Rp {{ number_format($item->total_price, 0, ',', '.') }}
            </td>
            <td class="text-center">
                <form action="{{ route('job-categories.items.delete', $item->id) }}"
                      method="POST"
                      onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-dark">
                        <i class="ti ti-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach

    {{-- SUBTOTAL --}}
    <tr class="fw-bold">
        <td colspan="7" class="text-end">
            JUMLAH {{ strtoupper(str_replace('.', '', $label)) }}
        </td>
        <td class="text-end">
            Rp {{ number_format($subtotal, 0, ',', '.') }}
        </td>
        <td></td>
    </tr>

@endforeach
</tbody>
</table>

@php
    $totalLabor = $items->where('category','labor')->sum('total_price');
    $totalProduct = $items->where('category','product')->sum('total_price');
    $totalEquipment = $items->where('category','equipment')->sum('total_price');

    $subTotal = $totalLabor + $totalProduct + $totalEquipment;

    // default overhead profit (misal 5%)
    $overheadPercent = $jobCategory->overhead_percent ?? 0;

    $overheadValue = $subTotal * ($overheadPercent / 100);
    $profitPercent = $jobCategory->profit_percent ?? 0;

    $profitValue = $subTotal * ($profitPercent / 100);
    $grandTotal = $subTotal + $overheadValue + $profitValue;
@endphp


<table class="table table-bordered table-sm mt-4">
    <tbody>
        {{-- SUBTOTAL --}}
        <tr>
            <td colspan="6" class="text-end fw-bold">JUMLAH (A + B + C)</td>
            <td class="text-end fw-bold" id="subtotal">
                Rp {{ number_format($subTotal, 0, ',', '.') }}
            </td>
        </tr>

        {{-- OVERHEAD --}}
        <tr>
            <td colspan="5" class="text-end fw-bold">Overhead</td>
            <td width="100">
                <input type="number"
                       step="0.01"
                       id="overhead_percent"
                       value="{{ $overheadPercent }}"
                       class="form-control form-control-sm text-end">
            </td>
            <td class="text-end fw-bold" id="overhead_value">
                Rp {{ number_format($overheadValue, 0, ',', '.') }}
            </td>
        </tr>

        {{-- PROFIT --}}
        <tr>
            <td colspan="5" class="text-end fw-bold">Profit</td>
            <td width="100">
                <input type="number"
                       step="0.01"
                       id="profit_percent"
                       value="{{ $profitPercent }}"
                       class="form-control form-control-sm text-end">
            </td>
            <td class="text-end fw-bold" id="profit_value">
                Rp {{ number_format($profitValue, 0, ',', '.') }}
            </td>
        </tr>

        {{-- GRAND TOTAL --}}
        <tr class="table-success">
            <td colspan="6" class="text-end fw-bold">
                HARGA SATUAN PEKERJAAN
            </td>
            <td class="text-end fw-bold" id="grand_total">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>



@push('js')

<script>
$(document).ready(function () {

    function formatRp(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num || 0);
    }

    function hitungTotal() {
        const subTotal = {{ $subTotal }};
        const overheadPercent = parseFloat($('#overhead_percent').val()) || 0;
        const profitPercent   = parseFloat($('#profit_percent').val()) || 0;

        const overheadValue = subTotal * (overheadPercent / 100);
        const profitValue   = subTotal * (profitPercent / 100);
        const grandTotal    = subTotal + overheadValue + profitValue;

        $('#overhead_value').text(formatRp(overheadValue));
        $('#profit_value').text(formatRp(profitValue));
        $('#grand_total').text(formatRp(grandTotal));
    }

    function autoSave() {
        const subTotal = {{ $subTotal }};
        const overheadPercent = parseFloat($('#overhead_percent').val()) || 0;
        const profitPercent   = parseFloat($('#profit_percent').val()) || 0;

        const overheadValue = subTotal * (overheadPercent / 100);
        const profitValue   = subTotal * (profitPercent / 100);
        const grandTotal    = subTotal + overheadValue + profitValue;

        $.post(
            "{{ route('job-categories.save-overhead-profit', $jobCategory->id) }}",
            {
                _token: "{{ csrf_token() }}",
                overhead_percent: overheadPercent,
                profit_percent: profitPercent,
                overhead_value: overheadValue,
                profit_value: profitValue,
                subtotal: subTotal,
                grand_total: grandTotal
            }
        );
    }

    $('#overhead_percent, #profit_percent').on('input', function () {
        console.log('INPUT BERUBAH');
        hitungTotal();
        clearTimeout(window.saveTimer);
        window.saveTimer = setTimeout(autoSave, 500);
    });
    hitungTotal();
    autoSave();
});
</script>

@endpush