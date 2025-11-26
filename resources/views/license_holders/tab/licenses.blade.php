<div class="card">
    <div class="card-header">
        <h4>Data Lisensi Pemilik: {{ $license_holder->name }}</h4>
    </div>
     <div class="card-body">
        @if ($license_holder->user && $license_holder->user->licenses->count())
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th class="w-1">Id Lisensi</th>
                                        <th>Tipe Lisensi</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Alamat</th>
                                        <th>Provinsi</th>
                                        <th>Kabupaten/Kota</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th>Kode Pos</th>
                                        <th>Telepon</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Expired date</th>
                                        <th>Nomor Aqad</th>
                                        <th>Status</th>
                                        <th>Tipe Bangunan</th>
                                        <th>Status Bangunan</th>
                                        <th>Tanggal Expired Sewa Bangunan</th>
                                        <th>Luas Bangunan</th>
                                        <th>Kondisi Bangunan</th>
                                        <th>Bangunan Punya AC?</th>
                                        <th>Instagram</th>
                                        <th>Halaman Facebook</th>
                                        <th>Tiktok</th>
                                        <th>Youtube</th>
                                        <th>Google Maps</th>
                                        <th>Landing Page Pendaftaran Siswa</th>
                                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($license_holder->user->licenses as $license)
                            <tr>
                                <td>{{ $license->license_id }}</td>
                                <td>{{ $license->license_type }}</td>
                                <td>{{ $license->name }}</td>
                                <td>{{ $license->email }}</td>
                                <td>{{ $license->address }}</td>
                                <td>{{ $license->province->name ?? '-' }}</td>
                                <td>{{ $license->city->name ?? '-'}}</td>
                                <td>{{ $license->district->name ?? '-' }}</td>
                                <td>{{ $license->subDistrict->name ?? '-'}}</td>
                                <td>{{ $license->postalCode->postal_code ?? '-'}}</td>
                                <td>{{ $license->phone }}</td>
                                <td>{{ $license->join_date }}</td>
                                <td>{{ $license->expired_date }}</td>
                                <td>{{ $license->contract_agreement_number }}</td>
                                <td>{{ ucfirst($license->status) }}</td>
                                <td>{{ $license->building_type }}</td>
                                <td>{{ $license->building_status }}</td>
                                <td>{{ $license->building_rent_expired_date }}</td>
                                <td>{{ $license->building_area }}</td>
                                <td>{{ $license->building_condition }}</td>
                                <td>{{ $license->building_has_ac }}</td>
                                <td>
                                    @if($license->instagram)
                                        <a href="{{ $license->instagram }}" target="_blank">
                                            <i class="ti ti-check text-success"></i>
                                        </a>
                                    @else
                                        <i class="ti ti-times text-danger"></i>
                                    @endif</td>
                                <td>
                                    @if($license->facebook_page)
                                        <a href="{{ $license->facebook_page }}" target="_blank">
                                            <i class="ti ti-check text-success"></i>
                                        </a>
                                    @else
                                        <i class="ti ti-minus text-muted"></i>
                                    @endif
                                </td>
                                <td>@if($license->tiktok)
                                        <a href="{{ $license->tiktok }}" target="_blank">
                                            <i class="ti ti-check text-success"></i>
                                        </a>
                                    @else
                                        <i class="ti ti-minus text-muted"></i>
                                    @endif</td>
                                <td>
                                    @if($license->youtube)
                                        <a href="{{ $license->youtube }}" target="_blank">
                                            <i class="ti ti-check text-success"></i>
                                        </a>
                                    @else
                                        <i class="ti ti-minus text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($license->google_maps)
                                        <a href="{{ $license->google_maps }}" target="_blank">
                                            <i class="ti ti-check text-success"></i>
                                        </a>
                                    @else
                                        <i class="ti ti-minus text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($license->landing_page_student_registration)
                                        <a href="{{ $license->landing_page_student_registration }}" target="_blank">
                                            <i class="ti ti-check text-success"></i>
                                        </a>
                                    @else
                                        <i class="ti ti-minus text-muted"></i>
                                    @endif
                                </td>

                                
                                <td>
                                @if (auth()->user()->can('pemilik-lisensi.ubah')) 
                                <a href="{{ route('license_holder_educations.edit', $license->id) }}" class="btn btn-sm btn-warning">Edit</a> 
                                @endif
                                
                                @unless (auth()->user()->hasRole('Pemilik Lisensi')) 
                                <form action="{{ route('license_holder_educations.destroy', $license->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                     <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                
                                @endunless
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
            <a href="{{ route('license_holders.index') }}" class="btn btn-outline-secondary">
                Back to List
            </a>
    </div>
</div>
