<?php

return [
    'group' => [
        'label' => 'Akses Portal',
    ],

    'grant' => [
        'label' => 'Berikan Akses Portal',

        'modal' => [
            'heading'     => 'Berikan Akses Portal',
            'description' => 'E-mail undangan akan dikirim ke :email berisi tautan untuk mengatur kata sandi portal pelanggan.',
        ],

        'notification' => [
            'email-missing' => [
                'title' => 'Alamat e-mail diperlukan',
                'body'  => 'Tambahkan alamat e-mail ke kontak ini sebelum memberikan akses portal.',
            ],

            'email-taken' => [
                'title' => 'Alamat e-mail sudah digunakan',
                'body'  => 'Kontak lain yang sudah memiliki akses portal sudah menggunakan alamat e-mail ini.',
            ],

            'granted' => [
                'title' => 'Akses portal diberikan',
                'body'  => 'Undangan untuk mengatur kata sandi telah dikirim ke :email.',
            ],

            'email-failed' => [
                'title' => 'Akses portal diberikan, tetapi e-mail undangan tidak dapat dikirim',
            ],
        ],
    ],

    'change-password' => [
        'label' => 'Ubah Kata Sandi',

        'modal' => [
            'heading'     => 'Ubah Kata Sandi Portal',
            'description' => 'Atur kata sandi portal pelanggan baru untuk :email.',
        ],

        'form' => [
            'password' => [
                'label' => 'Kata Sandi Baru',
            ],

            'password-confirmation' => [
                'label' => 'Konfirmasi Kata Sandi Baru',
            ],
        ],

        'notification' => [
            'changed' => [
                'title' => 'Kata sandi portal diubah',
                'body'  => 'Kontak kini dapat masuk ke portal pelanggan dengan kata sandi baru.',
            ],
        ],
    ],

    'password-reset' => [
        'label' => 'Kirim Pemulihan Kata Sandi',

        'modal' => [
            'heading'     => 'Kirim Pemulihan Kata Sandi',
            'description' => 'Tautan pemulihan kata sandi akan dikirim ke :email.',
        ],

        'notification' => [
            'sent' => [
                'title' => 'Tautan pemulihan kata sandi terkirim',
                'body'  => 'Tautan pemulihan kata sandi telah dikirim ke :email.',
            ],

            'failed' => [
                'title' => 'Tautan pemulihan kata sandi tidak dapat dikirim',
            ],
        ],
    ],

    'revoke' => [
        'label' => 'Cabut Akses Portal',

        'modal' => [
            'heading'     => 'Cabut Akses Portal',
            'description' => 'Kontak tidak akan dapat lagi masuk ke portal pelanggan. Semua tautan pemulihan kata sandi yang tertunda akan dinonaktifkan.',
        ],

        'notification' => [
            'revoked' => [
                'title' => 'Akses portal dicabut',
                'body'  => 'Kontak tidak dapat lagi masuk ke portal pelanggan.',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'portal-access' => 'Akses Portal',
        ],

        'filters' => [
            'portal-access' => 'Akses Portal',
        ],
    ],

    'infolist' => [
        'section' => [
            'title' => 'Portal Pelanggan',
        ],

        'entries' => [
            'status' => [
                'label'   => 'Akses Portal',
                'granted' => 'Diberikan',
                'none'    => 'Belum Diberikan',
            ],

            'email-verified-at' => [
                'label'       => 'E-mail Terverifikasi Pada',
                'placeholder' => 'Tidak pernah',
            ],

            'last-login-at' => [
                'label'       => 'Login Portal Terakhir',
                'placeholder' => 'Tidak pernah',
            ],
        ],
    ],
];
