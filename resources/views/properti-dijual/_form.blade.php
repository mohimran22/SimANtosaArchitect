<style>
    .foto-preview{
        border:1px dashed var(--tblr-border-color, #d9dce1); border-radius:10px; background:#f8f9fb;
        min-height:130px; display:flex; align-items:center; justify-content:center; overflow:hidden;
    }
    .foto-preview img{ max-width:100%; max-height:220px; object-fit:cover; display:block; }
    .foto-preview.avatar{ width:110px; height:110px; min-height:0; border-radius:50%; }
    .foto-preview.avatar img{ width:100%; height:100%; max-height:none; }
    .galeri-grid{ display:flex; flex-wrap:wrap; gap:12px; }
    .galeri-item{ position:relative; width:130px; height:95px; }
    .galeri-item img{ width:100%; height:100%; object-fit:cover; border-radius:8px; border:1px solid #e3e6eb; }
    .galeri-item .btn-x{
        position:absolute; top:4px; right:4px; width:22px; height:22px; padding:0; line-height:20px;
        border:none; border-radius:50%; background:rgba(220,38,38,.92); color:#fff; font-size:15px; cursor:pointer;
    }
</style>

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

    {{-- Harga: tampil berformat Rupiah, yang dikirim ke server angka murni --}}
    <div class="col-md-4">
        <label class="form-label">Harga</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="text" id="hargaView" class="form-control" inputmode="numeric" autocomplete="off" placeholder="0" required>
        </div>
        <input type="hidden" name="harga" id="hargaRaw" value="{{ old('harga', $item->harga) }}">
        <div class="form-hint" id="hargaHint">&nbsp;</div>
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

    @foreach ([['kt','Kamar Tidur',null], ['km','Kamar Mandi',null], ['lt','Luas Tanah','m²'], ['lb','Luas Bangunan','m²']] as [$f, $lbl, $suf])
        <div class="col-6 col-md-3">
            <label class="form-label">{{ $lbl }}</label>
            <div class="input-group">
                <input type="number" min="0" name="{{ $f }}" class="form-control" value="{{ old($f, $item->$f) }}">
                @if ($suf)<span class="input-group-text">{{ $suf }}</span>@endif
            </div>
        </div>
    @endforeach

    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $item->deskripsi) }}</textarea>
    </div>

    {{-- Foto utama --}}
    <div class="col-md-6">
        <label class="form-label">Foto utama</label>
        <input type="file" name="foto" id="inpFoto" accept="image/*" class="form-control" data-preview="#prevFoto" data-max="4">
        <div class="foto-preview mt-2" id="prevFoto" data-empty="Belum ada foto">
            @if ($item->foto)<img src="{{ asset('storage/'.$item->foto) }}" alt="">@else<span class="text-muted small">Belum ada foto</span>@endif
        </div>
    </div>

    {{-- Foto tambahan --}}
    <div class="col-md-6">
        <label class="form-label">Foto tambahan (bisa banyak)</label>
        <input type="file" name="galeri[]" id="inpGaleri" accept="image/*" multiple class="form-control">
        <div class="form-hint">JPG/PNG, maks 4 MB per foto. Pilih lagi untuk menambah; klik × untuk membatalkan satu foto.</div>
        <div class="foto-preview mt-2 d-block p-2" id="prevGaleri">
            <span class="text-muted small" id="galeriKosong">Belum ada foto baru dipilih</span>
            <div class="galeri-grid" id="galeriGrid"></div>
        </div>
    </div>

    {{-- Foto tersimpan (halaman edit) --}}
    @if ($item->exists && $item->fotos->isNotEmpty())
        <div class="col-12">
            <label class="form-label">Foto tersimpan ({{ $item->fotos->count() }})</label>
            <div class="galeri-grid">
                @foreach ($item->fotos as $f)
                    <div class="galeri-item">
                        <img src="{{ asset('storage/'.$f->path) }}" alt="">
                        {{-- dikirim lewat form terpisah (#formHapusFoto) karena tidak boleh ada form di dalam form --}}
                        <button type="button" class="btn-x js-hapus-foto"
                                data-action="{{ route('jual.foto.destroy', $f) }}" title="Hapus foto">&times;</button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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

    {{-- Foto agen --}}
    <div class="col-md-2">
        <label class="form-label">Foto agen</label>
        <input type="file" name="agen_foto" id="inpAgen" accept="image/*" class="form-control" data-preview="#prevAgen" data-max="2">
        <div class="foto-preview avatar mt-2" id="prevAgen" data-empty="">
            @if ($item->agen_foto)<img src="{{ asset('storage/'.$item->agen_foto) }}" alt="">@endif
        </div>
    </div>

    <div class="col-12">
        <label class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" @checked(old('is_published', $item->exists ? $item->is_published : true))>
            <span class="form-check-label">Tayangkan di homepage</span>
        </label>
    </div>
</div>

<script>
(function () {
    // ===== Harga berformat Rupiah =====
    var view = document.getElementById('hargaView');
    var raw  = document.getElementById('hargaRaw');
    var hint = document.getElementById('hargaHint');

    function ringkas(n) {
        if (n >= 1e9) return '≈ ' + (n / 1e9).toLocaleString('id-ID', {maximumFractionDigits: 2}) + ' Miliar';
        if (n >= 1e6) return '≈ ' + (n / 1e6).toLocaleString('id-ID', {maximumFractionDigits: 2}) + ' Juta';
        return '\u00a0';
    }
    function setHarga(digits) {
        digits = (digits || '').replace(/\D/g, '').replace(/^0+(?=\d)/, '');
        raw.value  = digits;
        view.value = digits ? Number(digits).toLocaleString('id-ID') : '';
        hint.textContent = digits ? ringkas(Number(digits)) : '\u00a0';
    }
    setHarga(raw.value);
    view.addEventListener('input', function () { setHarga(view.value); });

    // ===== Preview foto tunggal (foto utama & foto agen) =====
    function bindSingle(input) {
        var box = document.querySelector(input.dataset.preview);
        var original = box.innerHTML;
        var maxMb = parseFloat(input.dataset.max || '4');
        var url = null;

        input.addEventListener('change', function () {
            if (url) { URL.revokeObjectURL(url); url = null; }
            var f = input.files[0];
            if (!f) { box.innerHTML = original; return; }
            if (!f.type.startsWith('image/')) { alert('File harus berupa gambar.'); input.value = ''; box.innerHTML = original; return; }
            if (f.size > maxMb * 1024 * 1024) { alert('Ukuran foto maksimal ' + maxMb + ' MB.'); input.value = ''; box.innerHTML = original; return; }
            url = URL.createObjectURL(f);
            box.innerHTML = '<img src="' + url + '" alt="">';
        });
    }
    ['inpFoto', 'inpAgen'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) bindSingle(el);
    });

    // ===== Preview foto tambahan (banyak, bisa dibatalkan satu per satu) =====
    var inp   = document.getElementById('inpGaleri');
    var grid  = document.getElementById('galeriGrid');
    var kosong = document.getElementById('galeriKosong');
    var dt = new DataTransfer();

    function render() {
        grid.innerHTML = '';
        kosong.style.display = dt.files.length ? 'none' : '';
        Array.from(dt.files).forEach(function (file, i) {
            var item = document.createElement('div');
            item.className = 'galeri-item';
            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.onload = function () { URL.revokeObjectURL(img.src); };
            var x = document.createElement('button');
            x.type = 'button'; x.className = 'btn-x'; x.innerHTML = '&times;'; x.title = 'Batalkan foto ini';
            x.addEventListener('click', function () {
                var next = new DataTransfer();
                Array.from(dt.files).forEach(function (f, j) { if (j !== i) next.items.add(f); });
                dt = next; inp.files = dt.files; render();
            });
            item.appendChild(img); item.appendChild(x); grid.appendChild(item);
        });
    }

    inp.addEventListener('change', function () {
        var ditolak = [];
        Array.from(inp.files).forEach(function (f) {
            var dobel = Array.from(dt.files).some(function (e) {
                return e.name === f.name && e.size === f.size && e.lastModified === f.lastModified;
            });
            if (dobel) return;
            if (!f.type.startsWith('image/') || f.size > 4 * 1024 * 1024) { ditolak.push(f.name); return; }
            dt.items.add(f);
        });
        inp.files = dt.files;
        render();
        if (ditolak.length) alert('Tidak ditambahkan (bukan gambar / lebih dari 4 MB):\n' + ditolak.join('\n'));
    });
})();
</script>

@include('properti-dijual._swal')