<?php
$role = $_SESSION['role'] ?? 'owner';
$username = $_SESSION['user_name'] ?? 'User';
$store_name = $_SESSION['store_name'] ?? 'WarungKu';
$user_id = (string)($_SESSION['user_id'] ?? '');
$store_id = (string)($_SESSION['store_id'] ?? '');
$current_page = $page_name ?? '';
$role_prefix = $role === 'owner' ? '/owner' : '/cashier';
$home_url    = dashboard_url($role);

// Ambil data toko, akun, & notifikasi stok menipis (F011)
$store_info      = [];
$user_info       = [];
$notif_low_stock = [];
if (!empty($koneksi)) {
    if ($store_id !== '') {
        $store_info      = store_get($koneksi, $store_id);
        $notif_low_stock = product_get_low_stock($koneksi, $store_id);
    }
    if ($user_id !== '') {
        $user_info = user_find_by_id($koneksi, $user_id) ?: [];
    }
}
$notif_count = count($notif_low_stock);

$is_transaksi_active = in_array($current_page, ['kasir', 'transaksi'], true);
$is_product_active   = in_array($current_page, ['product', 'restock', 'kategori'], true);
$is_report_active    = in_array($current_page, ['laporan-laba-rugi', 'laporan-stok', 'laporan-terlaris'], true);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WarungKu | <?= escape_html($page_title ?? 'Dashboard') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css?v=<?= filemtime(__DIR__ . '/../../assets/css/main.css') ?>">
    <link rel="icon" type="image/x-icon" href="/assets/image/logo.ico">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-body-app">
    <div id="wrapper">

        <!-- ========== SIDEBAR ========== -->
        <aside id="sidebar-container">
            <div id="sidebar-wrapper" class="d-flex flex-column">
                <!-- Brand Header -->
                <div class="sidebar-brand-header d-flex align-items-center">
                    <a href="<?= $home_url ?>" class="brand-logo-container text-decoration-none">
                        <img src="/assets/image/logo.png" alt="Logo WarungKu" class="brand-logo-img">
                        <img src="/assets/image/warungku.png" alt="WarungKu" class="brand-text-img">
                    </a>
                </div>

                <div class="p-3">
                    <ul class="nav flex-column gap-1">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="<?= $home_url ?>" class="nav-link-custom <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                                <i data-lucide="layout-grid"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- Transaksi (Kasir & Riwayat Transaksi) -->
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
                                        <a href="<?= $role_prefix ?>/kasir" class="nav-link-custom <?= $current_page === 'kasir' ? 'active' : '' ?>">
                                            <span>Kasir</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $role_prefix ?>/transaksi" class="nav-link-custom <?= $current_page === 'transaksi' ? 'active' : '' ?>">
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
                                        <a href="<?= $role_prefix ?>/produk" class="nav-link-custom <?= $current_page === 'product' ? 'active' : '' ?>">
                                            <span>Daftar Produk</span>
                                        </a>
                                    </li>
                                    <?php if ($role === 'owner'): ?>
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
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>

                        <?php if ($role === 'owner'): ?>
                            <!-- Laporan (Khusus Owner) -->
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

                            <!-- Akun Kasir (Khusus Owner) -->
                            <li class="nav-item">
                                <a href="/owner/akun-kasir" class="nav-link-custom <?= $current_page === 'akun-kasir' ? 'active' : '' ?>">
                                    <i data-lucide="users"></i>
                                    <span>Akun Kasir</span>
                                </a>
                            </li>
                        <?php endif; ?>
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
                                        <?= escape_html($username) ?>
                                    </div>
                                    <div class="d-flex align-items-center mt-0.5">
                                        <small class="text-muted text-truncate" style="font-size: 0.675rem;">
                                            <?= $role === 'owner' ? 'Pemilik' : 'Kasir' ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-sidebar-logout" data-bs-toggle="modal" data-bs-target="#modalLogout">
                                <i data-lucide="log-out" class="icon-xs"></i>
                            </button>
                        </div>

                        <div class="d-flex gap-1.5">
                            <?php if ($role === 'owner'): ?>
                                <button type="button" class="btn-sidebar-action" data-bs-toggle="modal" data-bs-target="#modalProfilWarung">
                                    <i data-lucide="store"></i>
                                    <span>Warung</span>
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn-sidebar-action" data-bs-toggle="modal" data-bs-target="#modalProfilAkun">
                                <i data-lucide="user-cog"></i>
                                <span>Akun</span>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-light border w-100 mt-2 py-2 d-lg-none text-muted d-flex align-items-center justify-content-center gap-2" onclick="toggleSidebar()">
                        <i data-lucide="chevron-left" class="icon-sm"></i>
                        <span class="small fw-semibold">Tutup Menu</span>
                    </button>
                </div>
            </div>
        </aside>

        <div id="sidebar-backdrop" class="d-lg-none" onclick="toggleSidebar()"></div>

        <!-- ========== MAIN CONTENT ========== -->
        <div id="content-wrapper">
            <div id="topbar-container">
                <header class="topbar-header px-3 px-md-4 d-flex align-items-center justify-content-between gap-3">
                    <!-- Sisi Kiri: Toggle Mobile + Identitas Warung & Halaman Aktif -->
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <button type="button" class="btn btn-topbar-icon d-lg-none flex-shrink-0" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                            <i data-lucide="menu" class="icon-md"></i>
                        </button>

                        <div class="d-flex align-items-center gap-3 min-w-0">
                            <div class="topbar-store-badge d-none d-sm-flex">
                                <i data-lucide="store" class="icon-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.925rem; line-height: 1.3;">
                                    <?= escape_html($store_info['StoreName'] ?? $store_name) ?>
                                </div>
                                <?php if (!empty($store_info['Address'])): ?>
                                    <div class="text-muted text-truncate" style="font-size: 0.75rem; line-height: 1.3;">
                                        <?= escape_html($store_info['Address']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Notifikasi Stok Menipis (F011) -->
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <button type="button" class="btn btn-topbar-icon position-relative" data-bs-toggle="modal" data-bs-target="#modalNotification" title="Pusat Notifikasi">
                            <i data-lucide="bell" class="icon-md"></i>
                            <?php if ($notif_count > 0): ?>
                                <span class="notif-badge"><?= $notif_count > 99 ? '99+' : $notif_count ?></span>
                            <?php endif; ?>
                        </button>
                    </div>
                </header>
            </div>

            <main class="p-3 p-md-4">