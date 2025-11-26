<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Keluarga</h3>
    </div>
    <div class="card-body">
        @if ($license_holder->families && $license_holder->families->count())
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped" style="font-size: 0.9rem; font-weight: 500; font-family: 'Poppins', sans-serif;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Hubungan dengan pemilik</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Pekerjaan</th>
                            <th>Telepon Kantor Pekerjaan</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Nama Sekolah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($license_holder->families as $index => $fam)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fam->name }}</td>
                                <td>{{ $fam->readable_relationship }}</td>
                                <td>{{ $fam->readable_gender }}</td>
                                <td>{{ $fam->birth_date_formatted }}</td>
                                <td>{{ $fam->job }}</td>
                                <td>{{ $fam->job_phone }}</td>
                                <td>{{ $fam->last_education_level }}</td>
                                <td>{{ $fam->institution_name }}</td>
                                <td>
                                <a href="{{ route('license_holder_families.edit', $fam->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('license_holder_families.destroy', $fam->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
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
            <p class="text-muted">Belum ada data keluarga.</p>
        @endif
    </div>

    <div class="mt-4">
            <a href="{{ route('license_holder_families.create') }}?license_holder_id={{ $license_holder->id }}" class="btn btn-primary">
                Tambah Data
            </a>
            <a href="{{ route('license_holders.index') }}" class="btn btn-outline-secondary">
                Back to List
            </a>
    </div>
</div>
