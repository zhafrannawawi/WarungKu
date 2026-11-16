<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Manajemen Akun Kasir</h4>
        <p class="text-muted mb-0 small">Kelola hak akses kasir, buat akun baru, atur status staf dan reset kata sandi.</p>
    </div>
    <button class="btn btn-sm btn-primary rounded-btn-sm align-items-center gap-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalFormAkun">
        <i data-lucide="user-plus" class="icon-xs"></i> Tambah Akun
    </button>
</div>

<!-- Account Overview Cards -->
<div class="row g-3 mb-4">
    <?php
    $metric_cards = [
        [
            'label' => 'Total Pengguna',
            'icon'  => 'users',
            'color' => 'metric-icon-blue',
            'value' => $count_user['total'] . ' Akun',
            'sub'   => $count_user['owner_count'] . ' Owner, ' . $count_user['cashier_count'] . ' Kasir',
        ],
        [
            'label' => 'Kasir Aktif',
            'icon'  => 'user-check',
            'color' => 'metric-icon-green',
            'value' => $count_user['cashier_count'] . ' Staf',
            'sub'   => 'Shift operasional',
        ],
        [
            'label' => 'Total Transaksi Staf',
            'icon'  => 'receipt',
            'color' => 'metric-icon-purple',
            'value' => '142 Trx',
            'sub'   => 'Bulan ini',
        ],
        [
            'label'       => 'Staf Terproduktif',
            'icon'        => 'award',
            'color'       => 'metric-icon-orange',
            'value'       => 'Siti Aminah',
            'sub'         => '98 Transaksi',
            'value_style' => 'font-size:1.1rem;',
        ],
    ];
    foreach ($metric_cards as $card): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-modern p-3 h-100 metric-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium"><?= $card['label'] ?></span>
                    <div class="metric-icon-box <?= $card['color'] ?>">
                        <i data-lucide="<?= $card['icon'] ?>" class="icon-sm"></i>
                    </div>
                </div>
                <span class="metric-value text-dark" <?= isset($card['value_style']) ? 'style="' . $card['value_style'] . '"' : '' ?>><?= $card['value'] ?></span>
                <small class="text-muted mt-2 d-block" style="font-size:.725rem;"><?= $card['sub'] ?></small>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require APP_PATH . '/views/components/alerts/alert_message.php'; ?>

<!-- Main Table Card -->
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
                                        onclick="openEditModal('<?= $u['UserID'] ?>','<?= htmlspecialchars($u['Fullname'], ENT_QUOTES) ?>','<?= htmlspecialchars($u['Username'], ENT_QUOTES) ?>','<?= htmlspecialchars($u['PhoneNumber'] ?? '', ENT_QUOTES) ?>','<?= $u['IsActive'] ?>')">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                    <?php if ($u['Role'] !== 'owner'): ?>
                                        <button class="btn btn-sm btn-outline-danger rounded-btn-sm p-1 ms-1" title="Hapus Akun"
                                            onclick="openDeleteModal('<?= $u['UserID'] ?>','<?= htmlspecialchars($u['Fullname'], ENT_QUOTES) ?>')">
                                            <i data-lucide="trash-2" class="icon-xs"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="card-footer bg-white text-muted small py-2 px-3">
        Total <?= count($user_list) ?> akun terdaftar
    </div>

</div>

<?php require APP_PATH . '/views/owner/akun-kasir/modals/modal_add_cashier.php'; ?>
<?php require APP_PATH . '/views/owner/akun-kasir/modals/modal_edit_cashier.php'; ?>