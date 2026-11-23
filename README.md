# WarungKu - Aplikasi Kasir dan Manajemen Warung

WarungKu adalah aplikasi web kasir dan manajemen toko sederhana untuk membantu pemilik warung dan UMKM mengelola data toko, produk dagangan, stok barang masuk (restock), dan akses staf kasir.

Aplikasi ini dibangun menggunakan **PHP Native Struktural (Prosedural)** dengan pola pemisahan **MVC (Model, View, Controller)** yang rapi, bersih, dan sangat mudah dipahami untuk pembelajaran maupun ujian.

---

## 🛠️ Kebutuhan Sistem

- **PHP**: Versi 8.0 atau yang lebih baru
- **Database**: MySQL / MariaDB (XAMPP / Laragon / Web Hosting cPanel / InfinityFree)
- **Web Browser**: Google Chrome, Mozilla Firefox, Microsoft Edge

---

## 🗄️ Pengaturan Database

1. Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
2. Buat database baru bernama: **`warungku`**
3. Masuk ke database `warungku`, pilih menu **Import**, lalu pilih file **`warungku.sql`** di folder proyek ini dan klik **Kirim / Import**.
4. Pengaturan koneksi database ada di file: **`config/app.php`** (baris 7–10):
   ```php
   $host = 'localhost';
   $user = 'root';
   $pass = '';
   $db   = 'warungku';
   ```

---

## 🚀 Cara Menjalankan Aplikasi

1. Buka Terminal / Command Prompt di folder utama proyek (`D:\E`).
2. Jalankan perintah berikut:
   ```bash
   php run.php
   ```
   *(atau `php -S localhost:8000`)*
3. Buka browser dan kunjungi alamat:
   👉 **`http://localhost:8000`**

Sistem akan otomatis mengarahkan ke halaman login jika Anda belum masuk.

---

## 📁 Struktur Folder Proyek

```text
WarungKu/
├── config/
│   ├── database.php             # Cadangan array konfigurasi database
│   └── app.php                  # PUSAT: Sesi, koneksi PDO ($koneksi), dan pemanggilan model
├── models/                      # Kumpulan fungsi query database (murni fungsi biasa, tanpa class)
│   ├── user_model.php           # CRUD user, login, verifikasi lupa password, kasir
│   ├── store_model.php          # Ambil & update profil toko/warung
│   ├── product_model.php        # CRUD produk barang dagangan
│   ├── category_model.php       # CRUD kategori barang
│   └── restock_model.php        # Pencatatan restock barang masuk & penambahan stok
├── controllers/                 # Pengatur logika halaman & penanganan form
│   ├── auth/
│   │   ├── login.php            # Tampil form login & proses login/lupa password
│   │   ├── register.php         # Tampil form register owner & buat toko baru
│   │   └── logout.php           # Hancurkan sesi & redirect ke login
│   ├── owner/
│   │   ├── dashboard.php        # Halaman ringkasan metrik toko
│   │   ├── akun_kasir.php       # Kelola akun staf kasir (tambah, edit, hapus)
│   │   ├── product.php          # Kelola produk dagangan
│   │   ├── kategori.php         # Kelola kategori barang
│   │   ├── restock.php          # Catat restock & barang masuk
│   │   └── store.php            # Update profil toko (nama & alamat)
│   └── cashier/
│       └── dashboard.php        # Area kerja kasir
├── views/                       # Tampilan antarmuka HTML murni (Bootstrap 5 & Lucide Icons)
│   ├── partials/
│   │   ├── header.php           # Sidebar navigasi, topbar, modal profil toko
│   │   ├── footer.php           # Script Bootstrap & Lucide icons
│   │   └── alert.php            # Komponen notifikasi pop-up pesan sukses/error
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── owner/
│   │   ├── dashboard.php
│   │   ├── akun_kasir.php
│   │   ├── product.php
│   │   ├── kategori.php
│   │   └── restock.php
│   └── cashier/
│       └── dashboard.php
├── assets/                      # Berkas gambar (logo), css, dan js
├── index.php                    # Pintu masuk utama (redirect otomatis sesuai sesi)
├── run.php                      # Script praktis menjalankan server lokal
└── warungku.sql                 # File dump database awal
```

---

## 💡 Konsep Arsitektur MVC Struktural

Aplikasi ini memisahkan tanggung jawab kode tanpa menggunakan class OOP yang rumit:

1. **`config/app.php` sebagai Penghubung Tunggal**:
   Setiap file di `controllers/` hanya perlu memanggil file ini satu kali di paling atas:
   ```php
   require_once __DIR__ . '/../../config/app.php';
   ```
   Seketika itu juga:
   - Sesi login aktif (`$_SESSION`).
   - Objek database PDO (`$koneksi`) siap digunakan.
   - Semua fungsi di folder `models/` otomatis terhubung tanpa perlu di-`require` satu per satu.
   - Fungsi pembantu seperti `redirect()` dan `set_alert()` siap dipakai.

2. **Model (`models/`)**:
   Hanya berisi fungsi query biasa (tanpa `class`). Contoh:
   ```php
   function user_get_by_store($koneksi, $store_id) { ... }
   ```

3. **Controller (`controllers/`)**:
   Bertindak mengatur alur: periksa login, tangani form `POST`, panggil fungsi model, lalu buka file `view`.

4. **View (`views/`)**:
   Hanya menampilkan data ke dalam HTML dengan Bootstrap 5 tanpa sintaksis PHP yang berbelit-belit.

---

## 📖 Panduan Menambah Fitur / Halaman Baru

Jika Anda ingin menambahkan halaman baru (misalnya fitur **Laporan Penjualan**):

1. **Langkah 1: Tambah Query di Model** (misal di `models/laporan_model.php`):
   ```php
   function laporan_get_penjualan($koneksi, $store_id) {
       $stmt = $koneksi->prepare("SELECT * FROM tbltransaction WHERE StoreID = ?");
       $stmt->execute([$store_id]);
       return $stmt->fetchAll();
   }
   ```
   *(Daftarkan file ini di `config/app.php` bagian `require_once` model).*

2. **Langkah 2: Buat Controller** (di `controllers/owner/laporan.php`):
   ```php
   <?php
   require_once __DIR__ . '/../../config/app.php';

   // Cek login owner
   if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
       redirect('/controllers/auth/login.php');
   }

   $data_laporan = laporan_get_penjualan($koneksi, $_SESSION['store_id']);
   $page_title = 'Laporan Penjualan';
   include __DIR__ . '/../../views/owner/laporan.php';
   ```

3. **Langkah 3: Buat View Tampilan** (di `views/owner/laporan.php`):
   ```php
   <?php include __DIR__ . '/../partials/header.php'; ?>
   <div class="container-fluid px-4 py-4">
       <h4>Laporan Penjualan</h4>
       <!-- Tampilkan tabel data laporan di sini -->
   </div>
   <?php include __DIR__ . '/../partials/footer.php'; ?>
   ```

---

## 👥 Akun Default untuk Pengujian

| Role | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Owner** | `Zhafran` | `123456` | Pemilik warung (Akses penuh seluruh modul) |
| **Kasir** | `iqbaldez` | `123456` | Staf kasir operasional |

*(Atau Anda bisa langsung mendaftar warung baru melalui halaman **Daftar Warung Baru**).*
