<div class="text-center">

    @php
        $status = match($attendanceToday->status) {
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'leave' => 'Cuti',
            'business_trip' => 'Dinas Luar',
            'alpha' => 'Alpha',
            default => ucfirst($attendanceToday->status),
        };

        $icon = match($attendanceToday->status) {
            'alpha' => '❌',
            default => '✅',
        };

        $color = $attendanceToday->status === 'alpha'
            ? 'text-danger'
            : 'text-success';
    @endphp

    <h2 class="{{ $color }} mb-3">
        {{ $icon }} {{ $status }}
    </h2>

    @if($attendanceToday->status === 'alpha')
        <p class="text-secondary">
            Anda tidak melakukan absensi sebelum pukul 10.00 WIB.
        </p>
    @elseif($attendanceToday->notes)
        <p class="text-secondary">
            {{ $attendanceToday->notes }}
        </p>
    @endif

</div>