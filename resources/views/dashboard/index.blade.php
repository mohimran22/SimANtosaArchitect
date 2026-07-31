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
                            @include('attendances.partials.check-in')
                        @elseif(is_null($attendanceToday->check_out))
                            @include('attendances.partials.check-out')
                        @elseif(!$attendanceToday->overtime)
                            @include('attendances.partials.after-checkout')
                        @elseif(is_null($attendanceToday->overtime->end_time))
                            @include('attendances.partials.overtime-running')
                        @else
                            @include('attendances.partials.overtime-finished')
                        @endif
                    </div>
                </div>

            </div>
        </div>

        @endif
        {{-- <div class="modal fade" id="checkInModal">
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
        <div class="modal fade" id="checkOutModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Absensi Pulang</h5>
                    </div>

                    <form action="{{ route('attendances.check-out') }}" method="POST">
                        @csrf
                        <div class="modal-body text-center">
                            <video id="cameraCheckout" autoplay playsinline class="img-fluid rounded border"></video>
                            <canvas
                                id="canvasCheckout"
                                class="d-none">
                            </canvas>

                            <img id="previewCheckout" class="img-fluid rounded border d-none">
                            <input type="hidden" id="photoCheckOut" name="photo">
                            <input type="hidden" name="check_out_lat" id="check_out_lat">
                            <input type="hidden" name="check_out_lng" id="check_out_lng">
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" id="captureCheckout" class="btn btn-dark">
                                📸 Ambil Foto
                            </button>
                            <button type="button" id="retakeCheckout" class="btn btn-secondary d-none">
                                🔄 Ambil Ulang
                            </button>
                            <button type="submit" id="confirmCheckout" class="btn btn-success d-none">
                                ✅ Konfirmasi Pulang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        {{-- ===================== --}}
{{-- MODAL CAMERA --}}
{{-- ===================== --}}

<x-camera-modal
    modal-id="checkInModal"
    title="Absensi Masuk"
    :action="route('attendances.check-in')"
    prefix="checkIn"
    lat-name="check_in_lat"
    lng-name="check_in_lng"
    confirm-text="✅ Konfirmasi Hadir"
/>

<x-camera-modal
    modal-id="checkOutModal"
    title="Absensi Pulang"
    :action="route('attendances.check-out')"
    prefix="checkOut"
    lat-name="check_out_lat"
    lng-name="check_out_lng"
    confirm-text="✅ Konfirmasi Pulang"
/>

<x-camera-modal
    modal-id="startOvertimeModal"
    title="Mulai Lembur"
    :action="route('attendance-overtimes.start')"
    prefix="startOvertime"
    lat-name="start_lat"
    lng-name="start_lng"
    confirm-text="✅ Mulai Lembur"
/>

<x-camera-modal
    modal-id="finishOvertimeModal"
    title="Selesai Lembur"
    :action="route('attendance-overtimes.finish')"
    prefix="finishOvertime"
    lat-name="end_lat"
    lng-name="end_lng"
    confirm-text="✅ Selesai Lembur"
/>
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
// let stream;
// const modal = document.getElementById('checkInModal');
// const modalOut = document.getElementById('checkOutModal');

// const camera = document.getElementById('camera');
// const canvas = document.getElementById('canvas');
// const preview = document.getElementById('preview');
// const capture = document.getElementById('capture');
// const retake = document.getElementById('retake');
// const confirm = document.getElementById('confirm');
// const photo = document.getElementById('photo');
// const latInput = document.getElementById('check_in_lat');
// const lngInput = document.getElementById('check_in_lng');

// const cameraOut = document.getElementById('cameraCheckout');
// const canvasOut = document.getElementById('canvasCheckout');
// const previewOut = document.getElementById('previewCheckout');
// const captureOut = document.getElementById('captureCheckout');
// const retakeOut = document.getElementById('retakeCheckout');
// const confirmOut = document.getElementById('confirmCheckout');
// const photoOut = document.getElementById('photoCheckOut');
// const latOutInput = document.getElementById('check_out_lat');
// const lngOutInput = document.getElementById('check_out_lng');

// if(modal){
//     modal.addEventListener('shown.bs.modal', async () => {
//         if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {

//             alert("Browser tidak mendukung Camera API.");

//             return;
//         }
//         stream = await navigator.mediaDevices.getUserMedia({
//             video:{
//                 facingMode:"user"
//             }
//         });
//         camera.srcObject = stream;
//         if ('geolocation' in navigator) {

//             navigator.geolocation.getCurrentPosition(
//                 (position) => {
//                     latInput.value = position.coords.latitude;
//                     lngInput.value = position.coords.longitude;
//                 },
//                 (err) => {
//                     console.error(err);
//                     alert("Tidak bisa mendapatkan lokasi.");
//                 },
//                 {
//                     enableHighAccuracy: true,
//                     timeout: 10000,
//                     maximumAge: 0
//                 }
//             );

//         } else {
//             alert("Browser tidak mendukung Geolocation.");
//         }
//     });
// }
// capture.addEventListener('click',()=>{

//     canvas.width = camera.videoWidth;
//     canvas.height = camera.videoHeight;

//     canvas.getContext('2d')
//         .drawImage(camera,0,0);

//     const image = canvas.toDataURL('image/jpeg');

//     photo.value = image;
//     preview.src = image;
//     preview.classList.remove('d-none');
//     camera.classList.add('d-none');
//     capture.classList.add('d-none');
//     retake.classList.remove('d-none');
//     confirm.classList.remove('d-none');
// });
// retake.addEventListener('click',()=>{
//     photo.value = '';
//     preview.classList.add('d-none');

//     camera.classList.remove('d-none');

//     capture.classList.remove('d-none');

//     retake.classList.add('d-none');

//     confirm.classList.add('d-none');

// });
// if(modalOut){
//     modalOut.addEventListener('shown.bs.modal', async () => {
//         if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {

//             alert("Browser tidak mendukung Camera API.");

//             return;
//         }
//         stream = await navigator.mediaDevices.getUserMedia({
//             video:{
//                 facingMode:"user"
//             }
//         });
//         cameraOut.srcObject = stream;
//         if ('geolocation' in navigator) {

//             navigator.geolocation.getCurrentPosition(
//                 (position) => {
//                     latOutInput.value = position.coords.latitude;
//                     lngOutInput.value = position.coords.longitude;
//                 },
//                 (err) => {
//                     console.error(err);
//                     alert("Tidak bisa mendapatkan lokasi.");
//                 },
//                 {
//                     enableHighAccuracy: true,
//                     timeout: 10000,
//                     maximumAge: 0
//                 }
//             );

//         } else {
//             alert("Browser tidak mendukung Geolocation.");
//         }
//     });
// }
// captureOut.addEventListener('click',()=>{

//     canvasOut.width = cameraOut.videoWidth;
//     canvasOut.height = cameraOut.videoHeight;

//     canvasOut.getContext('2d')
//         .drawImage(cameraOut,0,0);

//     const image = canvasOut.toDataURL('image/jpeg');

//     photoOut.value = image;
//     previewOut.src = image;
//     previewOut.classList.remove('d-none');
//     cameraOut.classList.add('d-none');
//     captureOut.classList.add('d-none');
//     retakeOut.classList.remove('d-none');
//     confirmOut.classList.remove('d-none');
// });
// retake.addEventListener('click',()=>{
//     photoOut.value = '';
//     previewOut.classList.add('d-none');

//     cameraOut.classList.remove('d-none');

//     captureOut.classList.remove('d-none');

//     retakeOut.classList.add('d-none');

//     confirmOut.classList.add('d-none');

// });
let stream;

function initCamera(config) {

    const modal = document.getElementById(config.modal);
    if (!modal) return;

    const camera = document.getElementById(config.camera);
    const canvas = document.getElementById(config.canvas);
    const preview = document.getElementById(config.preview);

    const capture = document.getElementById(config.capture);
    const retake = document.getElementById(config.retake);
    const confirm = document.getElementById(config.confirm);

    const photo = document.getElementById(config.photo);

    const lat = document.getElementById(config.lat);
    const lng = document.getElementById(config.lng);

    modal.addEventListener('shown.bs.modal', async () => {

        if (!navigator.mediaDevices?.getUserMedia) {
            alert("Browser tidak mendukung Camera API.");
            return;
        }

        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "user"
            }
        });

        camera.srcObject = stream;

        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(

                (position) => {

                    lat.value = position.coords.latitude;
                    lng.value = position.coords.longitude;

                },

                () => {

                    alert("Tidak bisa mendapatkan lokasi.");

                },

                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }

            );

        }

    });

    modal.addEventListener('hidden.bs.modal', () => {

        if (stream) {

            stream.getTracks().forEach(track => track.stop());

        }

        camera.classList.remove('d-none');
        preview.classList.add('d-none');

        capture.classList.remove('d-none');
        retake.classList.add('d-none');
        confirm.classList.add('d-none');

        photo.value = '';

    });

    capture.addEventListener('click', () => {

        canvas.width = camera.videoWidth;
        canvas.height = camera.videoHeight;

        canvas.getContext('2d').drawImage(camera, 0, 0);

        const image = canvas.toDataURL('image/jpeg');

        photo.value = image;

        preview.src = image;

        preview.classList.remove('d-none');
        camera.classList.add('d-none');

        capture.classList.add('d-none');
        retake.classList.remove('d-none');
        confirm.classList.remove('d-none');

    });

    retake.addEventListener('click', () => {

        photo.value = '';

        preview.classList.add('d-none');
        camera.classList.remove('d-none');

        capture.classList.remove('d-none');
        retake.classList.add('d-none');
        confirm.classList.add('d-none');

    });

}
initCamera({

    modal: 'checkInModal',

    camera: 'checkInCamera',
    canvas: 'checkInCanvas',
    preview: 'checkInPreview',

    capture: 'checkInCapture',
    retake: 'checkInRetake',
    confirm: 'checkInConfirm',

    photo: 'checkInPhoto',

    lat: 'checkInLat',
    lng: 'checkInLng'

});
initCamera({

    modal: 'checkOutModal',

    camera: 'checkOutCamera',
    canvas: 'checkOutCanvas',
    preview: 'checkOutPreview',

    capture: 'checkOutCapture',
    retake: 'checkOutRetake',
    confirm: 'checkOutConfirm',

    photo: 'checkOutPhoto',

    lat: 'checkOutLat',
    lng: 'checkOutLng'

});
initCamera({

    modal: 'startOvertimeModal',

    camera: 'startOvertimeCamera',
    canvas: 'startOvertimeCanvas',
    preview: 'startOvertimePreview',

    capture: 'startOvertimeCapture',
    retake: 'startOvertimeRetake',
    confirm: 'startOvertimeConfirm',

    photo: 'startOvertimePhoto',

    lat: 'startOvertimeLat',
    lng: 'startOvertimeLng'

});
initCamera({

    modal: 'finishOvertimeModal',

    camera: 'finishOvertimeCamera',
    canvas: 'finishOvertimeCanvas',
    preview: 'finishOvertimePreview',

    capture: 'finishOvertimeCapture',
    retake: 'finishOvertimeRetake',
    confirm: 'finishOvertimeConfirm',

    photo: 'finishOvertimePhoto',

    lat: 'finishOvertimeLat',
    lng: 'finishOvertimeLng'

});
</script>
@endpush