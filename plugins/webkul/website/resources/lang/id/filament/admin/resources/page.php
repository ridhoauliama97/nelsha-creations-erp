<?php

return [
    'navigation' => [
        'title' => 'Halaman',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title' => 'Umum',

                'fields' => [
                    'title'             => 'Judul',
                    'title-placeholder' => 'Judul halaman ...',
                    'slug'              => 'Slug',
                    'content'           => 'Konten',
                ],
            ],

            'seo' => [
                'title' => 'SEO',

                'fields' => [
                    'meta-title'       => 'Judul Meta',
                    'meta-keywords'    => 'Kata Kunci Meta',
                    'meta-description' => 'Deskripsi Meta',
                ],
            ],

            'settings' => [
                'title' => 'Pengaturan',

                'fields' => [
                    'is-header-visible' => 'Tampilkan di Menu Header',
                    'is-footer-visible' => 'Tampilkan di Menu Footer',
                    'is-tracked'        => 'Lacak pengunjung',
                    'is-tracked-helper' => 'Mencatat tayangan halaman pengunjung pada halaman ini.',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'title'             => 'Judul',
            'slug'              => 'Slug',
            'creator'           => 'Dibuat Oleh',
            'is-published'      => 'Apakah Terbit',
            'is-header-visible' => 'Tampilkan di Menu Header',
            'is-footer-visible' => 'Tampilkan di Menu Footer',
            'created-at'        => 'Dibuat Pada',
            'updated-at'        => 'Diperbarui Pada',
        ],

        'groups' => [
            'created-at' => 'Dibuat Pada',
        ],

        'filters' => [
            'is-published' => 'Apakah Terbit',
            'creator'      => 'Dibuat Oleh',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Halaman diperbarui',
                    'body'  => 'Halaman berhasil diperbarui.',
                ],
            ],

            'restore' => [
                'notification' => [
                    'title' => 'Halaman dipulihkan',
                    'body'  => 'Halaman berhasil dipulihkan.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Halaman dihapus',
                    'body'  => 'Halaman berhasil dihapus.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Halaman dihapus permanen',
                    'body'  => 'Halaman berhasil dihapus permanen.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Halaman dipulihkan',
                    'body'  => 'Halaman-halaman berhasil dipulihkan.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Halaman dihapus',
                    'body'  => 'Halaman-halaman berhasil dihapus.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Halaman dihapus permanen',
                    'body'  => 'Halaman-halaman berhasil dihapus permanen.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'general' => [
                'title' => 'Umum',

                'entries' => [
                    'title'   => 'Judul',
                    'slug'    => 'Slug',
                    'content' => 'Konten',
                    'banner'  => 'Banner',
                ],
            ],

            'seo' => [
                'title' => 'SEO',

                'entries' => [
                    'meta-title'       => 'Judul Meta',
                    'meta-keywords'    => 'Kata Kunci Meta',
                    'meta-description' => 'Deskripsi Meta',
                ],
            ],

            'record-information' => [
                'title' => 'Informasi Catatan',

                'entries' => [
                    'author'          => 'Penulis',
                    'created-by'      => 'Dibuat Oleh',
                    'published-at'    => 'Diterbitkan Pada',
                    'last-updated-by' => 'Terakhir Diperbarui Oleh',
                    'last-updated'    => 'Terakhir Diperbarui Pada',
                    'created-at'      => 'Dibuat Pada',
                ],
            ],

            'settings' => [
                'title' => 'Pengaturan',

                'entries' => [
                    'is-header-visible' => 'Tampilkan di Menu Header',
                    'is-footer-visible' => 'Tampilkan di Menu Footer',
                    'is-tracked'        => 'Lacak pengunjung',
                ],
            ],
        ],
    ],
];
