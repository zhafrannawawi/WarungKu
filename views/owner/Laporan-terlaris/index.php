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

    /* Tabel: angka rapi dan tidak patah baris */
    .laporan-table td, .laporan-table th { white-space: nowrap; }
    .laporan-num { font-variant-numeric: tabular-nums; }

    /* Badge peringkat */
    .rank-badge {
        min-width: 42px;
        display: inline-block;
        text-align: center;
    }
</style>

<!-- Page Header: Laporan Produk Terlaris -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="trophy" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Laporan Produk Terlaris</h4>
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1" style="font-size: 0.7rem;">Peringkat</span>
            </div>
            <p class="text-secondary mb-0 small">Peringkat barang paling banyak terjual berdasarkan kuantitas dan kontribusi omzet.</p>
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

<!-- Baris 1: 3 Kartu Metrik -->
<div class="row g-3 mb-4">
    <!-- Produk Paling Laris -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Produk Paling Laris (#1)</span>
                <div class="bg-warning bg-opacity-10 text-warning-emphasis rounded-3 p-2">
                    <i data-lucide="award" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark text-truncate">
                <?= $produk_juara ? htmlspecialchars($produk_juara['ProductName']) : '-' ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= $produk_juara ? number_format($produk_juara['Terjual'], 0, ',', '.') . ' ' . htmlspecialchars($produk_juara['Unit']) . ' terjual' : 'Belum ada penjualan' ?>
            </small>
        </div>
    </div>

    <!-- Total Unit Terjual -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Unit Terjual</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="shopping-bag" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= number_format($total_terjual, 0, ',', '.') ?> Unit
            </div>

            <small class="text-secondary mt-2 d-block">
                Dari <?= count($top_products) ?> produk terjual
            </small>
        </div>
    </div>

    <!-- Kategori Terlaris -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Kategori Terlaris</span>
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                    <i data-lucide="layers" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark text-truncate">
                <?= htmlspecialchars($kategori_juara) ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= $kategori_juara_qty > 0 ? number_format($kategori_juara_qty, 0, ',', '.') . ' unit terjual' : 'Belum ada penjualan' ?>
            </small>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Produk Terlaris -->
<div class="bg-white border rounded-4 shadow-sm overflow-hidden">

    <!-- Header kartu -->
    <div class="p-3 border-bottom">
        <h6 class="fw-bold text-dark mb-1">Peringkat Penjualan Produk</h6>
        <small class="text-secondary">Urutan produk berdasarkan jumlah terjual, omzet, dan estimasi laba</small>
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
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold" style="width: 90px;">Urutan</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Nama Produk</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kategori</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Terjual</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Sisa Stok</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Total Omzet</th>
                    <th class="text-end pe-4 py-3 text-secondary small text-uppercase fw-semibold">Estimasi Laba</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($top_products)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-5">
                            <i data-lucide="package-x" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada data penjualan pada periode ini.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($top_products as $idx => $tp):
                        $is_low = (int)$tp['Stock'] <= (int)$tp['MinimumStock'];
                    ?>
                        <tr class="produk-row"
                            data-category="<?= htmlspecialchars($tp['CategoryName']) ?>"
                            data-stock="<?= (int)$tp['Stock'] ?>"
                            data-name="<?= htmlspecialchars(strtolower($tp['ProductName'])) ?>">
                            <td class="ps-4">
                                <span class="rank-badge badge fw-bold <?= $idx === 0 ? 'bg-warning bg-opacity-25 text-warning-emphasis border border-warning border-opacity-50' : 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25' ?>">
                                    #<?= $idx + 1 ?>
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-3 fw-bold small d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                        <?= strtoupper(substr($tp['ProductName'], 0, 2)) ?>
                                    </span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($tp['ProductName']) ?></span>
                                        <small class="text-secondary">Kode: #<?= htmlspecialchars($tp['ProductID']) ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    <?= htmlspecialchars($tp['CategoryName']) ?>
                                </span>
                            </td>

                            <td class="text-center fw-bold text-primary laporan-num">
                                <?= number_format($tp['Terjual'], 0, ',', '.') ?> <?= htmlspecialchars($tp['Unit']) ?>
                            </td>

                            <td class="text-center">
                                <?php if ($is_low): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fw-semibold">
                                        <?= (int)$tp['Stock'] ?> <?= htmlspecialchars($tp['Unit']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-semibold">
                                        <?= (int)$tp['Stock'] ?> <?= htmlspecialchars($tp['Unit']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end fw-bold text-dark laporan-num">
                                Rp <?= number_format($tp['TotalOmzet'], 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-semibold text-success pe-4 laporan-num">
                                Rp <?= number_format($tp['TotalLaba'], 0, ',', '.') ?>
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
            Menampilkan <strong class="text-dark"><?= count($top_products) ?></strong> produk terjual
        </small>
        <small class="text-secondary">
            Total Omzet: <strong class="text-dark">Rp <?= number_format($total_omzet, 0, ',', '.') ?></strong>
        </small>
    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>