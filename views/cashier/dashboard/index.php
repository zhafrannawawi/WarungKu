<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Dashboard Kerja Kasir -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Dashboard Kerja Harian</h4>
        <p class="text-muted mb-0 small">Halo <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['user_name']) ?>, berikut ringkasan transaksi yang Anda layani hari ini.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="btn btn-sm bg-white border text-muted rounded-btn-sm d-inline-flex align-items-center gap-1 pe-none">
            <i data-lucide="calendar" class="icon-xs"></i> <?= htmlspecialchars($tanggal_hari_ini) ?>
        </span>
        <a href="/cashier/kasir" class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold text-decoration-none">
            <i data-lucide="shopping-cart" class="icon-xs"></i> Buka Kasir
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
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Total penjualan Rp <?= number_format($summary['total_penjualan'], 0, ',', '.') ?></small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Uang Tunai Diterima</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="wallet" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-success">Rp <?= number_format($summary['tunai'], 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= (int)$summary['jml_tunai'] ?> pembayaran tunai di laci</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Pembayaran QRIS</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="qr-code" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-primary">Rp <?= number_format($summary['qris'], 0, ',', '.') ?></span>
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
                            <td class="ps-4"><span class="trx-faktur">#<?= htmlspecialchars($t['TransactionID']) ?></span></td>
                            <td class="text-muted small"><?= date('H:i', strtotime($t['TransactionDate'])) ?> WIB</td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$t['TotalItem'] ?> Item</span></td>
                            <td>
                                <span class="badge <?= $t['PaymentMethod'] === 'qris' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> border fw-semibold">
                                    <?= strtoupper(htmlspecialchars($t['PaymentMethod'])) ?>
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($t['TotalAmount'], 0, ',', '.') ?></td>
                            <td class="text-end text-muted pe-4">Rp <?= number_format($t['ChangeAmount'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
