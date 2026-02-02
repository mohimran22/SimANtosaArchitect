<h2 style="text-align:center; margin-bottom:20px;">
    REKAPITULASI RENCANA ANGGARAN BIAYA
</h2>

<table width="100%" style="margin-bottom:20px;">
<tr>
    <td width="20%">PEKERJAAN</td>
    <td>: {{ $rab->contact_name }}</td>
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
@php $no = 'A'; $grand = 0; @endphp
@foreach($grouped as $group)
<tr>
    <td align="center" style="font-weight:bold;">{{ $no }}</td>
    <td style="font-weight:bold;">{{ strtoupper($group['nama']) }}</td>
    <td align="right">
        Rp {{ number_format($group['subtotal'],0,',','.') }}
    </td>
</tr>
@php
    $grand += $group['subtotal'];
    $no++;
@endphp
@endforeach
</tbody>

<tfoot>
<tr style="font-weight:bold;">
    <td colspan="2" align="right">JUMLAH</td>
    <td align="right">Rp {{ number_format($grand,0,',','.') }}</td>
</tr>
<tr style="font-weight:bold;">
    <td colspan="2" align="right">DIBULATKAN</td>
    <td align="right">
        Rp {{ number_format(round($grand,-5),0,',','.') }}
    </td>
</tr>
</tfoot>
</table>
