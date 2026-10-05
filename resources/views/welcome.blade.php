@extends('layouts.website')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Saira:wght@600;700&display=swap" rel="stylesheet">
<style>
:root{ --accent:#ffb000; }

.hero{
    position:relative;
    min-height:100vh;
    display:flex;
    align-items:flex-start;
    padding:170px 0 320px;   /* ruang bawah lebih lega supaya gedung terlihat penuh */
    color:#fff;
    font-family:'Poppins',sans-serif;
    background:
        linear-gradient(to top, #000 0%, rgba(0,0,0,0) 22%),
        linear-gradient(90deg, rgba(0,0,0,.92) 0%, rgba(0,0,0,.6) 40%, rgba(0,0,0,0) 70%),
        url('{{ asset('bg-gedung.jpeg') }}') right bottom/cover no-repeat;
}
.hero-content{
    width:min(100% - 40px,1200px);
    margin:0 auto;
}
.hero h1{
    font-family:'Saira',sans-serif;
    font-weight:700;
    font-size:clamp(40px,5.73vw,88px);
    line-height:1.45;
    margin:0 0 32px;
}
.hero h2{
    font-size:clamp(18px,1.42vw,22px);
    font-weight:600;
    margin:0 0 24px;
}
.hero h2 span{ color:var(--accent); }
.rotating{
    display:inline-block;
    transition:opacity .4s ease, transform .4s ease;
}
.rotating.fade{ opacity:0; transform:translateY(8px); }

.hero-buttons{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:40px;
}
.hero-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:224px;
    height:39px;
    padding:0 20px;
    font-size:15px;
    font-weight:500;
    text-decoration:none;
    color:#000;
    transition:.25s;
}
.btn-primary-hero{ background:var(--accent); }
.btn-primary-hero:hover{ background:#e09c00; color:#000; }
.btn-secondary-hero{ background:#fff; }
.btn-secondary-hero:hover{ background:#e9e9e9; color:#000; }
.hero p{
    font-size:clamp(14px,0.98vw,15px);
    line-height:1.65;
    max-width:690px;
    margin:0;
}

@media (max-width:768px){
    .hero{
        align-items:center;
        padding:60px 0 120px;
        background:
            linear-gradient(to bottom, rgba(0,0,0,.8), rgba(0,0,0,.6)),
            url('{{ asset('bg-gedung.jpeg') }}') 85% top/cover no-repeat;
    }
    .hero-content{ width:calc(100% - 32px); }
    .hero-buttons{ margin-top:28px; }
    .hero-btn{ min-width:0; flex:1 1 150px; }
    .hero h1{ margin-bottom:24px; }
}

/* ===== Statistik ===== */
.stats{ background:#fff; padding:90px 0 70px; font-family:'Poppins',sans-serif; color:#111; }
.stats-inner{
    width:min(100% - 40px,1100px);
    margin:0 auto;
    display:grid;
    grid-template-columns:300px 1fr;
    gap:60px;
    align-items:center;
}
.stats-label{ font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#444; margin:0 0 14px; }
.stats h2{ font-family:'Saira',sans-serif; font-weight:600; font-size:34px; line-height:1.2; margin:0; }
.stats-line{ width:36px; height:2px; background:#111; margin-top:26px; }
.stats-cards{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.stat-card{
    height:160px;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    color:#fff;
    background:linear-gradient(135deg,#000 0%,#1a1a1a 100%);
}
.stat-card strong{ font-family:'Saira',sans-serif; font-weight:600; font-size:56px; line-height:1; }
.stat-card span{ margin-top:10px; font-size:12px; letter-spacing:1px; text-transform:uppercase; }

/* ===== Tentang Perusahaan ===== */
.about{
    position:relative;
    min-height:100vh;
    padding:80px 0 0;
    font-family:'Poppins',sans-serif;
    color:#111;
    background:
        linear-gradient(to bottom, #fff 0%, #fff 28%, rgba(255,255,255,0) 60%),
        url('{{ asset('images/tentang-perusahaan.webp') }}') center bottom/cover no-repeat,
        #fff;
}
.about-text{ width:min(100% - 40px,1100px); margin:0 auto; }
.about h2{ font-family:'Saira',sans-serif; font-weight:600; font-size:32px; margin:0 0 22px; }
.about p{ font-size:15px; line-height:1.65; max-width:735px; margin:0 0 22px; }

@media (max-width:991px){
    .stats-inner{ grid-template-columns:1fr; gap:30px; }
}
@media (max-width:768px){
    .stats{ padding:60px 0 40px; }
    .stats h2{ font-size:26px; }
    .stats-cards{ grid-template-columns:1fr; }
    .stat-card{ height:120px; }
    .stat-card strong{ font-size:44px; }
    .about{ padding-top:50px; }
    .about h2{ font-size:26px; }
}

/* ===== Mengapa Memilih ===== */
.why{
    padding:150px 0 0;
    font-family:'Poppins',sans-serif;
    color:#111;
    background:
        repeating-linear-gradient(115deg, rgba(0,0,0,.014) 0 2px, transparent 2px 44px),
        #fafafa;
    overflow:hidden;
}
.why-head{ width:min(100% - 40px,1060px); margin:0 auto 180px; }
.why-head h2{
    font-family:'Saira',sans-serif; font-weight:600;
    font-size:40px; line-height:1.3; max-width:600px; margin:0;
}

/* gambar 1600x900 = background satu section penuh, teks di area kosong kanan */
.why-body{
    aspect-ratio:16/9;
    display:flex;
    align-items:center;
    padding:0 6% 0 55%;
    background:url('{{ asset('images/Jasa-Arsitek-Jember-Value-Antosa-Architect-Destop.webp') }}') center top/100% auto no-repeat;
}
.why-mobile{ display:none; }
.why-list{ list-style:none; margin:0; padding:0; max-width:540px; }
.why-list li{ margin-bottom:24px; }
.why-list li:last-child{ margin-bottom:0; }
.why-list strong{ display:block; font-size:15px; font-weight:700; line-height:1.55; }
.why-list p{ font-size:15px; line-height:1.55; margin:0; }

@media (max-width:1250px){
    .why-body{
        aspect-ratio:auto;
        display:block;
        padding:0 0 60px;
        background:none;
    }
    /* hanya ambil kolase foto dari gambar (tanpa logo/hamburger) */
    .why-mobile{
        display:block;
        height:420px;
        background:url('{{ asset('images/Jasa-Arsitek-Jember-Value-Antosa-Architect-Destop.webp') }}') left 50%/190% auto no-repeat;
    }
    .why-list{ width:min(100% - 40px,720px); max-width:none; margin:40px auto 0; }
}
@media (max-width:768px){
    .why{ padding-top:70px; }
    .why-head{ margin-bottom:40px; }
    .why-head h2{ font-size:28px; }
    .why-mobile{ height:300px; }
    .why-list strong, .why-list p{ font-size:15px; }
}

/* ===== Founder ===== */
.founder{
    aspect-ratio:16/9;
    padding:150px 0 0 10%;
    font-family:'Poppins',sans-serif;
    color:#fff;
    background:url('{{ asset('images/founder.webp') }}') center top/100% auto no-repeat, #1b1b1b;
}
.founder-text{ max-width:770px; }
.founder h2{
    font-family:'Saira',sans-serif; font-weight:600;
    font-size:40px; line-height:1.3; margin:0 0 20px; max-width:720px;
}
.founder p{ font-size:15px; line-height:1.55; margin:0; }
.founder p strong{ font-weight:700; }
.founder-quote{
    margin-top:45px;
    padding-left:50px;
    position:relative;
    font-size:16px; line-height:1.5;
}
.founder-quote::before{
    content:""; position:absolute; left:0; top:0; bottom:0;
    width:10px; background:#fff;
}
.founder-mobile{ display:none; }

@media (max-width:1250px){
    .founder{ aspect-ratio:auto; background:#1b1b1b; padding:90px 20px 0; }
    .founder-text{ margin:0 auto; max-width:720px; }
    /* ambil bagian foto founder saja dari gambar */
    .founder-mobile{
        display:block;
        height:480px;
        margin:50px -20px 0;
        background:url('{{ asset('images/founder.webp') }}') 83% 30%/238% auto no-repeat;
    }
}
@media (max-width:768px){
    .founder{ padding-top:70px; }
    .founder h2{ font-size:28px; }
    .founder p{ font-size:15px; }
    .founder-quote{ font-size:15px; padding-left:30px; margin-top:32px; }
    .founder-mobile{ height:360px; }
}

/* ===== Legalitas ===== */
.legal{
    background:#000;
    padding:60px 0 100px;
    font-family:'Poppins',sans-serif;
    color:#fff;
}
.legal h2{
    font-family:'Saira',sans-serif; font-weight:600;
    font-size:40px; line-height:1.3; text-align:center; margin:0 0 28px;
}
.legal-grid{
    width:min(100% - 40px,1100px);
    margin:0 auto;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}
.legal-col{
    background:#070505;
    border-top:1px solid #161212;
    padding:62px 20px 60px;
    text-align:center;
}
.legal-col h3{
    font-family:'Saira',sans-serif; font-weight:500;
    font-size:24px; line-height:1.3; margin:0 0 26px;
}
.legal-col img{ display:block; margin:0 auto; height:auto; max-width:100%; }
.legal-col .img-a{ width:49%; }
.legal-col .img-b{ width:62%; }

@media (max-width:768px){
    .legal{ padding:50px 0 60px; }
    .legal h2{ font-size:28px; }
    .legal-grid{ grid-template-columns:1fr; }
    .legal-col{ padding:40px 20px; }
    .legal-col .img-a{ width:70%; }
    .legal-col .img-b{ width:90%; }
}

/* ===== Layanan ===== */
.services{
    background:#fff;
    padding:108px 0 100px;
    font-family:'Poppins',sans-serif;
    color:#111;
}
.services h2{
    font-family:'Saira',sans-serif; font-weight:600;
    font-size:40px; line-height:1.3; text-align:center; margin:0 0 20px;
}
.services-grid{
    width:min(100% - 40px,1120px);
    margin:0 auto;
    display:grid;
    grid-template-columns:repeat(3,1fr);
}
.service-card{
    display:flex; flex-direction:column;
    padding:10px 10px 10px;
    text-align:center;
    background:#f5f5f5;
    transition:background .3s ease, color .3s ease;
}
.service-card:nth-child(1){ background:#f9f9f9; }
.service-card:nth-child(2){ background:#efefef; }
.service-card img{ display:block; width:100%; height:198px; object-fit:cover; }
.service-card h3{ font-size:24px; font-weight:700; line-height:1.3; margin:30px 0 0; }
.service-card p{
    font-size:15px; line-height:1.65; color:#555;
    margin:12px 0 22px; padding:0 5px; flex:1;
    transition:color .3s ease;
}
.service-btn{
    align-self:center;
    display:inline-flex; align-items:center; justify-content:center;
    width:179px; height:39px;
    background:#000; color:#fff;
    font-size:15px; font-weight:500; text-decoration:none;
    transition:background .3s ease, color .3s ease;
}
.service-card:hover{ background:#000; color:#fff; }
.service-card:hover p{ color:#fff; }
.service-card:hover .service-btn{ background:#fff; color:#000; }

@media (max-width:900px){
    .services{ padding:70px 0 60px; }
    .services h2{ font-size:28px; padding:0 20px; }
    .services-grid{ grid-template-columns:1fr; max-width:480px; gap:12px; }
}

/* ===== Visi & Misi ===== */
.vm{
    background:#fff;
    font-family:'Poppins',sans-serif;
    color:#111;
    overflow:hidden;
}
.vm h2{
    font-family:'Saira',sans-serif; font-weight:700;
    font-size:40px; line-height:1.2; margin:0 0 20px;
}
.vm-visi{ padding:100px 10% 0; }
.vm-visi p{ font-size:16px; line-height:1.55; margin:0; max-width:780px; }

/* foto rumah: langit sudah memudar putih dari gambarnya sendiri */
.vm-photo{
    margin-top:65px;
    aspect-ratio:3.05/1;
    background:url('{{ asset('images/Jasa-Arsitek-Jember-Visi-Perusahaan.webp') }}') center bottom/cover no-repeat;
}

/* denah 3D = background selebar layar, "Misi" ada di atasnya */
.vm-plan-wrap{
    background:url('{{ asset('images/Jasa-Arsitek-Jember-Misi-Perusahaan.webp') }}') 50% -2.8vw/100% auto no-repeat, #fff;
    padding:30% 0 100px;   /* tinggi mengikuti lebar gambar */
}
.vm-misi{ padding:0 10%; }
.vm-misi ol{ margin:0; padding-left:45px; max-width:825px; }
.vm-misi li{ font-size:15px; line-height:1.65; }

@media (max-width:900px){
    .vm h2{ font-size:32px; }
    .vm-visi{ padding:70px 20px 0; }
    .vm-visi p{ font-size:15px; }
    .vm-photo{ aspect-ratio:auto; height:260px; margin-top:30px; }
    .vm-plan-wrap{
        background:url('{{ asset('images/Jasa-Arsitek-Jember-Misi-Perusahaan.webp') }}') -53vw -2vw/150% auto no-repeat, #fff;
        padding:190px 0 60px;
    }
    .vm-misi{ padding:0 20px; }
    .vm-misi ol{ padding-left:30px; }
}

/* ===== Portofolio Proyek ===== */
.pf{ background:#fff; padding:80px 0 90px; font-family:'Poppins',sans-serif; color:#222; }
.pf-wrap{ width:min(100% - 40px,1140px); margin:0 auto; }
.pf h2{ font-size:24px; font-weight:700; margin:0 0 6px; }
.pf-sub{ font-size:15px; color:#8a8a8a; margin:0 0 28px; }
.pf-tabs{ display:flex; gap:6px; border-bottom:1px solid #eee; margin-bottom:26px; overflow-x:auto; }
.pf-tab{
    background:none; border:none; border-bottom:2px solid transparent;
    padding:14px 16px; font-family:inherit; font-size:15px; color:#555;
    cursor:pointer; white-space:nowrap; margin-bottom:-1px; transition:.2s;
}
.pf-tab:hover{ color:#000; }
.pf-tab.active{ color:#000; border-bottom-color:var(--accent); font-weight:500; }

.pf-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; align-items:start; }
.pf-card{
    background:#fff; border:1px solid #ececec; border-radius:14px; overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,.04); transition:box-shadow .25s, transform .25s;
}
.pf-card:hover{ box-shadow:0 12px 30px rgba(0,0,0,.1); transform:translateY(-3px); }
.pf-card[hidden]{ display:none; }
.pf-img{ position:relative; display:block; height:224px; }
.pf-img img{ width:100%; height:100%; object-fit:cover; display:block; }
.pf-photos{
    position:absolute; right:12px; bottom:12px;
    background:rgba(0,0,0,.65); color:#fff; font-size:12px; font-weight:600;
    padding:5px 9px; border-radius:8px; display:flex; align-items:center; gap:5px;
}
.pf-body{ padding:16px; }
.pf-badges{ display:flex; gap:8px; margin-bottom:14px; }
.pf-badge{
    height:26px; padding:0 10px; display:inline-flex; align-items:center; gap:5px;
    font-size:12px; font-weight:700; text-transform:uppercase; border-radius:4px;
}
.pf-badge.main{ background:#000; color:#fff; }
.pf-badge.type{ border:1px solid #e3e3e3; color:#444; font-weight:500; }
.pf-label{ font-size:12px; color:#999; text-transform:uppercase; margin:0; }
.pf-price{ font-size:22px; font-weight:700; margin:2px 0 8px; }
.pf-loc{ font-size:13px; color:#666; display:flex; align-items:center; gap:5px; margin:0 0 6px; }
.pf-loc i{ color:#e0a000; }
.pf-title{
    font-size:16px; font-weight:600; line-height:1.4; margin:0 0 12px; padding-left:10px;
    border-left:3px solid var(--accent);
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.pf-title a{ color:inherit; text-decoration:none; }
.pf-specs{ display:flex; background:#f6f6f6; border-radius:10px; padding:8px 0; }
.pf-spec{ flex:1; text-align:center; border-left:1px solid #e8e8e8; }
.pf-spec:first-child{ border-left:none; }
.pf-spec b{ display:block; font-size:16px; font-weight:700; line-height:1.4; }
.pf-spec span{ font-size:11px; color:#8a8a8a; text-transform:uppercase; display:inline-flex; align-items:center; gap:4px; }
.pf-foot{
    display:flex; align-items:center; gap:10px;
    border-top:1px solid #f0f0f0; margin-top:18px; padding-top:16px;
}
.pf-agent{ display:flex; align-items:center; gap:10px; flex:1; min-width:0; }
.pf-agent img{ width:36px; height:36px; border-radius:50%; object-fit:cover; background:#eee; }
.pf-agent strong{ display:block; font-size:13px; font-weight:600; line-height:1.3; }
.pf-agent small{ font-size:11px; color:#777; display:flex; align-items:center; gap:5px; }
.pf-agent small::before{ content:""; width:6px; height:6px; border-radius:50%; background:#f5a400; }
.pf-call{
    width:40px; height:36px; border:1px solid #e3e3e3; border-radius:8px; background:#fff;
    display:flex; align-items:center; justify-content:center; color:#333; font-size:18px; text-decoration:none;
}
.pf-wa{
    height:36px; padding:0 14px; border-radius:8px; background:#22c55e; color:#fff;
    display:inline-flex; align-items:center; gap:6px; font-size:14px; font-weight:600; text-decoration:none;
}
.pf-wa:hover{ background:#16a34a; color:#fff; }

@media (max-width:1000px){ .pf-grid{ grid-template-columns:repeat(2,1fr); } }
@media (max-width:640px){
    .pf{ padding:60px 0; }
    .pf-grid{ grid-template-columns:1fr; }
}
</style>

<section class="hero">
    <div class="hero-content">
        <h1>Jasa Arsitek<br>Jember</h1>
        <h2>Rancang Kebaikan, <span class="rotating" id="rotatingText">Membangun Kehidupan</span></h2>
        <p>Antosa Architect merupakan jasa arsitek jember profesional berlisensi yang berkomitmen menghadirkan solusi hunian terbaik bagi Anda.</p>
        <div class="hero-buttons">
            <a href="https://wa.me/6285189523863" target="_blank" rel="noopener" class="hero-btn btn-primary-hero">Konsultasi Gratis</a>
            <a href="#" class="hero-btn btn-secondary-hero">Lihat Portofolio</a>
        </div>
    </div>
</section>

{{-- Statistik --}}
<section class="stats">
    <div class="stats-inner">
        <div>
            <p class="stats-label">Berpengalaman</p>
            <h2>10 Tahun Lebih Dipercaya Keluarga Indonesia</h2>
            <div class="stats-line"></div>
        </div>
        <div class="stats-cards">
            <div class="stat-card"><strong class="counter" data-start="1" data-end="239" data-suffix="+" data-duration="2000">1+</strong><span>Project Selesai</span></div>
            <div class="stat-card"><strong class="counter" data-start="1" data-end="179" data-suffix="+" data-duration="2000">1+</strong><span>Arsitektur</span></div>
            <div class="stat-card"><strong class="counter" data-start="1" data-end="58" data-suffix="+" data-duration="2000">1+</strong><span>Konstruksi</span></div>
        </div>
    </div>
</section>

{{-- Tentang Perusahaan --}}
<section class="about">
    <div class="about-text">
        <h2>Tentang Perusahaan</h2>
        <p>Antosa Architect adalah perusahaan terkemuka yang bergerak di bidang arsitektur, perencanaan pembangunan, jasa konstruksi, hingga renovasi bangunan.</p>
        <p>Dengan pengalaman lebih dari 10 tahun, kami telah membantu ribuan keluarga Indonesia mewujudkan rumah impian mereka. Didukung oleh tim profesional bersertifikasi dan berpengalaman, kami berkomitmen untuk menghadirkan desain yang tidak hanya estetis, tetapi juga fungsional, nyaman dan aman.</p>
    </div>
</section>

{{-- Mengapa Memilih --}}
<section class="why">
    <div class="why-head">
        <h2>Mengapa Memilih Jasa Arsitek Jember - Antosa Architect?</h2>
    </div>
    <div class="why-body">
        <div class="why-mobile"></div>
        <ul class="why-list">
            <li>
                <strong>01. Pengalaman Lebih dari 10 Tahun</strong>
                <p>Ribuan proyek telah kami tangani dengan hasil yang memuaskan.</p>
            </li>
            <li>
                <strong>02. Profesional dan Berlisensi</strong>
                <p>Tim kami memiliki keahlian di bidang arsitektur dengan sertifikasi yang diakui.</p>
            </li>
            <li>
                <strong>03. Desain Inovatif</strong>
                <p>Menciptakan solusi yang sesuai kebutuhan, hemat waktu, dan biaya.</p>
            </li>
            <li>
                <strong>04. Pendekatan Terpadu</strong>
                <p>Mulai dari perencanaan hingga konstruksi, kami memastikan setiap detail diperhatikan.</p>
            </li>
            <li>
                <strong>05. Komitmen Kualitas</strong>
                <p>Mengutamakan standar tinggi dalam setiap proyek.</p>
            </li>
        </ul>
    </div>
</section>

{{-- Founder --}}
<section class="founder">
    <div class="founder-text">
        <h2>Founder Jasa Arsitek Jember - Antosa Architect</h2>
        <p><strong>Ir. Ar. Dwiantosa Ahmad Fathony, IAI., IPP</strong> telah resmi lulus lisensi Arsitek Profesional! Dengan lisensi ini, kami memastikan bahwa setiap proyek yang kami tangani mengikuti standar profesional tertinggi dalam hal desain, keamanan, dan kualitas. Tidak hanya memperkuat kredibilitas kami sebagai perusahaan arsitektur, tapi juga memberikan jaminan kepada Anda bahwa proyek hunian impian Anda berada di tangan ahli yang terpercaya.</p>
        <div class="founder-quote">"Membangun dengan penuh amanah, menjadikan setiap desain sebagai ibadah, dan mengharap ridha Allah dalam setiap karya yang kami wujudkan."</div>
    </div>
    <div class="founder-mobile"></div>
</section>

{{-- Legalitas Perusahaan --}}
<section class="legal">
    <h2>Legalitas Perusahaan</h2>
    <div class="legal-grid">
        <div class="legal-col">
            <h3>Architect Legal</h3>
            {{-- ganti dengan nama file gambar kamu --}}
            <img class="img-a" src="{{ asset('images/legal-architect.webp') }}" alt="Legalitas arsitek Antosa Architect">
        </div>
        <div class="legal-col">
            <h3>Engineer Legal</h3>
            <img class="img-b" src="{{ asset('images/legal-engineer.webp') }}" alt="Legalitas insinyur Antosa Architect">
        </div>
    </div>
</section>

{{-- Layanan (siap diganti data dari database/CMS) --}}
@php
    $services = $services ?? [
        [
            'title'   => 'Jasa Arsitek',
            'excerpt' => 'Perancangan arsitektur, tata ruang, dan detail teknis seperti struktur, sirkulasi udara, pencahayaan alami, regulasi bangunan.',
            'image'   => 'images/layanan-arsitek.webp',
            'url'     => '#',
        ],
        [
            'title'   => 'Jasa Renovasi Rumah',
            'excerpt' => 'Merenovasi, perbaikan, dan perawatan bangunan, meningkatkan kualitas bangunan menjadi lebih sehat dan nyaman.',
            'image'   => 'images/layanan-renovasi.webp',
            'url'     => '#',
        ],
        [
            'title'   => 'Jasa Bangun Rumah',
            'excerpt' => 'Konstruksi Bangunan dari Nol, memastikan setiap bangunan memiliki kualitas terbaik, nyaman dan sehat.',
            'image'   => 'images/layanan-bangun.webp',
            'url'     => '#',
        ],
    ];
@endphp
<section class="services">
    <h2>Layanan Jasa Arsitek Jember - Antosa Architect</h2>
    <div class="services-grid">
        @foreach ($services as $service)
            <article class="service-card">
                <img src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}">
                <h3>{{ $service['title'] }}</h3>
                <p>{{ $service['excerpt'] }}</p>
                <a href="{{ $service['url'] }}" class="service-btn">Info Selengkapnya</a>
            </article>
        @endforeach
    </div>
</section>

{{-- Visi & Misi --}}
<section class="vm">
    <div class="vm-visi">
        <h2>Visi</h2>
        <p>Mewujudkan kualitas hidup masyarakat dengan menciptakan bangunan yang <strong>nyaman dan aman.</strong></p>
    </div>

    <div class="vm-photo" role="img" aria-label="Desain rumah Antosa Architect"></div>

    <div class="vm-plan-wrap">
        <div class="vm-misi">
            <h2>Misi</h2>
            <ol>
                <li>Menciptakan desain yang sesuai dengan karakter pemilik.</li>
                <li>Menciptakan keunikan dalam setiap karya desain.</li>
                <li>Menciptakan bangunan yang selaras dengan alam dan ramah lingkungan.</li>
                <li>Menjadi perusahaan arsitek yang bisa menjadi inspirasi dan manfaat untuk banyak orang dan alam semesta.</li>
                <li>Menjadi perusahaan arsitek global yang terus memperluas bisnis perusahaan.</li>
            </ol>
        </div>
    </div>
</section>

{{-- Portofolio Proyek (siap diganti data dari database/CMS) --}}
@php
    $projects = $projects ?? [
        ['title'=>'Desain Rumah Modern Tropis 2 Lantai','category'=>'Rumah Tinggal','service'=>'Desain','type'=>'Rumah','price'=>'Rp 850 Juta','location'=>'Jember, Jawa Timur','bedrooms'=>4,'bathrooms'=>3,'land'=>'200m²','building'=>'180m²','photos'=>6,'image'=>'images/banner1.jpg','url'=>'#'],
        ['title'=>'Renovasi Rumah Minimalis Type 90','category'=>'Renovasi','service'=>'Renovasi','type'=>'Rumah','price'=>'Rp 320 Juta','location'=>'Jember, Jawa Timur','bedrooms'=>3,'bathrooms'=>2,'land'=>'120m²','building'=>'90m²','photos'=>5,'image'=>'images/banner2.jpg','url'=>'#'],
        ['title'=>'Bangun Ruko Dua Lantai','category'=>'Komersial','service'=>'Bangun','type'=>'Ruko','price'=>'Rp 1,2 Miliar','location'=>'Jember, Jawa Timur','bedrooms'=>null,'bathrooms'=>2,'land'=>'150m²','building'=>'240m²','photos'=>8,'image'=>'images/banner3.jpg','url'=>'#'],
    ];
    $pfCategories = collect($projects)->pluck('category')->unique()->values();
@endphp
<section class="pf" id="portofolio">
    <div class="pf-wrap">
        <h2>Portofolio Proyek Antosa Architect</h2>
        <p class="pf-sub">Lihat hasil karya desain, bangun, dan renovasi rumah yang telah kami wujudkan untuk klien.</p>

        <div class="pf-tabs" id="pfTabs">
            <button type="button" class="pf-tab active" data-filter="all">Semua Proyek</button>
            @foreach ($pfCategories as $cat)
                <button type="button" class="pf-tab" data-filter="{{ $cat }}">{{ $cat }}</button>
            @endforeach
        </div>

        <div class="pf-grid">
            @foreach ($projects as $pr)
                <article class="pf-card" data-category="{{ $pr['category'] }}">
                    <a href="{{ $pr['url'] }}" class="pf-img">
                        <img src="{{ asset($pr['image']) }}" alt="{{ $pr['title'] }}">
                        <span class="pf-photos"><i class="ti ti-camera"></i> {{ $pr['photos'] }}</span>
                    </a>
                    <div class="pf-body">
                        <div class="pf-badges">
                            <span class="pf-badge main">{{ $pr['service'] }}</span>
                            <span class="pf-badge type"><i class="ti ti-home"></i> {{ $pr['type'] }}</span>
                        </div>
                        <p class="pf-label">Estimasi biaya</p>
                        <div class="pf-price">{{ $pr['price'] }}</div>
                        <p class="pf-loc"><i class="ti ti-map-pin-filled"></i> {{ $pr['location'] }}</p>
                        <h3 class="pf-title"><a href="{{ $pr['url'] }}">{{ $pr['title'] }}</a></h3>
                        <div class="pf-specs">
                            @if (!empty($pr['bedrooms']))<div class="pf-spec"><b>{{ $pr['bedrooms'] }}</b><span><i class="ti ti-bed"></i> KT</span></div>@endif
                            @if (!empty($pr['bathrooms']))<div class="pf-spec"><b>{{ $pr['bathrooms'] }}</b><span><i class="ti ti-bath"></i> KM</span></div>@endif
                            @if (!empty($pr['land']))<div class="pf-spec"><b>{{ $pr['land'] }}</b><span><i class="ti ti-arrows-maximize"></i> LT</span></div>@endif
                            @if (!empty($pr['building']))<div class="pf-spec"><b>{{ $pr['building'] }}</b><span><i class="ti ti-building"></i> LB</span></div>@endif
                        </div>
                        <div class="pf-foot">
                            <div class="pf-agent">
                                <img src="{{ asset('images/antosa.png') }}" alt="Antosa Architect">
                                <div>
                                    <strong>Antosa Architect</strong>
                                    <small>Arsitek Berlisensi</small>
                                </div>
                            </div>
                            <a href="tel:+6285189523863" class="pf-call" aria-label="Telepon"><i class="ti ti-phone"></i></a>
                            <a href="https://wa.me/6285189523863?text={{ urlencode('Halo Antosa Architect, saya tertarik dengan proyek: '.$pr['title']) }}" target="_blank" rel="noopener" class="pf-wa"><i class="ti ti-brand-whatsapp"></i> WhatsApp</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ===== Counter statistik =====
    const counters = document.querySelectorAll(".counter");

    function runCounter(el) {
        const start = parseInt(el.dataset.start) || 0;
        const end = parseInt(el.dataset.end) || 0;
        const suffix = el.dataset.suffix || "";
        const duration = parseInt(el.dataset.duration) || 2000;
        const t0 = performance.now();

        function tick(now) {
            const p = Math.min((now - t0) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3); // melambat di akhir
            const val = Math.round(start + (end - start) * eased);
            el.textContent = val.toLocaleString("id-ID") + suffix;
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    if ("IntersectionObserver" in window) {
        const io = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });
        counters.forEach(function (c) { io.observe(c); });
    } else {
        counters.forEach(runCounter);
    }

    // ===== Filter tab portofolio =====
    const pfTabs = document.querySelectorAll(".pf-tab");
    const pfCards = document.querySelectorAll(".pf-card");
    pfTabs.forEach(function (tab) {
        tab.addEventListener("click", function () {
            pfTabs.forEach(function (t) { t.classList.remove("active"); });
            tab.classList.add("active");
            const f = tab.dataset.filter;
            pfCards.forEach(function (card) {
                card.hidden = !(f === "all" || card.dataset.category === f);
            });
        });
    });

    const el = document.getElementById("rotatingText");
    if (!el) return;

    const words = ["Membangun Kehidupan", "Merancang Hunian"];
    let i = 0;

    setInterval(function () {
        el.classList.add("fade");
        setTimeout(function () {
            i = (i + 1) % words.length;
            el.textContent = words[i];
            el.classList.remove("fade");
        }, 400);
    }, 3500);
});
</script>
@endpush