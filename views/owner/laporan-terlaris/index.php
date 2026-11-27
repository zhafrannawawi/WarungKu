<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Laporan Produk Terlaris -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="trophy" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Laporan Produk Terlaris</h4>
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
            <input type="date" name="from" value="<?= escape_html($from_date) ?>" class="form-control">
            <span class="text-secondary small fw-medium">s/d</span>
            <input type="date" name="to" value="<?= escape_html($to_date) ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
            <i data-lucide="filter" width="16" height="16"></i>
            <span>Filter</span>
        </button>

        <button type="button" class="btn bg-white border-0 text-secondary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="exportDataProduk()">
            <i data-lucide="download" width="16" height="16"></i>
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
                <?= $produk_juara ? escape_html($produk_juara['ProductName']) : '-' ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= $produk_juara ? format_number($produk_juara['Terjual']) . ' ' . escape_html($produk_juara['Unit']) . ' terjual' : 'Belum ada penjualan' ?>
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
                <?= format_number($total_terjual) ?> Unit
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
                <?= escape_html($kategori_juara) ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= $kategori_juara_qty > 0 ? format_number($kategori_juara_qty) . ' unit terjual' : 'Belum ada penjualan' ?>
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
                    <option value="<?= escape_html($c['CategoryName']) ?>"><?= escape_html($c['CategoryName']) ?></option>
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
                            data-category="<?= escape_html($tp['CategoryName']) ?>"
                            data-stock="<?= (int)$tp['Stock'] ?>"
                            data-name="<?= escape_html(strtolower($tp['ProductName'])) ?>">
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
                                        <span class="fw-semibold text-dark d-block"><?= escape_html($tp['ProductName']) ?></span>
                                        <small class="text-secondary">Kode: #<?= escape_html($tp['ProductID']) ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    <?= escape_html($tp['CategoryName']) ?>
                                </span>
                            </td>

                            <td class="text-center fw-bold text-primary laporan-num">
                                <?= format_number($tp['Terjual']) ?> <?= escape_html($tp['Unit']) ?>
                            </td>

                            <td class="text-center">
                                <?php if ($is_low): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fw-semibold">
                                        <?= (int)$tp['Stock'] ?> <?= escape_html($tp['Unit']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-semibold">
                                        <?= (int)$tp['Stock'] ?> <?= escape_html($tp['Unit']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end fw-bold text-dark laporan-num">
                                <?= format_rupiah($tp['TotalOmzet']) ?>
                            </td>

                            <td class="text-end fw-semibold <?= (float)$tp['TotalLaba'] >= 0 ? 'text-success' : 'text-danger' ?> pe-4 laporan-num">
                                <?= format_rupiah($tp['TotalLaba']) ?>
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
            Total Omzet: <strong class="text-dark"><?= format_rupiah($total_omzet) ?></strong>
        </small>
    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>