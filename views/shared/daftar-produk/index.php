<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Daftar Produk</h4>
        <p class="text-muted mb-0 small">
            <?= $is_owner ? 'Kelola data inventaris, harga jual, kategori, dan pantau stok barang warung.' : 'Daftar harga jual, posisi rak penyimpanan, dan ketersediaan stok barang di toko.' ?>
        </p>
    </div>
    <?php if ($is_owner): ?>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary rounded-btn-sm d-flex align-items-center gap-1 bg-white" onclick="exportDataProduk()">
                <i data-lucide="download" class="icon-xs"></i> Export Excel
            </button>
            <button class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold" onclick="openAddProductModal()">
                <i data-lucide="plus" class="icon-xs"></i> Tambah Produk
            </button>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Total Produk -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Produk</span>
                <div class="metric-icon-box metric-icon-blue">
                    <i data-lucide="package" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark" id="stat-total-prod"><?= $total_produk ?> Item</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Semua terdaftar di katalog</small>
        </div>
    </div>

    <?php if ($is_owner): ?>
        <!-- Total Nilai Stok (Khusus Owner) -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card-modern p-3 h-100 metric-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Total Nilai Stok</span>
                    <div class="metric-icon-box metric-icon-green">
                        <i data-lucide="banknote" class="icon-sm"></i>
                    </div>
                </div>
                <span class="metric-value text-dark">Rp <?= number_format($total_nilai_stok, 0, ',', '.') ?></span>
                <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Estimasi modal barang</small>
            </div>
        </div>
    <?php else: ?>
        <!-- Total Kategori (Untuk Kasir) -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card-modern p-3 h-100 metric-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Kategori Barang</span>
                    <div class="metric-icon-box metric-icon-purple">
                        <i data-lucide="layers" class="icon-sm"></i>
                    </div>
                </div>
                <span class="metric-value text-dark"><?= $total_kategori ?> Kategori</span>
                <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Kelompok produk aktif</small>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stok Menipis -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Stok Menipis</span>
                <div class="metric-icon-box metric-icon-orange">
                    <i data-lucide="alert-triangle" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value <?= $stok_menipis > 0 ? 'text-danger' : 'text-dark' ?>"><?= $stok_menipis ?> Item</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;"><?= htmlspecialchars($produk_kritis_nama) ?></small>
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
                    <th class="ps-4">Produk</th>
                    <th>Kode</th>
                    <th>Kategori</th>
                    <?php if ($is_owner): ?>
                        <th class="text-end">Harga Modal</th>
                    <?php endif; ?>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-center <?= $is_owner ? '' : 'pe-4' ?>">Stok</th>
                    <?php if ($is_owner): ?>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="produk-table-body">
                <?php if (empty($product_list)): ?>
                    <tr>
                        <td colspan="<?= $is_owner ? 7 : 5 ?>" class="text-center py-4 text-muted">Belum ada data produk terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($product_list as $p):
                        $kode_produk = '#' . $p['ProductID'];
                        $cat_name    = $p['CategoryName'] ?: 'Umum';
                        $is_low      = (int)$p['Stock'] <= (int)$p['MinimumStock'];
                    ?>
                        <tr class="produk-row" data-category="<?= htmlspecialchars($cat_name) ?>" data-stock="<?= (int)$p['Stock'] ?>" data-min-stock="<?= (int)$p['MinimumStock'] ?>" data-name="<?= htmlspecialchars(strtolower($p['ProductName'])) ?>">
                            <td class="ps-4">
                                <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($p['ProductName']) ?></span>
                                <small class="text-muted">Satuan: <?= htmlspecialchars($p['Unit']) ?><?= !empty($p['Location']) ? ' | Lokasi: ' . htmlspecialchars($p['Location']) : '' ?></small>
                            </td>
                            <td>
                                <span class="trx-faktur"><?= htmlspecialchars($kode_produk) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($cat_name) ?></span>
                            </td>
                            <?php if ($is_owner): ?>
                                <td class="text-end text-muted">Rp <?= number_format($p['PurchasePrice'], 0, ',', '.') ?></td>
                            <?php endif; ?>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($p['SellingPrice'], 0, ',', '.') ?></td>
                            <td class="text-center <?= $is_owner ? '' : 'pe-4' ?>">
                                <span class="badge-status <?= $is_low ? 'danger' : 'success' ?> fw-semibold">
                                    <?= (int)$p['Stock'] ?> <?= htmlspecialchars($p['Unit']) ?>
                                </span>
                            </td>
                            <?php if ($is_owner): ?>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary rounded-btn-sm p-1" title="Edit Produk" onclick="editProduct(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i data-lucide="edit-3" class="icon-xs"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-btn-sm p-1" title="Hapus Produk" onclick="deleteProduct('<?= htmlspecialchars($p['ProductID'], ENT_QUOTES) ?>', '<?= htmlspecialchars($p['ProductName'], ENT_QUOTES) ?>')">
                                            <i data-lucide="trash-2" class="icon-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($product_list) ?></strong> produk</small>
    </div>
</div>

<?php if ($is_owner): ?>
    <?php include __DIR__ . '/modal-tambah-produk.php'; ?>
<?php endif; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
