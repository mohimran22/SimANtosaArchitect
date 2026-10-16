<?php

return [

    'jasa-arsitek' => [
        'title'      => 'Jasa Arsitek',
        'excerpt'    => 'Perancangan arsitektur, tata ruang, dan detail teknis seperti struktur, sirkulasi udara, pencahayaan alami, regulasi bangunan.',
        'image'      => 'images/layanan-arsitek.webp',          // gambar kartu di homepage
        'meta_title' => 'Jasa Arsitek Jember',
        'hero_image' => 'images/hero-jasa-arsitek.webp',        // kosongkan / tidak ada file => pakai bg-gedung.jpeg
        'hero_title' => 'Jasa Arsitek<br>Jember',
        'heading'    => 'Layanan Jasa Arsitek',
        'items'      => [
            ['title' => 'Desain Arsitektur',
             'excerpt' => 'Perencanaan dan pembuatan gambar desain bangunan, mulai dari konsep awal hingga gambar kerja (denah, tampak, potongan, dll) yang siap untuk tahap pembangunan.',
             'image' => 'images/layanan/layanan-arsitek.webp', 'url' => 'https://wa.link/r5j7io'],
            ['title' => 'Gambar Kerja dan RAB',
             'excerpt' => 'Meliputi perhitungan kebutuhan teknis, Rencana Anggaran Biaya, serta pengawasan pelaksanaan proyek agar sesuai desain, anggaran, dan waktu yang ditentukan.',
             'image' => 'images/layanan/layanan-renovasi.webp', 'url' => 'https://wa.link/r5j7io'],
            ['title' => 'Konsultasi Desain',
             'excerpt' => 'Anda bisa berkonsultasi kepada kami terkait dengan desain yang dibutuhkan oleh rumah ataupun bangunan Anda.',
             'image' => 'images/layanan/konsultasi-desain.webp', 'url' => 'https://wa.link/r5j7io'],
        ],
    ],

    'jasa-renovasi-rumah' => [
        'title'      => 'Jasa Renovasi Rumah',
        'excerpt'    => 'Merenovasi, perbaikan, dan perawatan bangunan, meningkatkan kualitas bangunan menjadi lebih sehat dan nyaman.',
        'image'      => 'images/layanan-renovasi.webp',
        'meta_title' => 'Jasa Renovasi Rumah Jember',
        'hero_image' => 'images/hero-jasa-renovasi-rumah.webp',
        'hero_title' => 'Jasa Renovasi<br>Rumah Jember',
        'heading'    => 'Layanan Jasa Renovasi Rumah',
        'items'      => [   // TODO: ganti dengan isi asli dari situs lama
            ['title' => 'Renovasi Total', 
            'excerpt' => 'Layanan renovasi menyeluruh untuk memperbarui seluruh bagian rumah, baik struktur, tampilan, maupun fungsi ruang.', 
            'image' => 'images/layanan/layanan-arsitek.webp', 'url' => 'https://wa.link/1p17t9'],
            ['title' => 'Renovasi Sebagian', 
            'excerpt' => 'Layanan pembaruan pada area tertentu saja, seperti dapur, kamar mandi, atau ruang tamu.', 
            'image' => 'images/layanan/layanan-renovasi.webp', 'url' => 'https://wa.link/1p17t9'],
            ['title' => 'Penambahan Ruang', 
            'excerpt' => 'Layanan khusus untuk menambah ruang baru, seperti kamar tambahan, ruang kerja, atau lantai atas.', 
            'image' => 'images/layanan/konsultasi-desain.webp', 'url' => 'https://wa.link/1p17t9'],
        ],
    ],

    'jasa-bangun-rumah' => [
        'title'      => 'Jasa Bangun Rumah',
        'excerpt'    => 'Konstruksi Bangunan dari Nol, memastikan setiap bangunan memiliki kualitas terbaik, nyaman dan sehat.',
        'image'      => 'images/layanan-bangun.webp',
        'meta_title' => 'Jasa Bangun Rumah Jember',
        'hero_image' => 'images/hero-jasa-bangun-rumah.webp',
        'hero_title' => 'Jasa Bangun<br>Rumah Jember',
        'heading'    => 'Layanan Jasa Bangun Rumah',
        'items'      => [   // TODO: ganti dengan isi asli dari situs lama
            ['title' => 'Bangun dari Nol', 
                'excerpt' => 'Layanan ini mencakup pembangunan rumah sepenuhnya di atas lahan kosong, mulai dari perencanaan desain arsitektur, pembuatan RAB, pengurusan IMB (jika diperlukan), hingga proses konstruksi sampai rumah siap huni.', 
                'image' => 'images/layanan/layanan-arsitek.webp', 'url' => 'https://wa.link/eayer7'],
            ['title' => 'Bangun Sebagian', 
             'excerpt' => 'Layanan ini ditujukan bagi Anda yang ingin membangun sebagian area rumah, seperti hanya membangun dapur, kamar tambahan, atau bagian tertentu lainnya.', 
             'image' => 'images/layanan/layanan-renovasi.webp', 'url' => 'https://wa.link/eayer7'],
            ['title' => 'Bangun Bertahap', 
             'excerpt' => 'Layanan ini menawarkan solusi pembangunan rumah secara bertahap sesuai dengan kebutuhan dan ketersediaan anggaran.', 
            'image' => 'images/layanan/bangun-3.webp', 'url' => 'https://wa.link/eayer7'],
        ],
    ],
];