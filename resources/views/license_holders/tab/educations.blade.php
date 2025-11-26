<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Pendidikan</h3>
    </div>
    <div class="card-body">
        @if ($license_holder->educations && $license_holder->educations->count())
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenjang</th>
                            <th>Nama Sekolah</th>
                            <th>Jurusan</th>
                            <th>Tahun Masuk</th>
                            <th>Tahun Lulus</th>
                            <th>Apakah Lulus?</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($license_holder->educations as $index => $edu)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $edu->education_level }}</td>
                                <td>{{ $edu->institution_name }}</td>
                                <td>{{ $edu->major }}</td>
                                <td>{{ $edu->start_year }}</td>
                                <td>{{ $edu->end_year }}</td>
                                <td>{{ $edu->readable_is_graduated }}</td>
                                <td>
                                <a href="{{ route('license_holder_educations.edit', $edu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('license_holder_educations.destroy', $edu->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                     <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                             </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Belum ada data pendidikan.</p>
        @endif
    </div>

    <div class="mt-4">
            <a href="{{ route('license_holder_educations.create') }}?license_holder_id={{ $license_holder->id }}" class="btn btn-primary">
                Tambah Data
            </a>
            <a href="{{ route('license_holders.index') }}" class="btn btn-outline-secondary">
                Back to List
            </a>
    </div>
</div>
