<div class="col-md-9">
    <div class="card">
        <div class="card-body">
            {{-- Foto & Nama --}}
            <div class="d-flex align-items-center mb-4">
                @if ($license_holder->photo)
                    <span class="avatar avatar-xl me-3 rounded" style="background-image: url('{{ asset('storage/photos/' . $license_holder->photo) }}')"></span>
                @else
                    <span class="avatar avatar-xl me-3 avatar-rounded bg-secondary-lt">?</span>
                @endif
                <div>
                    <h3 class="card-title mb-1">{{ $license_holder->fullname }}</h3>
                    <div class="text-muted">{{ $license_holder->job ?? 'No Job Title' }}</div>
                </div>
            </div>

           {{-- Informasi Pribadi --}}
            <h4 class="subheader">🧾 Informasi Pribadi</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Lisensi:</span>
                            {{ $license_holder->user->licenses->pluck('name')->implode(', ') ?: '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Telepon:</span> {{ $license_holder->phone ?? '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Agama:</span> {{ $license_holder->religion->name ?? '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Tempat Lahir:</span> {{ $license_holder->birth_place ?? '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Alamat:</span> {{ $license_holder->address ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Nomor KTP:</span> {{ $license_holder->identity_number ?? '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Nomor SIM:</span> {{ $license_holder->driver_license_number ?? '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Tanggal Lahir:</span> {{ $license_holder->birth_date_formatted ?? '-' }}
                        </div>
                        <div class="list-group-item">
                            <span class="text-secondary fw-normal">Hobi:</span> {{ $license_holder->hobby ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>


            {{-- Pernikahan --}}
            <h4 class="subheader">💍 Pernikahan</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item"><span class="text-secondary fw-normal">Status:</span> {{ $license_holder->readable_marital_status }}</div>
                        <div class="list-group-item"><span class="text-secondary fw-normal">Tanggal Nikah:</span> {{ $license_holder->married_date_formatted }}</div>
                    </div>
                </div>
            </div>

            {{-- Bahasa --}}
            <h4 class="subheader">🌐 Kemampuan Bahasa</h4>
            <div class="row mb-3">
                @php
                    function badgeColor($value) {
                        return $value === 'Lancar' ? 'green' : 'yellow';
                    }
                @endphp
                @foreach ($license_holder->readable_languages as $label => $value)
                    <div class="col-md-6 mb-2 d-flex align-items-center">
                
                        <div>
                            <span class="text-muted">{{ $label }}</span> :
                            <span class="badge bg-{{ badgeColor($value) }}-lt text-{{ badgeColor($value) }}">
                                {{ $value }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Aksi --}}
            <div class="mt-4">
                <a href="{{ route('license_holders.edit', $license_holder->id) }}" class="btn btn-primary">
                    Edit Profile
                </a>
                <a href="{{ route('license_holders.index') }}" class="btn btn-outline-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>