<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Restock Barang -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Restock Barang</h4>
        <p class="text-muted mb-0 small">Catat barang masuk dan pantau riwayat restock stok dagangan.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 bg-white" onclick="exportDataRestock()">
            <i data-lucide="download" class="icon-xs"></i>
            <span>Export Excel</span>
        </button>
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRestock">
            <i data-lucide="plus" class="icon-xs"></i>
            <span>Catat Restock Baru</span>
        </button>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Pengeluaran Beli Hari Ini</span>
                <div class="metric-icon-box metric-icon-blue">
                    <i data-lucide="wallet" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark">Rp <?= number_format($total_beli_hr, 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= count($today_list) ?> restock masuk hari ini</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Barang Masuk Hari Ini</span>
                <div class="metric-icon-box metric-icon-green">
                    <i data-lucide="package-plus" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-success"><?= number_format($unit_masuk_hr, 0, ',', '.') ?> Unit</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Stok langsung diperbarui</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Stok Hampir Habis</span>
                <div class="metric-icon-box metric-icon-orange">
                    <i data-lucide="alert-triangle" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value <?= count($low_stock) > 0 ? 'text-danger' : 'text-dark' ?>"><?= count($low_stock) ?> Item</span>
            <small class="text-muted mt-2 d-block text-truncate" style="font-size: 0.725rem;">
                <?= !empty($low_stock) ? htmlspecialchars($low_stock[0]['ProductName']) . ' (sisa ' . (int)$low_stock[0]['Stock'] . ')' : 'Semua stok aman' ?>
            </small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Restock Bulan Ini</span>
                <div class="metric-icon-box metric-icon-purple">
                    <i data-lucide="calendar-check" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark"><?= $jml_bulan_ini ?> Kali</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= date('M Y') ?></small>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="restock-table-search" class="search-input-box" placeholder="Cari nama produk..." oninput="filterRestockTable()">
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <select id="restock-filter-periode" class="form-select form-select-sm border filter-select" onchange="filterRestockTable()">
                <option value="all">Semua Periode</option>
                <option value="today">Hari Ini</option>
                <option value="7days">7 Hari Terakhir</option>
                <option value="30days">Bulan Ini</option>
            </select>

            <button class="btn btn-sm btn-outline-secondary" title="Reset Filter" onclick="resetRestockFilter()">
                <i data-lucide="rotate-ccw" class="icon-xs"></i>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">No. Restock</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Nama Barang</th>
                    <th>Qty Masuk</th>
                    <th class="text-end">Total Beli</th>
                    <th>Dicatat Oleh</th>
                    <th class="pe-4">Catatan</th>
                </tr>
            </thead>
            <tbody id="tabel-riwayat-restock">
                <?php if (empty($restock_list)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat restock barang.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($restock_list as $r):
                        $total_beli = (float)$r['PurchasePrice'] * (int)$r['Quantity'];
                        $tgl        = substr($r['RestockDate'], 0, 10);
                    ?>
                        <tr class="restock-row"
                            data-produk="<?= htmlspecialchars(strtolower($r['ProductName'] ?? '')) ?>"
                            data-date="<?= htmlspecialchars($tgl) ?>">
                            <td class="ps-4">
                                <span class="trx-faktur">#<?= htmlspecialchars($r['RestockID']) ?></span>
                            </td>
                            <td>
                                <div class="trx-waktu">
                                    <span class="trx-tanggal"><?= date('d M Y', strtotime($r['RestockDate'])) ?></span>
                                    <span class="trx-jam"><?= date('H:i', strtotime($r['RestockDate'])) ?> WIB</span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($r['ProductName'] ?? '-') ?></span>
                                <small class="text-muted">#<?= htmlspecialchars($r['ProductID']) ?> • @Rp <?= number_format($r['PurchasePrice'], 0, ',', '.') ?></small>
                            </td>
                            <td>
                                <span class="badge-status success">+<?= (int)$r['Quantity'] ?> <?= htmlspecialchars($r['Unit'] ?? 'pcs') ?></span>
                            </td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($total_beli, 0, ',', '.') ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($r['CashierName'] ?? '-') ?></td>
                            <td class="text-muted small pe-4"><?= htmlspecialchars($r['Note'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($restock_list) ?></strong> riwayat restock</small>
    </div>
</div>

<?php include __DIR__ . '/modal-restock.php'; ?>
<?php include __DIR__ . '/../../partials/footer.php'; ?>
