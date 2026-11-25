<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Manajemen Akun Kasir</h4>
        <p class="text-muted mb-0 small">Kelola akun staf kasir, atur status operasional, dan pantau aktivitas transaksi.</p>
    </div>
    <button class="btn btn-sm btn-primary rounded-btn-sm d-inline-flex align-items-center gap-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKasir">
        <i data-lucide="user-plus" class="icon-xs"></i> Tambah Kasir
    </button>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Kartu 1: Total Staf Kasir -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Staf Kasir</span>
                <div class="metric-icon-box metric-icon-blue">
                    <i data-lucide="users" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark"><?= (int)($count_user['total_kasir'] ?? 0) ?> Kasir</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">
                <?= (int)($count_user['active_kasir'] ?? 0) ?> Aktif • <?= (int)($count_user['inactive_kasir'] ?? 0) ?> Non-Aktif
            </small>
        </div>
    </div>

    <!-- Kartu 2: Kasir Aktif -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Kasir Aktif</span>
                <div class="metric-icon-box metric-icon-green">
                    <i data-lucide="user-check" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark"><?= (int)($count_user['active_kasir'] ?? 0) ?> Staf</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Dapat login shift kasir</small>
        </div>
    </div>

    <!-- Kartu 3: Total Transaksi Kasir -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Transaksi Kasir</span>
                <div class="metric-icon-box metric-icon-purple">
                    <i data-lucide="receipt" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark"><?= number_format((int)($count_user['trx_bulan_ini'] ?? 0), 0, ',', '.') ?> Trx</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Dilayani staf bulan ini</small>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="akun-table-search" class="search-input-box" placeholder="Cari nama kasir..." oninput="filterAkunTable()">
        </div>
        <div class="d-flex flex-wrap gap-2">
            <select id="akun-filter-status" class="form-select form-select-sm border filter-select" onchange="filterAkunTable()">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Non-Aktif</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Nama Kasir</th>
                    <th>Username</th>
                    <th>Kontak</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Total Transaksi</th>
                    <th class="text-center pe-4" style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="akun-table-body">
                <?php if (empty($user_list)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada staf kasir terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($user_list as $u): ?>
                        <tr class="akun-row" data-name="<?= htmlspecialchars(strtolower($u['Fullname'])) ?>" data-status="<?= $u['IsActive'] ? 'active' : 'inactive' ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="trx-avatar"><?= strtoupper(substr($u['Fullname'], 0, 2)) ?></span>
                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($u['Fullname']) ?></span>
                                        <small class="text-muted">#<?= htmlspecialchars($u['UserID']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="trx-faktur"><?= htmlspecialchars($u['Username']) ?></span></td>
                            <td class="text-muted small"><?= htmlspecialchars($u['PhoneNumber'] ?: '-') ?></td>
                            <td class="text-center">
                                <span class="badge-status <?= $u['IsActive'] ? 'success' : 'danger' ?>">
                                    <i data-lucide="<?= $u['IsActive'] ? 'check' : 'x' ?>" class="icon-xs"></i>
                                    <?= $u['IsActive'] ? 'Aktif' : 'Non-Aktif' ?>
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark"><?= number_format((int)$u['TotalTransaksi'], 0, ',', '.') ?> Trx</td>
                            <td class="text-center pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button class="btn btn-sm btn-outline-secondary rounded-btn-sm p-1" title="Edit Kasir" onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-btn-sm p-1" title="Hapus Kasir" onclick="deleteCashier('<?= htmlspecialchars($u['UserID'], ENT_QUOTES) ?>', '<?= htmlspecialchars($u['Fullname'], ENT_QUOTES) ?>')">
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

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($user_list) ?></strong> staf kasir</small>
    </div>
</div>

<?php include __DIR__ . '/modal-tambah.php'; ?>
<?php include __DIR__ . '/modal-edit.php'; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
