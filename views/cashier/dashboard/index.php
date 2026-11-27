<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Dashboard Kerja Kasir -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="layout-dashboard" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Dashboard Kerja Harian</h4>
            </div>
            <p class="text-secondary mb-0 small">Halo <?= escape_html($_SESSION['full_name'] ?? $_SESSION['user_name']) ?>, berikut ringkasan transaksi yang Anda layani hari ini.</p>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-white border-0 text-secondary small fw-medium rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 pe-none">
            <span class="bg-primary bg-opacity-10 text-primary rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="calendar" class="icon-xs"></i>
            </span>
            <span><?= escape_html($tanggal_hari_ini) ?></span>
        </span>
        <a href="/cashier/kasir" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold text-decoration-none">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="shopping-cart" class="icon-xs"></i>
            </span>
            <span>Buka Kasir</span>
        </a>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- 3 Kartu Ringkasan Kerja Shift Kasir (Tanpa Laba/Modal Toko) -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Transaksi Saya Hari Ini</span>
                <div class="metric-icon-box metric-icon-blue"><i data-lucide="receipt" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark"><?= (int)$summary['total_trx'] ?> Transaksi</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Total penjualan <?= format_rupiah($summary['total_penjualan']) ?></small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Uang Tunai Diterima</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="wallet" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-success"><?= format_rupiah($summary['tunai']) ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= (int)$summary['jml_tunai'] ?> pembayaran tunai di laci</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Pembayaran QRIS</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="qr-code" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-primary"><?= format_rupiah($summary['qris']) ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= (int)$summary['jml_qris'] ?> transaksi non-tunai</small>
        </div>
    </div>
</div>

<!-- 5 Transaksi Terakhir Saya Hari Ini -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold text-dark mb-0">Transaksi Terakhir Saya Hari Ini</h6>
            <small class="text-muted">5 nota terbaru yang Anda proses</small>
        </div>
        <a href="/cashier/transaksi" class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-btn-sm text-decoration-none">
            Lihat Semua
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">No. Faktur</th>
                    <th>Waktu</th>
                    <th class="text-center">Jumlah Barang</th>
                    <th>Metode</th>
                    <th class="text-end">Total Belanja</th>
                    <th class="text-end pe-4">Kembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_trx)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Anda belum melayani transaksi hari ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_trx as $t): ?>
                        <tr>
                            <td class="ps-4"><span class="trx-faktur">#<?= escape_html($t['TransactionID']) ?></span></td>
                            <td class="text-muted small"><?= date('H:i', strtotime($t['TransactionDate'])) ?> WIB</td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$t['TotalItem'] ?> Item</span></td>
                            <td>
                                <span class="badge <?= $t['PaymentMethod'] === 'qris' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> border fw-semibold">
                                    <?= strtoupper(escape_html($t['PaymentMethod'])) ?>
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark"><?= format_rupiah($t['TotalAmount']) ?></td>
                            <td class="text-end text-muted pe-4"><?= format_rupiah($t['ChangeAmount']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
