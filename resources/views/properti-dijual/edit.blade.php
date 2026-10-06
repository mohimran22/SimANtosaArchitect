@extends('tablar::page')
@section('content')
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('jual.index') }}" class="btn btn-dark d-flex align-items-center me-3">
                    <i class="ti ti-arrow-left"></i>
                </a>
                <h2 class="page-title mb-0">Edit Data Properti</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card shadow-sm border-0">
            <div class="card-body px-5 py-4">
                <form action="{{ route('jual.update', $item) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('properti-dijual._form')
                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-dark px-4">
                            <i class="ti ti-device-floppy me-1"></i> Perbarui Data
                        </button>
                    </div>
                </form>

                {{-- form kosong untuk tombol hapus foto (action diisi lewat formaction) --}}
                <form id="formHapusFoto" method="POST">@csrf @method('DELETE')</form>
            </div>
        </div>
    </div>
</div>
@endsection