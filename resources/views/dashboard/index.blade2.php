@extends('tablar::page')

@section('content')

<div class="page-body">
    <div class="container-xl dashboard-container">
        {{-- <div class="pt-2 pb-7 text-center">
            <h2 class="fw-bold">
                Selamat Datang {{ auth()->user()->fullname ?? 'Admin Utama' }} di Sistem Antosa Architect
            </h2>
        </div> --}}
        <div class="row g-4 mt-3">
            @can('lihat data absensi')
            <div class="col-xl-4">
                <div class="card shadow-sm border-0 rounded-4">
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
                        @elseif($attendanceToday->status !== 'present')
                            @include('attendances.partials.non-present')
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
            @endcan
            @can('lihat daftar absensi')
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

                                        @forelse($attendances as $attendance)

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

                                                <td class="jabatan-column"
                                                    title="{{ $attendance->employee->user->getRoleNames()->join(', ') }}">

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

                                        @empty

                                            <tr>

                                                <td colspan="5" class="text-center py-5">

                                                    <div class="text-secondary">

                                                        <div class="fs-4 mb-2">
                                                            📋
                                                        </div>

                                                        <div class="fw-semibold">
                                                            Belum ada karyawan yang hadir hari ini
                                                        </div>

                                                        <small>
                                                            Data absensi akan muncul setelah karyawan melakukan absen.
                                                        </small>

                                                    </div>

                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            @endcan
        </div>

        <div class="row g-4 mt-2">
            {{-- ===================== FINANCE ===================== --}}
            @can('lihat akun-akuntansi')
            <div class="col-12">
                <div class="card zh-section border-0 shadow-sm rounded-4">

                    <div class="zh-section-header">
                        <div class="zh-section-title">💰 Finance</div>
                        <div class="zh-section-meta">
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </div>
                    </div>

                    <div class="zh-section-body">
                        @php
                            $rp = fn($n) => ($n < 0 ? '-' : '') . 'Rp ' . number_format(abs($n), 0, ',', '.');
                        @endphp

                        <div class="zh-stat-grid zh-stat-grid-finance">

                            {{-- Total Kas & Bank --}}
                            <div class="zh-stat zh-stat-highlight">
                                <div class="zh-stat-label">Total Kas & Bank</div>
                                <div class="zh-stat-value zh-stat-value-xl {{ $totalCashBank < 0 ? 'text-danger' : 'text-primary' }}">
                                    {{ $rp($totalCashBank) }}
                                </div>
                            </div>

                            {{-- Pendapatan --}}
                            <a href="{{ route('journals.general') }}" class="zh-stat text-decoration-none">
                                <div class="zh-stat-label">📈 Pendapatan</div>
                                <div class="zh-stat-value text-success">{{ $rp($monthlyRevenue) }}</div>
                            </a>

                            {{-- Kas Masuk --}}
                            <div class="zh-stat">
                                <div class="zh-stat-label">📥 Kas Masuk</div>
                                <div class="zh-stat-value text-success">{{ $rp($cashInThisMonth) }}</div>
                            </div>

                            {{-- Kas Keluar --}}
                            <div class="zh-stat">
                                <div class="zh-stat-label">📤 Kas Keluar</div>
                                <div class="zh-stat-value text-danger">{{ $rp($cashOutThisMonth) }}</div>
                            </div>
                        </div>

                        <div class="zh-subtitle">Saldo per akun</div>

                        <div class="zh-account-grid">
                            @foreach($cashAccounts as $account)
                                <div class="zh-account">
                                    <div class="zh-account-icon">
                                        {{ str_contains(strtolower($account['account_name']), 'bank') ? '🏦' : '💵' }}
                                    </div>
                                    <div class="zh-account-info">
                                        <div class="zh-account-name">{{ $account['account_name'] }}</div>
                                        <div class="zh-account-balance {{ $account['balance'] < 0 ? 'text-danger' : '' }}">
                                            {{ $rp($account['balance']) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
            @endcan

            {{-- ===================== PROJECT ===================== --}}
            @can('lihat daftar proyek')
            <div class="col-12">
                <div class="card zh-section border-0 shadow-sm rounded-4">

                    <div class="zh-section-header">
                        <div class="zh-section-title">📁 Projects</div>
                        <a href="{{ route('projects.index') }}" class="zh-section-meta text-decoration-none">
                            Lihat semua →
                        </a>
                    </div>

                    <div class="zh-section-body">

                        <div class="zh-stat-grid zh-stat-grid-project">

                            {{-- Total --}}
                            <a href="{{ route('projects.index') }}" class="zh-stat zh-stat-highlight text-decoration-none">
                                <div class="zh-stat-label">📁 Total Project</div>
                                <div class="zh-stat-value zh-stat-value-xl text-primary">{{ $totalProject }}</div>
                            </a>

                            {{-- Sedang Dikerjakan --}}
                            <div class="zh-stat">
                                <div class="zh-stat-label">🚧 Dikerjakan</div>
                                <div class="zh-stat-value text-primary">{{ $runningBuild }}</div>
                            </div>

                            {{-- Selesai --}}
                            <div class="zh-stat">
                                <div class="zh-stat-label">✅ Selesai</div>
                                <div class="zh-stat-value text-success">{{ $completedBuild }}</div>
                            </div>

                            {{-- Desain --}}
                            <a href="{{ route('projects.index', ['type' => 1]) }}" class="zh-stat text-decoration-none">
                                <div class="zh-stat-label">🎨 Desain</div>
                                <div class="zh-stat-value text-info">{{ $totalDesign }}</div>
                            </a>

                            {{-- RAB --}}
                            <a href="{{ route('projects.index', ['type' => 2]) }}" class="zh-stat text-decoration-none">
                                <div class="zh-stat-label">📑 RAB</div>
                                <div class="zh-stat-value text-warning">{{ $totalRab }}</div>
                            </a>

                            {{-- Build --}}
                            <a href="{{ route('projects.index', ['type' => 3]) }}" class="zh-stat text-decoration-none">
                                <div class="zh-stat-label">🏗 Build</div>
                                <div class="zh-stat-value text-success">{{ $totalBuild }}</div>
                            </a>
                        </div>

                        <div class="zh-subtitle">Progress tertinggi</div>

                        <div class="zh-progress-grid">
                            @forelse($topBuildProjects as $project)
                                <div class="zh-progress-card">
                                    <div class="zh-progress-head">
                                        <span class="zh-progress-name" title="{{ $project->project_name }}">
                                            {{ $project->project_name }}
                                        </span>
                                        <span class="zh-progress-percent">
                                            {{ number_format($project->progress, 0) }}%
                                        </span>
                                    </div>
                                    <div class="progress" style="height:8px;">
                                        <div class="progress-bar" style="width: {{ $project->progress }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-secondary small">Belum ada project build yang berjalan.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
            @endcan
        </div>
        @can('lihat data absensi')
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
        <x-permission-modal
            modal-id="izinModal"
            title="Ajukan Izin"
            prefix="Izin"
            :confirm-text="match(optional($todayRequest)->status) {
                'pending'  => '⏳ Menunggu Persetujuan',
                'approved' => '✅ Izin Disetujui',
                'rejected' => '🔄 Ajukan Ulang',
                default    => '📨 Kirim Pengajuan',
            }"
        />
        @endcan
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
<style>
.footer.footer-transparent {
    display: flex;
    margin-left: 0;
    flex-direction: column;
    padding: 20px 20px 20px 16px;
    transition: all .3s ease;
}

.sidebar-collapsed .footer.footer-transparent {
    padding-left: 20px;
    padding-right: 18px;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* ===== Dashboard sections (Finance & Projects) ===== */
.zh-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.1rem 1.75rem;
    border-bottom: 1px solid var(--tblr-border-color, #e6e7e9);
}
.zh-section-title { font-weight: 600; font-size: .95rem; }
.zh-section-meta  { color: var(--tblr-secondary, #667382); font-size: 1rem; }
.zh-section-body  { padding: 1.75rem; }

.zh-subtitle {
    font-size: .8rem;
    font-weight: 600;
    margin: 1.75rem 0 .9rem;
}

/* Kartu statistik */
.zh-stat-grid { display: grid; gap: 1.25rem; }
.zh-stat-grid-finance { grid-template-columns: 1.5fr 1fr 1fr 1fr; }
.zh-stat-grid-project { grid-template-columns: 1.5fr repeat(5, 1fr); }

.zh-stat {
    display: block;
    color: inherit;
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 12px;
    padding: 1.1rem 1.5rem;
    transition: box-shadow .15s ease, transform .15s ease;
}
a.zh-stat:hover { box-shadow: 0 4px 14px rgba(0,0,0,.07); transform: translateY(-1px); color: inherit; }
.zh-stat-highlight {
    background: var(--tblr-primary-lt, #e7f0fa);
    border-color: transparent;
    padding-top: 1.4rem;
    padding-bottom: 1.4rem;
}
.zh-stat-label { color: var(--tblr-secondary, #667382); font-size: .95rem; margin-bottom: .35rem; }
.zh-stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
.zh-stat-value-xl { font-size: 2.2rem; }

/* Saldo per akun */
.zh-account-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
.zh-account {
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 12px;
    padding: 1.1rem 1.5rem;
}
.zh-account-icon {
    width: 48px; height: 48px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    background: #f3f4f6;
    border-radius: 12px;
}
.zh-account-info { min-width: 0; }
.zh-account-name {
    color: var(--tblr-secondary, #667382);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.zh-account-balance { font-weight: 700; }

/* Progress project */
.zh-progress-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.zh-progress-card {
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 12px;
    padding: 1.1rem 1.5rem;
}
.zh-progress-head { display: flex; justify-content: space-between; gap: 1rem; margin-bottom: .6rem; }
.zh-progress-name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.zh-progress-percent { font-weight: 700; }

/* Responsive */
@media (max-width: 1199.98px) {
    .zh-stat-grid-finance { grid-template-columns: repeat(2, 1fr); }
    .zh-stat-grid-project { grid-template-columns: repeat(3, 1fr); }
    .zh-stat-grid .zh-stat-highlight { grid-column: 1 / -1; }
    .zh-account-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575.98px) {
    .zh-stat-grid-finance, .zh-stat-grid-project, .zh-account-grid { grid-template-columns: 1fr; }
    .zh-stat-grid-project { grid-template-columns: repeat(2, 1fr); }
    .zh-section-body { padding: 1.1rem; }
}
</style>