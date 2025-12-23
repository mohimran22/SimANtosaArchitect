<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">


<style>
@page {
    margin: 140px 30px 110px 30px;
}

/* ================= BODY ================= */
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    line-height: 1.5;
    margin: 0;
    padding: 0;
}
.header {
    position: fixed;
    top: -110px;
    left: 0;
    right: 0;
    width: 100%;
}

.footer {
    position: fixed;
    bottom: -70px;
    left: 0;
    right: 0;
    width: 100%;
}
</style>
</head>

<body>
<div class="header">
    <img src="{{ public_path('images/header-penawaran.jpg') }}" style="width:100%;">
</div>

<div class="footer">
    <img src="{{ public_path('images/footer-penawaran.jpg') }}" style="width:100%;">
</div>

<h3 style="text-align:center; margin-bottom:20px;">
    RENCANA SURVEI LAPANGAN
</h3>
<table width="100%" cellpadding="6" cellspacing="0" border="1">
    <tr>
        <td width="30%"><strong>Nama Proyek</strong></td>
        <td>{{ $project->project_name }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal Survei</strong></td>
        <td>{{ \Carbon\Carbon::parse($planning->planning_date)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td><strong>Waktu Survei</strong></td>
        <td>{{ $planning->planning_time }}</td>
    </tr>
    <tr>
        <td><strong>Petugas Survei</strong></td>
        <td>
            @foreach($planningEmployees as $emp)
                {{ $emp->display_name }}@if(!$loop->last), @endif
            @endforeach
        </td>
    </tr>
</table>
<h4 style="margin-top:20px;">Alamat Survei</h4>

<table width="100%" cellpadding="6" cellspacing="0" border="1">
    <tr>
        <td colspan="2">{{ $planning->survey_address }}</td>
    </tr>
    <tr>
        <td>Provinsi</td>
        <td>{{ $planning->province->name ?? '-' }}</td>
    </tr>
    <tr>
        <td>Kab/Kota</td>
        <td>{{ $planning->city->name ?? '-' }}</td>
    </tr>
    <tr>
        <td>Kecamatan</td>
        <td>{{ $planning->district->name ?? '-' }}</td>
    </tr>
    <tr>
        <td>Kelurahan</td>
        <td>{{ $planning->subDistrict->name ?? '-' }}</td>
    </tr>
    <tr>
        <td>Kode Pos</td>
        <td>{{ $planning->postalCode->postal_code ?? '-' }}</td>
    </tr>
</table>
<h4 style="margin-top:20px;">Biaya Survei</h4>

<table width="100%" cellpadding="6" cellspacing="0" border="1">
    <tr>
        <td width="30%"><strong>Total Biaya</strong></td>
        <td>
            @if($invoice->amount > 0)
                Rp {{ number_format($invoice->amount,0,',','.') }}
            @else
                GRATIS
            @endif
        </td>
    </tr>
</table>
@if($planning->planning_notes)
<h4 style="margin-top:20px;">Catatan</h4>
<p>{{ $planning->planning_notes }}</p>
@endif
<table width="100%" style="margin-top:50px;">
    <tr>
        <td width="50%" style="text-align:center;">
            <p>Disusun oleh,</p>
            <br><br><br>
            <strong>Admin Antosa Architect</strong>
        </td>

        <td width="50%" style="text-align:center;">
            <p>Disetujui oleh Customer,</p>
            <br><br><br>
            <strong>{{ $project->customer->user->fullname ?? '................' }}</strong>
        </td>
    </tr>
</table>
@if($invoice->status === 'waiting_approval')
<hr>

<p style="text-align:center; font-size:12px;">
Silakan menyetujui atau menolak rencana survei melalui tautan berikut:
</p>

<p style="text-align:center;">
    <a href="{{ route('survey.invoice.approve', [$invoice->id, $invoice->approval_token]) }}">
        SETUJUI RENCANA SURVEI
    </a>
    |
    <a href="{{ route('survey.invoice.reject.form', [$invoice->id, $invoice->approval_token]) }}">
        TOLAK RENCANA SURVEI
    </a>
</p>
@endif

</body>
</html>