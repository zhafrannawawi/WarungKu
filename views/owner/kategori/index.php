<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Kategori Produk</h4>
        <p class="text-muted mb-0 small">Kelola klasifikasi dan pengelompokan barang untuk kemudahan inventaris & kasir.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold" onclick="openAddCategoryModal()">
            <i data-lucide="plus" class="icon-xs"></i> Tambah Kategori
        </button>
    </div>
</div>

<!-- Alert Notifikasi -->
<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Category Overview Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Kategori -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Kategori</span>
                <div class="metric-icon-box metric-icon-blue">
                    <i data-lucide="layers" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark"><?= $total_kategori ?> Kategori</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Struktur inventaris</small>
        </div>
    </div>

    <!-- Card 2: Kategori Terbanyak -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Kategori Dominan</span>
                <div class="metric-icon-box metric-icon-green">
                    <i data-lucide="trending-up" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark"><?= htmlspecialchars($kategori_terbanyak) ?></span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Varian produk terbanyak</small>
        </div>
    </div>

    <!-- Card 3: Total Produk Terdaftar -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Produk Terdaftar</span>
                <div class="metric-icon-box metric-icon-purple">
                    <i data-lucide="package" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark"><?= $total_produk_terdaftar ?> Produk</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Dalam <?= $total_kategori ?> kategori</small>
        </div>
    </div>
</div>

<!-- Main Table Card with Filter Controls -->
<div class="card-modern">
    <!-- Table Filter Toolbar -->
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <!-- Search Bar -->
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="kategori-table-search" class="search-input-box" placeholder="Cari nama atau kode kategori..." oninput="filterKategoriTable()">
        </div>

        <!-- Quick Tool Info -->
        <div class="text-muted small">
            <span>Daftar kategori untuk memudahkan pengelompokan produk di kasir</span>
        </div>
    </div>

    <!-- Data Table Responsive -->
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Nama Kategori</th>
                    <th>Kode</th>
                    <th class="text-center">Jumlah Produk</th>
                    <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategori-table-body">
                <?php if (empty($category_list)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada kategori ditambahkan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($category_list as $c): ?>
                        <?php $kode_kategori = '#CAT-' . str_pad($c['CategoryID'], 3, '0', STR_PAD_LEFT); ?>
                        <tr class="kategori-row" data-name="<?= htmlspecialchars(strtolower($c['CategoryName'] . ' ' . $kode_kategori)) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="trx-avatar"><?= strtoupper(substr($c['CategoryName'], 0, 2)) ?></span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($c['CategoryName']) ?></span>
                                        <small class="text-muted">Kelompok Produk</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="trx-faktur"><?= $kode_kategori ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border fw-semibold">
                                    <?= (int)$c['ProductCount'] ?> Produk
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button class="btn btn-sm btn-outline-secondary rounded-btn-sm p-1" title="Edit Kategori" onclick="editCategory(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-btn-sm p-1" title="Hapus Kategori" onclick="deleteCategory(<?= $c['CategoryID'] ?>, '<?= htmlspecialchars($c['CategoryName'], ENT_QUOTES) ?>')">
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
    <div class="p-3 border-top d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 bg-white rounded-bottom">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">Menampilkan <strong><?= count($category_list) ?></strong> kategori</small>
        </div>
    </div>
</div>

<!-- Form Hapus Hidden -->
<form id="form-delete-kategori" method="POST" action="/owner/kategori" style="display:none;">
    <input type="hidden" name="action" value="hapus">
    <input type="hidden" name="category_id" id="delete-category-id">
</form>

<?php include __DIR__ . '/modal-form.php'; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>