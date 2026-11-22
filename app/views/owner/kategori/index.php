<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Kategori Produk</h4>
        <p class="text-muted mb-0 small">Kelola klasifikasi dan pengelompokan barang untuk kemudahan inventaris & POS.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold" onclick="openAddCategoryModal()">
            <i data-lucide="plus" class="icon-xs"></i> Tambah Kategori
        </button>
    </div>
</div>

<!-- Category Overview Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Kategori</span>
                <div class="metric-icon-box metric-icon-blue">
                    <i data-lucide="layers" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark">6 Kategori</span>
                <span class="badge-status success"><i data-lucide="check" class="icon-xs"></i> Semua Aktif</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Struktur inventaris</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Kategori Terlaris</span>
                <div class="metric-icon-box metric-icon-green">
                    <i data-lucide="trending-up" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark">Sembako</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">182 item terjual</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Produk Terdaftar</span>
                <div class="metric-icon-box metric-icon-purple">
                    <i data-lucide="package" class="icon-sm"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark">6 Produk</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Dalam 6 kategori</small>
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
            <span>Klik nama kategori untuk melihat produk terkait di menu POS</span>
        </div>
    </div>

    <!-- Data Table Responsive -->
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Nama Kategori</th>
                    <th class="ps-4">Kode</th>
                    <th class="text-center">Jumlah Produk</th>
                    <th class="text-center " style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategori-table-body">
                <?php if (empty($user_list)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada akun terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kategori_list as $c): ?>
                        <tr class="kategori-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 bg-primary-subtle text-primary rounded-2">
                                        <i data-lucide="box" class="icon-sm"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($c['CategoryName']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="trx-faktur"><?= htmlspecialchars($c['CategoryID'])?></span></td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border fw-semibold">2 Produk</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button class="btn btn-sm btn-outline-secondary rounded-btn-sm p-1" title="Edit Kategori" onclick="editCategory(1)">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-btn-sm p-1" title="Hapus Kategori" onclick="deleteCategory(1)">
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

    <!-- Pagination & Total Rows Info Footer -->
    <div class="p-3 border-top d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 bg-white rounded-bottom">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">Menampilkan <strong>1 - 6</strong> dari <strong>6</strong> kategori</small>
        </div>
        <nav aria-label="Navigasi Halaman Kategori">
            <ul class="pagination pagination-clean mb-0">
                <li class="page-item disabled"><a class="page-link" href="#" aria-label="Sebelumnya">«</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#" aria-label="Berikutnya">»</a></li>
            </ul>
        </nav>
    </div>
</div>