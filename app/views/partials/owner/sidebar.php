<?php
$current_page = $page_name ?? '';

$is_transaksi_active = in_array($current_page, ['kasir', 'transaksi'], true);
$is_product_active   = in_array($current_page, ['product', 'restock', 'kategori'], true);
$is_report_active    = in_array($current_page, ['laporan-laba-rugi', 'laporan-stok', 'laporan-terlaris'], true);
?>
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
                            <a href="/owner/product" class="nav-link-custom <?= $current_page === 'product' ? 'active' : '' ?>">
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
                            <a href="/owner/laporan-laba-rugi" class="nav-link-custom <?= $current_page === 'laporan-laba-rugi' ? 'active' : '' ?>">
                                <span>Laba Rugi</span>
                            </a>
                        </li>
                        <li>
                            <a href="/owner/laporan-stok" class="nav-link-custom <?= $current_page === 'laporan-stok' ? 'active' : '' ?>">
                                <span>Stok</span>
                            </a>
                        </li>
                        <li>
                            <a href="/owner/laporan-terlaris" class="nav-link-custom <?= $current_page === 'laporan-terlaris' ? 'active' : '' ?>">
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
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Owner') ?>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-0.5">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-1 py-0" style="font-size: 0.625rem;">Buka</span>
                            <small class="text-muted text-truncate" style="font-size: 0.675rem;">
                                <?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'owner')) ?>
                            </small>
                        </div>
                    </div>
                </div>
                <a href="/logout" class="btn-sidebar-logout" title="Keluar dari sesi (Logout)" aria-label="Logout">
                    <i data-lucide="log-out" class="icon-xs"></i>
                </a>
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
    </div>
</div>

<?php require APP_PATH . '/views/layouts/modals/modal_profile_store.php' ?>