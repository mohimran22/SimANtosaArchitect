@extends('tablar::page')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Daftar Pekerjaan</h3>
                <a href="{{ route('job-categories.create') }}" class="btn btn-dark">
                    + Tambah
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bidang</th>
                            <th>Group</th>
                            <th>Kode Urut</th>
                            <th>Nama Pekerjaan</th>
                            <th>Satuan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $job->bidang }}</td>
                            <td>{{ $job->nama_group }}</td>
                            <td>{{ $job->kode_urut }}</td>
                            <td>{{ $job->nama_pekerjaan }}</td>
                            <td>{{ $job->satuan }}</td>
                            <td>
                                <a href="{{ route('job-categories.edit', $job->id) }}"
                                   class="btn btn-sm btn-dark">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <form action="{{ route('job-categories.destroy', $job->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-dark">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($jobs->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
@endsection
