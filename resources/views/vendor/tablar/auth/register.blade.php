@extends('tablar::auth.layout')
@section('title', 'Antosa Architect')
@section('content')
<style>
    body {
        background: url('{{ asset('images/bg-login.png') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Poppins', sans-serif !important;
        font-weight: 400 !important;
        background-color: #f8f9fc;
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
        width: clamp(180px, 18vw, 260px);
        height: auto;
    }

    .register-panel {

        position: relative;
        z-index: 2;

        width: 100%;
        max-width: 400px;

        margin-right: clamp(30px, 5vw, 80px);

        padding: 32px 30px;

        border-radius: 24px;

        background:
            linear-gradient(
                145deg,
                rgba(255,255,255,0.96),
                rgba(255,255,255,0.90)
            );

        backdrop-filter: blur(18px);

        box-shadow:
            0 8px 30px rgba(0,0,0,0.10),
            0 2px 10px rgba(0,0,0,0.05);

        border: 1px solid rgba(255,255,255,0.35);
    }

    .register-panel h3 {
        font-weight: 800;
        font-size: 24px;
        line-height: 1.2;
        margin-bottom: 1rem;
        color: #111;
        letter-spacing: -0.3px;
    }
    .mb-3 {
        margin-bottom: 10px !important;
    }

    .form-label {

        font-weight: 600;

        margin-bottom: 0.55rem;

        color: #222;
    }
    .form-control {
        border-radius: 12px;
        padding: 10px 10px;
        border: 1px solid #e5e7eb;
        font-size: 14px;
    }
    .form-control:focus {

        border-color: #111;

        box-shadow:
            0 0 0 4px rgba(0,0,0,0.05);
    }
    .btn-dark {

        border-radius: 12px;

        padding: 0.8rem;

        font-weight: 600;

        font-size: 12px;

        transition: all .25s ease;
    }

    .btn-dark:hover {

        transform: translateY(-2px);

        box-shadow:
            0 10px 25px rgba(0,0,0,0.18);
    }
    @media (max-width: 1200px) {
        body {
            background-attachment: scroll;
            background-position: center;
        }

        .auth-wrapper {
            align-items: center;
            justify-content: center;
            padding: 120px 24px 40px;
            flex-direction: column;
            gap: 28px;
        }

        .logo-left {
            position: absolute;
            left: 50%;
            top: 7%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 2;
        }

        .logo-left img {
            width: clamp(180px, 28vw, 260px);
            height: auto;
            margin-bottom: 10px;
        }
        .register-panel {
            max-width: 600px;
            margin-right: 0;
            margin-top: 0;
            padding: 35px 30px;
        }
        .register-panel h3 {
            font-size: 24px;
            margin-bottom: 1.3rem;
            text-align: center;
        }
    }

    /* MOBILE */
    @media (max-width: 576px) {
        .page {
            margin-left: 0 !important;
        }
        body {
            background-attachment: scroll;

            background-position: center;
        }

        .auth-wrapper {

            justify-content: center;
            align-items: center;

            padding: 24px 16px;
        }

        .overlay-dark {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        /* tampilkan logo di mobile */
        .logo-left {

            display: block !important;

            position: absolute;

            left: 50%;
            top: 6%;

            transform: translateX(-50%);

            text-align: center;

            z-index: 2;
        }

        .logo-left img {

            width: 180px;

            max-width: 75vw;

            height: auto;

            margin-bottom: 0;
        }

        .register-panel {
            width: 100%;
            max-width: 100%;
            margin-right: 0;
            padding: 28px 22px;
            border-radius: 18px;
            margin-top: 70px;
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(10px);
        }
        .register-panel h3 {
            font-size: 24px;
            margin-bottom: 1.3rem;
            text-align: center;
        }
        .form-control {
            padding: 0.85rem 1rem;
            font-size: 15px;
        }

        .input-group-text {

            padding-left: 14px;
            padding-right: 14px;
        }

        .btn-dark {

            padding: 0.85rem;

            border-radius: 12px;

            font-size: 15px;
        }

        .small {

            font-size: 12px !important;
        }
    }
</style>
    <div class="auth-wrapper">
        <div class="overlay-dark"> </div>
        <div class="logo-left">
            <img src="{{ asset('images/logo-putih.png') }}" alt="Logo Antosa">
        </div> 

        <div class="register-panel">
            <h3>Silahkan buat akun baru</h3>

            <form action="{{route('register')}}" method="post" autocomplete="off" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="ti ti-user"></i>
                        </span>
                        <input type="text" name="fullname"
                            class="form-control border-start-0 @error('fullname') is-invalid @enderror"
                            placeholder="Masukkan nama Anda" autofocus>
                    </div>
                    @error('fullname')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="ti ti-user"></i>
                        </span>
                        <input type="tel" name="phone"
                            class="form-control border-start-0 @error('fullname') is-invalid @enderror"
                            placeholder="Masukkan nomor HP Anda" autofocus>
                    </div>
                    @error('fullname')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="ti ti-mail"></i>
                        </span>
                        <input id="email" type="text" name="email"
                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                            placeholder="Masukkan alamat email Anda" autofocus>
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

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata kunci</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="ti ti-lock"></i>
                        </span>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                            placeholder="Masukkan kata kunci kembali">
                        <button type="button" id="togglePassword" class="btn btn-light border">
                            <i class="ti ti-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark text-white w-100">Daftar</button>

                <div class="text-center mt-3 small">
                    Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold text-dark">Silakan masuk</a>
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
        
                 

                
                {{-- <div class="mb-3">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input"/>
                        <span class="form-check-label">Agree the <a href="#" tabindex="-1">terms and policy</a>.</span>
                    </label>
                </div> --}}
                
         

