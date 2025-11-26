<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Role yang Termasuk Karyawan Perusahaan
    |--------------------------------------------------------------------------
    | Daftar role yang bila dimiliki oleh user, maka user tersebut otomatis
    | dianggap sebagai karyawan dan harus ada di tabel employees.
    */

    'roles' => [
        'Komisaris',
        'Direktur',
        'Manager Administrasi',
        'Manager Teknik',
        'Spv Marketing',
        'Spv Finance',
        'Spv HRD',
        'Spv Arsitek',
        'Spv Sipil',
        'Staff Marketing',
        'Staff Finance',
        'Staff HRD',
        'Drafter',
        'QC',
        'Estimator',
    ],
];
