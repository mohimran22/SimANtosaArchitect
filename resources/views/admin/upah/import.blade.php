@extends('tablar::page')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Import Harga Satuan Pekerjaan (UPH)</h5>
                </div>

                <div class="card-body">

                    {{-- Alert --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Form Upload --}}
                    <form action="{{ route('admin.upah.import') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                File Excel UPH (.xls / .xlsx)
                            </label>
                            <input type="file"
                                   name="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   accept=".xls,.xlsx"
                                   required>

                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Pastikan format sesuai template UPH
                            </small>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i>
                                Import Data
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
