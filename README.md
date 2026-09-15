# Si-BATUR — Sistem Bantuan Sosial Pemkab Lombok Barat

Aplikasi web untuk mengelola siklus bantuan sosial (bansos, hibah, bantuan ke masyarakat/BDSKM, subsidi bunga) mulai dari pendaftaran kelompok, verifikasi NIK oleh Dukcapil, pengajuan oleh OPD, verifikasi berjenjang, BAST, SP2D, hingga realisasi dan pelaporan.

Dibangun dengan **Laravel 12**, **Livewire 3**, **Bootstrap 5**, dan **Vite**.

---

## Daftar Isi

1. [Prasyarat](#1-prasyarat)
2. [Instalasi Cepat](#2-instalasi-cepat)
3. [Instalasi Manual (Langkah demi Langkah)](#3-instalasi-manual-langkah-demi-langkah)
4. [Konfigurasi `.env`](#4-konfigurasi-env)
5. [Akun Bawaan](#5-akun-bawaan)
6. [Menjalankan Aplikasi](#6-menjalankan-aplikasi)
7. [Deploy ke Server (Production)](#7-deploy-ke-server-production)
8. [Menjalankan Test](#8-menjalankan-test)
9. [Perintah Artisan Khusus](#9-perintah-artisan-khusus)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Prasyarat

| Kebutuhan | Versi minimum | Keterangan |
|---|---|---|
| PHP | **8.2** | Direkomendasikan 8.3 / 8.4 |
| Composer | 2.x | Manajer dependensi PHP |
| Node.js + npm | Node **20** | Untuk build aset Vite |
| Database | MySQL 8 / MariaDB 10.6 | SQLite hanya dipakai untuk test |
| Web server | Nginx / Apache | Untuk production; saat development cukup `php artisan serve` |

**Ekstensi PHP yang wajib aktif:**

```
bcmath  ctype  curl  dom  fileinfo  gd  json  mbstring  openssl  pdo_mysql  tokenizer  xml  zip
```

- `gd` dan `zip` dibutuhkan oleh Spatie MediaLibrary (unggah dokumen/foto) dan Maatwebsite Excel (ekspor laporan).
- `imagick` opsional, mempercepat pemrosesan gambar.

Cek ekstensi yang terpasang:

```bash
php -m | grep -iE "gd|zip|mbstring|pdo_mysql|bcmath|intl"
```

---

## 2. Instalasi Cepat

Untuk mesin development yang sudah memenuhi prasyarat dan **database sudah dibuat**:

```bash
git clone https://github.com/ahmad-mujamil/bansos.git
cd bansos

# sesuaikan koneksi database di .env terlebih dahulu (lihat bagian 4)
cp .env.example .env
nano .env

composer setup        # composer install → key:generate → migrate → npm install → npm run build
php artisan db:seed   # data awal + akun bawaan
php artisan storage:link
composer dev          # jalankan server, queue, log, dan vite sekaligus
```

Buka <http://localhost:8000>, masuk dengan akun `super` / `1q2w3e4r5t`.

> `composer setup` **tidak** menjalankan seeder dan `storage:link`. Dua perintah itu harus dijalankan manual seperti di atas.

---

## 3. Instalasi Manual (Langkah demi Langkah)

Gunakan bagian ini bila ingin memahami tiap tahap atau `composer setup` gagal di tengah jalan.

### 3.1 Ambil kode sumber

```bash
git clone https://github.com/ahmad-mujamil/bansos.git
cd bansos
```

### 3.2 Pasang dependensi PHP

```bash
composer install
```

Untuk production tambahkan flag agar lebih ringan:

```bash
composer install --no-dev --optimize-autoloader
```

### 3.3 Siapkan berkas lingkungan

```bash
cp .env.example .env
php artisan key:generate
```

Lalu ubah nilai-nilai di `.env` — minimal `APP_NAME`, `APP_URL`, dan blok `DB_*` (lihat [bagian 4](#4-konfigurasi-env)).

### 3.4 Buat database

Masuk ke MySQL/MariaDB dan buat database kosong dengan nama yang sama seperti `DB_DATABASE` di `.env`:

```sql
CREATE DATABASE bansos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.5 Migrasi & seed

```bash
php artisan migrate
php artisan db:seed
```

Seeder mengisi:

- **Tahun Anggaran** berjalan (`TA <tahun sekarang>`)
- Data wilayah (kecamatan, desa)
- Contoh penduduk, OPD, jenis bantuan, organisasi/kelompok, dan pengajuan
- Akun bawaan (lihat [bagian 5](#5-akun-bawaan))

Untuk mengulang dari nol (**menghapus semua data**):

```bash
php artisan migrate:fresh --seed
```

### 3.6 Tautkan penyimpanan publik

Dokumen dan foto (BAST, SP2D, laporan realisasi, SK organisasi) disimpan di `storage/app/public` dan diakses lewat `public/storage`:

```bash
php artisan storage:link
```

Tanpa langkah ini berkas yang diunggah tidak bisa dibuka dari browser.

### 3.7 Pasang & build aset frontend

```bash
npm install
npm run build
```

Saat development bisa memakai mode watch: `npm run dev`.

---

## 4. Konfigurasi `.env`

Nilai yang biasanya perlu disesuaikan:

```dotenv
APP_NAME="Si-BATUR"
APP_ENV=local            # production di server
APP_DEBUG=true           # false di server
APP_URL=http://localhost:8000
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bansos
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

Catatan:

- `.env.example` bawaan masih memakai nama `MemoFit` — ganti ke `Si-BATUR` supaya judul halaman dan email konsisten.
- `SESSION_DRIVER`, `CACHE_STORE`, dan `QUEUE_CONNECTION` memakai tabel database; tabelnya dibuat oleh migrasi bawaan, tidak perlu Redis.
- `APP_URL` harus sesuai alamat yang dipakai browser, karena dipakai untuk membentuk tautan aset dan berkas unggahan.
- Jika `APP_URL` diubah setelah aplikasi berjalan, jalankan `php artisan config:clear`.

---

## 5. Akun Bawaan

Dibuat oleh `php artisan db:seed`. Login memakai **username** (bukan email).

| Peran | Username | Password | Keterangan |
|---|---|---|---|
| Super Admin | `super` | `1q2w3e4r5t` | Akses penuh, kelola tahun anggaran & pengguna |
| Administrator | `admin` | `1q2w3e4r5t` | Dashboard & verifikasi lintas OPD |
| OPD | `dinas_sosial_kabupaten_lombok_barat` (dan OPD lain) | `password` | Satu akun per OPD; username = slug nama OPD |
| Pengguna / Pemohon | `ahmad_fauzi`, `siti_rahayu`, `budi_hartono`, … | `password` | Contoh pemohon perorangan/kelompok |

> **Segera ganti seluruh password bawaan setelah instalasi di server production**, atau hapus akun contoh yang tidak diperlukan.

Peran yang tersedia (`App\Enums\RoleUser`):

| Peran | Fungsi |
|---|---|
| `super` | Akses penuh |
| `admin` | Dashboard, master data, verifikasi lintas OPD, tahun anggaran |
| `opd` | Pengajuan & realisasi bantuan untuk OPD-nya sendiri |
| `bendahara` | Akses BAST / SP2D / realisasi |
| `dukcapil` | Verifikasi NIK penduduk |
| `user` | Pemohon (perorangan / kelompok) |

Akun `bendahara` dan `dukcapil` tidak dibuat oleh seeder — buat lewat menu Pengguna dengan akun Super Admin.

---

## 6. Menjalankan Aplikasi

### Development (semua layanan sekaligus)

```bash
composer dev
```

Perintah ini menjalankan secara paralel:

| Layanan | Perintah | Fungsi |
|---|---|---|
| server | `php artisan serve` | HTTP server di <http://localhost:8000> |
| queue | `php artisan queue:listen --tries=1` | Memproses antrian (`QUEUE_CONNECTION=database`) |
| logs | `php artisan pail` | Menampilkan log secara langsung di terminal |
| vite | `npm run dev` | Hot-reload aset CSS/JS |

### Menjalankan satu per satu

```bash
php artisan serve
npm run dev
```

---

## 7. Deploy ke Server (Production)

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan migrate --force
php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Pastikan di `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.go.id
```

### Hak akses direktori

Web server (mis. `www-data`) harus bisa menulis ke `storage/` dan `bootstrap/cache/`:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Queue worker

Karena `QUEUE_CONNECTION=database`, jalankan worker permanen menggunakan Supervisor:

```ini
[program:sibatur-worker]
command=php /var/www/bansos/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/bansos
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/bansos/storage/logs/worker.log
```

### Contoh konfigurasi Nginx

```nginx
server {
    listen 80;
    server_name domain-anda.go.id;
    root /var/www/bansos/public;
    index index.php;

    client_max_body_size 20M;   # unggahan dokumen PDF/foto

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
```

Sesuaikan `upload_max_filesize` dan `post_max_size` di `php.ini` agar tidak lebih kecil dari `client_max_body_size`.

### Setelah update kode

```bash
php artisan migrate --force
php artisan optimize:clear && php artisan optimize
sudo supervisorctl restart sibatur-worker
```

---

## 8. Menjalankan Test

Test memakai Pest dan SQLite in-memory (diatur di `phpunit.xml`), jadi tidak menyentuh database utama.

```bash
composer test                                   # semua test
php artisan test tests/Feature/ExampleTest.php  # satu berkas
php artisan test --filter=nama_test             # satu test
```

---

## 9. Perintah Artisan Khusus

| Perintah | Fungsi |
|---|---|
| `php artisan pengajuan:backfill-snapshot` | Mengisi snapshot kelompok/anggota untuk pengajuan lama yang dibuat sebelum fitur snapshot ada. Jalankan sekali setelah migrasi di data existing. Memakai data kelompok **saat ini** sebagai perkiraan. |

Data dipisah per **Tahun Anggaran**. Tahun aktif dipilih dari topbar; Super Admin / Admin dapat menambah tahun baru dan mengunci tahun lama (read-only) lewat menu Tahun Anggaran.

---

## 10. Troubleshooting

**`SQLSTATE[HY000] [1045] Access denied` saat migrate**
Cek `DB_USERNAME` / `DB_PASSWORD` di `.env`, lalu `php artisan config:clear`.

**Halaman tampil tanpa CSS/JS (putih polos)**
Aset belum dibuild — jalankan `npm run build` (atau `npm run dev` saat development). Pastikan juga `APP_URL` sesuai alamat di browser.

**Berkas unggahan (BAST, SP2D, foto) tidak bisa dibuka / 404**
`php artisan storage:link` belum dijalankan, atau `storage/` tidak bisa ditulis web server.

**`The stream or file "storage/logs/laravel.log" could not be opened`**
Masalah hak akses — lihat bagian [hak akses direktori](#hak-akses-direktori).

**Ekspor Excel gagal / `Class "ZipArchive" not found`**
Ekstensi PHP `zip` belum aktif. Pasang lalu restart PHP-FPM.

**Unggah gambar gagal**
Ekstensi `gd` belum aktif.

**`419 Page Expired` saat submit form**
Sesi kedaluwarsa atau tabel `sessions` belum ada — jalankan `php artisan migrate`, lalu segarkan halaman.

**Perubahan di `.env` tidak berpengaruh di production**
Konfigurasi ter-cache. Jalankan `php artisan config:clear` lalu `php artisan config:cache`.

**Data tidak muncul padahal ada di database**
Periksa Tahun Anggaran yang dipilih di topbar — data difilter per tahun.
