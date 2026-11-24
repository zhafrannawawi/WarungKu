<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Laporan Stok & Inventaris</h4>
        <p class="text-muted mb-0 small">Audit perputaran persediaan barang, stok masuk/keluar, dan estimasi valuasi aset.</p>
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
                <span class="text-muted small fw-medium">Total Nilai Persediaan</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="wallet" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark">Rp <?= number_format($total_nilai, 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= count($product_list) ?> SKU produk aktif</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Kuantitas Fisik</span>
                <div class="metric-icon-box metric-icon-blue"><i data-lucide="package" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark"><?= number_format($total_fisik, 0, ',', '.') ?> Unit</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Semua gudang/etalase</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Stok Menipis</span>
                <div class="metric-icon-box metric-icon-orange"><i data-lucide="alert-triangle" class="icon-sm"></i></div>
            </div>
            <span class="metric-value <?= $stok_menipis > 0 ? 'text-danger' : 'text-dark' ?>"><?= $stok_menipis ?> SKU</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Di bawah batas minimum</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Barang Keluar</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="shopping-bag" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark"><?= number_format($total_keluar, 0, ',', '.') ?> Unit</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Terjual ke konsumen</small>
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

            <select id="produk-filter-stock" class="form-select form-select-sm border filter-select" onchange="filterProdukTable()">
                <option value="all">Semua Status Stok</option>
                <option value="in_stock">Stok Tersedia (>10)</option>
                <option value="low_stock">Stok Menipis (≤10)</option>
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
                    <th class="ps-4">Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-end">Stok Awal</th>
                    <th class="text-end">Masuk</th>
                    <th class="text-end">Keluar</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-end">Harga Modal</th>
                    <th class="text-end pe-4">Valuasi Aset</th>
                </tr>
            </thead>
            <tbody id="produk-table-body">
                <?php if (empty($product_list)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data produk terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($product_list as $p):
                        $kode_produk = '#' . $p['ProductID'];
                        $cat_name    = $p['CategoryName'] ?: 'Umum';
                        $is_low      = (int)$p['Stock'] <= (int)$p['MinimumStock'];
                    ?>
                        <tr class="produk-row" data-category="<?= htmlspecialchars($cat_name) ?>" data-stock="<?= (int)$p['Stock'] ?>" data-name="<?= htmlspecialchars(strtolower($p['ProductName'])) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="trx-avatar"><?= strtoupper(substr($p['ProductName'], 0, 2)) ?></span>
                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($p['ProductName']) ?></span>
                                        <small class="text-muted"><?= htmlspecialchars($kode_produk) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($cat_name) ?></span></td>
                            <td class="text-end text-muted"><?= number_format($p['StokAwal'], 0, ',', '.') ?></td>
                            <td class="text-end <?= $p['Masuk'] > 0 ? 'text-success fw-semibold' : 'text-muted' ?>">+<?= number_format($p['Masuk'], 0, ',', '.') ?></td>
                            <td class="text-end <?= $p['Keluar'] > 0 ? 'text-danger fw-semibold' : 'text-muted' ?>">-<?= number_format($p['Keluar'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge-status <?= $is_low ? 'danger' : 'success' ?> fw-semibold">
                                    <?= (int)$p['Stock'] ?> <?= htmlspecialchars($p['Unit']) ?>
                                </span>
                            </td>
                            <td class="text-end text-muted">Rp <?= number_format($p['PurchasePrice'], 0, ',', '.') ?></td>
                            <td class="text-end fw-bold text-dark pe-4">Rp <?= number_format($p['Valuasi'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($product_list) ?></strong> produk</small>
        <small class="text-dark">Total Valuasi: <strong>Rp <?= number_format($total_nilai, 0, ',', '.') ?></strong></small>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
