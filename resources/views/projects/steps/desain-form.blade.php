<form action="{{ route('projects.offers.store') }}" method="POST">
    @csrf

    <input type="hidden" name="project_id" value="{{ $project->id }}">

    <h4 class="fw-bold mb-3">Informasi Penawaran</h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Nomor Penawaran</label>
            <input type="text" name="nomor_penawaran" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Tanggal Penawaran</label>
            <input type="date" name="tanggal_penawaran" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Nama Customer</label>
            <input type="text" name="kepada_nama" value="{{ $project->customer->user->fullname }}" class="form-control">
        </div>
    </div>

    <div class="mb-3">
        <label>Alamat / Lokasi</label>
        <input type="text" name="kepada_alamat" class="form-control" value="{{ $project->city->name ?? '' }}">
    </div>

    <div class="mb-3">
        <label>Jenis Pekerjaan</label>
        <input type="text" name="jenis_pekerjaan" class="form-control">
    </div>

    <div class="mb-3">
        <label>Lokasi Pekerjaan</label>
        <input type="text" name="lokasi" class="form-control" value="{{ $project->project_location ?? '' }}">
    </div>

    <hr>

    <h4 class="fw-bold mb-3">Harga Penawaran</h4>

    <div class="row mb-3">
        <div class="col-md-5">
            <label>Nama Paket</label>
            <input type="text" name="paket_nama" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Volume</label>
            <input type="text" name="volume" class="form-control">
        </div>
        <div class="col-md-2">
            <label>Harga Satuan (Rp)</label>
            <input type="number" name="harga_satuan" class="form-control">
        </div>
        <div class="col-md-2">
            <label>Total Harga (Rp)</label>
            <input type="number" name="total_harga" class="form-control">
        </div>
    </div>

    <hr>

    <h4 class="fw-bold mb-3">Rincian Pekerjaan</h4>

    <table class="table table-bordered" id="items-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Nama Item</th>
                <th>Optional?</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <button type="button" class="btn btn-sm btn-dark" id="add-item">Tambah Item</button>

    <hr>

    <h4 class="fw-bold mb-3">Keterangan</h4>

    <textarea name="keterangan" rows="5" class="form-control"></textarea>

    <div class="mt-4">
        <button class="btn btn-primary">Simpan Penawaran</button>
    </div>
</form>
