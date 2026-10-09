<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<header class="website-header">

    {{-- TOPBAR: logo + sosmed + user --}}
    <div class="topbar">
        <div class="topbar-inner">
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                <i class="ti ti-menu-2"></i>
            </button>

            <a href="/" class="website-logo">
                <img src="{{ asset('images/antosa.png') }}" alt="Antosa Architect">
            </a>

            <div class="website-icons">
                <div class="social-links">
                    <a href="https://www.youtube.com/@antosa.architect" aria-label="YouTube"  target="_blank" rel="noopener"><i class="ti ti-brand-youtube"></i></a>
                    <a href="https://www.tiktok.com/@antosa.architect" aria-label="Tiktok"  target="_blank" rel="noopener"><i class="ti ti-brand-tiktok"></i></i></a>
                    <a href="https://instagram.com/antosa_architect" aria-label="Instagram"  target="_blank" rel="noopener"><i class="ti ti-brand-instagram"></i></a>
                </div>

                <div class="user-dropdown" id="userDropdown">
                    <button class="user-btn" id="userBtn" aria-label="Akun">
                        <i class="ti ti-user"></i>
                    </button>
                    <div class="user-menu">
                        @guest
                            <a href="{{ route('login') }}" target="_blank" rel="noopener">
                                <i class="ti ti-login"></i>
                                Masuk
                            </a>
                            {{-- <a href="https://si.antosaarchitect.com/login" target="_blank" rel="noopener">
                                <i class="ti ti-login"></i>
                                Masuk
                            </a> --}}
                            <a href="{{ route('register') }}" target="_blank" rel="noopener">
                                <i class="ti ti-user-plus"></i>
                                Daftar
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}">
                                <i class="ti ti-layout-dashboard"></i>
                                Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <i class="ti ti-logout"></i>
                                    Logout
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NAVBAR HITAM (jadi drawer di mobile) --}}
    <div class="main-bar">
        <div class="main-bar-inner">
            <nav class="website-menu" id="websiteMenu">
                <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>

                <div class="menu-dropdown">
                    <button type="button" class="menu-link">
                        Layanan
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="mega-menu">
                        <a href="https://si.antosaarchitect.com/jasa-arsitek">Jasa Arsitek</a>
                        <a href="https://si.antosaarchitect.com/jasa-bangun-rumah">Jasa Bangun Rumah</a>
                        <a href="https://si.antosaarchitect.com/jasa-renovasi-rumah">Jasa Renovasi Rumah</a>
                    </div>
                </div>

                <a href="#">Portofolio</a>
                <a href="#" class="{{ request()->is('/') ? 'active' : '' }}">Tentang Kami</a>
                <a href="https://wa.me/6285189523863">Kontak Kami</a>
                <a href="{{ url('/') }}#listing">Listing Rumah</a>
                <a href="https://wa.me/6285189523863" target="_blank" rel="noopener" class="btn-contact">Hubungi Kami</a>
            </nav>
        </div>
    </div>

    <div class="mobile-overlay" id="mobileOverlay"></div>
</header>

<style>
:root{ --accent:#ffb000; }

.website-header{
    position:relative;
    width:100%;
    z-index:9999;
    font-family:'Poppins',sans-serif;
}

/* ===== TOPBAR ===== */
.topbar{
    background:#fff;
    border-bottom:21px solid #f3f3f3;
}
.topbar-inner{
    width:min(100% - 40px, 1200px);
    margin:0 auto;
    height:74px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.website-logo img{ height:45px; width:auto; display:block; }

.website-icons{ display:flex; align-items:center; gap:20px; }
.social-links{ display:flex; align-items:center; gap:20px; }
.social-links a,
.user-btn{
    background:none;
    border:none;
    padding:0;
    color:#000;
    font-size:24px;
    line-height:1;
    cursor:pointer;
    text-decoration:none;
}
.social-links a:hover,
.user-btn:hover{ color:var(--accent); }

.mobile-menu-btn{ display:none; }

/* ===== NAVBAR HITAM ===== */
.main-bar{
    background:#000;
    border-bottom:1px solid #444;
}
.main-bar-inner{
    width:min(100% - 40px, 1200px);
    margin:0 auto;
}
.website-menu{
    display:flex;
    align-items:center;
    gap:0;
    height:60px;
}
.website-menu > a,
.menu-link{
    background:none;
    border:none;
    color:#fff;
    text-decoration:none;
    font-family:inherit;
    font-size:15px;
    font-weight:500;
    padding:0 15px;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:6px;
    transition:.25s;
}
.website-menu > a:hover,
.website-menu > a.active,
.menu-link:hover{ color:var(--accent); }

.website-menu > a.btn-contact{
    margin-left:auto;
    margin-right:0;
    padding:12px 45px;
    background:#fff;
    color:#000;
    font-size:15px;
    font-weight:500;
    text-transform:uppercase;
    border-radius:0;
}
.website-menu > a.btn-contact:hover{ background:var(--accent); color:#000; }

/* dropdown layanan */
.menu-dropdown{ position:relative; height:100%; display:flex; align-items:center; }
.mega-menu{
    position:absolute;
    top:100%;
    left:0;
    width:300px;
    background:#111;
    border:1px solid #333;
    display:none;
    z-index:99;
}
.menu-dropdown.open .mega-menu{ display:block; }
.mega-menu a{
    display:block;
    padding:16px 24px;
    color:#fff;
    text-decoration:none;
    font-weight:500;
    border-bottom:1px solid #2a2a2a;
}
.mega-menu a:last-child{ border-bottom:none; }
.mega-menu a:hover{ background:var(--accent); color:#000; }

/* dropdown user */
.user-dropdown{ position:relative; }
.user-menu{
    position:absolute;
    top:38px;
    right:0;
    width:190px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
    display:none;
    overflow:hidden;
    z-index:99;
}
.user-menu a,
.user-menu button{
    width:100%;
    padding:14px 18px;
    display:flex;
    align-items:center;
    gap:10px;
    background:none;
    border:none;
    text-align:left;
    color:#111;
    text-decoration:none;
    cursor:pointer;
    font-family:inherit;
    font-size:15px;
}
.user-menu a:hover,
.user-menu button:hover{ background:#f5f5f5; }
.user-dropdown.open .user-menu{ display:block; }

.mobile-overlay{ display:none; }

/* ===== TABLET ===== */
@media (min-width:769px) and (max-width:1200px){
    .website-menu > a,
    .menu-link{ font-size:14px; padding:0 9px; }
    .website-menu > a.btn-contact{ padding:11px 24px; font-size:13px; }
    .social-links a,
    .user-btn{ font-size:22px; }
    .website-logo img{ height:40px; }
}

/* ===== MOBILE ===== */
@media (max-width:768px){

    .topbar{ border-bottom:none; box-shadow:0 2px 12px rgba(0,0,0,.08); }

    .topbar-inner{
        width:calc(100% - 28px);
        height:68px;
        display:grid;
        grid-template-columns:44px 1fr 44px;
        align-items:center;
    }

    .mobile-menu-btn{
        grid-column:1;
        display:flex;
        align-items:center;
        justify-content:center;
        width:44px;
        height:44px;
        padding:0;
        border:none;
        background:transparent;
        font-size:30px;
        color:#000;
        cursor:pointer;
    }

    .website-logo{ grid-column:2; display:flex; justify-content:center; }
    .website-logo img{ height:48px; }

    .website-icons{ grid-column:3; justify-content:flex-end; }
    .social-links{ display:none; }
    .user-btn{ font-size:30px; }
    .user-menu{ top:42px; }

    /* bar hitam hilang, menu jadi drawer */
    .main-bar{ background:none; border:none; }
    .main-bar-inner{ width:auto; margin:0; }

    .website-menu{
        position:fixed;
        top:0;
        left:-340px;
        width:340px;
        max-width:85vw;
        height:100vh;
        padding:90px 24px 30px;
        background:#fff;
        flex-direction:column;
        align-items:stretch;
        gap:0;
        overflow-y:auto;
        box-shadow:8px 0 30px rgba(0,0,0,.15);
        transition:left .35s ease;
        z-index:10002;
    }
    .website-menu.active{ left:0; }

    .website-menu > a,
    .website-menu .menu-link{
        width:100%;
        justify-content:space-between;
        padding:16px 0;
        font-size:14px;
        font-weight:600;
        color:#111;
        text-align:left;
        border-bottom:1px solid #f1f1f1;
    }
    .website-menu > a:hover,
    .website-menu > a.active,
    .menu-link:hover{ color:var(--accent); }

    .website-menu > a.btn-contact{
        margin:24px 0 0;
        padding:14px 20px;
        justify-content:center;
        background:#000;
        color:#fff;
        border:none;
        border-radius:10px;
        font-size:14px;
    }
    .website-menu > a.btn-contact:hover{ background:var(--accent); color:#000; }

    .menu-dropdown{ display:block; height:auto; }

    .mega-menu{
        position:static;
        width:calc(100% - 16px);
        margin:0 0 12px 16px;
        padding:8px 0;
        background:#f8f8f8;
        border:none;
        border-radius:0 10px 10px 0;
        overflow:hidden;
    }
    .mega-menu a{
        padding:12px 18px;
        font-size:13px;
        color:#555;
        border:none;
    }
    .mega-menu a:hover{ background:#eee; color:#000; }

    .mobile-overlay{
        display:block;
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.45);
        opacity:0;
        visibility:hidden;
        transition:opacity .3s ease;
        z-index:10001;
    }
    .mobile-overlay.show{ opacity:1; visibility:visible; }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const menu = document.getElementById("websiteMenu");
    const btn = document.getElementById("mobileMenuBtn");
    const overlay = document.getElementById("mobileOverlay");

    if (!menu || !btn || !overlay) {
        console.error("Navbar mobile element tidak ditemukan.");
        return;
    }

    btn.addEventListener("click", function () {
        menu.classList.toggle("active");
        overlay.classList.toggle("show");
    });

    overlay.addEventListener("click", function () {
        menu.classList.remove("active");
        overlay.classList.remove("show");
    });

    document.querySelectorAll(".menu-link").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.closest(".menu-dropdown").classList.toggle("open");
        });
    });

    const userBtn = document.getElementById("userBtn");
    const userDropdown = document.getElementById("userDropdown");

    if (userBtn && userDropdown) {
        userBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            userDropdown.classList.toggle("open");
        });
    }

    // klik di luar: tutup dropdown user & layanan
    document.addEventListener("click", function () {
        if (userDropdown) userDropdown.classList.remove("open");
        document.querySelectorAll(".menu-dropdown").forEach(item => item.classList.remove("open"));
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            menu.classList.remove("active");
            overlay.classList.remove("show");
            document.querySelectorAll(".menu-dropdown").forEach(item => item.classList.remove("open"));
        }
    });

});
</script>