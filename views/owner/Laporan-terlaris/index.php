<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Laporan Produk Terlaris</h4>
        <p class="text-muted mb-0 small">Peringkat barang paling banyak terjual berdasarkan kuantitas dan kontribusi omzet.</p>
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
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Produk Paling Laris (#1)</span>
                <div class="metric-icon-box metric-icon-orange"><i data-lucide="award" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark text-truncate"><?= $produk_juara ? htmlspecialchars($produk_juara['ProductName']) : '-' ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">
                <?= $produk_juara ? number_format($produk_juara['Terjual'], 0, ',', '.') . ' ' . htmlspecialchars($produk_juara['Unit']) . ' terjual' : 'Belum ada penjualan' ?>
            </small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Unit Terjual</span>
                <div class="metric-icon-box metric-icon-blue"><i data-lucide="shopping-bag" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark"><?= number_format($total_terjual, 0, ',', '.') ?> Unit</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Dari <?= count($top_products) ?> produk terjual</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Kategori Terlaris</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="layers" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark text-truncate"><?= htmlspecialchars($kategori_juara) ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">
                <?= $kategori_juara_qty > 0 ? number_format($kategori_juara_qty, 0, ',', '.') . ' unit terjual' : 'Belum ada penjualan' ?>
            </small>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="produk-table-search" class="search-input-box" placeholder="Cari nama produk..." oninput="filterProdukTable()">
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <select id="produk-filter-category" class="form-select form-select-sm border filter-select" onchange="filterProdukTable()">
                <option value="all">Semua Kategori</option>
                <?php foreach ($category_list as $c): ?>
                    <option value="<?= htmlspecialchars($c['CategoryName']) ?>"><?= htmlspecialchars($c['CategoryName']) ?></option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-sm btn-outline-secondary rounded-btn-sm" title="Reset Filter" onclick="resetProdukFilter()">
                <i data-lucide="rotate-ccw" class="icon-xs"></i>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 75px;">Urutan</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-center">Terjual</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-end">Total Omzet</th>
                    <th class="text-end pe-4">Estimasi Laba</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($top_products)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data penjualan pada periode ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($top_products as $idx => $tp):
                        $is_low = (int)$tp['Stock'] <= (int)$tp['MinimumStock'];
                    ?>
                        <tr class="produk-row"
                            data-category="<?= htmlspecialchars($tp['CategoryName']) ?>"
                            data-stock="<?= (int)$tp['Stock'] ?>"
                            data-name="<?= htmlspecialchars(strtolower($tp['ProductName'])) ?>">
                            <td class="ps-4">
                                <span class="badge <?= $idx === 0 ? 'bg-warning-subtle text-warning-emphasis border-warning-subtle' : 'bg-light text-dark' ?> border fw-bold">
                                    #<?= $idx + 1 ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="trx-avatar"><?= strtoupper(substr($tp['ProductName'], 0, 2)) ?></span>
                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($tp['ProductName']) ?></span>
                                        <small class="text-muted">#<?= htmlspecialchars($tp['ProductID']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($tp['CategoryName']) ?></span></td>
                            <td class="text-center fw-bold text-primary"><?= number_format($tp['Terjual'], 0, ',', '.') ?> <?= htmlspecialchars($tp['Unit']) ?></td>
                            <td class="text-center">
                                <span class="badge-status <?= $is_low ? 'danger' : 'success' ?>">
                                    <?= (int)$tp['Stock'] ?> <?= htmlspecialchars($tp['Unit']) ?>
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($tp['TotalOmzet'], 0, ',', '.') ?></td>
                            <td class="text-end fw-semibold text-success pe-4">Rp <?= number_format($tp['TotalLaba'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($top_products) ?></strong> produk terjual</small>
        <small class="text-dark">Total Omzet: <strong>Rp <?= number_format($total_omzet, 0, ',', '.') ?></strong></small>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
