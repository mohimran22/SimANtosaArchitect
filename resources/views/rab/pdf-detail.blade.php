<h3 style="text-align:center; margin-bottom:15px;">
    RINCIAN RENCANA ANGGARAN BIAYA
</h3>

<table width="100%" cellspacing="0" cellpadding="6" border="1">
<thead style="background:#eee; font-weight:bold; text-align:center;">
<tr>
    <th width="4%">NO</th>
    <th>URAIAN PEKERJAAN</th>
    <th width="6%">SAT</th>
    <th width="8%">VOL</th>
    <th width="15%">HARGA SATUAN</th>
    <th width="17%">JUMLAH HARGA</th>
</tr>
</thead>

<tbody>
@php $noGroup = 'A'; @endphp

@foreach($grouped as $group)
<tr style="font-weight:bold; background:#f5f5f5;">
    <td align="center">{{ $noGroup }}</td>
    <td colspan="5">{{ strtoupper($group['nama']) }}</td>
</tr>

@php $no = 1; @endphp
@foreach($group['items'] as $item)
<tr>
    <td align="center">{{ $no++ }}</td>
    <td>{{ $item->job_name }}</td>
    <td align="center">{{ $item->satuan }}</td>
    <td align="right">{{ number_format($item->volume,2,',','.') }}</td>
    <td align="right">Rp {{ number_format($item->price,0,',','.') }}</td>
    <td align="right">Rp {{ number_format($item->total,0,',','.') }}</td>
</tr>
@endforeach

<tr style="font-weight:bold;">
    <td colspan="5" align="right">Jumlah {{ $group['nama'] }}</td>
    <td align="right">
        Rp {{ number_format($group['subtotal'],0,',','.') }}
    </td>
</tr>

@php $noGroup++; @endphp
@endforeach
</tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">SUBTOTAL</th>
                <th class="text-end">{{ number_format($rab->subtotal,0,',','.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-end">DISCOUNT</th>
                <th class="text-end">{{ number_format($rab->discount,0,',','.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                <th class="text-end">{{ number_format($rab->subtotal_after_discount,0,',','.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-end">TAX ({{ $rab->tax_rate }}%)</th>
                <th class="text-end">{{ number_format($rab->tax_total,0,',','.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-end">SHIPPING</th>
                <th class="text-end">{{ number_format($rab->shipping,0,',','.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-end fw-bold">GRAND TOTAL</th>
                <th class="text-end fw-bold">{{ number_format($rab->grand_total,0,',','.') }}</th>
            </tr>
        </tfoot>
</table>
