<h2 style="text-align:center; margin-bottom:20px;">
    REKAPITULASI RENCANA ANGGARAN BIAYA
</h2>

<table width="100%" style="margin-bottom:20px;">
<tr>
    <td width="20%">PEKERJAAN</td>
    <td>: {{ $rab->project->project_name ?? '-' }}</td>
</tr>
<tr>
    <td>LOKASI</td>
    <td>: {{ $rab->project->city->name ?? '-' }}</td>
</tr>
<tr>
    <td>DURASI</td>
    <td>: {{ $rab->job_duration ?? '-' }}</td>
</tr>
</table>

<table width="100%" border="1" cellpadding="6">
    <thead class="thead-dark">
        <tr>
            <th width="5%">NO</th>
            <th>URAIAN PEKERJAAN</th>
            <th width="30%">JUMLAH HARGA</th>
        </tr>
    </thead>

    <tbody>
        @php 
            $no = 'A'; 
        @endphp

        @foreach($rab->categories as $category)

            @php
                $subtotal = $category->uraians->flatMap->items->sum('total');
            @endphp

            <tr>
                <td align="center" style="font-weight:bold;">{{ $no }}</td>

                <td style="font-weight:bold;">
                    {{ strtoupper($category->name) }}
                </td>

                <td align="right">
                    Rp {{ number_format($subtotal,0,',','.') }}
                </td>
            </tr>

            @php
                $no++;
            @endphp

        @endforeach
    </tbody>

    <tfoot>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">SUBTOTAL</td>
            <td align="right">Rp {{ number_format($rab->subtotal,0,',','.') }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">DISKON</td>
            <td align="right">{{ number_format($rab->discount,0,',','.') }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">SUBTOTAL AFTER DISKON</td>
            <td align="right">{{ number_format($rab->subtotal_after_discount,0,',','.') }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">TAX ({{ $rab->tax_rate }}%)</td>
            <td align="right">{{ number_format($rab->tax_total,0,',','.') }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">SHIPPING</td>
            <td align="right">{{ number_format($rab->shipping,0,',','.') }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">GRAND TOTAL</td>
            <td align="right">{{ number_format($rab->grand_total,0,',','.') }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <td colspan="2" align="right">DIBULATKAN</td>
            <td align="right">
                Rp {{ number_format(floor($rab->grand_total / 100000) * 100000,0,',','.') }}
            </td>
        </tr>
    </tfoot>
</table>
