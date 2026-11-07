<?php

return [
    'title'       => 'Hubungi Kami',
    'intro'       => 'Hubungi kami untuk apa pun yang berkaitan dengan perusahaan atau layanan kami.',
    'intro-reply' => 'Kami akan berusaha sebaik mungkin membalas secepat mungkin.',
    'submit'      => 'Kirim',
    'lead-note'   => 'Dibuat dari formulir Hubungi Kami di website.',

    'fields' => [
        'name'     => 'Nama',
        'phone'    => 'Nomor Telepon',
        'email'    => 'E-mail',
        'company'  => 'Perusahaan',
        'subject'  => 'Subjek',
        'question' => 'Pertanyaan',
    ],

    'validation' => [
        'no-markup' => 'Mohon masukkan teks biasa saja. HTML, skrip, dan tautan seperti javascript: tidak diizinkan.',
        'phone'     => 'Mohon masukkan nomor telepon yang valid (angka, spasi, +, -, kurung).',
    ],

    'notifications' => [
        'failed'   => 'Pesan Anda tidak dapat dikirim. Silakan coba lagi nanti.',
        'too-many' => 'Terlalu banyak pesan. Silakan coba lagi dalam :minutes menit.',

        'sent' => [
            'title' => 'Terima kasih!',
            'body'  => 'Pesan Anda telah terkirim. Kami akan segera membalas Anda.',
        ],
    ],

    'mail' => [
        'subject' => 'Kontak website: :subject',
        'intro'   => 'Pesan baru dikirim dari formulir Hubungi Kami di website.',
    ],
];
