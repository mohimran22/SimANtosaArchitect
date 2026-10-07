<style>
    .foto-preview{
        border:1px dashed var(--tblr-border-color, #d9dce1); border-radius:10px; background:#f8f9fb;
        min-height:130px; display:flex; align-items:center; justify-content:center; overflow:hidden;
    }
    .foto-preview img{ max-width:100%; max-height:220px; object-fit:cover; display:block; }
    .foto-preview.avatar{ width:110px; height:110px; min-height:0; border-radius:50%; }
    .foto-preview.avatar img{ width:100%; height:100%; max-height:none; }
    /* select2 di dalam .input-group (dropdown Tipe + tombol +) */
    .input-group > .select2-container{ flex:1 1 auto; width:1% !important; min-width:0; }
    .input-group > .select2-container .select2-selection{
        border-top-right-radius:0; border-bottom-right-radius:0; height:100%;
    }
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
        <select name="status" class="form-select select2" data-minimum-results-for-search="-1">
            @foreach (['dijual','disewa','terjual'] as $v)
                <option value="{{ $v }}" @selected(old('status', $item->status ?? 'dijual') === $v)>{{ ucfirst($v) }}</option>
            @endforeach
        </select>
    </div>
    @php
        $tipeList = \App\Models\TipeProperti::orderBy('nama')->pluck('nama');
        if ($item->tipe && ! $tipeList->contains($item->tipe)) { $tipeList->push($item->tipe); }
        $tipeDipilih = old('tipe', $item->tipe ?? ($tipeList->contains('rumah') ? 'rumah' : $tipeList->first()));
    @endphp
    <div class="col-md-4">
        <label class="form-label">Tipe</label>
        <div class="input-group">
            <select name="tipe" id="tipeSelect" class="form-select select2">
                @foreach ($tipeList as $v)
                    <option value="{{ $v }}" @selected($tipeDipilih === $v)>{{ ucfirst($v) }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-outline-secondary" id="btnTipeBaru" title="Tambah tipe baru">
                <i class="ti ti-plus"></i>
            </button>
        </div>
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
    @php
        $provId = old('province_id', $item->province_id);
        $cityId = old('city_id', $item->city_id);
        $distId = old('district_id', $item->district_id);
        $subId  = old('sub_district_id', $item->sub_district_id);
        $postId = old('postal_code_id', $item->postal_code_id);

        $provinces = \App\Models\Province::orderBy('name')->get(['id', 'name']);
        $cities    = $provId ? \App\Models\City::where('province_id', $provId)->orderBy('name')->get(['id', 'name']) : collect();
        $districts = $cityId ? \App\Models\District::where('city_id', $cityId)->orderBy('name')->get(['id', 'name']) : collect();
        $subs      = $distId ? \App\Models\SubDistrict::where('district_id', $distId)->orderBy('name')->get(['id', 'name']) : collect();
        $posts     = $subId ? \App\Models\PostalCode::where('sub_district_id', $subId)->get(['id', 'postal_code']) : collect();
    @endphp

    <div class="col-12 mt-4">
        <h4 class="mb-0"><i class="ti ti-map-pin me-1"></i> Lokasi Properti</h4>
        <hr class="mt-2 mb-0">
    </div>
    <div class="col-md-6">
        <label class="form-label">Provinsi</label>
        <select name="province_id" id="province" class="form-select select2" required>
            <option value="">-- Pilih Provinsi --</option>
            @foreach ($provinces as $p)
                <option value="{{ $p->id }}" @selected((string) $provId === (string) $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Kabupaten/Kota</label>
        <select name="city_id" id="city" class="form-select select2" required>
            <option value="">-- Pilih Kota --</option>
            @foreach ($cities as $c)
                <option value="{{ $c->id }}" @selected((string) $cityId === (string) $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <label class="form-label">Kecamatan</label>
        <select name="district_id" id="district" class="form-select select2">
            <option value="">-- Pilih Kecamatan --</option>
            @foreach ($districts as $d)
                <option value="{{ $d->id }}" @selected((string) $distId === (string) $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <label class="form-label">Kelurahan</label>
        <select name="sub_district_id" id="sub_district" class="form-select select2">
            <option value="">-- Pilih Kelurahan --</option>
            @foreach ($subs as $sd)
                <option value="{{ $sd->id }}" @selected((string) $subId === (string) $sd->id)>{{ $sd->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Kode Pos</label>
        <select name="postal_code_id" id="postal_code" class="form-select select2">
            <option value="">-- Kode Pos --</option>
            @foreach ($posts as $pc)
                <option value="{{ $pc->id }}" @selected((string) $postId === (string) $pc->id)>{{ $pc->postal_code }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Alamat / nama jalan (opsional)</label>
        <input type="text" name="lokasi" class="form-control" placeholder="mis. Jl. Mawar No. 5, Perumahan Griya Asri" value="{{ old('lokasi', $item->lokasi) }}">
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

    @php
        $karyawan = \App\Models\Employee::with('user:id,fullname,phone,photo')->get()
            ->filter(fn ($e) => $e->user)
            ->sortBy(fn ($e) => mb_strtolower($e->user->fullname))
            ->values();

        // tambah: default karyawan yang sedang login | edit: agen yang tersimpan (boleh kosong)
        $agenDipilih = old('employee_id', $item->exists ? $item->employee_id : auth()->user()?->employee?->id);
    @endphp
    <div class="col-md-6">
        <label class="form-label">Agen / Karyawan</label>
        <select name="employee_id" id="agenSelect" class="form-select select2">
            <option value="">Antosa Architect (tanpa karyawan tertentu)</option>
            @foreach ($karyawan as $e)
                @php
                    $jabatan = collect($e->position ?? [])->first(fn ($p) => is_string($p) && ! is_numeric($p) && trim($p) !== '');
                @endphp
                <option value="{{ $e->id }}"
                        data-phone="{{ $e->user->phone }}"
                        data-jabatan="{{ $jabatan }}"
                        data-foto="{{ $e->user->photo ? asset('storage/'.$e->user->photo) : '' }}"
                        @selected((string) $agenDipilih === (string) $e->id)>
                    {{ $e->user->fullname }}
                </option>
            @endforeach
        </select>
        <div class="form-hint">Nama, nomor WhatsApp, dan foto agen diambil otomatis dari data karyawan.</div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Pratinjau agen</label>
        <div id="agenPreview" class="d-flex align-items-center gap-3 border rounded p-2" style="min-height:58px"></div>
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

<script>
// ===== Dropdown wilayah bertingkat (memakai endpoint /api/... yang sama dengan form user) =====
document.addEventListener('DOMContentLoaded', function () {
    var $ = window.jQuery;
    if (!$) return;

    // --- Inisialisasi select2 untuk dropdown wilayah ---
    // Kalau layout sudah memuat select2, langsung dipakai. Kalau belum, dimuat dari CDN.
    function pakaiSelect2(pakaiTema) {
        $('#province, #city, #district, #sub_district, #postal_code, #tipeSelect, #agenSelect, select[name="status"]').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) return;   // sudah diinisialisasi layout
            var opsi = { width: '100%' };
            if (this.dataset.minimumResultsForSearch) opsi.minimumResultsForSearch = parseInt(this.dataset.minimumResultsForSearch, 10) < 0 ? Infinity : parseInt(this.dataset.minimumResultsForSearch, 10);
            if (pakaiTema) opsi.theme = 'bootstrap-5';
            $(this).select2(opsi);
        });
    }

    if ($.fn.select2) {
        pakaiSelect2(false);
    } else {
        var css1 = document.createElement('link');
        css1.rel = 'stylesheet';
        css1.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
        var css2 = document.createElement('link');
        css2.rel = 'stylesheet';
        css2.href = 'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css';
        document.head.appendChild(css1);
        document.head.appendChild(css2);

        var js = document.createElement('script');
        js.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        js.onload = function () { pakaiSelect2(true); };
        document.head.appendChild(js);
    }

    var rantai = [
        { el: '#province',     next: '#city',        url: '/api/cities/',       label: '-- Pilih Kota --' },
        { el: '#city',         next: '#district',    url: '/api/districts/',    label: '-- Pilih Kecamatan --' },
        { el: '#district',     next: '#sub_district',url: '/api/sub_districts/',label: '-- Pilih Kelurahan --' },
        { el: '#sub_district', next: '#postal_code', url: '/api/postal_codes/', label: '-- Kode Pos --', text: 'postal_code' }
    ];

    function kosongkan(sel, label) {
        $(sel).empty().append(new Option(label, '')).trigger('change.select2');
    }

    rantai.forEach(function (r, i) {
        $(r.el).on('change', function () {
            var id = $(this).val();
            for (var j = i; j < rantai.length; j++) kosongkan(rantai[j].next, rantai[j].label);
            if (!id) return;

            $.get(r.url + id, function (data) {
                var $n = $(r.next);
                data.forEach(function (d) { $n.append(new Option(d[r.text || 'name'], d.id)); });
                $n.trigger('change.select2');
            });
        });
    });
});
</script>

<script>
// ===== Pratinjau agen =====
document.addEventListener('DOMContentLoaded', function () {
    var sel = document.getElementById('agenSelect');
    var box = document.getElementById('agenPreview');
    if (!sel || !box) return;

    function tampil() {
        var o = sel.options[sel.selectedIndex];
        box.innerHTML = '';
        if (!o || !o.value) {
            var t = document.createElement('span');
            t.className = 'text-muted small';
            t.textContent = 'Kartu listing memakai kontak Antosa Architect.';
            box.appendChild(t);
            return;
        }
        var foto = document.createElement(o.dataset.foto ? 'img' : 'span');
        if (o.dataset.foto) {
            foto.src = o.dataset.foto;
            foto.style.cssText = 'width:42px;height:42px;border-radius:50%;object-fit:cover';
        } else {
            foto.className = 'avatar';
            foto.textContent = o.text.trim().charAt(0).toUpperCase();
        }
        var info = document.createElement('div');
        var nama = document.createElement('strong');
        nama.textContent = o.text.trim();
        var det = document.createElement('div');
        det.className = 'text-muted small';
        det.textContent = [o.dataset.jabatan, o.dataset.phone].filter(Boolean).join(' · ') || 'Nomor WhatsApp belum diisi di data karyawan';
        info.appendChild(nama); info.appendChild(det);
        box.appendChild(foto); box.appendChild(info);
    }
    // select2 hanya memicu event 'change' milik jQuery, jadi pakai jQuery bila tersedia
    if (window.jQuery) { jQuery(sel).on('change', tampil); } else { sel.addEventListener('change', tampil); }
    tampil();
});
</script>

<script>
// ===== Tambah tipe properti baru dari form =====
(function () {
    var btn = document.getElementById('btnTipeBaru');
    var sel = document.getElementById('tipeSelect');
    if (!btn || !sel) return;

    btn.addEventListener('click', async function () {
        try { await window.pastikanSwal(); } catch (e) { alert('SweetAlert gagal dimuat.'); return; }

        var r = await Swal.fire({
            title: 'Tambah tipe properti',
            input: 'text',
            inputPlaceholder: 'mis. Villa, Gudang, Kos-kosan',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            inputValidator: function (v) { if (!v || !v.trim()) return 'Nama tipe wajib diisi'; }
        });
        if (!r.isConfirmed) return;

        try {
            var res = await fetch("{{ route('jual.tipe.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nama: r.value })
            });
            var data = await res.json();
            if (!res.ok) throw new Error((data.errors && Object.values(data.errors)[0][0]) || data.message || 'Gagal menyimpan');

            var ada = Array.from(sel.options).some(function (o) { return o.value === data.nama; });
            if (!ada) {
                var o = new Option(data.label, data.nama);
                sel.add(o);
                // urutkan A-Z supaya rapi
                Array.from(sel.options).sort(function (a, b) { return a.text.localeCompare(b.text); })
                     .forEach(function (x) { sel.add(x); });
            }
            sel.value = data.nama;
            if (window.jQuery) jQuery(sel).trigger('change');   // refresh tampilan select2
            Swal.fire({ icon: 'success', title: data.baru ? 'Tipe ditambahkan' : 'Tipe sudah ada', timer: 1400, showConfirmButton: false });
        } catch (err) {
            Swal.fire('Gagal', err.message, 'error');
        }
    });
})();
</script>

@include('properti-dijual._swal')