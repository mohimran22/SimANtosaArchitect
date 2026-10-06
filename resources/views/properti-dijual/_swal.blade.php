<script>
(function () {
    if (window.__konfirmasiHapus) return;
    window.__konfirmasiHapus = true;

    // Pakai SweetAlert2 dari layout kalau sudah ada; kalau belum, muat dari CDN.
    function loadSwal() {
        if (window.Swal) return Promise.resolve();
        return new Promise(function (res, rej) {
            var s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            s.onload = res; s.onerror = rej;
            document.head.appendChild(s);
        });
    }

    window.pastikanSwal = loadSwal;   // dipakai script lain (mis. tambah tipe properti)

    function tanya(judul, teks) {
        return loadSwal().then(function () {
            return Swal.fire({
                title: judul,
                text: teks,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d63939',
                reverseButtons: true,
                focusCancel: true
            }).then(function (r) { return r.isConfirmed; });
        }).catch(function () {
            return window.confirm(judul);   // cadangan kalau CDN gagal dimuat
        });
    }

    // Form hapus properti (form.js-hapus) — termasuk baris yang dibuat DataTables
    document.addEventListener('submit', function (e) {
        var f = e.target.closest ? e.target.closest('form.js-hapus') : null;
        if (!f) return;
        e.preventDefault();
        tanya(f.dataset.judul || 'Hapus data ini?', f.dataset.teks || 'Data yang dihapus tidak dapat dikembalikan.')
            .then(function (ok) { if (ok) f.submit(); });
    });

    // Tombol hapus foto tersimpan (halaman edit)
    document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('.js-hapus-foto') : null;
        if (!b) return;
        e.preventDefault();
        tanya('Hapus foto ini?', 'Foto akan dihapus permanen.').then(function (ok) {
            if (!ok) return;
            var f = document.getElementById('formHapusFoto');
            f.action = b.dataset.action;
            f.submit();
        });
    });
})();
</script>