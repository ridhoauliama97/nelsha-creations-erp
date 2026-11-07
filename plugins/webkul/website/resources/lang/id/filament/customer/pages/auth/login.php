<?php

return [
    'title'    => 'Masuk',
    'heading'  => 'Masuk',
    'messages' => [
        'failed' => 'Kredensial ini tidak cocok dengan data kami.',
    ],
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
        'password' => [
            'label' => 'Kata sandi',
        ],
        'remember' => [
            'label' => 'Ingat saya',
        ],
        'actions' => [
            'authenticate' => [
                'label' => 'Masuk',
            ],
        ],
    ],
    'actions' => [
        'register' => [
            'before' => 'Belum punya akun?',
            'label'  => 'Buat akun',
        ],
        'request_password_reset' => [
            'label' => 'Lupa kata sandi?',
        ],
    ],
];
