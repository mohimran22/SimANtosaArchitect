<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ $title }}</h5>
            </div>

            <form action="{{ $action }}" method="POST">

                @csrf

                <div class="modal-body text-center">

                    <video
                        id="{{ $prefix }}Camera"
                        autoplay
                        playsinline
                        class="img-fluid rounded border">
                    </video>

                    <canvas
                        id="{{ $prefix }}Canvas"
                        class="d-none">
                    </canvas>

                    <img
                        id="{{ $prefix }}Preview"
                        class="img-fluid rounded border d-none">

                    <input
                        type="hidden"
                        id="{{ $prefix }}Photo"
                        name="photo">

                    <input
                        type="hidden"
                        name="{{ $latName }}"
                        id="{{ $prefix }}Lat">

                    <input
                        type="hidden"
                        name="{{ $lngName }}"
                        id="{{ $prefix }}Lng">

                </div>

                <div class="modal-footer justify-content-center">

                    <button
                        type="button"
                        id="{{ $prefix }}Capture"
                        class="btn btn-dark">

                        📸 Ambil Foto

                    </button>

                    <button
                        type="button"
                        id="{{ $prefix }}Retake"
                        class="btn btn-secondary d-none">

                        🔄 Ambil Ulang

                    </button>

                    <button
                        type="submit"
                        id="{{ $prefix }}Confirm"
                        class="btn btn-success d-none">

                        {{ $confirmText }}

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>