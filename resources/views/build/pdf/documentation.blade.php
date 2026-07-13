<h2 style="text-align:center;margin-bottom:20px">
    Dokumentasi Proyek
</h2>

@foreach($dailyReports as $report)

    @if($report->documentations->count())

        <h4>
            {{ $report->tanggal_formatted }}
            (Minggu {{ $report->minggu }})
        </h4>

        <p>
            {{ $report->pekerjaan }}
        </p>

        <table width="100%" cellpadding="5">
            <tr>

                @foreach($report->documentations as $doc)

                    <td width="33%" align="center">

                    <img
                        src="file://{{ storage_path('app/public/'.$doc->file_path) }}"
                        style="width:170px;height:120px;object-fit:cover;">

                    </td>

                    @if(($loop->iteration % 3)==0)
                        </tr><tr>
                    @endif

                @endforeach

            </tr>
        </table>

        <br>

    @endif

@endforeach