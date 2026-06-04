@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                {{-- <div class="col">
                    <h2 class="page-title">
                        Data Pengguna
                    </h2>
                </div> --}}
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                  @can('tambah data role')
                        <a href="{{ route("roles.create") }}" class="btn btn-dark">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Role Baru
                        </a>
                    @endcan     
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <p class="text-center mb-4" style="font-size: 1.4rem; font-weight: 400; font-family: 'Figtree', sans-serif;">
                                 Daftar Role
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Role</th>
                                        <th>Permission</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $role->name }}</strong></td>
                                            <td>
                                                @if($role->permissions->count() > 0)
                                                    {{ $role->permissions->count() }}
                                                @else
                                                    <span class="text-muted">Belum ada permission</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <button class="btn btn-icon btn-sm btn-dark edit-permissions" title="Ubah"
                                                        data-role-id="{{ $role->id }}"
                                                        data-role-name="{{ $role->name }}">
                                                    <i class="ti ti-edit"></i>
                                                </button> --}}
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-dark" title="Ubah">
                                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-dark" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Edit Permissions -->
<div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-height: 90vh;">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="bi bi-shield-lock me-2"></i> Kelola Permissions untuk Role: 
                    <span id="roleName" class="fw-bold"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="permissionsForm">
                @csrf
                

                <input type="hidden" name="role_id" id="roleId">

                <div class="modal-body">
                    @php
                        $permissions = \Spatie\Permission\Models\Permission::orderBy('modules')->get()->groupBy('modules');
                    @endphp

                    @foreach ($permissions as $moduleName => $modulePermissions)
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <strong class="text-dark">{{ $moduleName ?? 'Tanpa Grup' }}</strong>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-select-module" data-module="{{ \Illuminate\Support\Str::slug($moduleName) }}">
                                    Pilih Semua
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($modulePermissions as $perm)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input permission-checkbox module-{{ \Illuminate\Support\Str::slug($moduleName) }}" 
                                                       id="perm-{{ $perm->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $perm->id }}">
                                                <label class="form-check-label" for="perm-{{ $perm->id }}">
                                                    {{ ($perm->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="savePermissionsBtn" class="btn btn-dark">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@can('tambah data role')
<a href="{{ route('roles.create') }}"
   class="mobile-fab d-md-none">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="26"
         height="26"
         viewBox="0 0 24 24"
         stroke-width="2"
         stroke="currentColor"
         fill="none"
         stroke-linecap="round"
         stroke-linejoin="round">

        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>

    </svg>

</a>
@endcan
@endsection

{{-- @push('js')
<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ Role Permissions script aktif");

    const modal = new bootstrap.Modal(document.getElementById('editPermissionsModal'));
    const form = document.getElementById('permissionsForm');
    const saveBtn = document.getElementById('savePermissionsBtn');

    // Tombol edit
    document.querySelectorAll('.edit-permissions').forEach(btn => {
        btn.addEventListener('click', function () {
            const roleId = this.dataset.roleId;
            const roleName = this.dataset.roleName;

            document.getElementById('roleName').textContent = roleName;
            document.getElementById('roleId').value = roleId;

            // Reset semua checkbox
            form.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

            // Ambil permissions dari server
            fetch(`{{ url('/roles') }}/${roleId}`)

                .then(res => res.json())
                .then(data => {
                    if (data.permissions) {
                        data.permissions.forEach(p => {
                            const cb = document.querySelector(`#perm-${p.id}`);
                            if (cb) cb.checked = true;
                        });
                    }
                    
                });
            modal.show();
        });
    });

    // Pilih semua per grup
    document.querySelectorAll('.btn-select-module').forEach(btn => {
        btn.addEventListener('click', function () {
            const moduleSlug = this.dataset.module;
            const moduleCheckboxes = document.querySelectorAll(`.module-${moduleSlug}`);
            const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
            moduleCheckboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Pilih Semua' : 'Batalkan Semua';
        });
    });

    // Simpan perubahan
    saveBtn.addEventListener('click', function () {
        const roleId = document.getElementById('roleId').value;
        const formData = new FormData(form);

        fetch(`/roles/${roleId}/update-permissions`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
            },
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modal.hide();

                // 🔹 Update jumlah permission di tabel tanpa reload
                const countEl = document.getElementById(`permission-count-${roleId}`);
                if (countEl) countEl.textContent = data.count;

                // Notifikasi kecil
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed bottom-0 end-0 m-3';
                toast.innerHTML = `<i class="bi bi-check-circle me-1"></i> ${data.message}`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            } else {
                alert('❌ Gagal memperbarui permissions.');
            }
        })
        .catch(err => console.error(err));
    });
});
</script>

<style>
.modal-dialog-scrollable .modal-body {
    max-height: calc(90vh - 150px);
    overflow-y: auto;
}
</style>
@endpush --}}


