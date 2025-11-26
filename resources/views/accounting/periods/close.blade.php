@extends('tablar::page')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Tutup Periode Akuntansi</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('periods.close') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="period" class="form-label">Periode (Format: YYYYMM)</label>
            <input type="text" name="period" id="period" class="form-control" placeholder="Contoh: 202507" value="{{ old('period') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Tutup Periode</button>
    </form>
</div>
@endsection
