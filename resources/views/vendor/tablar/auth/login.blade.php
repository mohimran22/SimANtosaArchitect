{{-- @extends('tablar::auth.layout')
@section('title', 'Login')
@section('content')
    <div class="container container-tight py-4">
        
        <div class="text-center mb-1 mt-5">
            <a href="" class="navbar-brand navbar-brand-autodark">
                <img src="{{asset(config('tablar.auth_logo.img.path','assets/logo.svg'))}}" height="36"
                     alt="">
            </a>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <p class="text-center mb-4" style="font-size: 1.4rem; font-weight: 400; font-family: 'Figtree', sans-serif;">
                    Selamat datang di AHA Right Brain
                </p>

                <form class="font-normal" style="font-weight: 400; font-family: 'Figtree', sans-serif;" action="{{route('login')}}" method="post" autocomplete="off" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label ">Email</label>
                        <input type="email" class="form-control font-normal italic-placeholder @error('email') is-invalid @enderror" name="email"
                               placeholder="emailkamu@email.com"
                               autocomplete="off">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Kata sandi
                        </label>

                        <div class="input-group input-group-flat">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Kata sandi Anda"
                                   autocomplete="off">

                            <span class="input-group-text">
                                <a href="#" id="togglePassword" class="link-secondary" data-bs-toggle="tooltip" title="Show password" aria-label="Show password">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12"r="2"/>
                                    <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                </svg>

                                <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="icon" style="display: none;" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 3l18 18" />
                                    <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                    <path d="M9.878 5.878c.995-.586 2.111-.878 3.122-.878 4 0 7.333 2.333 10 7 -1.193 2.089 -2.495 3.695 -3.907 4.82" />
                                    <path d="M6.14 6.128c-1.653 1.147 -3.041 2.797 -4.14 4.872 2.667 4.667 6 7 10 7 1.575 0 3.082 -.355 4.5 -1" />
                                </svg>
                                </a>
                            </span>
                  
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <script>
                        // Tooltip init
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                        });

                        // Show/hide password toggle
                        const passwordInput = document.getElementById('password');
                        const togglePassword = document.getElementById('togglePassword');
                        const eyeIcon = document.getElementById('eyeIcon');
                        const eyeOffIcon = document.getElementById('eyeOffIcon');

                        togglePassword.addEventListener('click', function (e) {

                            e.preventDefault();

                            const isPassword = passwordInput.getAttribute('type') === 'password';
                            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                            eyeIcon.style.display = isPassword ? 'none' : 'inline';
                            eyeOffIcon.style.display = isPassword ? 'inline' : 'none';

                            const title = isPassword ? 'Hide password' : 'Show password';

                            // Ubah atribut untuk aksesibilitas & tooltip dasar
                            togglePassword.setAttribute('aria-label', title);
                            togglePassword.setAttribute('title', title); // Ini penting untuk Bootstrap tooltip

                            const tooltip = bootstrap.Tooltip.getInstance(togglePassword);
                            if (tooltip) {
                                tooltip.setContent({ '.tooltip-inner': newTitle });
                            }

                            // Update tooltip manually (needed for Bootstrap 5)
                            bootstrap.Tooltip.getInstance(togglePassword)?.setContent({ '.tooltip-inner': title });
                        });
                    </script>
                   
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </div>

                </form>
            </div>
            
        </div>
       
    </div>
@endsection --}}


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
                Antosa Architect © {{ date('Y') }} All Rights Reserved
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
