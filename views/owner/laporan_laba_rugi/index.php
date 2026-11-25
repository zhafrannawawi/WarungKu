<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Laporan Laba Rugi</h4>
        <p class="text-muted mb-0 small">Rekapitulasi omzet penjualan, harga pokok modal (HPP), dan margin keuntungan bersih.</p>
    </div>

    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center gap-1">
            <input type="date" name="from" value="<?= htmlspecialchars($from_date) ?>" class="form-control form-control-sm border rounded-btn-sm bg-white" style="width: auto;">
            <span class="text-muted small">s/d</span>
            <input type="date" name="to" value="<?= htmlspecialchars($to_date) ?>" class="form-control form-control-sm border rounded-btn-sm bg-white" style="width: auto;">
        </div>
        <button type="submit" class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold">
            <i data-lucide="filter" class="icon-xs"></i> Filter
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-btn-sm d-flex align-items-center gap-1 bg-white" onclick="exportDataProduk()">
            <i data-lucide="download" class="icon-xs"></i> Export
        </button>
    </form>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Omzet</span>
                <div class="metric-icon-box metric-icon-blue"><i data-lucide="banknote" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark">Rp <?= number_format($total_omzet, 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Pendapatan kotor penjualan</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Modal (HPP)</span>
                <div class="metric-icon-box metric-icon-orange"><i data-lucide="package" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark">Rp <?= number_format($total_modal, 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Harga beli barang terjual</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Laba Bersih</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="trending-up" class="icon-sm"></i></div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value <?= $total_laba >= 0 ? 'text-success' : 'text-danger' ?>">Rp <?= number_format($total_laba, 0, ',', '.') ?></span>
                <span class="badge-status <?= $total_laba >= 0 ? 'success' : 'danger' ?>"><?= $margin_pct ?>%</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Margin bersih setelah modal</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Transaksi</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="receipt" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark"><?= number_format($total_trx, 0, ',', '.') ?> Nota</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Dalam <?= count($daily_report) ?> hari aktif</small>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold text-dark mb-0">Rincian Laba Rugi Harian</h6>
            <small class="text-muted">Ringkasan per tanggal pada periode terpilih</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th class="text-center">Transaksi</th>
                    <th class="text-end">Omzet Penjualan</th>
                    <th class="text-end">Modal (HPP)</th>
                    <th class="text-end">Laba Bersih</th>
                    <th class="text-center pe-4">Margin</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daily_report)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi pada periode ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daily_report as $row):
                        $omzet  = (float)$row['TotalOmzet'];
                        $laba   = (float)$row['LabaBersih'];
                        $margin = $omzet > 0 ? round(($laba / $omzet) * 100, 1) : 0;
                    ?>
                        <tr>
                            <td class="ps-4 fw-semibold text-dark"><?= date('d M Y', strtotime($row['Tanggal'])) ?></td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$row['JumlahTransaksi'] ?> Nota</span></td>
                            <td class="text-end text-dark">Rp <?= number_format($omzet, 0, ',', '.') ?></td>
                            <td class="text-end text-muted">Rp <?= number_format($row['TotalModal'], 0, ',', '.') ?></td>
                            <td class="text-end fw-bold <?= $laba >= 0 ? 'text-success' : 'text-danger' ?>">Rp <?= number_format($laba, 0, ',', '.') ?></td>
                            <td class="text-center pe-4">
                                <span class="badge-status <?= $laba >= 0 ? 'success' : 'danger' ?>"><?= $margin ?>%</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($daily_report) ?></strong> hari transaksi</small>
        <small class="text-dark">Total Laba Bersih: <strong class="<?= $total_laba >= 0 ? 'text-success' : 'text-danger' ?>">Rp <?= number_format($total_laba, 0, ',', '.') ?></strong></small>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
