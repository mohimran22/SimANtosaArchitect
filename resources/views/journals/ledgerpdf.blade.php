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
            bottom: -100px;
            left: 0;
            right: 0;
            width: 100%;
        }
        h2, p {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: auto;
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
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            page-break-inside: avoid;
        }
        table th:nth-child(1),
        table td:nth-child(1){
            width:75px;
        }

        table th:nth-child(2),
        table td:nth-child(2){
            width:75px;
        }

        table th:nth-child(3),
        table td:nth-child(3){
            width:auto;
            word-wrap: break-word;
        }

        table th:nth-child(4),
        table td:nth-child(4),
        table th:nth-child(5),
        table td:nth-child(5),
        table th:nth-child(6),
        table td:nth-child(6){
            width:90px;
            text-align:right;
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

        <h2>Buku Besar</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>

    @foreach($ledger as $accountId => $data)
        <h4>{{ $data['account']->account_code }} - {{ $data['account']->account_name }}</h4>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Deskripsi</th>
                    <th class="text-end">Debit</th>
                    <th class="text-end">Kredit</th>
                    <th class="text-end">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $lastJournal = null; @endphp
                @foreach($data['rows'] as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row['transaction_date'])->format('d/m/Y') }}</td>
                        <td>
                            @if($lastJournal !== $row['journal_code'])
                                ({{ $row['journal_code'] }})
                                @php $lastJournal = $row['journal_code']; @endphp
                            @endif
                        </td>
                        <td>{{ $row['description'] }}</td>
                        <td>Rp {{ number_format($row['debit'], 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($row['credit'], 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($row['balance'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endforeach
</body>
</html>

