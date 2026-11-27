# WarungKu — Static HTML (Vercel & AI Figma)

Branch `html` berisi versi **HTML statis murni** dari seluruh tampilan aplikasi **WarungKu** (UI 100% sama dengan branch `v1.4`), disiapkan khusus untuk:
- Deploy langsung ke **Vercel** (Static Site, tanpa PHP/MySQL).
- Copy desain UI ke **Figma** menggunakan AI Figma (misalnya `html.to.design`).
- Tampilan **Owner** dan **Cashier** sudah dipisahkan secara penuh.
- Seluruh **Modal** sudah dikeluarkan dari UI utama dan memiliki **link/halaman tersendiri** dalam kondisi terbuka agar bisa langsung di-capture oleh AI Figma.

---

## Cara Deploy ke Vercel

1. Push branch `html` ke repository GitHub/GitLab Anda.
2. Import project di [Vercel](https://vercel.com), lalu pilih branch **`html`** sebagai Production Branch (atau preview branch).
3. Biarkan **Framework Preset** = `Other`, **Build Command** = kosong, dan **Output Directory** = `.` (root).
4. Setelah deploy selesai, buka domain Vercel Anda (misalnya `https://warungku-html.vercel.app`) untuk mengakses direktori utama (`index.html`) atau langsung buka URL halaman/modal di bawah ini.

> **Tips:** Ganti `<DOMAIN_VERCEL>` di bawah dengan URL hasil deploy Vercel Anda, misalnya `https://warungku-html.vercel.app`.

---

## Daftar Link Halaman Modal (Tampilan Tersendiri)

Karena seluruh modal **tidak ditampilkan di UI utama**, gunakan URL berikut untuk membuka dan meng-copy masing-masing tampilan modal ke AI Figma:

### 1. Modal Autentikasi (Login)
| Nama Modal | Path URL | Link Lengkap (Contoh) |
| :--- | :--- | :--- |
| Atur Ulang Password (Verifikasi Akun) | `/modals/auth/reset-password.html` | `<DOMAIN_VERCEL>/modals/auth/reset-password.html` |
| Buat Password Baru | `/modals/auth/new-password.html` | `<DOMAIN_VERCEL>/modals/auth/new-password.html` |

### 2. Modal Global (Header & Sidebar)
| Nama Modal | Path URL | Link Lengkap (Contoh) |
| :--- | :--- | :--- |
| Notifikasi Stok Menipis (Versi Owner) | `/modals/global/notifikasi-owner.html` | `<DOMAIN_VERCEL>/modals/global/notifikasi-owner.html` |
| Notifikasi Stok Menipis (Versi Cashier) | `/modals/global/notifikasi-cashier.html` | `<DOMAIN_VERCEL>/modals/global/notifikasi-cashier.html` |
| Konfirmasi Keluar / Logout (Versi Owner) | `/modals/global/logout-owner.html` | `<DOMAIN_VERCEL>/modals/global/logout-owner.html` |
| Konfirmasi Keluar / Logout (Versi Cashier) | `/modals/global/logout-cashier.html` | `<DOMAIN_VERCEL>/modals/global/logout-cashier.html` |
| Profil Warung (Khusus Owner) | `/modals/global/profil-warung.html` | `<DOMAIN_VERCEL>/modals/global/profil-warung.html` |
| Pengaturan Akun & Password | `/modals/global/profil-akun.html` | `<DOMAIN_VERCEL>/modals/global/profil-akun.html` |
| Konfirmasi Hapus Data Global | `/modals/global/hapus.html` | `<DOMAIN_VERCEL>/modals/global/hapus.html` |

### 3. Modal Khusus Owner (Form Tambah & Edit)
| Nama Modal | Path URL | Link Lengkap (Contoh) |
| :--- | :--- | :--- |
| Tambah Produk Baru | `/modals/owner/tambah-produk.html` | `<DOMAIN_VERCEL>/modals/owner/tambah-produk.html` |
| Edit Data Produk | `/modals/owner/edit-produk.html` | `<DOMAIN_VERCEL>/modals/owner/edit-produk.html` |
| Catat Barang Masuk (Restock) | `/modals/owner/restock-barang.html` | `<DOMAIN_VERCEL>/modals/owner/restock-barang.html` |
| Tambah Kategori Baru | `/modals/owner/tambah-kategori.html` | `<DOMAIN_VERCEL>/modals/owner/tambah-kategori.html` |
| Edit Data Kategori | `/modals/owner/edit-kategori.html` | `<DOMAIN_VERCEL>/modals/owner/edit-kategori.html` |
| Tambah Akun Kasir Baru | `/modals/owner/tambah-akun-kasir.html` | `<DOMAIN_VERCEL>/modals/owner/tambah-akun-kasir.html` |
| Edit / Perbarui Akun Kasir | `/modals/owner/edit-akun-kasir.html` | `<DOMAIN_VERCEL>/modals/owner/edit-akun-kasir.html` |

### 4. Modal Transaksi & Kasir (Shared)
| Nama Modal | Path URL | Link Lengkap (Contoh) |
| :--- | :--- | :--- |
| Struk Transaksi Berhasil (Siap Cetak) | `/modals/shared/struk-kasir.html` | `<DOMAIN_VERCEL>/modals/shared/struk-kasir.html` |
| Peringatan Batas Stok Tercapai | `/modals/shared/batas-stok.html` | `<DOMAIN_VERCEL>/modals/shared/batas-stok.html` |
| Detail Nota Transaksi | `/modals/shared/detail-transaksi.html` | `<DOMAIN_VERCEL>/modals/shared/detail-transaksi.html` |

---

## Daftar Link Halaman Utama

### Portal & Autentikasi
| Halaman | Path URL |
| :--- | :--- |
| Direktori Semua Halaman & Modal | `/` atau `/index.html` |
| Login | `/auth/login.html` |
| Register Warung Baru | `/auth/register.html` |
| Error 404 | `/404.html` |

### Tampilan Owner (`owner/`)
| Halaman | Path URL |
| :--- | :--- |
| Dashboard Harian | `/owner/dashboard.html` |
| Kasir (POS) | `/owner/kasir.html` |
| Riwayat Transaksi | `/owner/transaksi.html` |
| Daftar Produk (Owner) | `/owner/daftar-produk.html` |
| Restock Barang | `/owner/restock-barang.html` |
| Kategori Produk | `/owner/kategori.html` |
| Laporan Laba Rugi | `/owner/laporan-laba-rugi.html` |
| Laporan Stok & Inventaris | `/owner/laporan-stok.html` |
| Laporan Produk Terlaris | `/owner/laporan-terlaris.html` |
| Manajemen Akun Kasir | `/owner/akun-kasir.html` |

### Tampilan Cashier (`cashier/`)
| Halaman | Path URL |
| :--- | :--- |
| Dashboard Kerja Harian | `/cashier/dashboard.html` |
| Kasir (POS) | `/cashier/kasir.html` |
| Riwayat Transaksi | `/cashier/transaksi.html` |
| Daftar Produk (Cashier) | `/cashier/daftar-produk.html` |
