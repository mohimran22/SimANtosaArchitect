<h2 style="text-align:center; margin-bottom:20px;">
    Dokumentasi Proyek
</h2>

@foreach($dailyReports as $report)

    <h4 style="margin-bottom:5px;">
        {{ $report->tanggal_formatted }}
        (Minggu {{ $report->minggu }})
    </h4>

    <p style="margin-top:0; margin-bottom:10px;">
        {{ $report->pekerjaan }}
    </p>

    @if($report->documentations->count())

            <table
                align="center"
                cellpadding="2"
                cellspacing="0"
                style="border-collapse:collapse;margin-bottom:10px;">
                <tr>
                    @foreach($report->documentations->take(3) as $doc)
                        <td width="33%" align="center" style="border:none; vertical-align:top;">
                            <img
                                src="file://{{ storage_path('app/public/'.$doc->file_path) }}"
                                style="width:170px; height:120px; object-fit:cover;">
                        </td>
                        @if($loop->iteration % 3 == 0 && !$loop->last)
                            </tr><tr>
                        @endif
                    @endforeach
                </tr>
            </table>
    @else
        <div style="
            text-align:center;
            padding:25px;
            color:#666;
            font-size:12px;
            border:1px dashed #999;
            background:#f8f8f8;
        ">
            <strong>Dokumentasi Belum Tersedia</strong><br><br>
            Mohon maaf, foto dokumentasi untuk tanggal
            <strong>{{ $report->tanggal_formatted }}</strong>
            masih belum tersedia.
        </div>
    @endif

    <br>

@endforeach