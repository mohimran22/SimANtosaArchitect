<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Antosa Architect</title>
    <style>
        @page {
            margin: 140px 30px 110px 30px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
        }
        table td.text-left {
            text-align: left;
        }
        h3, h4, p {
            text-align: center;
            margin-bottom: 10px;
            font-size: 15px;
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
        <h1 style="text-align:center;">Laporan Neraca </h1>
        <h2 style="text-align:center;">Antosa Architect </h2>
        <h3>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/M/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/M/Y') }}</h3>
        @if($licenses->where('id', $request->license_id)->first())
            <h4>Lisensi: {{ $licenses->where('id', $request->license_id)->first()->name }}</h4>
        @endif


    <table>
        <thead>
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedAccounts as $category => $subs)
                <tr class="bg-light">
                    <td colspan="4" class="text-center fw-bold"><strong>{{ $category }}</strong></td>
                </tr>
                @foreach($subs as $subCat => $data)
                    <tr class="table-secondary">
                        <td colspan="4" class="fw-semibold fst-italic"><em>{{ $subCat }}</em></td>
                    </tr>
                    @foreach($data['accounts'] as $acc)
                            <tr>
                                <td>{{ $acc['account_code'] }}</td>
                                <td class="text-left">{{ $acc['account_name'] }}</td>
                                <td>Rp {{ number_format($acc['debit'], 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($acc['credit'], 2, ',', '.') }}</td>
                            </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right"><strong>Subtotal {{ $subCat }}</strong></td>
                        <td><strong>Rp {{ number_format($data['subtotalDebit'], 2, ',', '.') }}</strong></td>
                        <td><strong>Rp {{ number_format($data['subtotalCredit'], 2, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($totalDebit, 2, ',', '.') }}</strong></td>
                <td><strong>Rp {{ number_format($totalCredit, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
