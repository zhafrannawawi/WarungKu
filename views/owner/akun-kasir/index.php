<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Manajemen Akun Kasir</h4>
        <p class="text-muted mb-0 small">Kelola hak akses kasir, buat akun baru, atur status staf dan reset kata sandi.</p>
    </div>
    <button class="btn btn-sm btn-primary rounded-btn-sm align-items-center gap-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKasir">
        <i data-lucide="user-plus" class="icon-xs"></i> Tambah Akun
    </button>
</div>

<!-- Alert Notifikasi -->
<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Account Overview Cards (4 Kartu Ringkasan) -->
<div class="row g-3 mb-4">

    <!-- Kartu 1: Total Pengguna -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Pengguna</span>
                <div class="metric-icon-box metric-icon-blue">
                    <i data-lucide="users" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark"><?= $count_user['total'] ?? 0 ?> Akun</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">
                <?= $count_user['owner_count'] ?? 1 ?> Owner, <?= $count_user['cashier_count'] ?? 0 ?> Kasir
            </small>
        </div>
    </div>

    <!-- Kartu 2: Kasir Aktif -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Kasir Aktif</span>
                <div class="metric-icon-box metric-icon-green">
                    <i data-lucide="user-check" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark"><?= $count_user['cashier_count'] ?? 0 ?> Staf</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Shift operasional</small>
        </div>
    </div>

    <!-- Kartu 3: Total Transaksi Staf -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Transaksi Staf</span>
                <div class="metric-icon-box metric-icon-purple">
                    <i data-lucide="receipt" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark">0 Trx</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Bulan ini</small>
        </div>
    </div>

    <!-- Kartu 4: Staf Terproduktif -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Staf Terproduktif</span>
                <div class="metric-icon-box metric-icon-orange">
                    <i data-lucide="award" class="icon-sm"></i>
                </div>
            </div>
            <span class="metric-value text-dark" style="font-size: 1.1rem;">-</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">0 Transaksi</small>
        </div>
    </div>

</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm">

    <!-- Card Header: Search & Filter -->
    <div class="card-header bg-white d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 py-3">
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="akun-table-search" class="search-input-box" placeholder="Cari nama, username, role..." oninput="filterAkunTable()">
        </div>
        <div class="d-flex flex-wrap gap-2">
            <select id="akun-filter-role" class="form-select form-select-sm border filter-select" onchange="filterAkunTable()">
                <option value="all">Semua Role</option>
                <option value="owner">Owner (Admin)</option>
                <option value="kasir">Kasir (Staff)</option>
            </select>
            <select id="akun-filter-status" class="form-select form-select-sm border filter-select" onchange="filterAkunTable()">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Non-Aktif</option>
            </select>
        </div>
    </div>

    <!-- Card Body: Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Pengguna</th>
                        <th>Username</th>
                        <th>Kontak</th>
                        <th>Hak Akses</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Total Transaksi</th>
                        <th class="text-center pe-4" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="akun-table-body">
                    <?php if (empty($user_list)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada akun terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($user_list as $u): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="trx-avatar"><?= strtoupper(substr($u['Fullname'], 0, 2)) ?></span>
                                        <div>
                                            <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($u['Fullname']) ?></span>
                                            <small class="text-muted"><?= $u['Role'] === 'owner' ? 'Pemilik Warung' : 'Staf Kasir' ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="trx-faktur"><?= htmlspecialchars($u['Username']) ?></span></td>
                                <td class="text-muted small"><?= htmlspecialchars($u['PhoneNumber'] ?? '-') ?></td>
                                <td>
                                    <span class="badge <?= $u['Role'] === 'owner' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-primary-subtle text-primary' ?> border fw-semibold">
                                        <?= ucfirst($u['Role']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-status <?= $u['IsActive'] ? 'success' : 'danger' ?>">
                                        <i data-lucide="<?= $u['IsActive'] ? 'check' : 'x' ?>" class="icon-xs"></i>
                                        <?= $u['IsActive'] ? 'Aktif' : 'Non-Aktif' ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dark">-</td>
                                <td class="text-center pe-4">
                                    <button class="btn btn-sm btn-outline-warning rounded-btn-sm p-1" title="Edit Akun"
                                        onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($u)) ?>)">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/modal-tambah.php'; ?>
<?php include __DIR__ . '/modal-edit.php'; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
