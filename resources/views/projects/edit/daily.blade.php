<form id="editReportForm">

    @csrf
    @method('PUT')

    <input type="hidden" name="id" value="{{ $report->id }}">

    {{-- TANGGAL --}}
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date"
               name="tanggal"
               class="form-control"
               value="{{ $report->tanggal }}">
    </div>

    {{-- LIBUR --}}
    <div class="form-check mb-3">
        <input type="checkbox"
               name="is_libur"
               value="1"
               class="form-check-input"
               {{ $report->is_libur ? 'checked' : '' }}>
        <label class="form-check-label">Hari Libur</label>
    </div>

    <hr>

    {{-- ================= BAHAN ================= --}}
    <h6>Bahan</h6>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Volume</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="bahanWrapper">
            @foreach($report->material as $i => $material)
                <tr>
                    <td>
                        <input type="text"
                               name="material[{{ $i }}][nama]"
                               class="form-control"
                               value="{{ $material->nama }}">
                    </td>
                    <td>
                        <input type="number"
                               name="material[{{ $i }}][volume]"
                               class="form-control"
                               value="{{ $material->volume }}">
                    </td>
                    <td>
                        <button type="button"
                                class="btn btn-danger btn-sm remove-row">
                            x
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" id="addmaterial" class="btn btn-sm btn-secondary">
        + Tambah Bahan
    </button>

    <hr>

    {{-- Pekerjaan dan Material tinggal duplikat pola yang sama --}}

</form>