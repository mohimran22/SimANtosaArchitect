@extends('tablar::page')

@section('content')

<div class="page-body">
    <div class="container-xl dashboard-container">
        <div id="alertCarousel" class="position-relative">
            <div class="overflow-hidden rounded-4 shadow-sm bg-white position-relative">
                <div class="alert-wrapper d-flex">
                    @if($incompleteProfile)
                        <div class="alert alert-warning alert-dismissible fade show alert-item mb-0 flex-shrink-0 border-0 rounded-0" role="alert">
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

                    <div class="alert alert-warning alert-dismissible fade show alert-item mb-0 flex-shrink-0 border-0 rounded-0" role="alert">
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

                <button class="btn btn-sm btn-light border position-absolute top-50 end-0 translate-middle-y me-2 shadow-sm" 
                    id="nextAlert" title="Berikutnya" style="border-radius: 50%;">
                    <i class="ti ti-chevron-right fs-5"></i>
                </button>
            </div>
        </div>
        <div class="pt-5 pb-7 text-center">
            <h2 class="fw-bold g-4">
                Selamat Datang {{ auth()->user()->fullname ?? 'Admin Utama' }} di Sistem Antosa Architect
            </h2>
        </div>
        @if(auth()->user()->isInternal())

        <div class="row mb-4">
            <div class="col-lg-6 col-xl-5 mx-auto">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h3 class="mb-1">
                                Selamat Pagi,
                                <strong>{{ auth()->user()->fullname }}</strong>
                            </h3>

                            <div class="text-secondary">
                                {{ now()->translatedFormat('l, d F Y') }}
                            </div>

                            <div class="fs-2 fw-bold mt-2" id="clock"></div>
                        </div>

                        <hr>

                        @if(!$attendanceToday)

                            {{-- BELUM HADIR --}}
                            <div class="text-center py-2">

                                <div class="mb-2 text-secondary">
                                    Status Absensi Hari Ini
                                </div>

                                <h2 class="text-warning mb-3">
                                    ⭕ Belum Hadir
                                </h2>

                                <button
                                    class="btn btn-dark btn-lg px-5 rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#checkInModal">

                                    <i class="ti ti-login me-2"></i>
                                    Silahkan absen

                                </button>

                            </div>
                        @elseif(is_null($attendanceToday->check_out))

                            {{-- SUDAH HADIR --}}
                            <div class="text-center">

                                <h2 class="text-success mb-3">
                                    ✅ Sudah Hadir
                                </h2>

                                <div class="row mt-4">

                                    <div class="col">
                                        <small class="text-secondary">Jam Masuk</small>
                                        <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
                                    </div>

                                    <div class="col">
                                        <small class="text-secondary">Jam Pulang</small>
                                        <h4>--:--</h4>
                                    </div>

                                </div>

                                <form action="{{ route('attendances.check-out') }}" method="POST" class="mt-4">
                                    @csrf
                                    <button class="btn btn-danger btn-lg rounded-pill">
                                        <i class="ti ti-logout me-2"></i>
                                        Pulang
                                    </button>
                                </form>

                            </div>

                        @else

                            {{-- SUDAH PULANG --}}
                            <div class="text-center">

                                <h2 class="text-success mb-4">
                                    ✅ Absensi Selesai
                                </h2>

                                <div class="row">

                                    <div class="col">
                                        <small class="text-secondary">Jam Masuk</small>
                                        <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
                                    </div>

                                    <div class="col">
                                        <small class="text-secondary">Jam Pulang</small>
                                        <h4>{{ $attendanceToday->check_out->format('H:i') }}</h4>
                                    </div>

                                </div>

                            </div>

                        @endif
                    </div>
                </div>

            </div>
        </div>

        @endif
        <div class="modal fade" id="checkInModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Absensi Masuk</h5>
                    </div>

                <form action="{{ route('attendances.check-in') }}" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <video id="camera" autoplay playsinline class="img-fluid rounded border"></video>
                        <canvas
                            id="canvas"
                            class="d-none">
                        </canvas>

                        <img id="preview" class="img-fluid rounded border d-none">
                        <input type="hidden" id="photo" name="photo">
                        <input type="hidden" name="check_in_lat" id="check_in_lat">
                        <input type="hidden" name="check_in_lng" id="check_in_lng">
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" id="capture" class="btn btn-dark">
                            📸 Ambil Foto
                        </button>
                        <button type="button" id="retake" class="btn btn-secondary d-none">
                            🔄 Ambil Ulang
                        </button>
                        <button type="submit" id="confirm" class="btn btn-success d-none">
                            ✅ Konfirmasi Hadir
                        </button>
                    </div>
                </form>
                </div>
            </div>
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
<script>

function updateClock() {

    const now = new Date();

    document.getElementById('clock').innerHTML =
        now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        }) + ' WIB';
}

updateClock();
setInterval(updateClock, 1000);
let stream;

const camera = document.getElementById('camera');
const canvas = document.getElementById('canvas');
const preview = document.getElementById('preview');

const capture = document.getElementById('capture');
const retake = document.getElementById('retake');
const confirm = document.getElementById('confirm');

const photo = document.getElementById('photo');
const latInput = document.getElementById('check_in_lat');
const lngInput = document.getElementById('check_in_lng');
const modal = document.getElementById('checkInModal');
if(modal){
    modal.addEventListener('shown.bs.modal', async () => {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {

            alert("Browser tidak mendukung Camera API.");

            return;
        }
        stream = await navigator.mediaDevices.getUserMedia({
            video:{
                facingMode:"user"
            }
        });
        camera.srcObject = stream;
        if ('geolocation' in navigator) {

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latInput.value = position.coords.latitude;
                    lngInput.value = position.coords.longitude;
                },
                (err) => {
                    console.error(err);
                    alert("Tidak bisa mendapatkan lokasi.");
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );

        } else {
            alert("Browser tidak mendukung Geolocation.");
        }
    });
}
capture.addEventListener('click',()=>{

    canvas.width = camera.videoWidth;
    canvas.height = camera.videoHeight;

    canvas.getContext('2d')
        .drawImage(camera,0,0);

    const image = canvas.toDataURL('image/jpeg');

    photo.value = image;
    preview.src = image;
    preview.classList.remove('d-none');
    camera.classList.add('d-none');
    capture.classList.add('d-none');
    retake.classList.remove('d-none');
    confirm.classList.remove('d-none');
});
retake.addEventListener('click',()=>{
    photo.value = '';
    preview.classList.add('d-none');

    camera.classList.remove('d-none');

    capture.classList.remove('d-none');

    retake.classList.add('d-none');

    confirm.classList.add('d-none');

});
</script>
@endpush