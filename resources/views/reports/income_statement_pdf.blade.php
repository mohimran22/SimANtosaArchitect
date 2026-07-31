<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Antosa Architect</title>
    <style>
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
        h2, h4 {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
        tfoot td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/header-penawaran.jpg') }}" style="width:100%;">
    </div>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        <img src="{{ public_path('images/footer-penawaran.jpg') }}" style="width:100%;">
    </div>
    <h2>Laporan Laba Rugi</h2>
    <h4>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Kategori</th>
                <th>Sub Kategori</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $acc)
                <tr>
                    <td>{{ $acc->account_code }}</td>
                    <td>{{ $acc->account_name }}</td>
                    <td>{{ $acc->category }}</td>
                    <td>{{ $acc->sub_category }}</td>
                    <td style="text-align: right;">{{ number_format($acc->balance, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right">Total Pendapatan</td>
                <td style="text-align: right;">{{ number_format($totalIncome, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" align="right">Total Beban</td>
                <td style="text-align: right;">{{ number_format($totalExpense, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" align="right">Laba Bersih</td>
                <td style="text-align: right;">{{ number_format($netIncome, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
