<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="layers" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Kategori Produk</h4>
            </div>
            <p class="text-secondary mb-0 small">Kelola pengelompokan barang untuk kemudahan inventaris &amp; kasir</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-primary rounded-btn-sm rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="openAddCategoryModal()">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="plus" class="icon-xs"></i>
            </span>
            <span>Tambah Kategori</span>
        </button>
    </div>
</div>

<!-- Alert Notifikasi -->
<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Category Overview Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Kategori -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Kategori</span>
                <div class="metric-icon-box metric-icon-blue bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="layers" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value fs-4 fw-bold text-dark"><?= $total_kategori ?> Kategori</span>
            </div>
            <small class="text-secondary mt-2 d-block">Struktur inventaris warung</small>
        </div>
    </div>

    <!-- Card 2: Kategori Terbanyak -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Kategori Dominan</span>
                <div class="metric-icon-box metric-icon-green bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i data-lucide="trending-up" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value fs-4 fw-bold text-success text-truncate" title="<?= escape_html($kategori_terbanyak) ?>"><?= escape_html($kategori_terbanyak) ?></span>
            </div>
            <small class="text-secondary mt-2 d-block">Varian produk terbanyak</small>
        </div>
    </div>

    <!-- Card 3: Total Produk Terdaftar -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern metric-card bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Produk Terdaftar</span>
                <div class="metric-icon-box metric-icon-purple bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="package" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value fs-4 fw-bold text-primary"><?= $total_produk_terdaftar ?> Produk</span>
            </div>
            <small class="text-secondary mt-2 d-block">Tersebar dalam <?= $total_kategori ?> kategori</small>
        </div>
    </div>
</div>

<!-- Main Table Card with Filter Controls -->
<div class="card-modern bg-white border rounded-4 shadow-sm overflow-hidden">
    <!-- Table Filter Toolbar -->
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div>
            <h6 class="fw-bold text-dark mb-1">Daftar Kategori</h6>
            <small class="text-secondary">Cari, ubah, atau hapus kelompok produk</small>
        </div>

        <!-- Search Bar (asli) -->
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="kategori-table-search" class="search-input-box" placeholder="Cari nama kategori..." oninput="filterKategoriTable()">
        </div>
    </div>

    <!-- Data Table Responsive -->
    <div class="table-responsive">
        <table class="table table-modern table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">Nama Kategori</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kode</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Jumlah Produk</th>
                    <th class="text-center pe-4 py-3 text-secondary small text-uppercase fw-semibold" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategori-table-body">
                <?php if (empty($category_list)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-5">
                            <i data-lucide="layers" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada kategori ditambahkan.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($category_list as $c): ?>
                        <?php $kode_kategori = '#' . $c['CategoryID']; ?>
                        <tr class="kategori-row" data-name="<?= escape_html(strtolower($c['CategoryName'])) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="trx-avatar bg-primary bg-opacity-10 text-primary rounded-3 fw-bold small"><?= strtoupper(substr($c['CategoryName'], 0, 2)) ?></span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= escape_html($c['CategoryName']) ?></span>
                                        <small class="text-secondary">Kelompok Produk</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="trx-faktur"><?= escape_html($kode_kategori) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 fw-semibold">
                                    <?= (int)$c['ProductCount'] ?> Produk
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button class="btn btn-sm btn-outline-secondary rounded-btn-sm rounded-3 p-2" title="Edit Kategori" aria-label="Edit kategori" onclick="editCategory(<?= json_attr($c) ?>)">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-btn-sm rounded-3 p-2" title="Hapus Kategori" aria-label="Hapus kategori" onclick="deleteCategory('<?= escape_html($c['CategoryID']) ?>', '<?= escape_html($c['CategoryName']) ?>')">
                                        <i data-lucide="trash-2" class="icon-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Info -->
    <div class="p-3 border-top bg-light text-center rounded-bottom-4">
        <small class="text-secondary">Menampilkan <strong class="text-dark"><?= count($category_list) ?></strong> kategori produk aktif</small>
    </div>
</div>

<?php include __DIR__ . '/modal-form.php'; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>