<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Dashboard Harian -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Dashboard Harian</h4>
        <p class="text-muted mb-0 small">Ringkasan aktivitas operasional warung hari ini</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="btn btn-sm bg-white border text-muted rounded-btn-sm d-inline-flex align-items-center gap-1 pe-none">
            <i data-lucide="calendar" class="icon-xs"></i> <?= htmlspecialchars($tanggal_hari_ini) ?>
        </span>
        <a href="/owner/kasir" class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold text-decoration-none">
            <i data-lucide="shopping-cart" class="icon-xs"></i> Buka Kasir
        </a>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: 4 Kartu Metrik Finansial & Kasir Harian -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border-0 rounded-4 shadow-sm p-4 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Omzet Hari Ini</span>
                <div class="metric-icon-box metric-icon-blue"><i data-lucide="banknote" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark">Rp <?= number_format($summary['omzet'], 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= (int)$summary['total_trx'] ?> transaksi • Rata-rata Rp <?= number_format($rata_trx, 0, ',', '.') ?></small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border-0 rounded-4 shadow-sm p-4 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Uang Tunai di Laci</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="wallet" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-success">Rp <?= number_format($summary['tunai'], 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= (int)$summary['jml_tunai'] ?> pembayaran tunai kasir</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border-0 rounded-4 shadow-sm p-4 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">QRIS / Non-Tunai</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="qr-code" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-primary">Rp <?= number_format($summary['qris'], 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= (int)$summary['jml_qris'] ?> transaksi QRIS digital</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border-0 rounded-4 shadow-sm p-4 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Estimasi Laba Bersih</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="trending-up" class="icon-sm"></i></div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark">Rp <?= number_format($summary['laba_bersih'], 0, ',', '.') ?></span>
                <span class="badge-status success"><?= $margin_pct ?>%</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Margin bersih setelah modal</small>
        </div>
    </div>
</div>

<!-- Baris 2: Transaksi Terbaru & Peringatan Stok Menipis -->
<div class="row g-3 mb-4">
    <!-- 5 Transaksi Terkini Hari Ini -->
    <div class="col-12 col-lg-7">
        <div class="card-modern h-100">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold text-dark mb-0">Transaksi Terbaru Hari Ini</h6>
                    <small class="text-muted">Catatan penjualan kasir masuk</small>
                </div>
                <a href="/owner/transaksi" class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-btn-sm text-decoration-none">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Faktur</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Metode</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_trx)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi hari ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_trx as $t): ?>
                                <tr>
                                    <td class="ps-4"><span class="trx-faktur">#<?= htmlspecialchars($t['TransactionID']) ?></span></td>
                                    <td class="text-muted small"><?= date('H:i', strtotime($t['TransactionDate'])) ?> WIB</td>
                                    <td class="fw-medium text-dark"><?= htmlspecialchars($t['CashierName'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= $t['PaymentMethod'] === 'qris' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> border fw-semibold">
                                            <?= strtoupper(htmlspecialchars($t['PaymentMethod'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark pe-4">Rp <?= number_format($t['TotalAmount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel Peringatan Stok Menipis -->
    <div class="col-12 col-lg-5">
        <div class="card-modern h-100 d-flex flex-column justify-content-between">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold text-dark mb-0">Peringatan Stok Menipis</h6>
                    <small class="text-muted">Barang di bawah batas minimum</small>
                </div>
                <a href="/owner/restock" class="btn btn-sm btn-outline-secondary rounded-btn-sm text-decoration-none">
                    Restock
                </a>
            </div>

            <div class="p-3 flex-grow-1 d-flex flex-column justify-content-center">
                <?php if (empty($low_stock_list)): ?>
                    <div class="text-center py-3">
                        <span class="badge-status success mb-2"><i data-lucide="check-circle" class="icon-xs"></i> Stok Aman</span>
                        <p class="text-muted small mb-0">Seluruh stok barang berada di atas batas minimum.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($low_stock_list as $lp): ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded border bg-light">
                                <div>
                                    <span class="fw-semibold text-dark small d-block"><?= htmlspecialchars($lp['ProductName']) ?></span>
                                    <small class="text-muted" style="font-size: 0.725rem;">Minimum: <?= (int)$lp['MinimumStock'] ?> <?= htmlspecialchars($lp['Unit']) ?></small>
                                </div>
                                <span class="badge-status danger">Sisa <?= (int)$lp['Stock'] ?> <?= htmlspecialchars($lp['Unit']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="p-2 border-top bg-light text-center rounded-bottom">
                <small class="text-muted" style="font-size: 0.75rem;">Total <strong><?= $total_katalog ?></strong> produk aktif dalam katalog warung</small>
            </div>
        </div>
    </div>
</div>

<!-- Baris 3: Produk Paling Laku Hari Ini -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold text-dark mb-0">Produk Terlaris Hari Ini</h6>
            <small class="text-muted">Urutan barang paling banyak terjual sepanjang hari ini</small>
        </div>
        <a href="/owner/laporan/terlaris" class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-btn-sm text-decoration-none">
            Lihat Laporan
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 70px;">Urutan</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-center">Terjual Hari Ini</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-end pe-4">Total Omzet</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($top_today)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada produk terjual hari ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($top_today as $idx => $tp):
                        $is_low = (int)$tp['Stock'] <= (int)$tp['MinimumStock'];
                    ?>
                        <tr>
                            <td class="ps-4">
                                <span class="badge <?= $idx === 0 ? 'bg-warning-subtle text-warning-emphasis border-warning-subtle' : 'bg-light text-dark' ?> border fw-bold">
                                    #<?= $idx + 1 ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($tp['ProductName']) ?></span>
                                <small class="text-muted">#<?= htmlspecialchars($tp['ProductID']) ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($tp['CategoryName']) ?></span></td>
                            <td class="text-center fw-bold text-primary"><?= number_format($tp['Terjual'], 0, ',', '.') ?> <?= htmlspecialchars($tp['Unit']) ?></td>
                            <td class="text-center">
                                <span class="badge-status <?= $is_low ? 'danger' : 'success' ?>">
                                    <?= (int)$tp['Stock'] ?> <?= htmlspecialchars($tp['Unit']) ?>
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark pe-4">Rp <?= number_format($tp['TotalOmzet'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
