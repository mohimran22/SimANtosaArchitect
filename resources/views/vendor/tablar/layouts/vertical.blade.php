@php
    $layoutData['cssClasses'] =  'navbar navbar-vertical navbar-expand-lg';
@endphp
@section('body')
    <body>
        <div class="page">
            <!-- Sidebar -->
            @include('tablar::partials.navbar.sidebar')
            
            <div class="page-wrapper">
                @include('tablar::partials.header.sidebar-top')
                <!-- Page Content -->
                @hasSection('content')
                    @yield('content')
                @endif
                <!-- Page Error -->
                @include('tablar::error')
                @include('tablar::partials.footer.bottom')
                
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
        <script src="https://cdn.datatables.net/v/bs5/dt-2.3.2/fc-5.0.4/fh-4.0.3/datatables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function(){

                const header = document.querySelector(".kurva-header");
                const body   = document.getElementById("kurvaSCollapse");
                const icon   = document.getElementById("kurvaIcon");

                const storageKey = "kurvaS_minimized";

                // restore posisi setelah refresh
                const savedState = localStorage.getItem(storageKey);

                if(savedState === "true"){
                    body.style.display = "none";
                    icon.innerHTML = "▲";
                }else{
                    body.style.display = "block";
                    icon.innerHTML = "▼";
                }

                // toggle minimize
                header.addEventListener("click", function(){

                    const isHidden = body.style.display === "none";

                    if(isHidden){
                        body.style.display = "block";
                        icon.innerHTML = "▼";
                        localStorage.setItem(storageKey,"false");
                    }else{
                        body.style.display = "none";
                        icon.innerHTML = "▲";
                        localStorage.setItem(storageKey,"true");
                    }

                });

            });
        </script>
        @yield('scripts')
    </body>
@stop
