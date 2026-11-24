# Peta Jalan Pengembangan & Daftar Pekerjaan (ROADMAP)
## WarungKu — Aplikasi Kasir & Manajemen Warung UMKM

Dokumen ini memetakan seluruh fitur, form, rute, dan tombol yang belum memiliki logika (*missing logic / placeholder*), lengkap dengan **urutan pengerjaan bertahap** yang terstruktur, aman, dan mudah dipahami oleh pemula.

---

## 📊 Matriks Status Fitur Saat Ini

| Area / Fitur | Rute URL | Status Logika | Kategori Masalah |
|---|---|---|---|
| **Kategori Produk** | `/owner/kategori` | ✅ Selesai (100%) | Sudah standar & teruji |
| **Daftar Produk** | `/owner/produk` | ✅ Selesai (100%) | Sudah standar & teruji |
| **Akun Staf Kasir** | `/owner/akun-kasir` | ✅ Selesai (100%) | Sudah standar & teruji |
| **Profil Toko** | `/owner/store` | ✅ Selesai (100%) | Handler update toko aktif |
| **Restock Barang** | `/owner/restock` | 🛠️ Dikerjakan Pengguna | Penyesuaian loop view |
| **Ganti Password** | `/owner/change-password` | ❌ Belum Ada Controller | Form submit $\rightarrow$ Error 404 |
| **Laporan Stok** | `/owner/laporan/stok` | ⚠️ Logika Salah Sasaran | Controller masih copy-paste Kategori |
| **Kasir (POS Transaksi)** | `/owner/kasir` | ⭕ Belum Dibuat | Controller & View belum ada (404) |
| **Riwayat Transaksi** | `/owner/transaksi` | ⭕ Belum Dibuat | Controller & View belum ada (404) |
| **Laporan Laba Rugi** | `/owner/laporan/laba-rugi` | ⭕ Belum Dibuat | Controller & View belum ada (404) |
| **Laporan Terlaris** | `/owner/laporan/terlaris` | ⭕ Belum Dibuat | Controller & View belum ada (404) |
| **Ekspor Excel Data** | Tombol di Produk & Restock | ⚠️ Placeholder `alert()` | Belum ada script unduh file |
| **Pusat Notifikasi** | Ikon Lonceng Topbar | ⚠️ Tampilan Statis | Belum membaca data stok menipis |

---

## 🧭 Urutan Pengerjaan Bertahap (4 Fase)

```text
┌────────────────────────┐      ┌────────────────────────┐
│        FASE 1          │      │        FASE 2          │
│ Perbaikan Cepat & Bug  │ ───> │   Fitur Inti Kasir     │
│ (Password & Lap. Stok) │      │  (POS & Struk Riwayat) │
└────────────────────────┘      └────────────────────────┘
            │                               │
            ▼                               ▼
┌────────────────────────┐      ┌────────────────────────┐
│        FASE 3          │      │        FASE 4          │
│ Laporan Bisnis & Omzet │ ───> │ Polishing, Ekspor CSV  │
│ (Laba Rugi & Terlaris) │      │   & Notifikasi Otomatis│
└────────────────────────┘      └────────────────────────┘
```

---

### 🟢 FASE 1: Perbaikan Bug & Form Rusak (Quick Wins)
> **Tujuan:** Menghilangkan semua potensi error 404 pada fitur yang tombolnya sudah terlihat oleh pengguna saat ini.

#### 1. Handler Ganti Password Pengguna
- **File Baru:** `controllers/owner/change_password.php`
- **Model yang Dipakai:** Fungsi `user_update_password()` di `models/model_pengguna.php`.
- **Alur Kerja:**
  1. Terima input: `password_lama`, `password_baru`, `konfirmasi_password`.
  2. Verifikasi apakah password lama cocok dengan hash di database (`password_verify`).
  3. Validasi password baru minimal 6 karakter dan sama dengan konfirmasi.
  4. Simpan password baru menggunakan `password_hash($baru, PASSWORD_DEFAULT)`.
  5. Berikan flash alert sukses/gagal lalu redirect kembali ke halaman sebelumnya.

#### 2. Perbaikan Controller Laporan Stok
- **File Diperbaiki:** `controllers/owner/laporan_stok.php`
- **Alur Kerja:**
  1. Bersihkan kode proses aksi Kategori yang nyasar.
  2. Ambil data produk toko dan hitung kalkulasi perputaran:
     - Stok Awal = Sisa Stok Fisik + Barang Keluar - Barang Masuk
     - Total Valuasi Aset = Jumlah Sisa Stok $\times$ Harga Beli Satuan (HPP)
  3. Kirim data `$produk`, `$kategoriList`, dan `$ringkasan` ke view agar tabel dan kartu metrik terisi riil.

---

### 🔵 FASE 2: Fitur Inti Bisnis Kasir (Point of Sale)
> **Tujuan:** Memberikan fungsi transaksi penjualan nyata agar toko dapat mencatat penjualan pelanggan dan stok terpotong otomatis.

#### 3. Model Transaksi Database
- **File Baru:** `models/model_transaksi.php`
- **Fungsi Standar:**
  - `transaction_create(PDO $koneksi, int $store_id, int $user_id, array $items, float $paid)`: Menggunakan PDO Transaction (`beginTransaction`, `commit`, `rollBack`) untuk menyimpan ke `tbltransaction`, rincian ke `tbltransactiondetail`, dan memotong stok di `tblproduct` secara atomik.
  - `transaction_get_all(PDO $koneksi, int $store_id, array $filter)`: Mengambil daftar struk penjualan.
  - `transaction_get_detail(PDO $koneksi, int $trx_id, int $store_id)`: Mengambil rincian barang belanjaan dalam 1 faktur.
  - `transaction_get_today_summary(PDO $koneksi, int $store_id)`: Menghitung total omzet dan kas masuk hari ini untuk kartu dashboard.

#### 4. Halaman Kasir POS (`/owner/kasir`)
- **File Baru:** `controllers/owner/kasir.php` & `views/owner/kasir/index.php`
- **Fitur:**
  - Panel pencarian produk interaktif (ketik nama produk atau klik daftar item).
  - Keranjang belanja kasir: atur jumlah beli, subtotal, dan diskon bila ada.
  - Kalkulator kasir: input nominal bayar, otomatis hitung kembalian tunai.
  - Simpan transaksi dan opsi cetak struk nota belanja mini.

#### 5. Halaman Riwayat Transaksi (`/owner/transaksi`)
- **File Baru:** `controllers/owner/transaksi.php` & `views/owner/transaksi/index.php`
- **Fitur:**
  - Tabel faktur transaksi belanja (No. Faktur, Jam, Nama Kasir, Total Belanja, Tunai, Kembalian).
  - Modal Detail Transaksi: klik untuk melihat rincian item apa saja yang dibeli pada faktur tersebut.
  - Filter rentang tanggal transaksi.

---

### 🟡 FASE 3: Laporan Finansial & Rekapitulasi Toko
> **Tujuan:** Memberi wawasan kepada pemilik toko terkait performa keuntungan dan produk unggulan.

#### 6. Laporan Laba Rugi (`/owner/laporan/laba-rugi`)
- **File Baru:** `controllers/owner/laporan_laba_rugi.php` & view terkait.
- **Logika:**
  - Omzet Kotor = Total penjualan pada periode yang dipilih.
  - Total HPP (Modal) = $\sum (\text{Qty Terjual} \times \text{Harga Beli Saat Transaksi})$.
  - Estimasi Laba Bersih = Omzet Kotor $-$ Total HPP.

#### 7. Laporan Produk Terlaris (`/owner/laporan/terlaris`)
- **File Baru:** `controllers/owner/laporan_terlaris.php` & view terkait.
- **Logika:**
  - Agregasi `SUM(Quantity)` dari `tbltransactiondetail` diurutkan dari yang terbanyak.
  - Membantu pemilik toko mengetahui barang yang paling cepat laku (Fast Moving).

---

### 🟣 FASE 4: Polishing & Penyempurnaan Utilitas
> **Tujuan:** Membersihkan sisa placeholder dummy dan meningkatkan kemudahan penggunaan.

#### 8. Fitur Ekspor CSV Native (Tanpa Library Berat)
- Mengganti fungsi dummy `alert()` dengan file generator CSV standar:
  - Ekspor Data Produk $\rightarrow$ File `.csv` yang langsung bisa dibuka di Microsoft Excel.
  - Ekspor Riwayat Restock $\rightarrow$ File `.csv`.
  - Ekspor Laporan Stok $\rightarrow$ File `.csv`.

#### 9. Pusat Notifikasi Pintar (Topbar Bell)
- Menghubungkan ikon lonceng dengan query otomatis:
  - Jika ada produk yang stoknya $\le$ stok minimum, tampilkan peringatan merah *"Stok Produk [Nama] Hampir Habis!"*.
  - Link langsung ke modal restock untuk mempermudah kulakan.

---

## 📌 Prinsip Pengerjaan
1. **Satu per Satu:** Selesaikan satu modul hingga teruji, baru berpindah ke modul berikutnya.
2. **Kesesuaian Pola:** Setiap controller baru wajib mengikuti **Anatomi 5 Bagian Baku** yang telah disepakati.
3. **Ramah Pemula:** Mengutamakan keterbacaan kode (clean code, penamaan variabel jelas, dan komentar berbahasa Indonesia yang informatif).
