@extends('tablar::page')

@section('content')
<div class="page-header mb-4">
    <h2 class="page-title">Tutup Buku Tahunan</h2>
</div>

<div class="card">
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($periods as $p)
                    <tr>
                        <td><strong>{{ $p->year }}</strong></td>
                        <td>{{ $p->start_date }} s/d {{ $p->end_date }}</td>
                        <td>
                            @if($p->is_closed)
                                <span class="badge bg-danger">Closed</span>
                            @else
                                <span class="badge bg-success">Open</span>
                            @endif
                        </td>
                        <td>
                            @if(!$p->is_closed)
                                <form action="{{ route('periods.close') }}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="year" value="{{ $p->year }}">
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin tutup buku tahun {{ $p->year }}?')">
                                        Tutup Buku
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('periods.reopen') }}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="year" value="{{ $p->year }}">
                                    <button class="btn btn-sm btn-warning"
                                        onclick="return confirm('Buka kembali tahun {{ $p->year }}?')">
                                        Reopen
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection
