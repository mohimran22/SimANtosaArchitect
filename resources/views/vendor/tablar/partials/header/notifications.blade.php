@auth
    <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
           aria-label="Show notifications">
            <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path
                    d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
                <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
            </svg>
            @if(auth()->user()->unreadNotifications->count())
                <span class="badge bg-red">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif

        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Last updates</h3>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">

                    @forelse(auth()->user()->unreadNotifications as $notif)
                        <a href="{{ $notif->data['url'] }}"
                        class="list-group-item list-group-item-action"
                        onclick="markAsRead('{{ $notif->id }}')">

                            <div class="row align-items-start">
                                <div class="col-auto pt-1">
                                    <span class="status-dot status-dot-animated bg-red d-block"></span>
                                </div>

                                <div class="col">
                                    <div class="text-body fw-semibold">
                                        {{ $notif->data['message'] }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $notif->data['project_name'] }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endif

@push('js')
<script>
function markAsRead(notificationId) {
    fetch('/notifications/' + notificationId + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
}
</script>
@endpush