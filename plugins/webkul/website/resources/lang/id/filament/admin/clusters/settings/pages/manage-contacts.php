<?php

return [
    'title' => 'Kelola Kontak',

    'form' => [
        'sections' => [
            'contact-form' => [
                'title'       => 'Hubungi Kami',
                'description' => 'Formulir Hubungi Kami di website dan apa yang terjadi saat pengunjung mengirimkannya.',

                'fields' => [
                    'enabled'        => 'Aktifkan Hubungi Kami',
                    'enabled-helper' => 'Menampilkan halaman Hubungi Kami beserta tautannya di menu website.',
                    'action'         => 'Setelah pengiriman',
                    'email-helper'   => 'Pesan dikirim melalui e-mail ke :email.',
                    'crm-helper'     => 'Pesan membuat catatan di CRM beserta detail pengunjungnya.',
                ],
            ],

            'contacts' => [
                'title' => 'Kontak',

                'fields' => [
                    'email' => 'E-mail',
                    'phone' => 'Telepon',
                ],
            ],

            'social-links' => [
                'title' => 'Tautan Sosial Media',

                'fields' => [
                    'twitter'   => 'Twitter',
                    'facebook'  => 'Facebook',
                    'instagram' => 'Instagram',
                    'linkedin'  => 'LinkedIn',
                    'pinterest' => 'Pinterest',
                    'tiktok'    => 'TikTok',
                    'github'    => 'GitHub',
                    'slack'     => 'Slack',
                    'whatsapp'  => 'WhatsApp',
                    'youtube'   => 'YouTube',
                ],
            ],
        ],
    ],
];
