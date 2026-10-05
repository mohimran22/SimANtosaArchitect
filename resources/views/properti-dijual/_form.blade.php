@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Judul</label>
        <input type="text" name="judul" class="form-control" value="{{ old('judul', $item->judul) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach (['dijual','disewa','terjual'] as $v)
                <option value="{{ $v }}" @selected(old('status', $item->status ?? 'dijual') === $v)>{{ ucfirst($v) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipe</label>
        <select name="tipe" class="form-select">
            @foreach (['rumah','tanah','ruko','apartemen'] as $v)
                <option value="{{ $v }}" @selected(old('tipe', $item->tipe ?? 'rumah') === $v)>{{ ucfirst($v) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga (angka, Rp)</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga', $item->harga) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Cicilan (opsional)</label>
        <input type="text" name="cicilan" class="form-control" placeholder="Rp 7,12 juta/bln" value="{{ old('cicilan', $item->cicilan) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Kota</label>
        <input type="text" name="kota" class="form-control" value="{{ old('kota', $item->kota) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Lokasi / Alamat singkat</label>
        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $item->lokasi) }}" required>
    </div>
    @foreach (['kt' => 'Kamar Tidur', 'km' => 'Kamar Mandi', 'lt' => 'Luas Tanah (m²)', 'lb' => 'Luas Bangunan (m²)'] as $f => $lbl)
        <div class="col-6 col-md-3">
            <label class="form-label">{{ $lbl }}</label>
            <input type="number" name="{{ $f }}" class="form-control" value="{{ old($f, $item->$f) }}">
        </div>
    @endforeach
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $item->deskripsi) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto utama</label>
        <input type="file" name="foto" accept="image/*" class="form-control">
        @if ($item->foto)<img src="{{ asset('storage/'.$item->foto) }}" width="120" class="mt-2 rounded">@endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto tambahan (bisa banyak)</label>
        <input type="file" name="galeri[]" accept="image/*" multiple class="form-control">
        @if ($item->galeri)<div class="text-muted small mt-1">{{ count($item->galeri) }} foto tersimpan (upload baru akan ditambahkan)</div>@endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama agen / pemilik</label>
        <input type="text" name="agen_nama" class="form-control" value="{{ old('agen_nama', $item->agen_nama ?? 'Antosa Architect') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Peran</label>
        <input type="text" name="agen_peran" class="form-control" value="{{ old('agen_peran', $item->agen_peran ?? 'Pemilik Properti') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">No. WhatsApp</label>
        <input type="text" name="agen_telepon" class="form-control" placeholder="0851..." value="{{ old('agen_telepon', $item->agen_telepon) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Foto agen</label>
        <input type="file" name="agen_foto" accept="image/*" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" @checked(old('is_published', $item->exists ? $item->is_published : true))>
            <span class="form-check-label">Tayangkan di homepage</span>
        </label>
    </div>
</div>
