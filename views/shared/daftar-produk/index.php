<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Daftar Produk -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="package" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Daftar Produk</h4>
            </div>
            <p class="text-secondary mb-0 small">
                <?= $is_owner ? 'Kelola data inventaris, harga jual, kategori, dan pantau stok barang warung.' : 'Daftar harga jual, posisi rak penyimpanan, dan ketersediaan stok barang di toko.' ?>
            </p>
        </div>
    </div>

    <?php if ($is_owner): ?>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn bg-white border-0 text-secondary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="exportDataProduk()">
                <span class="bg-secondary bg-opacity-10 text-secondary rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i data-lucide="download" class="icon-xs"></i>
                </span>
                <span>Export Excel</span>
            </button>
            <button type="button" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="openAddProductModal()">
                <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i data-lucide="plus" class="icon-xs"></i>
                </span>
                <span>Tambah Produk</span>
            </button>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: 3 Kartu Metrik Produk -->
<div class="row g-3 mb-4">
    <!-- Total Produk -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Produk</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="package" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark" id="stat-total-prod">
                <?= $total_produk ?> Item
            </div>

            <small class="text-secondary mt-2 d-block">
                Semua terdaftar di katalog
            </small>
        </div>
    </div>

    <?php if ($is_owner): ?>
        <!-- Total Nilai Stok (Khusus Owner) -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary fw-medium">Total Nilai Stok</span>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                        <i data-lucide="banknote" width="20" height="20"></i>
                    </div>
                </div>

                <div class="fs-4 fw-bold text-dark">
                    <?= format_rupiah($total_nilai_stok) ?>
                </div>

                <small class="text-secondary mt-2 d-block">
                    Estimasi modal barang
                </small>
            </div>
        </div>
    <?php else: ?>
        <!-- Total Kategori (Untuk Kasir) -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary fw-medium">Kategori Barang</span>
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                        <i data-lucide="layers" width="20" height="20"></i>
                    </div>
                </div>

                <div class="fs-4 fw-bold text-dark">
                    <?= $total_kategori ?> Kategori
                </div>

                <small class="text-secondary mt-2 d-block">
                    Kelompok produk aktif
                </small>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stok Menipis -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Stok Menipis</span>
                <div class="bg-warning bg-opacity-10 text-warning-emphasis rounded-3 p-2">
                    <i data-lucide="alert-triangle" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold <?= $stok_menipis > 0 ? 'text-danger' : 'text-dark' ?>">
                <?= $stok_menipis ?> Item
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= escape_html($produk_kritis_nama) ?>
            </small>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Daftar Produk -->
<div class="bg-white border rounded-4 shadow-sm overflow-hidden">

    <!-- Header kartu -->
    <div class="p-3 border-bottom">
        <h6 class="fw-bold text-dark mb-1">Katalog Produk</h6>
        <small class="text-secondary">Cari, filter, dan pantau stok barang warung</small>
    </div>

    <!-- Toolbar: pencarian (kiri) + filter pill (kanan) -->
    <div class="px-3 py-3 border-bottom d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-2 gap-lg-3">

        <!-- Pencarian pill -->
        <div class="position-relative flex-grow-1" style="max-width: 360px; min-width: 220px;">
            <span class="position-absolute top-50 translate-middle-y text-secondary d-inline-flex" style="left: 16px; pointer-events: none;">
                <i data-lucide="search" width="16" height="16"></i>
            </span>
            <input type="text"
                id="produk-table-search"
                class="form-control rounded-pill bg-white"
                style="padding-left: 2.6rem !important; height: 40px; font-size: 0.875rem;"
                placeholder="Cari nama produk..."
                oninput="filterProdukTable()">
        </div>

        <!-- Filter pill + reset -->
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2">
            <select id="produk-filter-category"
                class="form-select produk-pill-select rounded-pill px-3 fw-medium text-secondary flex-grow-1"
                style="min-width: 160px; height: 40px; font-size: 0.875rem;"
                onchange="filterProdukTable()">
                <option value="all">Semua Kategori</option>
                <?php foreach ($category_list as $c): ?>
                    <option value="<?= escape_html($c['CategoryName']) ?>"><?= escape_html($c['CategoryName']) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="produk-filter-stock"
                class="form-select produk-pill-select rounded-pill px-3 fw-medium text-secondary flex-grow-1"
                style="min-width: 180px; height: 40px; font-size: 0.875rem;"
                onchange="filterProdukTable()">
                <option value="all">Semua Status Stok</option>
                <option value="in_stock">Stok Aman</option>
                <option value="low_stock">Stok Menipis</option>
            </select>

            <button type="button"
                class="btn produk-pill-btn text-secondary rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0 p-0"
                style="width: 40px; height: 40px;"
                title="Reset Filter"
                onclick="resetProdukFilter()">
                <i data-lucide="rotate-ccw" width="16" height="16"></i>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">Produk</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kode</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kategori</th>
                    <?php if ($is_owner): ?>
                        <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Harga Modal</th>
                    <?php endif; ?>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Harga Jual</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold <?= $is_owner ? '' : 'pe-4' ?>">Stok</th>
                    <?php if ($is_owner): ?>
                        <th class="text-center pe-4 py-3 text-secondary small text-uppercase fw-semibold" style="width: 120px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody id="produk-table-body">
                <?php if (empty($product_list)): ?>
                    <tr>
                        <td colspan="<?= $is_owner ? 7 : 5 ?>" class="text-center text-secondary py-5">
                            <i data-lucide="package-x" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada data produk terdaftar.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($product_list as $p):
                        $kode_produk = '#' . $p['ProductID'];
                        $cat_name    = $p['CategoryName'] ?: 'Umum';
                        $is_low      = (int)$p['Stock'] <= (int)$p['MinimumStock'];
                    ?>
                        <tr class="produk-row" data-category="<?= escape_html($cat_name) ?>" data-stock="<?= (int)$p['Stock'] ?>" data-min-stock="<?= (int)$p['MinimumStock'] ?>" data-name="<?= escape_html(strtolower($p['ProductName'])) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-3 fw-bold small d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                        <?= strtoupper(substr($p['ProductName'], 0, 2)) ?>
                                    </span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= escape_html($p['ProductName']) ?></span>
                                        <small class="text-secondary">Satuan: <?= escape_html($p['Unit']) ?><?= !empty($p['Location']) ? ' | Lokasi: ' . escape_html($p['Location']) : '' ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                    <?= escape_html($kode_produk) ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    <?= escape_html($cat_name) ?>
                                </span>
                            </td>

                            <?php if ($is_owner): ?>
                                <td class="text-end text-secondary">
                                    <?= format_rupiah($p['PurchasePrice']) ?>
                                </td>
                            <?php endif; ?>

                            <td class="text-end fw-bold text-dark">
                                <?= format_rupiah($p['SellingPrice']) ?>
                            </td>

                            <td class="text-center <?= $is_owner ? '' : 'pe-4' ?>">
                                <?php if ($is_low): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fw-semibold">
                                        <?= (int)$p['Stock'] ?> <?= escape_html($p['Unit']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-semibold">
                                        <?= (int)$p['Stock'] ?> <?= escape_html($p['Unit']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <?php if ($is_owner): ?>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit Produk" onclick="editProduct(<?= json_attr($p) ?>)">
                                            <i data-lucide="edit-3" class="icon-xs"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus Produk" onclick="deleteProduct('<?= escape_html($p['ProductID']) ?>', '<?= escape_html($p['ProductName']) ?>')">
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

    <!-- Footer (center) -->
    <div class="p-3 border-top bg-light text-center rounded-bottom-4">
        <small class="text-secondary">
            Menampilkan <strong class="text-dark"><?= count($product_list) ?></strong> produk
        </small>
    </div>

</div>

<?php if ($is_owner): ?>
    <?php include __DIR__ . '/modal-tambah-produk.php'; ?>
<?php endif; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>