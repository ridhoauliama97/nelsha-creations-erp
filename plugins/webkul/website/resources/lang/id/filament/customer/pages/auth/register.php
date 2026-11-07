<?php

return [
    'title'         => 'Daftar',
    'heading'       => 'Daftar',
    'notifications' => [
        'throttled' => [
            'title' => 'Terlalu banyak percobaan. Coba lagi dalam :seconds detik.',
            'body'  => 'Mohon tunggu :seconds detik (:minutes menit) sebelum mencoba lagi.',
        ],
    ],
    'form' => [
        'name' => [
            'label' => 'Nama',
        ],
        'email' => [
            'label' => 'Alamat e-mail',
        ],
        'password' => [
            'label'                => 'Kata sandi',
            'validation_attribute' => 'kata sandi',
        ],
        'password_confirmation' => [
            'label' => 'Konfirmasi kata sandi',
        ],
        'actions' => [
            'register' => [
                'label' => 'Buat akun',
            ],
        ],
    ],
    'actions' => [
        'login' => [
            'before' => 'Sudah punya akun?',
            'label'  => 'Masuk',
        ],
    ],
];
