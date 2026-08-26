<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>
        RENCANA ANGGARAN BIAYA {{ $project->project_name }}
    </title>

    <style>

        @page {
            margin: 135px 30px 100px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;

            top: -115px;
            left: 0;
            right: 0;

            width: 100%;
            height: 100px;

            text-align: center;
        }

        .header img {
            width: 100%;
            height: auto;
            display: block;
        }

        .footer {
            position: fixed;

            bottom: -75px;
            left: 0;
            right: 0;

            width: 100%;
            height: 70px;

            text-align: center;
        }

        .footer img {
            width: 100%;
            height: auto;
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .group-header {
            background: #ddd;
            font-weight: bold;
        }

        .thead-dark th {
            background: #999 !important;
            color: #fff !important;
        }

        .page-break {
            page-break-after: always;
        }

        tr {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>
    <div class="header">
        <img
            src="{{ public_path('images/header-penawaran.jpg') }}"
        >
    </div>
    <div class="footer">
        <img
            src="{{ public_path('images/footer-penawaran.jpg') }}"
        >
    </div>

    @include('rab.pdf-rekap')

    <div class="page-break"></div>

    @include('rab.pdf-detail')

</body>
</html>