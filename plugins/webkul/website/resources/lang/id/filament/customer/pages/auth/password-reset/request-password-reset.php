<?php

return [
    'title'         => 'Lupa kata sandi',
    'heading'       => 'Lupa kata sandi',
    'notifications' => [
        'throttled' => [
            'title' => 'Terlalu banyak percobaan. Coba lagi dalam :seconds detik.',
            'body'  => 'Mohon tunggu :seconds detik (:minutes menit) sebelum mencoba lagi.',
        ],
    ],
    'form' => [
        'email' => [
            'label' => 'Alamat e-mail',
        ],
        'actions' => [
            'request' => [
                'label' => 'Kirim tautan pemulihan',
            ],
        ],
    ],
    'actions' => [
        'login' => [
            'label' => 'Kembali ke halaman masuk',
        ],
    ],
];
