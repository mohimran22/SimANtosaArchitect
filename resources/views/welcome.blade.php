@extends('layouts.website')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Saira:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    background:url('{{ asset('images/visi-rumah.webp') }}') center bottom/cover no-repeat;
}

/* denah 3D = background selebar layar, "Misi" ada di atasnya */
.vm-plan-wrap{
    background:url('{{ asset('images/denah-3d.webp') }}') 50% -2.8vw/100% auto no-repeat, #fff;
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
        background:url('{{ asset('images/denah-3d.webp') }}') -53vw -2vw/150% auto no-repeat, #fff;
        padding:190px 0 60px;
    }
    .vm-misi{ padding:0 20px; }
    .vm-misi ol{ padding-left:30px; }
}
html{ scroll-behavior:smooth; }
#listing{ scroll-margin-top:80px; }
@media (prefers-reduced-motion:reduce){ html{ scroll-behavior:auto; } }
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
.pf-cicilan{
    display:inline-flex; align-items:center; gap:6px; background:#fff6e0; color:#555;
    font-size:13px; padding:5px 10px; border-radius:6px; margin:0 0 8px;
}
.pf-cicilan b{ color:#c77700; font-weight:600; }
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

/* ===== Portfolio / Artikel ===== */
.pfo{ background:#000; color:#fff; padding:150px 0 110px; font-family:'Poppins',sans-serif; text-align:center; }
.pfo-wrap{ width:min(100% - 40px,1425px); margin:0 auto; }
.pfo h2{ font-size:clamp(30px,3.4vw,48px); font-weight:600; margin:0 0 26px; color:#fff; }
.pfo-sub{ font-size:clamp(15px,1.4vw,20px); font-weight:400; margin:0 0 80px; color:#fff; }
.pfo-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:0; }
.pfo-item{ position:relative; display:block; aspect-ratio:475/261; overflow:hidden; background:#000; color:#fff; text-decoration:none; }
.pfo-item img{ width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s ease; }
.pfo-item:hover img{ transform:scale(1.05); }
.pfo-item::after{ content:""; position:absolute; inset:0; background:rgba(0,0,0,0); transition:background .3s; }
.pfo-item:hover::after{ background:rgba(0,0,0,.35); }
.pfo-text{ display:flex; align-items:center; justify-content:center; padding:24px 30px; }
.pfo-text span{ font-family:'Saira',sans-serif; font-weight:700; font-size:clamp(20px,2.1vw,30px); line-height:1.3; }
.pfo-btn-wrap{ margin-top:85px; }
.pfo-btn{
    display:inline-block; background:#f9b719; color:#000; font-family:'Saira',sans-serif; font-weight:500;
    font-size:20px; padding:15px 33px; border-radius:3px; text-decoration:none; transition:background .2s;
}
.pfo-btn:hover{ background:#e0a30e; color:#000; }
@media (max-width:1000px){ .pfo-grid{ grid-template-columns:repeat(2,1fr); } .pfo{ padding:100px 0 80px; } .pfo-sub{ margin-bottom:50px; } }
@media (max-width:560px){ .pfo-grid{ grid-template-columns:1fr; } .pfo-btn-wrap{ margin-top:50px; } }

/* ===== Klien Kami ===== */
.kl{ background:#fff; color:#222; padding:190px 0 200px; text-align:center; font-family:'Saira',sans-serif; }
.kl-wrap{ width:min(100% - 40px,1100px); margin:0 auto; }
.kl h2{ font-family:'Saira',sans-serif; font-size:clamp(32px,3.4vw,48px); font-weight:700; margin:0 0 28px; color:#1f1f1f; }
.kl-sub{ font-size:clamp(15px,1.4vw,20px); font-weight:400; margin:0 0 110px; color:#222; }
.kl-grid{ display:grid; grid-template-columns:repeat(3,1fr); row-gap:70px; align-items:center; justify-items:center; }
.kl-item{ display:flex; align-items:center; justify-content:center; width:100%; height:135px; padding:0 24px; }
.kl-item img{ max-width:290px; width:100%; max-height:135px; object-fit:contain; display:block; transition:transform .3s; }
a.kl-item:hover img{ transform:scale(1.06); }
@media (max-width:760px){
    .kl{ padding:100px 0 100px; }
    .kl-sub{ margin-bottom:50px; }
    .kl-grid{ grid-template-columns:repeat(2,1fr); row-gap:30px; }
    .kl-item{ height:100px; padding:0 12px; }
}

/* ===== Review Pelanggan ===== */
.rv{ background:radial-gradient(ellipse at top,#1a1a1a 0%,#0c0c0c 70%); color:#fff; padding:100px 0 120px; text-align:center; font-family:'Poppins',sans-serif; }
.rv-wrap{ width:min(100% - 40px,1142px); margin:0 auto; }
.rv-eyebrow{ font-size:16px; font-weight:400; margin:0 0 30px; color:#fff; }
.rv h2{ font-family:'Saira',sans-serif; font-size:clamp(34px,4vw,60px); font-weight:700; margin:0 0 80px; color:#fff; }
.rv-grid{ display:grid; grid-template-columns:repeat(2,1fr); column-gap:114px; row-gap:70px; text-align:left; }
.rv-quote{ display:block; width:40px; height:34px; margin:0 0 36px; fill:#fff; }
.rv-video{ position:relative; width:100%; aspect-ratio:16/9; background:#000 center/cover no-repeat; overflow:hidden; cursor:pointer; display:block; border:0; padding:0; }
.rv-video iframe{ position:absolute; inset:0; width:100%; height:100%; border:0; }
.rv-play{
    position:absolute; left:50%; top:50%; width:62px; height:62px; transform:translate(-50%,-50%);
    border:2px solid #fff; border-radius:50%; display:flex; align-items:center; justify-content:center;
    transition:transform .2s, background .2s;
}
.rv-play::before{ content:""; margin-left:4px; border-left:16px solid #fff; border-top:10px solid transparent; border-bottom:10px solid transparent; }
.rv-video:hover .rv-play{ transform:translate(-50%,-50%) scale(1.1); background:rgba(0,0,0,.35); }
.rv-text{ font-size:16px; line-height:2; font-weight:400; margin:22px 0 22px; color:#fff; }
.rv-name{ font-size:20px; font-weight:600; margin:0; line-height:1.3; }
.rv-role{ font-size:14px; letter-spacing:3px; text-transform:uppercase; margin:2px 0 0; color:#fff; }
@media (max-width:900px){
    .rv{ padding:70px 0 80px; }
    .rv-grid{ grid-template-columns:1fr; row-gap:50px; }
    .rv h2{ margin-bottom:50px; }
}

/* ===== CTA Konsultasi ===== */
.cta{ background:#fff; padding:100px 0 125px; font-family:'Poppins',sans-serif; color:#111; }
.cta-wrap{ width:min(100% - 40px,1140px); margin:0 auto; display:flex; align-items:center; justify-content:space-between; gap:40px; }
.cta h2{ font-family:'Saira',sans-serif; font-size:clamp(28px,3vw,42px); font-weight:700; line-height:1.25; margin:0 0 22px; max-width:780px; }
.cta p{ font-size:15px; margin:0; color:#222; }
.cta p b{ font-weight:700; color:#000; }
.cta-btn{
    display:inline-flex; align-items:center; justify-content:center; gap:14px; flex:0 0 auto;
    background:#000; color:#fff; font-size:14px; font-weight:600; text-transform:uppercase; letter-spacing:.2px;
    padding:0 44px; height:46px; min-width:208px; text-decoration:none; transition:background .2s;
}
.cta-btn:hover{ background:#22c55e; color:#fff; }
.cta-btn svg{ width:16px; height:16px; fill:currentColor; }
@media (max-width:800px){
    .cta{ padding:60px 0; }
    .cta-wrap{ flex-direction:column; align-items:flex-start; }
}

/* ===== FAQ ===== */
.faq{ background:#f4f4f4; padding:100px 0 110px; font-family:'Poppins',sans-serif; color:#111; }
.faq-wrap{ width:min(100% - 40px,900px); margin:0 auto; }
.faq h2{ font-family:'Saira',sans-serif; font-size:clamp(28px,3vw,42px); font-weight:700; margin:0 0 44px; }
.faq-item{ border-bottom:1px solid #d9d9d9; }
.faq-item:last-child{ border-bottom:0; }
.faq-item summary{
    list-style:none; cursor:pointer; padding:13px 0 13px 30px; position:relative;
    font-size:15px; font-weight:600; color:#000; line-height:1.5;
}
.faq-item summary::-webkit-details-marker{ display:none; }
.faq-item summary::before{
    content:""; position:absolute; left:14px; top:50%; transform:translateY(-50%);
    border-left:5px solid #000; border-top:4px solid transparent; border-bottom:4px solid transparent;
    transition:transform .2s;
}
.faq-item[open] summary::before{ transform:translateY(-50%) rotate(90deg); }
.faq-item .faq-a{ padding:0 0 18px 30px; font-size:15px; line-height:1.8; color:#444; }
@media (max-width:700px){ .faq{ padding:60px 0 70px; } }
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
<section class="about" id="tentang">
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
<section class="services" id="layanan">
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

{{-- Listing Rumah: data dari SIMAntosa ($listings dikirim dari controller) --}}
@php
    // ---- data contoh, otomatis diabaikan kalau controller mengirim $listings ----
    $listings = $listings ?? [
        ['status'=>'Dijual','tipe'=>'Rumah','harga'=>1000000000,'cicilan'=>7120000,'lokasi'=>'Jember, Jawa Timur','kota'=>'Jember','judul'=>'Dijual Rumah Minimalis 2 Lantai Strategis','kt'=>4,'km'=>2,'lt'=>'99m²','lb'=>'90m²','jumlah_foto'=>6,'foto'=>'images/listing-1.jpg','agen_nama'=>'Antosa Architect','agen_peran'=>'Pemilik Properti','agen_foto'=>'images/antosa.png','agen_telepon'=>'085189523863','url'=>'#'],
        ['status'=>'Dijual','tipe'=>'Tanah','harga'=>350000000,'lokasi'=>'Kaliwates, Jember','kota'=>'Jember','judul'=>'Dijual Tanah Kavling Siap Bangun','kt'=>null,'km'=>null,'lt'=>'580m²','lb'=>null,'jumlah_foto'=>5,'foto'=>'images/listing-2.jpg','agen_nama'=>'Antosa Architect','agen_peran'=>'Agen Independen','agen_foto'=>'images/antosa.png','agen_telepon'=>'085189523863','url'=>'#'],
        ['status'=>'Dijual','tipe'=>'Rumah','harga'=>650000000,'lokasi'=>'Banyuwangi, Jawa Timur','kota'=>'Banyuwangi','judul'=>'Dijual Rumah Modern Tropis Dekat Pusat Kota','kt'=>3,'km'=>2,'lt'=>'120m²','lb'=>'90m²','jumlah_foto'=>5,'foto'=>'images/listing-3.jpg','agen_nama'=>'Antosa Architect','agen_peran'=>'Pemilik Properti','agen_foto'=>'images/antosa.png','agen_telepon'=>'085189523863','url'=>'#'],
    ];
    $lsTabs = collect($listings)->map(fn($l) => data_get($l, 'kota'))->filter()->unique()->values();
    $lsImg  = fn($path) => ! $path ? asset('images/antosa.png') : (str_starts_with($path, 'http') ? $path : (str_starts_with($path, 'images/') ? asset($path) : asset('storage/' . $path)));
    $lsM2   = fn($v) => is_numeric($v) ? $v . 'm²' : $v;
    $lsRp = fn($v) => is_numeric($v)
        ? 'Rp ' . number_format($v, 0, ',', '.')
        : $v;
    // $lsRp   = fn($v) => is_numeric($v)
    //     ? 'Rp ' . ($v >= 1000000000 ? rtrim(rtrim(number_format($v / 1000000000, 2, ',', '.'), '0'), ',') . ' Miliar'
    //              : ($v >= 1000000 ? rtrim(rtrim(number_format($v / 1000000, 2, ',', '.'), '0'), ',') . ' Juta' : number_format($v, 0, ',', '.')))
    //     : $v;
    $lsWa   = function ($no) { $n = preg_replace('/\D/', '', (string) $no); return str_starts_with($n, '0') ? '62' . substr($n, 1) : $n; };
    $waMarketing = $lsWa(config('antosa.wa_marketing'));
@endphp
<section class="pf" id="listing">
    <div class="pf-wrap">
        <h2>Pilihan Properti Dijual</h2>
        <p class="pf-sub">Temukan rumah dan properti pilihan langsung dari pemilik &amp; agen terverifikasi.</p>

        <div class="pf-tabs" id="pfTabs">
            <button type="button" class="pf-tab active" data-filter="all">Semua Lokasi</button>
            @foreach ($lsTabs as $kota)
                <button type="button" class="pf-tab" data-filter="{{ $kota }}">{{ $kota }}</button>
            @endforeach
        </div>

        <div class="pf-grid">
            @foreach ($listings as $l)
                @php
                    $status = data_get($l, 'status', 'Dijual');
                    $url    = data_get($l, 'url', '#');
                @endphp
                <article class="pf-card" data-category="{{ data_get($l, 'kota') }}">
                    <a href="{{ $url }}" class="pf-img">
                        <img src="{{ $lsImg(data_get($l, 'foto')) }}" alt="{{ data_get($l, 'judul') }}" loading="lazy">
                        @if (data_get($l, 'jumlah_foto'))
                            <span class="pf-photos"><i class="ti ti-camera"></i> {{ data_get($l, 'jumlah_foto') }}</span>
                        @endif
                    </a>
                    <div class="pf-body">
                        <div class="pf-badges">
                            <span class="pf-badge main">{{ $status }}</span>
                            <span class="pf-badge type"><i class="ti ti-home"></i> {{ data_get($l, 'tipe') }}</span>
                        </div>
                        <p class="pf-label">{{ strtolower($status) === 'disewa' ? 'Harga Sewa' : 'Harga Jual' }}</p>
                        <div class="pf-price">{{ $lsRp(data_get($l, 'harga')) }}</div>
                        @if (is_numeric(data_get($l, 'cicilan')) && data_get($l, 'cicilan') > 0)
                            <div class="pf-cicilan">
                                <i class="ti ti-credit-card"></i>
                                Cicilan mulai
                                <b>Rp {{ number_format((float) data_get($l, 'cicilan'), 0, ',', '.') }}/bln</b>
                            </div>
                        @endif
                        <p class="pf-loc"><i class="ti ti-map-pin-filled"></i> {{ data_get($l, 'lokasi_lengkap', data_get($l, 'lokasi')) }}</p>
                        <h3 class="pf-title"><a href="{{ $url }}">{{ data_get($l, 'judul') }}</a></h3>
                        <div class="pf-specs">
                            @if (data_get($l, 'kt'))<div class="pf-spec"><b>{{ data_get($l, 'kt') }}</b><span><i class="ti ti-bed"></i> KT</span></div>@endif
                            @if (data_get($l, 'km'))<div class="pf-spec"><b>{{ data_get($l, 'km') }}</b><span><i class="ti ti-bath"></i> KM</span></div>@endif
                            @if (data_get($l, 'lt'))<div class="pf-spec"><b>{{ $lsM2(data_get($l, 'lt')) }}</b><span><i class="ti ti-arrows-maximize"></i> LT</span></div>@endif
                            @if (data_get($l, 'lb'))<div class="pf-spec"><b>{{ $lsM2(data_get($l, 'lb')) }}</b><span><i class="ti ti-building"></i> LB</span></div>@endif
                        </div>
                        <div class="pf-foot">
                            <div class="pf-agent">
                                <img src="{{ $lsImg(data_get($l, 'agen_foto')) }}" alt="{{ data_get($l, 'agen_nama') }}">
                                <div>
                                    <strong>{{ data_get($l, 'agen_nama') }}</strong>
                                    {{-- <small>{{ data_get($l, 'agen_peran') }}</small> --}}
                                </div>
                            </div>
                            <a href="tel:+{{ $waMarketing }}" class="pf-call" aria-label="Telepon"><i class="ti ti-phone"></i></a>
                            <a href="https://wa.me/{{ $waMarketing }}?text={{ urlencode('Halo, saya tertarik dengan listing: ' . data_get($l, 'judul')) }}" target="_blank" rel="noopener" class="pf-wa"><i class="ti ti-brand-whatsapp"></i> WhatsApp</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
{{-- Portfolio / Artikel (3 kolom x 4 baris). Siap diganti data CMS: kirim $articles dari controller --}}
@php
    // Tiap item: title, image (boleh kosong => tampil kartu teks), url
    $articles = collect($articles ?? [
        ['title'=>'Proses Pemasangan Granit',            'image'=>'images/portfolio/1.webp',  'url'=>'#'],
        ['title'=>'Rumah Minimalis Pagar Kayu',          'image'=>'images/portfolio/2.webp',  'url'=>'#'],
        ['title'=>'Kampoeng Heritage Kajoetangan',       'image'=>'images/portfolio/3.webp',  'url'=>'#'],
        ['title'=>'Wisata Kuliner Malam',                'image'=>'images/portfolio/4.webp',  'url'=>'#'],
        ['title'=>'City Tour Malang Heritage',           'image'=>'images/portfolio/5.webp',  'url'=>'#'],
        ['title'=>'Gazebo UNSIL Ambruk',                 'image'=>'images/portfolio/6.webp',  'url'=>'#'],
        ['title'=>'Heritage Malang Arsitektur Cor Jesu', 'image'=>'images/portfolio/7.webp',  'url'=>'#'],
        ['title'=>'Rumah Subsidi Industrialis Keren',    'image'=>'images/portfolio/8.webp',  'url'=>'#'],
        ['title'=>'Jingle Antosa Architect',             'image'=>'images/portfolio/9.webp',  'url'=>'#'],
        ['title'=>'Tips Kolom Bangunan Rata Tembok',     'image'=>'images/portfolio/10.webp', 'url'=>'#'],
        ['title'=>'Desain Rumah Industrialis: Gaya Modern yang Tangguh dan Estetik', 'image'=>null, 'url'=>'#'],
        ['title'=>'Perbedaan Besi Ulir dan Polos',       'image'=>'images/portfolio/12.webp', 'url'=>'#'],
    ])->take(12);
    // Normalisasi: bisa menerima model Article (title/judul, thumbnail/image/cover/featured_image, slug) atau array contoh
    $artTitle = fn($a) => data_get($a, 'title') ?? data_get($a, 'judul');
    $artPhoto = fn($a) => data_get($a, 'thumbnail') ?? data_get($a, 'image') ?? data_get($a, 'cover') ?? data_get($a, 'featured_image') ?? data_get($a, 'foto');
    $artUrl   = fn($a) => data_get($a, 'url') ?? (data_get($a, 'slug') && Route::has('articles.show') ? route('articles.show', data_get($a, 'slug')) : '#');
    $artImg = fn($p) => ! $p ? null : (str_starts_with($p, 'http') ? $p : (str_starts_with($p, 'images/') ? asset($p) : asset('storage/' . $p)));
@endphp
<section class="pfo" id="portfolio">
    <div class="pfo-wrap">
        <h2>Portfolio Kami</h2>
        <p class="pfo-sub">Berikut beberapa hasil pekerjaan jasa arsitek jember - Antosa Architect</p>

        <div class="pfo-grid">
            @foreach ($articles as $a)
                @php $img = $artImg($artPhoto($a)); @endphp
                <a href="{{ $artUrl($a) }}" class="pfo-item {{ $img ? '' : 'pfo-text' }}" title="{{ $artTitle($a) }}">
                    @if ($img)
                        <img src="{{ $img }}" alt="{{ $artTitle($a) }}" loading="lazy">
                    @else
                        <span>{{ $artTitle($a) }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="pfo-btn-wrap">
            <a href="{{ Route::has('artikel.index') ? route('artikel.index') : '#' }}" class="pfo-btn">Portfolio Selengkapnya</a>
        </div>
    </div>
</section>
{{-- Klien Kami (siap diganti data CMS: kirim $clients dari controller; tiap item name, logo, url opsional) --}}
@php
    $clients = collect($clients ?? [
        ['name'=>'Galena Logistics',          'logo'=>'images/klien/galena.webp'],
        ['name'=>'CV. Duta Timber Group',     'logo'=>'images/klien/duta-timber.webp'],
        ['name'=>'Bamboe Badja Corp',         'logo'=>'images/klien/bamboe-badja.webp'],
        ['name'=>'Sembilan Bintang Lestari',  'logo'=>'images/klien/sembilan-bintang.webp'],
        ['name'=>'Bank Syariah Indonesia',    'logo'=>'images/klien/bsi.webp'],
        ['name'=>'Grades',                    'logo'=>'images/klien/grades.webp'],
        ['name'=>'Masjid Al-Bahmudah',        'logo'=>'images/klien/al-bahmudah.webp'],
        ['name'=>'Multi Bangunan',            'logo'=>'images/klien/multi-bangunan.webp'],
        ['name'=>'Villa Camelia Indah',       'logo'=>'images/klien/villa-camelia.webp'],
        ['name'=>'Me Mak Enak Indonesia',     'logo'=>'images/klien/me-mak-enak.webp'],
        ['name'=>'Mie Sakera',                'logo'=>'images/klien/mie-sakera.webp'],
        ['name'=>'Yatim Mandiri',             'logo'=>'images/klien/yatim-mandiri.webp'],
    ]);
    $klImg = fn($p) => ! $p ? asset('images/antosa.png') : (str_starts_with($p, 'http') ? $p : (str_starts_with($p, 'images/') ? asset($p) : asset('storage/' . $p)));
@endphp
<section class="kl" id="klien">
    <div class="kl-wrap">
        <h2>Klien Kami</h2>
        <p class="kl-sub">Mereka yang telah mempercayai jasa arsitek jember - Antosa Architect</p>

        <div class="kl-grid">
            @foreach ($clients as $c)
                @php $href = data_get($c, 'url'); @endphp
                @if ($href)
                    <a href="{{ $href }}" target="_blank" rel="noopener" class="kl-item" title="{{ data_get($c, 'name') }}">
                        <img src="{{ $klImg(data_get($c, 'logo')) }}" alt="{{ data_get($c, 'name') }}" loading="lazy">
                    </a>
                @else
                    <div class="kl-item" title="{{ data_get($c, 'name') }}">
                        <img src="{{ $klImg(data_get($c, 'logo')) }}" alt="{{ data_get($c, 'name') }}" loading="lazy">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
{{-- Review Pelanggan (video YouTube). Siap diganti data CMS: kirim $reviews dari controller.
     'video' boleh berupa URL YouTube atau ID-nya saja; 'thumb' opsional (gambar sampul sendiri). --}}
@php
    $reviews = collect($reviews ?? [
        ['video'=>'GANTI_ID_VIDEO_1','thumb'=>null,'text'=>'Saya sangat sangat berapresiasi kepada Antosa dimana dalam pembangunan Rumah Quran ini sangat sangat memuaskan, jadi memang kepercayaan itu sangat sangat mahal.','name'=>'Bapak Abdullah','role'=>'Pendiri Masjid & Rumah Al Quran Al Bahmudah'],
        ['video'=>'GANTI_ID_VIDEO_2','thumb'=>null,'text'=>'Pekerjaannya bagus banget ya, perbedaannya jauh banget dari bentuk awalnya, lebih minimalis, Pasti pangling deh.','name'=>'Ibu Yenny','role'=>'Pemilik Rumah'],
        ['video'=>'GANTI_ID_VIDEO_3','thumb'=>null,'text'=>'Semua Oke, nggak molor waktu pengerjaannya, tepat waktu, cara kerjanya juga bagus, sesuai ekspektasi.','name'=>'Ibu Siti Chotimah','role'=>'Pemilik Rumah'],
        ['video'=>'GANTI_ID_VIDEO_4','thumb'=>null,'text'=>'Kalau mau bangun rumah kita emang harus hati-hati. apalagi membutuhkan biaya yang lumayan besar,itu yang melandasi kenapa kami memilih Antosa Architect. Kita menilainya antosa architect ini Amanah','name'=>'Bapak Herman Setio Budi','role'=>'Pemilik Rumah'],
    ])->take(4);
    $ytId = function ($v) {
        if (preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{6,})~', (string) $v, $m)) return $m[1];
        return trim((string) $v);
    };
@endphp
<section class="rv" id="review">
    <div class="rv-wrap">
        <p class="rv-eyebrow">Review Pelanggan</p>
        <h2>Apa Kata Mereka?</h2>

        <div class="rv-grid">
            @foreach ($reviews as $r)
                @php
                    $vid   = $ytId(data_get($r, 'video'));
                    $thumb = data_get($r, 'thumb');
                    $thumb = $thumb ? (str_starts_with($thumb, 'http') ? $thumb : (str_starts_with($thumb, 'images/') ? asset($thumb) : asset('storage/' . $thumb))) : "https://i.ytimg.com/vi/{$vid}/hqdefault.jpg";
                @endphp
                <figure style="margin:0">
                    <svg class="rv-quote" viewBox="0 0 512 512" aria-hidden="true"><path d="M0 216C0 149.7 53.7 96 120 96h8c17.7 0 32 14.3 32 32s-14.3 32-32 32h-8c-30.9 0-56 25.1-56 56v8h64c35.3 0 64 28.7 64 64v64c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V216zm256 0c0-66.3 53.7-120 120-120h8c17.7 0 32 14.3 32 32s-14.3 32-32 32h-8c-30.9 0-56 25.1-56 56v8h64c35.3 0 64 28.7 64 64v64c0 35.3-28.7 64-64 64h-64c-35.3 0-64-28.7-64-64V216z"/></svg>
                    <button type="button" class="rv-video" data-yt="{{ $vid }}" style="background-image:url('{{ $thumb }}')" aria-label="Putar video {{ data_get($r, 'name') }}">
                        <span class="rv-play"></span>
                    </button>
                    <p class="rv-text">{{ data_get($r, 'text') }}</p>
                    <p class="rv-name">{{ data_get($r, 'name') }}</p>
                    <p class="rv-role">{{ data_get($r, 'role') }}</p>
                </figure>
            @endforeach
        </div>
    </div>
</section>
{{-- CTA Konsultasi --}}
@php
    $ctaWa = $waMarketing ?? preg_replace('/^0/', '62', preg_replace('/\D/', '', (string) config('antosa.wa_marketing')));
@endphp
<section class="cta" id="konsultasi">
    <div class="cta-wrap">
        <div>
            <h2>Konsultasikan Kebutuhan Rumah Impian Anda</h2>
            <p><b>Gratis.</b> dengan klik tombol Whatsapp berikut ini.</p>
        </div>
        <a href="https://wa.me/{{ $ctaWa }}?text={{ urlencode('Halo Antosa Architect, saya ingin konsultasi kebutuhan rumah impian saya.') }}" target="_blank" rel="noopener" class="cta-btn">
            <svg viewBox="0 0 512 512" aria-hidden="true"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64c0 247.4 200.6 448 448 448 18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
            WhatsApp
        </a>
    </div>
</section>

{{-- FAQ (siap diganti data CMS: kirim $faqs berisi q & a) --}}
@php
    $faqs = collect($faqs ?? [
        ['q'=>'Apa saja layanan yang ditawarkan?','a'=>'Kami melayani jasa desain arsitek (denah, tampak, 3D, RAB, gambar kerja), renovasi rumah, dan bangun rumah dari nol, termasuk pengawasan pekerjaan di lapangan.'],
        ['q'=>'Apakah bisa hanya menggunakan jasa desain tanpa pembangunan?','a'=>'Bisa. Anda dapat memakai jasa desain saja, hasilnya berupa gambar kerja dan RAB yang bisa dibangun oleh kontraktor pilihan Anda sendiri.'],
        ['q'=>'Apakah ada survei lokasi sebelum memulai proyek?','a'=>'Ya, kami melakukan survei lokasi untuk mengukur lahan dan memahami kondisi sekitar sebelum desain dibuat.'],
        ['q'=>'Berapa biaya jasa desain arsitek?','a'=>'Biaya bergantung pada luas bangunan, tingkat kerumitan, dan lingkup layanan. Hubungi kami via WhatsApp untuk mendapatkan penawaran yang sesuai.'],
        ['q'=>'Apakah bisa mengurus IMB/PBG juga?','a'=>'Bisa. Kami membantu pengurusan perizinan PBG (dahulu IMB) beserta kelengkapan dokumen teknisnya.'],
        ['q'=>'Bagaimana sistem pembayaran jasa?','a'=>'Pembayaran dilakukan bertahap sesuai progres pekerjaan, dengan rincian disepakati di awal dalam surat perjanjian kerja.'],
        ['q'=>'Apakah hasil desain bisa direvisi?','a'=>'Pembayaran dilakukan bertahap sesuai progres pekerjaan, dengan rincian disepakati di awal dalam surat perjanjian kerja.'],
        ['q'=>'Apakah bisa menggunakan material sesuai permintaan klien?','a'=>'Pembayaran dilakukan bertahap sesuai progres pekerjaan, dengan rincian disepakati di awal dalam surat perjanjian kerja.'],
        ['q'=>'Apakah ada garansi hasil kerja?','a'=>'Pembayaran dilakukan bertahap sesuai progres pekerjaan, dengan rincian disepakati di awal dalam surat perjanjian kerja.'],
        ['q'=>'Di area mana saja layanan ini tersedia?','a'=>'Pembayaran dilakukan bertahap sesuai progres pekerjaan, dengan rincian disepakati di awal dalam surat perjanjian kerja.'],
    ]);
@endphp
<section class="faq" id="faq">
    <div class="faq-wrap">
        <h2>Pertanyaan Yang Sering Diajukan</h2>
        @foreach ($faqs as $f)
            <details class="faq-item">
                <summary>{{ data_get($f, 'q') }}</summary>
                <div class="faq-a">{{ data_get($f, 'a') }}</div>
            </details>
        @endforeach
    </div>
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        'mainEntity' => $faqs->map(fn($f) => [
            '@type' => 'Question',
            'name'  => data_get($f, 'q'),
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => data_get($f, 'a')],
        ])->values()->all(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
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
// Review: video YouTube dimuat saat diklik (lebih ringan)
document.querySelectorAll('.rv-video').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = btn.dataset.yt;
        if (!id) return;
        var f = document.createElement('iframe');
        f.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(id) + '?autoplay=1&rel=0';
        f.allow = 'accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen';
        f.allowFullscreen = true;
        f.title = 'Review pelanggan';
        btn.innerHTML = '';
        btn.style.cursor = 'default';
        btn.appendChild(f);
    }, { once: true });
});
</script>

@endpush