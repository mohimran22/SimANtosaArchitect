@extends('tablar::page')

@section('content')

<div class="page-body">
    <div class="container-xl dashboard-container">
        <div id="alertCarousel" class="position-relative mb-4">
            <div class="overflow-hidden rounded-4 shadow-sm bg-white position-relative">
                <div class="alert-wrapper d-flex" style="width: max-content;">
                    {{-- 🔸 Alert 1 --}}
                    @if($incompleteProfile)
                        <div class="alert alert-warning alert-dismissible fade show alert-item mb-0 flex-shrink-0 w-100 border-0 rounded-0" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-user-exclamation me-2 fs-3"></i>
                                <div>
                                    <strong>Profil Belum Lengkap</strong>
                                    Lengkapi profilmu untuk unlock fitur penuh dan pelayanan yang lebih personal dari 
                                    <b>Antosa Architect</b>.
                                    <a href="{{ route('customer.profile') }}" class="alert-link text-warning fw-semibold">Lengkapi sekarang.</a>
                                </div>
                            </div>                  
                        </div>
                    @endif

                    {{-- 🔸 Alert 2 --}}
                    <div class="alert alert-warning alert-dismissible fade show alert-item mb-0 flex-shrink-0 w-100 border-0 rounded-0" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-exclamation-circle me-2 fs-3"></i>
                            <div>
                                <strong>Lengkapi Profil Affiliator!</strong>
                                Beberapa data penting untuk peran <b>Affiliator</b> belum diisi.
                                <a href="{{ route('affiliators.profile') }}" class="alert-link text-warning fw-semibold">Klik di sini untuk melengkapi.</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol panah kanan --}}
                <button class="btn btn-sm btn-light border position-absolute top-50 end-0 translate-middle-y me-2 shadow-sm" 
                    id="nextAlert" title="Berikutnya" style="border-radius: 50%;">
                    <i class="ti ti-chevron-right fs-5"></i>
                </button>
            </div>
        </div>

        <div class="pt-3 pb-2 text-center">
            <h2 class="fw-bold mb-3">
                Selamat Datang {{ auth()->user()->fullname ?? 'Admin Utama' }} di Sistem Antosa Architect
            </h2>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.querySelector('.alert-wrapper');
    if (!wrapper) return;

    const alerts = document.querySelectorAll('.alert-item');
    const total = alerts.length;
    let currentIndex = 0;

    function updateSlide() {
        const offset = -currentIndex * 100;
        wrapper.style.transform = `translateX(${offset}%)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % total;
        updateSlide();
    }

    const nextBtn = document.getElementById('nextAlert');
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }

    updateSlide();
});
</script>

<style>
.alert-wrapper {
    transition: transform 0.6s ease-in-out;
    display: flex;
}

/* ✅ Border radius halus dan jarak antar komponen */
#alertCarousel .overflow-hidden {
    border-radius: 0.75rem;
}

/* ✅ Tambah jarak dari sidebar kiri via container-xl bawaan Tablar */
.dashboard-container {
    margin-top: 16px;
    padding-left: 30px !important;
}

/* Tombol panah elegan */
#alertCarousel button#nextAlert {
    transition: all 0.2s ease-in-out;
    z-index: 10;
}
#alertCarousel button#nextAlert:hover {
    background-color: #f8f9fa;
    transform: translateY(-50%) scale(1.1);
}

/* Bayangan lembut agar floating */
#alertCarousel .alert {
    box-shadow: inset 0 -1px 0 rgba(0,0,0,0.05);
}
</style>
@endpush
