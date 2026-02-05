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
    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,0.65),
            rgba(0,0,0,0.35)
        ),
        url('{{ asset("bg-gedung.jpeg") }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: right bottom;
}

/* CONTENT */
.hero-content {
    position: relative;
    z-index: 2;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 64px;
    transform: translateY(-40px);
}

/* LOGO */
.brand-logo {
    width: 120px;
    opacity: 0.95;
}

/* BUTTON */
.btn-hero {
    padding: 14px 48px;
    font-size: 17px;
    border-radius: 12px;
    min-width: 220px;
    letter-spacing: 0.6px;
    transition: all 0.25s ease;
}
.btn-light {
    background: #ffffff;
    color: #000;
    border: none;
}


/* hover lebih premium */
.btn-light:hover {
    transform: translateY(-2px);
}

.btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255,255,255,0.15);
}

.btn-outline-light:hover {
    background: #fff;
    color: #000;
}

.btn-outline-light {
    border: 2px solid rgba(255,255,255,0.85);
    color: #fff;
}

/* FOOTER */
.footer-text {
    position: absolute;
    bottom: 28px;
    width: 100%;
    text-align: center;
    color: rgba(255,255,255,0.7);
    font-size: 13px;
    letter-spacing: 0.4px;
    transform: translateY(-20px);
}

/* MOBILE FIX */
@media (max-width: 576px) {
    .btn-hero {
        min-width: 160px;
        font-size: 16px;
        padding: 12px 20px;
    }

    .brand-logo {
        width: 100px;
        size: 40px;
    }
}
</style>
</head>

<body>

<section class="hero d-flex align-items-center justify-content-center text-center pt-5">

<div class="hero-content text-center">

    <!-- LOGO -->
    <img src="{{ asset('logo-putih.png') }}" class="brand-logo">



    <!-- BUTTON -->

    <div class="button-group d-flex gap-4 justify-content-center">

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