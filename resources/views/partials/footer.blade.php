{{-- resources/views/partials/footer.blade.php (di-include oleh layouts/website.blade.php) --}}
@php
    $ftWa = preg_replace('/^0/', '62', preg_replace('/\D/', '', (string) config('antosa.wa_marketing', '6285189523863')));
    $ftHome = url('/');
    $ftLinks = [
        ['Beranda',  $ftHome, []],
        ['Layanan',  $ftHome . '#layanan', [
            ['Jasa Arsitek',        $ftHome . '#layanan'],
            ['Jasa Bangun rumah',   $ftHome . '#layanan'],
            ['Jasa Renovasi Rumah', $ftHome . '#layanan'],
        ]],
        ['Portofolio',   Route::has('artikel.index') ? route('artikel.index') : $ftHome . '#portfolio', []],
        ['Tentang Kami', $ftHome . '#tentang', []],
        ['Kontak Kami',  $ftHome . '#konsultasi', []],
    ];
    $ftSocial = [
        'youtube'   => config('antosa.youtube',   '#'),
        'facebook'  => config('antosa.facebook',  '#'),
        'instagram' => config('antosa.instagram', '#'),
    ];
@endphp

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Saira:wght@600;700&display=swap" rel="stylesheet">
<style>
.ft{ background:linear-gradient(135deg,#0a0a0a 0%,#1b1b1b 100%); color:#fff; padding:95px 0 95px; font-family:'Poppins',sans-serif; }
.ft-wrap{ width:min(100% - 40px,1220px); margin:0 auto; display:grid; grid-template-columns:263px 1fr; column-gap:362px; }
.ft-logo{ display:block; width:263px; height:auto; margin:0 0 42px; }
.ft-addr{ font-size:15.5px; line-height:1.6; font-weight:500; margin:0 0 42px; color:#fff; }
.ft-tel{ display:block; font-size:15.5px; font-weight:500; color:#fff; text-decoration:none; margin:0 0 42px; }
.ft-contact{ display:block; width:263px; }
.ft-contact img{ width:100%; height:auto; display:block; }
.ft h4{ font-family:'Saira',sans-serif; font-size:22px; font-weight:700; margin:0 0 38px; color:#fff; }
.ft-menu{ list-style:none; margin:0; padding:0; }
.ft-menu li{ font-size:15.5px; line-height:1.6; margin:0 0 1px; }
.ft-menu a{ color:#fff; text-decoration:none; }
.ft-menu a:hover{ color:#f9b719; }
.ft-menu ul{ list-style:none; margin:6px 0 6px 20px; padding:0; }
.ft-bar{ background:#fff; color:#000; padding:36px 0; font-family:'Poppins',sans-serif; }
.ft-bar-wrap{ width:min(100% - 40px,1220px); margin:0 auto; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap; }
.ft-copy{ font-size:15.5px; font-weight:500; margin:0; }
.ft-copy b{ font-weight:700; }
.ft-social{ display:flex; gap:22px; padding-right:20px; }
.ft-social a{ color:#000; display:inline-flex; transition:color .2s; }
.ft-social a:hover{ color:#f9b719; }
.ft-social svg{ width:21px; height:21px; fill:currentColor; }
@media (max-width:900px){
    .ft{ padding:60px 0; }
    .ft-wrap{ grid-template-columns:1fr; row-gap:50px; }
    .ft-social{ padding-right:0; }
}
</style>

<footer class="ft" id="kontak">
    <div class="ft-wrap">
        <div>
            <img class="ft-logo" src="{{ asset('images/logo-antosa-putih.png') }}" alt="Antosa Architect">
            <p class="ft-addr">Bernady Land, Cluster Camelia Blok E6, Puring, Slawu, Kec. Patrang, Kabupaten Jember, Jawa Timur 68116</p>
            <a class="ft-tel" href="tel:+{{ $ftWa }}">+62 851 8952 3863</a>
            <a class="ft-contact" href="https://wa.me/{{ $ftWa }}" target="_blank" rel="noopener">
                <img src="{{ asset('images/contact-us.webp') }}" alt="Contact Us" loading="lazy">
            </a>
        </div>
        <div>
            <h4>Quick Link</h4>
            <ul class="ft-menu">
                @foreach ($ftLinks as [$label, $href, $children])
                    <li>
                        <a href="{{ $href }}">{{ $label }}</a>
                        @if ($children)
                            <ul>
                                @foreach ($children as [$cl, $ch])
                                    <li><a href="{{ $ch }}">{{ $cl }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>

<div class="ft-bar">
    <div class="ft-bar-wrap">
        <p class="ft-copy">Copyright © {{ date('Y') }} jasabangunrumahjember.com | <b>Antosa Architect</b></p>
        <div class="ft-social">
            <a href="{{ $ftSocial['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 576 512"><path d="M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6C14.9 167 14.9 256.4 14.9 256.4s0 89.4 11.4 132.3c6.3 23.6 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zM232.2 337.6V175.2l142.7 81.2-142.7 81.2z"/></svg></a>
            <a href="{{ $ftSocial['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 448 512"><path d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.3V327.7h-63V256h63v-54.6c0-62.2 37-96.5 93.7-96.5 27.1 0 55.5 4.8 55.5 4.8v61h-31.3c-30.8 0-40.4 19.1-40.4 38.7V256h68.8l-11 71.7h-57.8V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"/></svg></a>
            <a href="{{ $ftSocial['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a>
        </div>
    </div>
</div>