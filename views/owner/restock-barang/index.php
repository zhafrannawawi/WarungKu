<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Restock Barang -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="package-plus" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Restock Barang</h4>
            </div>
            <p class="text-secondary mb-0 small">Catat barang masuk dan pantau riwayat restock stok dagangan</p>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-white bg-white border-0 text-secondary fw-medium rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2" onclick="exportDataRestock()">
            <span class="bg-primary bg-opacity-10 text-primary rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="download" class="icon-xs"></i>
            </span>
            <span>Export Excel</span>
        </button>
        <button class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRestock">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="plus" class="icon-xs"></i>
            </span>
            <span>Catat Restock Baru</span>
        </button>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Pengeluaran Beli Hari Ini</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="wallet" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= format_rupiah($total_beli_hr) ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= count($today_list) ?> restock masuk hari ini
            </small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Barang Masuk Hari Ini</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i data-lucide="package-plus" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-success">
                <?= format_number($unit_masuk_hr) ?> Unit
            </div>

            <small class="text-secondary mt-2 d-block">
                Stok langsung diperbarui
            </small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Stok Hampir Habis</span>
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                    <i data-lucide="alert-triangle" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold <?= count($low_stock) > 0 ? 'text-danger' : 'text-dark' ?>">
                <?= count($low_stock) ?> Item
            </div>

            <small class="text-secondary mt-2 d-block text-truncate">
                <?= !empty($low_stock) ? escape_html($low_stock[0]['ProductName']) . ' (sisa ' . (int)$low_stock[0]['Stock'] . ')' : 'Semua stok aman' ?>
            </small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Restock Bulan Ini</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="calendar-check" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= $jml_bulan_ini ?> Kali
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= date('M Y') ?>
            </small>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white border rounded-4 shadow-sm overflow-hidden">

    <!-- Header kartu -->
    <div class="p-3 border-bottom">
        <h6 class="fw-bold text-dark mb-1">Riwayat Restock Barang</h6>
        <small class="text-secondary">Catatan barang masuk ke stok warung</small>
    </div>

    <!-- Toolbar: pencarian (kiri) + filter pill (kanan) -->
    <div class="px-3 py-3 border-bottom d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-2 gap-md-3">

        <!-- Pencarian pill -->
        <div class="position-relative flex-grow-1" style="max-width: 360px; min-width: 220px;">
            <span class="position-absolute top-50 translate-middle-y text-secondary d-inline-flex" style="left: 16px; pointer-events: none;">
                <i data-lucide="search" width="16" height="16"></i>
            </span>
            <input type="text"
                id="restock-table-search"
                class="form-control rounded-pill bg-white"
                style="padding-left: 2.6rem !important; height: 40px; font-size: 0.875rem;"
                placeholder="Cari nama produk..."
                oninput="filterRestockTable()">
        </div>

        <!-- Filter pill + reset -->
        <div class="d-flex flex-nowrap align-items-center gap-2">
            <select id="restock-filter-periode"
                class="form-select restock-pill-select rounded-pill px-3 fw-medium text-secondary flex-grow-1"
                style="min-width: 160px; height: 40px; font-size: 0.875rem;"
                onchange="filterRestockTable()">
                <option value="all">Semua Periode</option>
                <option value="today">Hari Ini</option>
                <option value="7days">7 Hari Terakhir</option>
                <option value="30days">Bulan Ini</option>
            </select>

            <button class="btn restock-pill-btn text-secondary rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0 p-0"
                style="width: 40px; height: 40px;"
                title="Reset Filter"
                onclick="resetRestockFilter()">
                <i data-lucide="rotate-ccw" width="16" height="16"></i>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">No. Restock</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Tanggal &amp; Waktu</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Nama Barang</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Qty Masuk</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Total Beli</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Dicatat Oleh</th>
                    <th class="pe-4 py-3 text-secondary small text-uppercase fw-semibold">Catatan</th>
                </tr>
            </thead>
            <tbody id="tabel-riwayat-restock">
                <?php if (empty($restock_list)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-5">
                            <i data-lucide="package-open" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada riwayat restock barang.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($restock_list as $r):
                        $total_beli = (float)$r['PurchasePrice'] * (int)$r['Quantity'];
                        $tgl        = substr($r['RestockDate'], 0, 10);
                    ?>
                        <tr class="restock-row"
                            data-produk="<?= escape_html(strtolower($r['ProductName'] ?? '')) ?>"
                            data-date="<?= escape_html($tgl) ?>">
                            <td class="ps-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                    #<?= escape_html($r['RestockID']) ?>
                                </span>
                            </td>

                            <td>
                                <span class="fw-medium text-dark d-block"><?= date('d M Y', strtotime($r['RestockDate'])) ?></span>
                                <small class="text-secondary"><?= date('H:i', strtotime($r['RestockDate'])) ?> WIB</small>
                            </td>

                            <td>
                                <span class="fw-semibold text-dark d-block"><?= escape_html($r['ProductName'] ?? '-') ?></span>
                                <small class="text-secondary">#<?= escape_html($r['ProductID']) ?> • @<?= format_rupiah($r['PurchasePrice']) ?></small>
                            </td>

                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-semibold">
                                    +<?= (int)$r['Quantity'] ?> <?= escape_html($r['Unit'] ?? 'pcs') ?>
                                </span>
                            </td>

                            <td class="text-end fw-bold text-dark"><?= format_rupiah($total_beli) ?></td>

                            <td class="fw-medium text-dark small"><?= escape_html($r['CashierName'] ?? '-') ?></td>

                            <td class="text-secondary small pe-4"><?= escape_html($r['Note'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer (center) -->
    <div class="p-3 border-top bg-light text-center rounded-bottom-4">
        <small class="text-secondary">
            Menampilkan <strong class="text-dark"><?= count($restock_list) ?></strong> riwayat restock
        </small>
    </div>

</div>

<?php include __DIR__ . '/modal-restock.php'; ?>
<?php include __DIR__ . '/../../partials/footer.php'; ?>