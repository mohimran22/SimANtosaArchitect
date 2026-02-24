@extends('tablar::auth.layout')
@section('title', 'Antosa Architect')
@section('content')
<style>
    body {
        background: url('{{ asset('images/bg-login.png') }}') no-repeat center center fixed;
        background-size: cover;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        position: relative;
    }

    .overlay-dark {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    .logo-left {
        position: absolute;
        left: 10%;
        top: 50%;
        transform: translateY(-30%);
        color: #fff;
        text-align: left;
    }

    .logo-left img {
        width: 20%;
        margin-bottom: 10px;
    }

    .login-panel {
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        padding: 40px;
        width: 50%;
        max-width: 400px;
        margin-right: 5%;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .login-panel h3 {
        font-weight: 900;
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }

    .btn-login {
        border-radius: 10px;
        padding: 0.75rem;
        font-weight: 600;
    }

    /* @media (max-width: 768px) {
        .auth-wrapper {
            justify-content: center;
        }
        .brand-left {
            position: static;
            transform: none;
            margin-bottom: 2rem;
        }
        .login-panel {
            margin: 0 1rem;
        }
    } */
</style>

<div class="auth-wrapper">
    <div class="overlay-dark"></div>

    {{-- Bagian kiri logo  --}}
    <div class="logo-left d-none d-md-block">
        <img src="{{ asset('images/logo-putih.png') }}" alt="Logo Antosa">
    </div> 

    {{-- Panel login kanan --}}
    <div class="login-panel">
        <h3>Masuk</h3>

        <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="ti ti-mail"></i>
                    </span>
                    <input id="email" type="text" name="email"
                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                           placeholder="Masukkan email Anda" autofocus>
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="ti ti-lock"></i>
                    </span>
                    <input id="password" type="password" name="password"
                           class="form-control border-start-0 @error('password') is-invalid @enderror"
                           placeholder="Masukkan kata kunci">
                    <button type="button" id="togglePassword" class="btn btn-light border">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-dark text-white w-100">Masuk</button>

            <div class="text-center mt-3 small">
                Lupa Password? Tenang, <a href="{{ route('password.request') }}" class="fw-semibold text-dark">klik disini!</a>
            </div>

            <div class="text-center mt-3 small">
                Atau belum punya akun? <a href="{{ route('register') }}" class="fw-semibold text-dark">Daftar sekarang!</a>
            </div>

            <div class="text-center text-muted mt-4 small">
                Antosa Architect © {{ date('Y') }} Semua Hak Dilindungi
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle password show/hide
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' 
            ? '<i class="ti ti-eye"></i>' 
            : '<i class="ti ti-eye-off"></i>';
    });
</script>
@endsection
