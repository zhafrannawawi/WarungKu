<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Manajemen Akun Kasir -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="users" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Manajemen Akun Kasir</h4>
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1" style="font-size: 0.7rem;">Staf</span>
            </div>
            <p class="text-secondary mb-0 small">Kelola akun staf kasir, atur status operasional, dan pantau aktivitas transaksi.</p>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKasir">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="user-plus" class="icon-xs"></i>
            </span>
            <span>Tambah Kasir</span>
        </button>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: 3 Kartu Metrik Staf Kasir -->
<div class="row g-3 mb-4">
    <!-- Kartu 1: Total Staf Kasir -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Staf Kasir</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="users" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= (int)($count_user['total_kasir'] ?? 0) ?> Kasir
            </div>

            <small class="text-secondary mt-2 d-block">
                <span class="text-success fw-semibold"><?= (int)($count_user['active_kasir'] ?? 0) ?> Aktif</span>
                <span class="mx-1">·</span>
                <span class="text-danger fw-semibold"><?= (int)($count_user['inactive_kasir'] ?? 0) ?> Non-Aktif</span>
            </small>
        </div>
    </div>

    <!-- Kartu 2: Kasir Aktif -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Kasir Aktif</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i data-lucide="user-check" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-success">
                <?= (int)($count_user['active_kasir'] ?? 0) ?> Staf
            </div>

            <small class="text-secondary mt-2 d-block">
                Dapat login shift kasir
            </small>
        </div>
    </div>

    <!-- Kartu 3: Total Transaksi Kasir -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Transaksi Kasir</span>
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                    <i data-lucide="receipt" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= number_format((int)($count_user['trx_bulan_ini'] ?? 0), 0, ',', '.') ?> Trx
            </div>

            <small class="text-secondary mt-2 d-block">
                Dilayani staf bulan ini
            </small>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Daftar Kasir -->
<div class="bg-white border rounded-4 shadow-sm overflow-hidden">

    <!-- Header: Judul + Pencarian + Filter -->
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div>
            <h6 class="fw-bold text-dark mb-1">Daftar Staf Kasir</h6>
            <small class="text-secondary">Cari, filter, dan kelola akun kasir warung</small>
        </div>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
            <div class="input-group input-group-sm" style="min-width: 240px;">
                <span class="input-group-text bg-light border-0 text-secondary ps-3">
                    <i data-lucide="search" width="16" height="16"></i>
                </span>
                <input type="text" id="akun-table-search" class="form-control bg-light border-0 py-2" placeholder="Cari nama kasir..." oninput="filterAkunTable()">
            </div>

            <select id="akun-filter-status" class="form-select form-select-sm border-0 bg-light text-dark fw-medium rounded-3 ps-3 py-2 w-auto" style="padding-right: 2.25rem;" onchange="filterAkunTable()">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Non-Aktif</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">Nama Kasir</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Username</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kontak</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Status</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Total Transaksi</th>
                    <th class="text-center pe-4 py-3 text-secondary small text-uppercase fw-semibold" style="width: 120px;">Aksi</th>
                </tr>
            </thead>

            <tbody id="akun-table-body">
                <?php if (empty($user_list)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-5">
                            <i data-lucide="users" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada staf kasir terdaftar.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($user_list as $u): ?>
                        <tr class="akun-row" data-name="<?= htmlspecialchars(strtolower($u['Fullname'])) ?>" data-status="<?= $u['IsActive'] ? 'active' : 'inactive' ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-3 fw-bold small d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                        <?= strtoupper(substr($u['Fullname'], 0, 2)) ?>
                                    </span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block">
                                            <?= htmlspecialchars($u['Fullname']) ?>
                                        </span>
                                        <small class="text-secondary">#<?= htmlspecialchars($u['UserID']) ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                    <?= htmlspecialchars($u['Username']) ?>
                                </span>
                            </td>

                            <td class="text-secondary small">
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i data-lucide="phone" width="13" height="13"></i>
                                    <?= htmlspecialchars($u['PhoneNumber'] ?: '-') ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <?php if ($u['IsActive']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-semibold d-inline-flex align-items-center gap-1">
                                        <i data-lucide="check" width="13" height="13"></i>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fw-semibold d-inline-flex align-items-center gap-1">
                                        <i data-lucide="x" width="13" height="13"></i>
                                        Non-Aktif
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end fw-bold text-dark">
                                <?= number_format((int)$u['TotalTransaksi'], 0, ',', '.') ?> Trx
                            </td>

                            <td class="text-center pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit Kasir" onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i data-lucide="edit-3" class="icon-xs"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus Kasir" onclick="deleteCashier('<?= htmlspecialchars($u['UserID'], ENT_QUOTES) ?>', '<?= htmlspecialchars($u['Fullname'], ENT_QUOTES) ?>')">
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

    <!-- Footer -->
    <div class="p-3 border-top bg-light text-center">
        <small class="text-secondary">
            Menampilkan <strong class="text-dark"><?= count($user_list) ?></strong> staf kasir terdaftar
        </small>
    </div>

</div>

<?php include __DIR__ . '/modal-tambah.php'; ?>
<?php include __DIR__ . '/modal-edit.php'; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>