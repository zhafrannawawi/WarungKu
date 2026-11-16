# WarungKu - Aplikasi Kasir dan Manajemen Warung

WarungKu adalah aplikasi web sederhana untuk membantu pemilik warung dan UMKM mengelola data toko, produk, transaksi kasir, dan laporan penjualan.

Aplikasi ini dibuat menggunakan PHP native dengan konsep MVC sederhana dan mendukung multi-tenant (satu database untuk banyak warung).

---

## Kebutuhan Sistem

- PHP versi 8.0 atau yang lebih baru
- Web Server MySQL / MariaDB (misalnya menggunakan XAMPP atau Laragon)
- Web Browser (Google Chrome, Mozilla Firefox, Microsoft Edge)

---

## Pengaturan Database

1. Buka phpMyAdmin di browser Anda.
2. Buat database baru bernama: `warungku`
3. Pastikan tabel utama sudah tersedia:
   - tblstore (data toko/warung)
   - tbluser (data akun pemilik dan kasir)
   - tblProduct (data produk dan stok)
4. Pengaturan koneksi database ada di file: `app/config/database.php`
   - Host: localhost
   - Database: warungku
   - User: root
   - Password: (kosongkan jika tanpa password)

---

## Cara Menjalankan Aplikasi
1. Jalankan XAMPP dan Nyalakan Apache dan MySQL
2. Buka Terminal atau Command Prompt di folder utama.
3. Ketik perintah berikut:

   php run

   atau

   php run.php

3. Buka browser dan kunjungi alamat:
   http://localhost:8000

---

## Alur Penggunaan

1. Pendaftaran Warung Baru:
   - Buka menu Daftar Warung Baru di halaman utama.
   - Isi nama warung, alamat, nama pemilik, email, nomor HP, username, dan password.
   - Klik Daftar Warung Sekarang. Toko dan akun owner akan dibuat secara otomatis.

2. Masuk / Login:
   - Masukkan username dan password yang telah didaftarkan.
   - Sistem akan mengarahkan Anda ke dashboard sesuai peran:
     - Owner: Masuk ke halaman /owner/dashboard
     - Kasir: Masuk ke halaman /cashier/dashboard

3. Keluar / Logout:
   - Klik tombol Logout di sidebar atau menu profil untuk mengakhiri sesi login Anda dengan aman.

---

## Konsep CSS dan JavaScript (2 Main & 2 Spesifik)

Aplikasi ini menggunakan sistem 2 lapis CSS dan 2 lapis JS:
1. File Main (Global):
   - CSS Main: `public/assets/css/main.css` (berlaku untuk seluruh aplikasi).
   - JS Main: `public/assets/js/main.js` (berlaku untuk seluruh aplikasi).

2. File Spesifik (Khusus per Halaman):
   - Diletakkan langsung di dalam folder view halaman itu sendiri, berdampingan dengan `index.php`.

---

## Panduan Menambah Halaman Baru

Frontend tidak perlu menyentuh folder public untuk mengatur CSS/JS halaman baru. Semua file halaman terkumpul jadi satu di dalam folder view-nya.

Sebagai contoh, jika Anda ingin membuat halaman baru bernama "kategori" untuk peran "owner" (alamat URL: /owner/kategori):

1. Buat Folder & File di dalam `app/views/`:
   Buat struktur folder berikut di dalam `app/views/owner/kategori/`:

   app/views/owner/kategori/
   ├── index.php             (Wajib: Berisi HTML tampilan konten)
   ├── style/
   │   └── style.css         (Opsional: Berisi CSS khusus halaman ini)
   └── script/
       └── script.js         (Opsional: Berisi JS khusus halaman ini)

2. Isi File index.php:
   Hanya tulis isi kontennya saja (tanpa tag html, head, atau body), contoh:
   <h1>Daftar Kategori Produk</h1>
   <p>Kelola kategori barang dagangan di sini.</p>

3. Isi File style.css & script.js (Jika Dibutuhkan):
   - Masukkan gaya tampilan khusus di `style/style.css`.
   - Masukkan logika JavaScript khusus di `script/script.js`.
   Sistem Controller akan otomatis memuat file CSS dan JS tersebut ke dalam layout halaman tanpa Anda harus mengetik tag link atau script secara manual.

4. Buat File Controller:
   Buat file pengendali di `app/controllers/owner/KategoriController.php`:
   <?php
   class OwnerKategoriController extends Controller
   {
       public function index()
       {
           $this->view('owner', 'kategori', [
               'page_title' => 'Kategori Produk'
           ]);
       }
   }

---

## Struktur Folder Singkat

- app/config/ : Berisi konfigurasi database.
- app/controllers/ : Berisi logika proses halaman (login, register, owner, kasir).
- app/core/ : File inti sistem (Controller dasar dan koneksi Database).
- app/models/ : Berisi query database untuk data user, toko, dan produk.
- app/views/ : Berisi seluruh tampilan (HTML view, serta folder style/ dan script/ per halaman).
- public/assets/ : Hanya berisi aset global (main.css, main.js, dan gambar logo).
- run.php : File pembantu untuk menjalankan server lokal dengan cepat.
