<h1 align="center">Nelsha Creations ERP</h1>

<p align="center">
  Sistem <strong>Enterprise Resource Planning</strong> internal <strong>Nelsha Creations</strong>, dibangun di atas
  <a href="https://github.com/aureuserp/aureuserp">AureusERP</a> (open-source).
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13-red" alt="Laravel"></a>
  <a href="https://filamentphp.com"><img src="https://img.shields.io/badge/FilamentPHP-5-pink" alt="Filament"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-%5E8.3-blue" alt="PHP"></a>
  <a href="https://www.docker.com"><img src="https://img.shields.io/badge/Docker-compose-2496ED" alt="Docker"></a>
</p>

---

## 📋 Daftar Isi

1. [Tentang](#-tentang)
2. [Konfigurasi Instance](#-konfigurasi-instance)
3. [Menjalankan dengan Docker](#-menjalankan-dengan-docker)
4. [Instalasi Manual (tanpa Docker)](#-instalasi-manual-tanpa-docker)
5. [Plugin & Modul](#-plugin--modul)
6. [Perintah Berguna](#-perintah-berguna)
7. [Kredit & Lisensi](#-kredit--lisensi)

---

## 🚀 Tentang

ERP yang mengelola akuntansi, penjualan, pembelian, inventaris, manufaktur, CRM, dan point-of-sale.
Branch repositori ini adalah **instance produksi/sandbox Nelsha Creations** yang berbasis
framework plugin-modular AureusERP:

- 🧩 **Plugin-modular** — aktifkan modul sesuai kebutuhan (21 plugin aktif pada instance ini)
- 🎨 Admin panel **FilamentPHP** + Livewire 4, UI responsif TailwindCSS 4
- 🔐 Role & permission via Filament Shield
- 🌐 Multi-bahasa / kompatibel RTL
- 🐳 Deploy Docker ringan berbasis Alpine (php-fpm + nginx, MariaDB 11, queue worker terpisah)

> Base framework: [aureuserp/aureuserp](https://github.com/aureuserp/aureuserp) — dokumentasi modul:
> [docs.aureuserp.com](https://docs.aureuserp.com/) • developer docs:
> [devdocs.aureuserp.com](https://devdocs.aureuserp.com/)

---

## ✅ Konfigurasi Instance

| Item | Nilai | Catatan |
|---|---|---|
| Nama aplikasi (`APP_NAME`) | **Nelsha Creations** | dipakai branding UI, pengirim e-mail, dan README ini |
| Locale / bahasa | `id` (Indonesia) | termasuk `lang/id/` untuk pesan framework |
| Mata uang | **IDR** | default currency |
| Pajak | **PPN 11 %** | tax sale + purchase, negara pajak: Indonesia |
| Font export PDF | **Roboto** | semua laporan PDF (DomPDF) — berkas di `public/fonts/Roboto/` |
| Batas upload foto profil | 10 MB | |
| Queue worker | `queue:work --memory=512` | container `queue`, healthcheck via `pgrep` |

Akun awal: `admin@example.com` / `password` — **ganti setelah login pertama**.

---

## 🐳 Menjalankan dengan Docker

```bash
# build + jalankan (app: php-fpm+nginx :8080, mariadb, queue worker)
docker compose up -d --build

# pertama kali / setelah ganti kode:vo file baked via COPY pada build,
# rebuild dijalankan untuk memakai kode terbaru
docker compose logs -f        # lihat log
docker compose down           # berhenti
docker compose down -v        # berhenti + hapus volume data (HATI-HATI: data hilang)
```

Panel admin: <http://localhost:8080/admin>

Semua perintah `artisan` dijalankan lewat container `app`:

```bash
docker exec aureuserp-app-1 php artisan <perintah>
```

Detail service & override (healthcheck queue, env) ada di [`docker-compose.yml`](docker-compose.yml)
dan [`docker/php/`](docker/php/).

---

## 🛠️ Instalasi Manual (tanpa Docker)

```bash
git clone git@github.com:ridhoauliama97/nelsha-creations-erp.git
cd nelsha-creations-erp
composer install

php artisan erp:install        # jalankan hanya pada database KOSONG — me-wipe data
php artisan db:seed --class=InstanceSeeder   # konfigurasi instance: PPN 11 %, IDR, locale, dsb.
php artisan serve              # http://localhost:8000
```

Instalasi `erp:install` otomatis: migrasi, seeder, role & permission (Filament Shield),
akun admin, dan konfigurasi environment.

---

## 🧩 Plugin & Modul

### Plugin core (terpasang default)

Analytics, Chatter, Fields, Security, Support, Table View.

### Modul bisnis aktif pada instance ini

- 💼 **Financial** — Accounting, Accounts, Invoices, Payments
- 📦 **Operations** — Inventories, Manufacturing, Point of Sale, Products, Purchases, Sales
- 🤝 **Customer** — Contacts, CRM, Partners
- 📊 **Content/Project** — Blogs, Projects, Website
- 👥 **HR** — Employees, Recruitments, Timesheet

Kelola plugin lewat Admin → Settings → Plugins, atau via CLI:

```bash
php artisan <plugin-name>:install      # contoh: php artisan inventories:install
php artisan <plugin-name>:uninstall    # ⚠️ menghapus tabel & data plugin — backup dulu
```

---

## 🔧 Perintah Berguna

```bash
# cache (setelah mengubah konfigurasi/atau data settings)
docker exec aureuserp-app-1 php artisan cache:clear

# format kode
vendor/bin/pint --dirty

# render test PDF (font Roboto)
docker exec aureuserp-app-1 php artisan tinker --execute="$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML(view('pdf.fonts')->render() . '<p style=\'font-family: Roboto\'>test</p>')->setOption('defaultFont', 'Roboto'); echo strlen($pdf->output());"
```

Jika perubahan UI tidak muncul: `npm run build` (atau jalankan vite dev) — aset via Vite.

---

## 📄 Kredit & Lisensi

Instance ini memodifikasi dan menjalankan [AureusERP](https://github.com/aureuserp/aureuserp)
— ERP open-source buatan [Webkul](https://webkul.com), dirilis dengan [MIT License](LICENSE).
Plugin di atasnya mengikuti ketentuan MIT sama.

Terima kasih kepada komunitas Laravel, FilamentPHP, DomPDF, dan Webkul.
