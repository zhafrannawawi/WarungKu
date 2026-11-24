<?php
$role = $_SESSION['role'] ?? 'owner';
$username = $_SESSION['user_name'] ?? 'User';
$store_name = $_SESSION['store_name'] ?? 'WarungKu';
$store_id = $_SESSION['store_id'] ?? 0;
$current_page = $page_name ?? '';

// Ambil data toko untuk modal profil
$store_info = [];
if (!empty($koneksi) && $store_id > 0) {
    $store_info = store_get($koneksi, $store_id);
}

$is_transaksi_active = in_array($current_page, ['kasir', 'transaksi'], true);
$is_product_active   = in_array($current_page, ['product', 'restock', 'kategori'], true);
$is_report_active    = in_array($current_page, ['laporan-laba-rugi', 'laporan-stok', 'laporan-terlaris'], true);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WarungKu | <?= $page_title ?? 'Dashboard' ?></title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Utama -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-body-app">
    <div id="wrapper">

        <!-- ========== SIDEBAR ========== -->
        <aside id="sidebar-container">
            <div id="sidebar-wrapper" class="d-flex flex-column">
                <!-- Brand Header -->
                <div class="sidebar-brand-header d-flex align-items-center">
                    <a href="/owner/dashboard" class="brand-logo-container text-decoration-none">
                        <img src="/assets/image/logo.png" alt="Logo WarungKu" class="brand-logo-img">
                        <img src="/assets/image/warungku.png" alt="WarungKu" class="brand-text-img">
                    </a>
                </div>

                <div class="p-3">
                    <!-- Navigation List -->
                    <ul class="nav flex-column gap-1">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="/owner/dashboard" class="nav-link-custom <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                                <i data-lucide="layout-grid"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- Transaksi -->
                        <li class="nav-item">
                            <a href="#collapse-transaksi" class="nav-link-custom justify-content-between <?= $is_transaksi_active ? '' : 'collapsed' ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?= $is_transaksi_active ? 'true' : 'false' ?>">
                                <span class="d-flex align-items-center gap-2">
                                    <i data-lucide="shopping-cart"></i>
                                    <span>Transaksi</span>
                                </span>
                                <i data-lucide="chevron-down" class="rotate-icon"></i>
                            </a>
                            <div class="collapse <?= $is_transaksi_active ? 'show' : '' ?>" id="collapse-transaksi">
                                <ul class="submenu-nav">
                                    <li>
                                        <a href="/owner/kasir" class="nav-link-custom <?= $current_page === 'kasir' ? 'active' : '' ?>">
                                            <span>Kasir</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/owner/transaksi" class="nav-link-custom <?= $current_page === 'transaksi' ? 'active' : '' ?>">
                                            <span>Riwayat Transaksi</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Produk -->
                        <li class="nav-item">
                            <a href="#collapse-produk" class="nav-link-custom justify-content-between <?= $is_product_active ? '' : 'collapsed' ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?= $is_product_active ? 'true' : 'false' ?>">
                                <span class="d-flex align-items-center gap-2">
                                    <i data-lucide="package"></i>
                                    <span>Produk</span>
                                </span>
                                <i data-lucide="chevron-down" class="rotate-icon"></i>
                            </a>
                            <div class="collapse <?= $is_product_active ? 'show' : '' ?>" id="collapse-produk">
                                <ul class="submenu-nav">
                                    <li>
                                        <a href="/owner/produk" class="nav-link-custom <?= $current_page === 'product' ? 'active' : '' ?>">
                                            <span>Daftar Produk</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/owner/restock" class="nav-link-custom <?= $current_page === 'restock' ? 'active' : '' ?>">
                                            <span>Restock Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/owner/kategori" class="nav-link-custom <?= $current_page === 'kategori' ? 'active' : '' ?>">
                                            <span>Kategori</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Laporan -->
                        <li class="nav-item">
                            <a href="#collapse-laporan" class="nav-link-custom justify-content-between <?= $is_report_active ? '' : 'collapsed' ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?= $is_report_active ? 'true' : 'false' ?>">
                                <span class="d-flex align-items-center gap-2">
                                    <i data-lucide="bar-chart-3"></i>
                                    <span>Laporan</span>
                                </span>
                                <i data-lucide="chevron-down" class="rotate-icon"></i>
                            </a>
                            <div class="collapse <?= $is_report_active ? 'show' : '' ?>" id="collapse-laporan">
                                <ul class="submenu-nav">
                                    <li>
                                        <a href="/owner/laporan/laba-rugi" class="nav-link-custom <?= $current_page === 'laporan-laba-rugi' ? 'active' : '' ?>">
                                            <span>Laba Rugi</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/owner/laporan/stok" class="nav-link-custom <?= $current_page === 'laporan-stok' ? 'active' : '' ?>">
                                            <span>Stok</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/owner/laporan/terlaris" class="nav-link-custom <?= $current_page === 'laporan-terlaris' ? 'active' : '' ?>">
                                            <span>Terlaris</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Akun Kasir -->
                        <li class="nav-item">
                            <a href="/owner/akun-kasir" class="nav-link-custom <?= $current_page === 'akun-kasir' ? 'active' : '' ?>">
                                <i data-lucide="users"></i>
                                <span>Akun Kasir</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Sidebar Bottom: Profil Pengguna & Aksi Cepat -->
                <div class="mt-auto p-3 border-top">
                    <div class="sidebar-user-card">
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2 overflow-hidden me-1">
                                <div class="sidebar-user-avatar">
                                    <i data-lucide="user" class="icon-sm"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.8125rem; line-height: 1.25;">
                                        <?= htmlspecialchars($username) ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 mt-0.5">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-1 py-0" style="font-size: 0.625rem;">Buka</span>
                                        <small class="text-muted text-truncate" style="font-size: 0.675rem;">
                                            <?= ucfirst(htmlspecialchars($role)) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-sidebar-logout" data-bs-toggle="modal" data-bs-target="#modalLogout">
                                <i data-lucide="log-out" class="icon-xs"></i>
                            </button>
                        </div>

                        <!-- Tombol Aksi: Profil & Ganti Password -->
                        <div class="d-flex gap-1.5">
                            <button type="button" class="btn-sidebar-action" data-bs-toggle="modal" data-bs-target="#modalProfilWarung">
                                <i data-lucide="store"></i>
                                <span>Profil</span>
                            </button>
                            <button type="button" class="btn-sidebar-action" data-bs-toggle="modal" data-bs-target="#modalGantiPassword">
                                <i data-lucide="key"></i>
                                <span>Password</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Tutup Sidebar Lebar di Bagian Bawah (Mobile) -->
                    <button type="button" class="btn btn-light border w-100 mt-2 py-2 d-md-none text-muted d-flex align-items-center justify-content-center gap-2" onclick="toggleSidebar()">
                        <i data-lucide="chevron-left" class="icon-sm"></i>
                        <span class="small fw-semibold">Tutup Menu</span>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Overlay Gelap untuk Menutup Sidebar Saat Area Luar Diklik (Mobile) -->
        <div id="sidebar-backdrop" class="d-md-none" onclick="toggleSidebar()"></div>

        <!-- ========== MAIN CONTENT ========== -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <div id="topbar-container">
                <div class="topbar-header px-4 d-flex align-items-center justify-content-between">
                    <!-- Sisi Kiri: Tombol Toggle Mobile & Identitas Toko -->
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-circle d-md-none" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                            <i data-lucide="menu"></i>
                        </button>
                        <span class="text-uppercase fw-bold fs-6 text-dark" style="letter-spacing:.08em;">
                            <?= htmlspecialchars($store_info['StoreName'] ?? $store_name) ?>
                        </span>
                    </div>

                    <!-- Sisi Kanan: Tombol Buka Modal Notifikasi -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-circle position-relative" data-bs-toggle="modal" data-bs-target="#modalNotification" title="Pusat Notifikasi">
                            <i data-lucide="bell" class="icon-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Body Content -->
            <main class="p-4">