<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Dashboard Harian -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Dashboard Harian</h4>
        <p class="text-muted mb-0 small">Ringkasan aktivitas operasional warung hari ini</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="btn btn-sm bg-white border text-muted rounded-btn-sm d-inline-flex align-items-center gap-1 pe-none">
            <i data-lucide="calendar" class="icon-xs"></i> <?= htmlspecialchars($tanggal_hari_ini) ?>
        </span>
        <a href="/owner/kasir" class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold text-decoration-none">
            <i data-lucide="shopping-cart" class="icon-xs"></i> Buka Kasir
        </a>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: 4 Kartu Metrik Finansial & Kasir Harian -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
     <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary  fw-medium">Omzet Hari Ini</span>

                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="banknote" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                Rp <?= number_format($summary['omzet'], 0, ',', '.') ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                <?= (int)$summary['total_trx'] ?> transaksi
                <span class="mx-1">·</span>
                Rata-rata Rp <?= number_format($rata_trx, 0, ',', '.') ?>
            </small>
     </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
    <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-secondary  fw-medium">Uang Tunai di Laci</span>
            <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                <i data-lucide="wallet" width="20" height="20"></i>
            </div>
        </div>

        <div class="fs-4 fw-bold text-success">
            Rp <?= number_format($summary['tunai'], 0, ',', '.') ?>
        </div>

        <small class="text-secondary mt-2 d-block">
            <?= (int)$summary['jml_tunai'] ?> pembayaran tunai kasir
        </small>
    </div>
</div>

    <div class="col-12 col-sm-6 col-xl-3">
    <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-secondary  fw-medium">QRIS / Non-Tunai</span>
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                <i data-lucide="qr-code" width="20" height="20"></i>
            </div>
        </div>

        <div class="fs-4 fw-bold text-primary">
            Rp <?= number_format($summary['qris'], 0, ',', '.') ?>
        </div>

        <small class="text-secondary mt-2 d-block">
            <?= (int)$summary['jml_qris'] ?> transaksi QRIS digital
        </small>
    </div>
</div>

    <div class="col-12 col-sm-6 col-xl-3">
    <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-secondary  fw-medium">Estimasi Laba Bersih</span>
            <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                <i data-lucide="trending-up" width="20" height="20"></i>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between gap-2">
            <span class="fs-4 fw-bold text-dark">
                Rp <?= number_format($summary['laba_bersih'], 0, ',', '.') ?>
            </span>

            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                <?= $margin_pct ?>%
            </span>
        </div>

        <small class="text-secondary mt-2 d-block">
            Margin bersih setelah modal
        </small>
    </div>
</div>
</div>

<!-- Baris 2: Transaksi Terbaru & Peringatan Stok Menipis -->
<div class="row g-3 mb-4">
    <!-- 5 Transaksi Terkini Hari Ini -->
    <div class="col-12 col-lg-7">
    <div class="bg-white border rounded-4 shadow-sm h-100">

        <!-- Header -->
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold text-dark mb-1">Transaksi Terbaru Hari Ini</h6>
                <small class="text-secondary">Catatan penjualan kasir masuk</small>
            </div>

            <a href="/owner/transaksi"
               class="btn btn-sm btn-outline-secondary px-3 rounded-3">
                Lihat Semua
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">No. Faktur</th>
                        <th class="py-3 text-secondary small text-uppercase fw-semibold">Waktu</th>
                        <th class="py-3 text-secondary small text-uppercase fw-semibold">Kasir</th>
                        <th class="py-3 text-secondary small text-uppercase fw-semibold">Metode</th>
                        <th class="text-end pe-4 py-3 text-secondary small text-uppercase fw-semibold">Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($recent_trx)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-5">
                                <i data-lucide="receipt-text" width="28" height="28" class="mb-2"></i>
                                <div class="small">Belum ada transaksi hari ini.</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_trx as $t): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                        #<?= htmlspecialchars($t['TransactionID']) ?>
                                    </span>
                                </td>

                                <td class="text-secondary small">
                                    <?= date('H:i', strtotime($t['TransactionDate'])) ?> WIB
                                </td>

                                <td class="fw-medium text-dark">
                                    <?= htmlspecialchars($t['CashierName'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="badge <?= $t['PaymentMethod'] === 'qris' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> border fw-semibold">
                                        <?= strtoupper(htmlspecialchars($t['PaymentMethod'])) ?>
                                    </span>
                                </td>

                                <td class="text-end fw-bold text-dark pe-4">
                                    Rp <?= number_format($t['TotalAmount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

    <!-- Panel Peringatan Stok Menipis -->
    
<!-- Panel Peringatan Stok Menipis -->
<div class="col-12 col-lg-5">
    <div class="bg-white border rounded-4 shadow-sm h-100 d-flex flex-column">

        <!-- Header -->
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold text-dark mb-1">Peringatan Stok Menipis</h6>
                <small class="text-secondary">Barang di bawah batas minimum</small>
            </div>

            <a href="/owner/restock"
               class="btn btn-sm btn-outline-secondary px-3 rounded-3">
                Restock
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive flex-grow-1">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">Produk</th>
                        <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Minimum</th>
                        <th class="text-end pe-4 py-3 text-secondary small text-uppercase fw-semibold">Sisa Stok</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($low_stock_list)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-4">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mb-2">
                                    <i data-lucide="check-circle" width="13" height="13"></i>
                                    Stok Aman
                                </span>
                                <div class="small">Semua stok di atas batas minimum.</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($low_stock_list as $lp):
                            $habis = (int)$lp['Stock'] <= 0;
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 fw-bold small">
                                            <?= strtoupper(substr($lp['ProductName'], 0, 2)) ?>
                                        </span>

                                        <div>
                                            <span class="fw-semibold text-dark d-block">
                                                <?= htmlspecialchars($lp['ProductName']) ?>
                                            </span>

                                            <small class="text-secondary">
                                                <?= !empty($lp['Location']) ? htmlspecialchars($lp['Location']) : '#' . htmlspecialchars($lp['ProductID']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center text-secondary small">
                                    <?= (int)$lp['MinimumStock'] ?> <?= htmlspecialchars($lp['Unit']) ?>
                                </td>

                                <td class="text-end pe-4">
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        <?= $habis ? 'Habis' : (int)$lp['Stock'] . ' ' . htmlspecialchars($lp['Unit']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-3 border-top bg-light text-center rounded-bottom-4 mt-auto">
            <small class="text-secondary">
                Total <strong class="text-dark"><?= $total_katalog ?></strong> produk aktif dalam katalog warung
            </small>
        </div>

    </div>
</div>

</div>

<!-- Baris 3: Produk Paling Laku Hari Ini -->
<div class="bg-white border rounded-4 shadow-sm d-flex flex-column overflow-hidden">

    <!-- Header -->
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold text-dark mb-1">Produk Terlaris Hari Ini</h6>
            <small class="text-secondary">Urutan barang paling banyak terjual sepanjang hari ini</small>
        </div>

        <a href="/owner/laporan/terlaris"
           class="btn btn-sm btn-outline-secondary px-3 rounded-3">
            Lihat Laporan
        </a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold" style="width: 70px;">Urutan</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Nama Produk</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kategori</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Terjual Hari Ini</th>
                    <th class="text-center py-3 text-secondary small text-uppercase fw-semibold">Sisa Stok</th>
                    <th class="text-end pe-4 py-3 text-secondary small text-uppercase fw-semibold">Total Omzet</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($top_today)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 mb-2">
                                <i data-lucide="shopping-bag" width="13" height="13"></i>
                                Belum Ada Penjualan
                            </span>
                            <div class="small">Belum ada produk terjual hari ini.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($top_today as $idx => $tp):
                        $is_low = (int)$tp['Stock'] <= (int)$tp['MinimumStock'];
                    ?>
                        <tr>
                            <td class="ps-4">
                                <span class="badge <?= $idx === 0 ? 'bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25' : 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25' ?> fw-bold">
                                    #<?= $idx + 1 ?>
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 fw-bold small">
                                        <?= strtoupper(substr($tp['ProductName'], 0, 2)) ?>
                                    </span>

                                    <div>
                                        <span class="fw-semibold text-dark d-block">
                                            <?= htmlspecialchars($tp['ProductName']) ?>
                                        </span>
                                        <small class="text-secondary">#<?= htmlspecialchars($tp['ProductID']) ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    <?= htmlspecialchars($tp['CategoryName']) ?>
                                </span>
                            </td>

                            <td class="text-center fw-bold text-primary">
                                <?= number_format($tp['Terjual'], 0, ',', '.') ?> <?= htmlspecialchars($tp['Unit']) ?>
                            </td>

                            <td class="text-center">
                                <?php if ($is_low): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        <?= (int)$tp['Stock'] ?> <?= htmlspecialchars($tp['Unit']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <?= (int)$tp['Stock'] ?> <?= htmlspecialchars($tp['Unit']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end fw-bold text-dark pe-4">
                                Rp <?= number_format($tp['TotalOmzet'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
