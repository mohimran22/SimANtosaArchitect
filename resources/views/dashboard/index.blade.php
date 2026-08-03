@extends('tablar::page')

@section('content')

<div class="page-body">
    <div class="container-xl dashboard-container">
        {{-- <div id="alertCarousel" class="position-relative">
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
        </div> --}}
        <div class="pt-2 pb-7 text-center">
            <h2 class="fw-bold g-4">
                Selamat Datang {{ auth()->user()->fullname ?? 'Admin Utama' }} di Sistem Antosa Architect
            </h2>
        </div>
        <div class="row g-4">
            <div class="col-xl-4">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h3 class="mb-1">
                                {{ $greeting }},
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
                {{-- <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            Ringkasan Hari Ini
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-6">
                                <div class="border rounded-3 p-3 text-center">
                                    <div class="fs-3 fw-bold text-success">
                                        {{ $hadir }}
                                    </div>
                                    <small>Hadir</small>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="border rounded-3 p-3 text-center">
                                    <div class="fs-3 fw-bold text-warning">
                                        {{ $terlambat }}
                                    </div>
                                    <small>Terlambat</small>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="border rounded-3 p-3 text-center">
                                    <div class="fs-3 fw-bold text-danger">
                                        {{ $belumHadir }}
                                    </div>
                                    <small>Belum Hadir</small>
                                </div>
                            </div>

                            {{-- <div class="col-6">
                                <div class="border rounded-3 p-3 text-center">
                                    <div class="fs-3 fw-bold text-info">
                                        {{ $lembur }}
                                    </div>
                                    <small>Lembur</small>
                                </div>
                            </div> 

                        </div>

                    </div>

                </div> --}}

            </div>
            <div class="col-xl-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white d-flex justify-content-between">

                            <div>
                                <h4 class="mb-0">
                                    Daftar Karyawan yang Hadir Hari Ini   
                                </h4>

                                <small class="text-secondary">
                                    {{ now()->translatedFormat('d F Y') }}
                                </small>
                            </div>

                            <span class="badge bg-success">
                                {{ $attendances->count() }} Orang
                            </span>

                        </div>

                        <div class="card-body p-0">

                            <div class="attendance-scroll">

                                <table class="table table-hover align-middle mb-0">

                                    <thead>

                                    <tr>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Jam Masuk</th>
                                        <th>Status</th>
                                    </tr>

                                    </thead>

                                    <tbody>

                                    @foreach($attendances as $attendance)

                                    <tr>

                                        <td width="70">

                                            <img
                                                src="{{ $attendance->employee->user->photo_url }}"
                                                class="avatar-img">

                                        </td>

                                        <td>

                                            <div class="fw-semibold">
                                                {{ $attendance->employee->user->fullname ?? '-' }}
                                            </div>

                                            <small class="text-secondary">
                                                {{ $attendance->employee->employee_code }}
                                            </small>

                                        </td>

                                        <td>
                                            {{-- {{ $attendance->employee->user->getRoleNames()->first() ?? '-' }} --}}
                                            {{ $attendance->employee->user->getRoleNames()->join(', ') }}
                                        </td>

                                        <td>

                                            {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}

                                        </td>

                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $attendance->attendance_code ?? '-' }}
                                            </span>
                                        </td>

                                    </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
            <div class="row g-4 mt-1">
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                💰 Finance
                            </h5>
                        </div>

                        <div class="card-body">
                            {{-- Akun kas berjalan --}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                📁 Project
                            </h5>
                        </div>

                        <div class="card-body">
                            {{-- Project berjalan --}}
                        </div>
                    </div>
                </div>
            </div>
        

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