<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Antosa Architect</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
}

/* HERO */
.hero {
    min-height: 100vh;
    position: relative;
    background: url('{{ asset("bg-gedung.jpeg") }}') center center / cover no-repeat;
}

/* overlay — lebih soft */
.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0,0,0,0.55),
        rgba(0,0,0,0.75)
    );
}

/* CONTENT */
.hero-content {
    position: relative;
    z-index: 2;
    color: white;
}

/* LOGO */
.brand-logo {
    width: 110px;
    margin-bottom: 18px;
    opacity: 0.95;
}

/* BUTTON */
.btn-hero {
    padding: 14px 42px;
    font-size: 18px;
    border-radius: 14px;
    min-width: 210px;
    letter-spacing: 0.4px;
}

/* hover lebih premium */
.btn-light:hover {
    transform: translateY(-2px);
}

.btn-outline-light:hover {
    background: white;
    color: black;
}

/* FOOTER */
.footer-text {
    position: absolute;
    bottom: 36px;
    width: 100%;
    text-align: center;
    color: #cfcfcf;
    font-size: 14px;
    z-index: 2;
    letter-spacing: 0.5px;
}

/* MOBILE FIX */
@media (max-width: 576px) {
    .btn-hero {
        min-width: 160px;
        font-size: 16px;
        padding: 12px 20px;
    }

    .brand-logo {
        width: 80px;
    }
}
</style>
</head>

<body>

<section class="hero d-flex align-items-center justify-content-center text-center">

<div class="hero-content">

    <!-- LOGO -->
    <img src="{{ asset('logo-putih.png') }}" class="brand-logo">

    <!-- BUTTON -->
    <div class="d-flex gap-4 justify-content-center">

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light btn-hero fw-semibold">
                    Masuk Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-hero fw-semibold">
                    Masuk
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-hero fw-semibold">
                        Daftar
                    </a>
                @endif
            @endauth
        @endif

    </div>

</div>

<div class="footer-text">
    Sistem Informasi Management <b>Antosa Architect</b>
</div>

</section>

</body>
</html>