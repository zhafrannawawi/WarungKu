<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Area Transaksi Kasir</h4>
        <p class="text-muted mb-0 small">Selamat bertugas, <?= $_SESSION['full_name'] ?? $_SESSION['user_name'] ?>. Layani pelanggan dengan ramah.</p>
    </div>

    <!-- Alert Notifikasi -->
    <?php include __DIR__ . '/../../partials/alert.php'; ?>

    <div class="card border-0 shadow-sm p-4 bg-white" style="max-width: 600px;">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                <i data-lucide="shopping-bag" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Shift Kasir Aktif</h5>
                <small class="text-muted">Toko: <?= $store_info['StoreName'] ?? 'WarungKu' ?></small>
            </div>
        </div>
        <p class="text-muted small">Modul kasir dan pencatatan transaksi penjualan siap digunakan.</p>
        <div class="d-flex gap-2">
            <a href="/logout" class="btn btn-outline-danger btn-sm">
                <i data-lucide="log-out" style="width:14px;height:14px;"></i> Akhiri Shift (Keluar)
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
