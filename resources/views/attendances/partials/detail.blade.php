<ul class="nav nav-tabs mb-3" id="attendanceTabs" role="tablist">

    <li class="nav-item">
        <button
            class="nav-link active"
            data-bs-toggle="tab"
            data-bs-target="#tab-information">

            <i class="ti ti-file-description me-1"></i>
            Informasi

        </button>
    </li>

    <li class="nav-item">
        <button
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#tab-location">

            <i class="ti ti-map-pin me-1"></i>
            Lokasi

        </button>
    </li>

    <li class="nav-item">
        <button
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#tab-photo">

            <i class="ti ti-camera me-1"></i>
            Dokumentasi

        </button>
    </li>

    <li class="nav-item">
        <button
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#tab-revision">

            <i class="ti ti-history me-1"></i>
            Riwayat

        </button>
    </li>

</ul>
<div class="tab-content">

    <div
        class="tab-pane fade show active"
        id="tab-information">

        @include('attendances.partials.tabs.information')

    </div>

    <div
        class="tab-pane fade"
        id="tab-location">

        @include('attendances.partials.tabs.location')

    </div>

    <div
        class="tab-pane fade"
        id="tab-photo">

        @include('attendances.partials.tabs.photo')

    </div>

    <div
        class="tab-pane fade"
        id="tab-revision">

        @include('attendances.partials.tabs.revisions')

    </div>

</div>
{{-- <div class="container-fluid">




    @include('attendances.partials.revisions')
    <div class="text-center mt-4">

        <button
            class="btn btn-secondary btn-back-history"
            data-employee="{{ $attendance->employee_id }}">

            <i class="ti ti-arrow-left me-2"></i>
            Kembali

        </button>

    </div>

</div> --}}