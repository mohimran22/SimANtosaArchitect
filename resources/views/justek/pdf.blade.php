<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Penawaran {{ $technicalJustification->justek_number }}</title>

<style>
/* ================= PAGE ================= */
@page {
    margin: 140px 30px 110px 30px;
}

/* ================= BODY ================= */
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    line-height: 1.5;
    margin: 0;
    padding: 0;
}

/* ================= HEADER & FOOTER ================= */
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

/* ================= TABLE HANDLING ================= */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    page-break-inside: auto;
}

thead {
    display: table-header-group; /* penting */
}

tfoot {
    display: table-footer-group;
}

tr {
    page-break-inside: auto;
}

.no-break {
    page-break-inside: avoid; /* hanya untuk kategori */
}

th, td {
    padding: 6px;
    border: 1px solid #444;
}

th {
    background: #efefef;
}

/* ================= UTIL ================= */
.no-border td {
    border: none !important;
    padding: 4px 0;
}

.text-right { text-align: right; }
.bold { font-weight: bold; }

p { margin: 5px 0; }
.closing-block {
    page-break-inside: avoid;
    margin-top: 20px;
}

.signature {
    margin-top: 10px;
}

.signature img {
    height: 140px;
}

</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="header">
    <img src="{{ public_path('images/header-penawaran.jpg') }}" style="width:100%;">
</div>

<!-- ================= FOOTER ================= -->
<div class="footer">
    <img src="{{ public_path('images/footer-penawaran.jpg') }}" style="width:100%;">
</div>

<!-- ================= KONTEN ================= -->
{{-- <div style="height:60px;"></div> --}}

<table class="no-border" width="100%">
    <tr>
        <!-- KIRI -->
        <td width="60%">
            <table class="no-border">
                <tr>
                    <td width="30%">Nomor</td>
                    <td>: {{ $technicalJustification->justek_number }}</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>: -</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>: Penawaran Harga</td>
                </tr>
            </table>
        </td>

        <!-- KANAN -->
        <td width="40%" style="vertical-align: top; text-align: right;">
            <strong>
                {{ $technicalJustification->project->city?->name ?? 'Jember' }},
                {{ \Carbon\Carbon::parse($technicalJustification->offer_date)->translatedFormat('d F Y') }}
            </strong>
        </td>
    </tr>
</table>

<br>

<p>Kepada Yth.</p>
<p><strong>{{ $technicalJustification->contact_name }}</strong></p>
<p>{{ $technicalJustification->project->project_location ?? '-' }}</p>
<br>
<p>Dengan hormat,</p>
<p>
Sebagai tindak lanjut dari penyesuaian pekerjaan bangun rumah sebelumnya,
berikut kami sampaikan penawaran biaya tambahan untuk pelaksanaan pekerjaan:
</p>

<table class="no-border">
<tr><td width="25%">Jenis Pekerjaan</td><td>: {{ $technicalJustification->project->project_name ?? '-' }}</td></tr>
<tr><td>Lokasi</td><td>: {{ $technicalJustification->project->project_location ?? '-' }}</td></tr>
</table>

<p><strong>Berikut rincian harga yang kami tawarkan:</strong></p>

<!-- ================= TABEL RINCIAN ================= -->
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
            @php
                $categoryIndex = 0;
            @endphp
        @foreach($grouped as $floorName => $categories)

            <tr style="font-weight:bold; background:#ddd;">

                <td colspan="6">
                    {{ strtoupper($floorName ?: 'Tanpa Lantai') }}
                </td>

            </tr>

            @foreach($categories as $categoryName => $category)

                @php
                    $categoryLetter = number_to_letters($categoryIndex);
                    $categorySubtotal = $category['subtotal'];
                    $itemNo = 1;
                    $lastDescription = null;
                @endphp

                <tr style="font-weight:bold; background:#f5f5f5;">

                    <td align="center">
                        {{ $categoryLetter }}
                    </td>

                    <td colspan="4">

                        {{ strtoupper(
                            $categoryName ?: 'Tanpa Kategori'
                        ) }}

                    </td>

                    <td align="right">

                        Rp
                        {{ number_format(
                            $categorySubtotal,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                </tr>

                @foreach($category['items'] as $item)

                    @php

                        $description = trim((string) $item->description);

                        $showNumber = false;

                        if ($description === '') {

                            $showNumber = true;

                        } elseif (
                            $description !== $lastDescription
                        ) {
                            $showNumber = true;
                        }

                        $currentNo = $itemNo;

                        if ($showNumber) {
                            $itemNo++;
                        }

                        $lastDescription = $description;

                    @endphp


                    <tr>

                        {{-- NO --}}

                        <td align="center">

                            @if($showNumber)
                                {{ $currentNo }}
                            @endif

                        </td>


                        {{-- PEKERJAAN --}}

                        <td>

                            {{ $item->job_name }}

                            @if($description !== '')

                                <br>

                                <span style="
                                    font-size:11px;
                                    color:#666;
                                ">
                                    {{ $description }}
                                </span>

                            @endif

                        </td>


                        {{-- SAT --}}

                        <td align="center">
                            {{ $item->satuan }}
                        </td>


                        {{-- VOL --}}

                        <td align="right">

                            {{ rtrim(
                                rtrim(
                                    number_format(
                                        $item->volume,
                                        5,
                                        ',',
                                        '.'
                                    ),
                                    '0'
                                ),
                                ','
                            ) }}

                        </td>


                        {{-- HARGA --}}

                        <td align="right">

                            Rp
                            {{ number_format(
                                $item->price,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        {{-- TOTAL --}}

                        <td align="right">

                            Rp
                            {{ number_format(
                                $item->total,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                @endforeach


                @php
                    $categoryIndex++;
                @endphp

            @endforeach

        @endforeach

    </tbody>

    @php

        $subtotal = $technicalJustification->subtotal ?? 0;

        $discount = $technicalJustification->discount ?? 0;

        $subtotalAfterDiscount = $technicalJustification->subtotal_after_discount ?? 0;

        $taxRate = $technicalJustification->tax_rate ?? 0;

        $totalTax = $technicalJustification->tax_total ?? 0;

        $shipping = $technicalJustification->shipping ?? 0;

        $grandTotal = $technicalJustification->grand_total ?? 0;
    @endphp

    <tfoot>

        <tr>

            <th colspan="5" align="right">
                SUBTOTAL
            </th>

            <th align="right">

                Rp
                {{ number_format(
                    $subtotal,
                    0,
                    ',',
                    '.'
                ) }}

            </th>

        </tr>

        <tr>
            <th colspan="5" align="right">
                DISCOUNT
            </th>
            <th align="right">

                Rp
                {{ number_format(
                    $discount,
                    0,
                    ',',
                    '.'
                ) }}

            </th>

        </tr>

        <tr>

            <th colspan="5" align="right">
                SUBTOTAL AFTER DISCOUNT
            </th>

            <th align="right">

                Rp
                {{ number_format(
                    $subtotalAfterDiscount,
                    0,
                    ',',
                    '.'
                ) }}

            </th>

        </tr>

        <tr>

            <th colspan="5" align="right">
                TAX ({{ $taxRate }}%)
            </th>

            <th align="right">

                Rp
                {{ number_format(
                    $totalTax,
                    0,
                    ',',
                    '.'
                ) }}

            </th>

        </tr>

        <tr>

            <th colspan="5" align="right">
                SHIPPING
            </th>

            <th align="right">

                Rp
                {{ number_format(
                    $shipping,
                    0,
                    ',',
                    '.'
                ) }}

            </th>

        </tr>

        <tr style="font-weight:bold;">

            <th colspan="5" align="right">
                GRAND TOTAL
            </th>

            <th align="right">

                Rp
                {{ number_format(
                    $grandTotal,
                    0,
                    ',',
                    '.'
                ) }}

            </th>

        </tr>

    </tfoot>

</table>

<p><strong>TERBILANG :</strong> {{ strtoupper(terbilang($grandTotal)) }} RUPIAH</p>

<div class="closing-block">
    <h4>Keterangan:</h4>
    <ol>
    <li>Penawaran harga ini berlaku 7 hari dari tanggal penawaran</li>
    <li>Mekanisme pembayaran dapat di transfer ke rek atas nama Dwiantosa Ahmad Fathoni</li>
    <li>Pekerjaan akan segera dilaksanakan setelah pembayaran diterima</li>
    </ol>

    <p>Demikian penawaran harga kami sampaikan, besar harapan kami Bapak/Ibu berminat dengan harga yang kami tawarkan.
        <br>
    Atas perhatiannya kami ucapkan terima kasih.</p>

    <div class="signature">
        <p>Hormat Kami,</p>

        <p>
            <strong>PT. Tosa Ahmad Jaya</strong><br>
            <strong>Antosa Architect</strong>
        </p>

        <img src="{{ public_path('images/ttd-dwiantosa.png') }}">

        <p>
            <strong><u>Ir. Ar. Dwiantosa Ahmad Fathony, IAI., IPP</u></strong><br>
            Direktur Utama
        </p>
    </div>
</div>

</body>
</html>