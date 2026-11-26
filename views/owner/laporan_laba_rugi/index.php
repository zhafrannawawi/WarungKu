<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Laporan Laba Rugi -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="line-chart" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Laporan Laba Rugi</h4>
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1" style="font-size: 0.7rem;">Periode</span>
            </div>
            <p class="text-secondary mb-0 small">Rekapitulasi omzet penjualan, harga pokok modal (HPP), dan margin keuntungan bersih</p>
        </div>
    </div>

    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
        <div class="bg-white border-0 shadow-sm rounded-3 d-inline-flex align-items-center gap-2 px-2 py-1">
            <input type="date" name="from" value="<?= htmlspecialchars($from_date) ?>" class="form-control form-control-sm border-0 bg-transparent shadow-none" style="width: auto;" aria-label="Dari tanggal">
            <span class="text-secondary small">s/d</span>
            <input type="date" name="to" value="<?= htmlspecialchars($to_date) ?>" class="form-control form-control-sm border-0 bg-transparent shadow-none" style="width: auto;" aria-label="Sampai tanggal">
        </div>

        <button type="submit" class="btn btn-primary rounded-btn-sm rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                <i data-lucide="filter" class="icon-xs"></i>
            </span>
            <span>Filter</span>
        </button>

        <button type="button" class="btn btn-outline-secondary rounded-btn-sm rounded-3 bg-white d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="exportDataProduk()">
            <i data-lucide="download" class="icon-xs"></i>
            <span>Export</span>
        </button>
    </form>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: Kartu Ringkasan -->
<div class="row g-3 mb-4">
    <!-- Total Omzet -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Omzet</span>
                <div class="metric-icon-box metric-icon-blue bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="banknote" class="icon-sm"></i>
                </div>
            </div>
            <div class="metric-value fs-4 fw-bold text-dark">Rp <?= number_format($total_omzet, 0, ',', '.') ?></div>
            <small class="text-secondary mt-2 d-block">Pendapatan kotor penjualan</small>
        </div>
    </div>

    <!-- Total Modal (HPP) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Modal (HPP)</span>
                <div class="metric-icon-box metric-icon-orange bg-warning bg-opacity-10 text-warning-emphasis rounded-3 p-2">
                    <i data-lucide="package" class="icon-sm"></i>
                </div>
            </div>
            <div class="metric-value fs-4 fw-bold text-dark">Rp <?= number_format($total_modal, 0, ',', '.') ?></div>
            <small class="text-secondary mt-2 d-block">Harga beli barang terjual</small>
        </div>
    </div>

    <!-- Laba Bersih -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Laba Bersih</span>
                <div class="metric-icon-box metric-icon-green <?= $total_laba >= 0 ? 'bg-success text-success' : 'bg-danger text-danger' ?> bg-opacity-10 rounded-3 p-2">
                    <i data-lucide="trending-up" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value fs-4 fw-bold <?= $total_laba >= 0 ? 'text-success' : 'text-danger' ?>">Rp <?= number_format($total_laba, 0, ',', '.') ?></span>
                <span class="badge-status <?= $total_laba >= 0 ? 'success' : 'danger' ?> badge <?= $total_laba >= 0 ? 'bg-success text-success border-success' : 'bg-danger text-danger border-danger' ?> bg-opacity-10 border border-opacity-25">
                    <?= $margin_pct ?>%
                </span>
            </div>
            <small class="text-secondary mt-2 d-block">Margin bersih setelah modal</small>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Transaksi</span>
                <div class="metric-icon-box metric-icon-purple bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="receipt" class="icon-sm"></i>
                </div>
            </div>
            <div class="metric-value fs-4 fw-bold text-primary"><?= number_format($total_trx, 0, ',', '.') ?> Nota</div>
            <small class="text-secondary mt-2 d-block">Dalam <?= count($daily_report) ?> hari aktif</small>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Rincian Harian -->
<div class="card-modern bg-white border rounded-4 shadow-sm overflow-hidden">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold text-dark mb-1">Rincian Laba Rugi Harian</h6>
            <small class="text-secondary">Ringkasan per tanggal pada periode terpilih</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">Tanggal</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Transaksi</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Omzet Penjualan</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Modal (HPP)</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Laba Bersih</th>
                    <th class="text-center pe-4 py-3 text-secondary small text-uppercase fw-semibold">Margin</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daily_report)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-5">
                            <i data-lucide="receipt-text" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada data transaksi pada periode ini.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daily_report as $row):
                        $omzet  = (float)$row['TotalOmzet'];
                        $laba   = (float)$row['LabaBersih'];
                        $margin = $omzet > 0 ? round(($laba / $omzet) * 100, 1) : 0;
                    ?>
                        <tr>
                            <td class="ps-4 fw-semibold text-dark"><?= date('d M Y', strtotime($row['Tanggal'])) ?></td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 fw-semibold"><?= (int)$row['JumlahTransaksi'] ?> Nota</span>
                            </td>
                            <td class="text-end text-dark">Rp <?= number_format($omzet, 0, ',', '.') ?></td>
                            <td class="text-end text-secondary">Rp <?= number_format($row['TotalModal'], 0, ',', '.') ?></td>
                            <td class="text-end fw-bold <?= $laba >= 0 ? 'text-success' : 'text-danger' ?>">Rp <?= number_format($laba, 0, ',', '.') ?></td>
                            <td class="text-center pe-4">
                                <span class="badge-status <?= $laba >= 0 ? 'success' : 'danger' ?> badge <?= $laba >= 0 ? 'bg-success text-success border-success' : 'bg-danger text-danger border-danger' ?> bg-opacity-10 border border-opacity-25">
                                    <?= $margin ?>%
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top bg-light rounded-bottom-4 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
        <small class="text-secondary">Menampilkan <strong class="text-dark"><?= count($daily_report) ?></strong> hari transaksi</small>
        <small class="text-secondary">Total Laba Bersih: <strong class="<?= $total_laba >= 0 ? 'text-success' : 'text-danger' ?>">Rp <?= number_format($total_laba, 0, ',', '.') ?></strong></small>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>