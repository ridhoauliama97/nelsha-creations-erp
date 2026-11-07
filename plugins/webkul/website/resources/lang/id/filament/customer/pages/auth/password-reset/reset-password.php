<?php

return [
    'title'         => 'Atur ulang kata sandi',
    'heading'       => 'Atur ulang kata sandi',
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
            'label'                => 'Kata sandi baru',
            'validation_attribute' => 'kata sandi',
        ],
        'password_confirmation' => [
            'label' => 'Konfirmasi kata sandi baru',
        ],
        'actions' => [
            'reset' => [
                'label' => 'Atur ulang kata sandi',
            ],
        ],
    ],
];
