<?php include __DIR__ . '/../../partials/header.php'; ?>

<style>
    /* Pencarian pill: efek fokus senada warna primary dashboard */
    #produk-table-search:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
        background-color: #fff;
    }
    /* Filter pill */
    .produk-pill-select {
        background-color: #f1f3f5;
        border: 1px solid transparent;
        transition: background-color .15s ease, border-color .15s ease;
    }
    .produk-pill-select:hover { background-color: #e9ecef; }
    .produk-pill-select:focus {
        background-color: #fff;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
    }
    .produk-pill-btn {
        background-color: #f1f3f5;
        border: 1px solid transparent;
        transition: background-color .15s ease;
    }
    .produk-pill-btn:hover { background-color: #e9ecef; }

    /* Filter tanggal: satu kapsul putih berisi dua input */
    .laporan-date-group {
        background-color: #fff;
        border: 1px solid transparent;
        transition: box-shadow .15s ease, border-color .15s ease;
    }
    .laporan-date-group:focus-within {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
    }
    .laporan-date-group input[type="date"] {
        border: 0;
        background: transparent;
        box-shadow: none;
        font-size: 0.875rem;
        height: 36px;
        padding: 0 .35rem;
        width: auto;
        color: #495057;
    }
    .laporan-date-group input[type="date"]:focus {
        outline: none;
        box-shadow: none;
    }

    /* Header tabel: angka lebih rapi */
    .laporan-table td, .laporan-table th { white-space: nowrap; }
    .laporan-num { font-variant-numeric: tabular-nums; }
</style>

<!-- Page Header: Laporan Stok & Inventaris -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="clipboard-list" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Laporan Stok & Inventaris</h4>
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1" style="font-size: 0.7rem;">Audit</span>
            </div>
            <p class="text-secondary mb-0 small">Audit perputaran persediaan barang, stok masuk/keluar, dan estimasi valuasi aset.</p>
        </div>
    </div>

    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
        <!-- Rentang tanggal -->
        <div class="laporan-date-group d-inline-flex align-items-center gap-1 rounded-3 shadow-sm px-2">
            <span class="text-secondary d-inline-flex ps-1">
                <i data-lucide="calendar" width="16" height="16"></i>
            </span>
            <input type="date" name="from" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
            <span class="text-secondary small fw-medium">s/d</span>
            <input type="date" name="to" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="filter" class="icon-xs"></i>
            </span>
            <span>Filter</span>
        </button>

        <button type="button" class="btn bg-white border-0 text-secondary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="exportDataProduk()">
            <span class="bg-secondary bg-opacity-10 text-secondary rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="download" class="icon-xs"></i>
            </span>
            <span>Export</span>
        </button>
    </form>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: 4 Kartu Metrik -->
<div class="row g-3 mb-4">
    <!-- Total Nilai Persediaan -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Nilai Persediaan</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i data-lucide="wallet" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                Rp <?= number_format($total_nilai, 0, ',', '.') ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= count($product_list) ?> SKU produk aktif
            </small>
        </div>
    </div>

    <!-- Total Kuantitas Fisik -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Kuantitas Fisik</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="package" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= number_format($total_fisik, 0, ',', '.') ?> Unit
            </div>

            <small class="text-secondary mt-2 d-block">
                Semua gudang/etalase
            </small>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Stok Menipis</span>
                <div class="bg-warning bg-opacity-10 text-warning-emphasis rounded-3 p-2">
                    <i data-lucide="alert-triangle" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold <?= $stok_menipis > 0 ? 'text-danger' : 'text-dark' ?>">
                <?= $stok_menipis ?> SKU
            </div>

            <small class="text-secondary mt-2 d-block">
                Di bawah batas minimum
            </small>
        </div>
    </div>

    <!-- Total Barang Keluar -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Barang Keluar</span>
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                    <i data-lucide="shopping-bag" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= number_format($total_keluar, 0, ',', '.') ?> Unit
            </div>

            <small class="text-secondary mt-2 d-block">
                Terjual ke konsumen
            </small>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Laporan -->
<div class="bg-white border rounded-4 shadow-sm overflow-hidden">

    <!-- Header kartu -->
    <div class="p-3 border-bottom">
        <h6 class="fw-bold text-dark mb-1">Rincian Mutasi Stok</h6>
        <small class="text-secondary">Pantau stok awal, barang masuk, barang keluar, dan valuasi aset per produk</small>
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
                    <option value="<?= htmlspecialchars($c['CategoryName']) ?>"><?= htmlspecialchars($c['CategoryName']) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="produk-filter-stock"
                    class="form-select produk-pill-select rounded-pill px-3 fw-medium text-secondary flex-grow-1"
                    style="min-width: 180px; height: 40px; font-size: 0.875rem;"
                    onchange="filterProdukTable()">
                <option value="all">Semua Status Stok</option>
                <option value="in_stock">Stok Tersedia (>10)</option>
                <option value="low_stock">Stok Menipis (≤10)</option>
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
        <table class="table table-hover align-middle mb-0 laporan-table">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">Nama Barang</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kategori</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Stok Awal</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Masuk</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Keluar</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Sisa Stok</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Harga Modal</th>
                    <th class="text-end pe-4 py-3 text-secondary small text-uppercase fw-semibold">Valuasi Aset</th>
                </tr>
            </thead>

            <tbody id="produk-table-body">
                <?php if (empty($product_list)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-5">
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
                        <tr class="produk-row" data-category="<?= htmlspecialchars($cat_name) ?>" data-stock="<?= (int)$p['Stock'] ?>" data-min-stock="<?= (int)$p['MinimumStock'] ?>" data-name="<?= htmlspecialchars(strtolower($p['ProductName'])) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-3 fw-bold small d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                        <?= strtoupper(substr($p['ProductName'], 0, 2)) ?>
                                    </span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($p['ProductName']) ?></span>
                                        <small class="text-secondary">Kode: <?= htmlspecialchars($kode_produk) ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    <?= htmlspecialchars($cat_name) ?>
                                </span>
                            </td>

                            <td class="text-end text-secondary laporan-num">
                                <?= number_format($p['StokAwal'], 0, ',', '.') ?>
                            </td>

                            <td class="text-end laporan-num <?= $p['Masuk'] > 0 ? 'text-success fw-semibold' : 'text-secondary' ?>">
                                +<?= number_format($p['Masuk'], 0, ',', '.') ?>
                            </td>

                            <td class="text-end laporan-num <?= $p['Keluar'] > 0 ? 'text-danger fw-semibold' : 'text-secondary' ?>">
                                -<?= number_format($p['Keluar'], 0, ',', '.') ?>
                            </td>

                            <td class="text-center">
                                <?php if ($is_low): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fw-semibold">
                                        <?= (int)$p['Stock'] ?> <?= htmlspecialchars($p['Unit']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-semibold">
                                        <?= (int)$p['Stock'] ?> <?= htmlspecialchars($p['Unit']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end text-secondary laporan-num">
                                Rp <?= number_format($p['PurchasePrice'], 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold text-dark pe-4 laporan-num">
                                Rp <?= number_format($p['Valuasi'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="p-3 border-top bg-light rounded-bottom-4 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-1">
        <small class="text-secondary">
            Menampilkan <strong class="text-dark"><?= count($product_list) ?></strong> produk
        </small>
        <small class="text-secondary">
            Total Valuasi: <strong class="text-dark">Rp <?= number_format($total_nilai, 0, ',', '.') ?></strong>
        </small>
    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>