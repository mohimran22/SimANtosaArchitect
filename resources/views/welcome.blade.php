<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Antosa Architect — Sistem Informasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('logo.png') }}?v=3">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }

        .hero {
            min-height: 100vh;
            background: linear-gradient(120deg, #1f2937, #111827);
            color: white;
        }

        .hero-card {
            border-radius: 20px;
            border: none;
        }

        .logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
        }

        .badge-soft {
            background: rgba(255,255,255,0.1);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 14px;
        }

        .btn-main {
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="hero d-flex align-items-center">
    <div class="container">

        <div class="row align-items-center">

            <!-- LEFT -->
            <div class="col-lg-6 text-white mb-5 mb-lg-0">

                <div class="badge-soft mb-3">
                    Sistem Informasi Internal
                </div>

                <h1 class="fw-bold mb-3">
                    Antosa Architect
                </h1>

                <h4 class="mb-4 text-light opacity-75">
                    Manajemen Proyek, RAB, Peralatan, Supplier, dan Administrasi dalam satu platform terintegrasi.
                </h4>

                <p class="text-light opacity-75 mb-4">
                    Dirancang untuk efisiensi operasional dan kontrol data yang akurat bagi tim Antosa Architect.
                </p>

            </div>

            <!-- RIGHT CARD -->
            <div class="col-lg-5 offset-lg-1">
                <div class="card hero-card shadow-lg">
                    <div class="card-body p-5 text-center">

                        <img src="{{ asset('logo.png') }}" class="logo mb-3">

                        <h4 class="fw-semibold mb-2">
                            Portal Sistem
                        </h4>

                        <p class="text-muted mb-4">
                            Silakan masuk untuk mengakses dashboard sistem.
                        </p>

                        <div class="d-grid gap-3">

                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-dark btn-main">
                                        Buka Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-dark btn-main">
                                        Login Sistem
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-main">
                                            Daftar Akun
                                        </a>
                                    @endif
                                @endauth
                            @endif

                        </div>

                        <hr class="my-4">

                        <small class="text-muted">
                            © {{ date('Y') }} Antosa Architect
                        </small>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>

