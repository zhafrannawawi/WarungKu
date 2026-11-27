<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Riwayat Transaksi -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="receipt" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Riwayat Transaksi</h4>
            </div>
            <p class="text-secondary mb-0 small">Daftar nota penjualan pelanggan dan rincian barang belanjaan.</p>
        </div>
    </div>

    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
        <div class="laporan-date-group d-inline-flex align-items-center gap-1 rounded-3 shadow-sm px-2">
            <span class="text-secondary d-inline-flex ps-1">
                <i data-lucide="calendar" width="16" height="16"></i>
            </span>
            <input type="date" name="from" value="<?= escape_html($from_date) ?>" class="form-control" aria-label="Dari tanggal">
            <span class="text-secondary small fw-medium">s/d</span>
            <input type="date" name="to" value="<?= escape_html($to_date) ?>" class="form-control" aria-label="Sampai tanggal">
        </div>
        <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
            <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i data-lucide="filter" class="icon-xs"></i>
            </span>
            <span>Filter</span>
        </button>
    </form>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Baris 1: 3 Kartu Metrik Transaksi -->
<div class="row g-3 mb-4">
    <!-- Kartu 1: Total Transaksi -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Total Transaksi</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="receipt" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-dark">
                <?= format_number($total_trx) ?> Nota
            </div>

            <small class="text-secondary mt-2 d-block">
                Omzet <?= format_rupiah($total_omzet) ?>
            </small>
        </div>
    </div>

    <!-- Kartu 2: Pembayaran Tunai -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Pembayaran Tunai</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                    <i data-lucide="wallet" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-success">
                <?= format_rupiah($total_tunai) ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                Uang tunai diterima
            </small>
        </div>
    </div>

    <!-- Kartu 3: Pembayaran QRIS -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary fw-medium">Pembayaran QRIS</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i data-lucide="qr-code" width="20" height="20"></i>
                </div>
            </div>

            <div class="fs-4 fw-bold text-primary">
                <?= format_rupiah($total_qris) ?>
            </div>

            <small class="text-secondary mt-2 d-block">
                Non-tunai digital
            </small>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Riwayat Transaksi -->
<div class="bg-white border rounded-4 shadow-sm overflow-hidden">

    <!-- Header: Judul + Pencarian + Filter -->
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div>
            <h6 class="fw-bold text-dark mb-1">Daftar Nota Penjualan</h6>
            <small class="text-secondary">Cari nomor faktur dan lihat rincian belanjaan</small>
        </div>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
            <div class="input-group input-group-sm" style="min-width: 240px;">
                <span class="input-group-text bg-light border-0 text-secondary ps-3">
                    <i data-lucide="search" width="16" height="16"></i>
                </span>
                <input type="text" id="trx-table-search" class="form-control bg-light border-0 py-2" placeholder="Cari nomor faktur..." oninput="filterTrxTable()">
            </div>

            <select id="trx-filter-method" class="form-select form-select-sm border-0 bg-light text-dark fw-medium rounded-3 ps-3 py-2 w-auto" style="padding-right: 2.25rem;" onchange="filterTrxTable()">
                <option value="all">Semua Metode</option>
                <option value="cash">Tunai (Cash)</option>
                <option value="qris">QRIS</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small text-uppercase fw-semibold">No. Faktur</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Tanggal &amp; Waktu</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Kasir</th>
                    <th class="py-3 text-secondary small text-uppercase fw-semibold">Metode</th>
                    <th class="text-end py-3 text-secondary small text-uppercase fw-semibold">Total Belanja</th>
                    <th class="text-center pe-4 py-3 text-secondary small text-uppercase fw-semibold" style="width: 100px;">Detail</th>
                </tr>
            </thead>

            <tbody id="trx-table-body">
                <?php if (empty($trx_list)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-5">
                            <i data-lucide="receipt-text" width="28" height="28" class="mb-2"></i>
                            <div class="small">Belum ada transaksi pada periode ini.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($trx_list as $t): ?>
                        <tr class="trx-row" data-faktur="<?= escape_html(strtolower($t['TransactionID'])) ?>" data-method="<?= escape_html($t['PaymentMethod']) ?>">
                            <td class="ps-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                    #<?= escape_html($t['TransactionID']) ?>
                                </span>
                            </td>

                            <td>
                                <span class="text-dark fw-medium d-block"><?= date('d M Y', strtotime($t['TransactionDate'])) ?></span>
                                <small class="text-secondary"><?= date('H:i', strtotime($t['TransactionDate'])) ?> WIB</small>
                            </td>

                            <td class="fw-medium text-dark">
                                <?= escape_html($t['CashierName'] ?? '-') ?>
                            </td>

                            <td>
                                <span class="badge <?= $t['PaymentMethod'] === 'qris' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> border fw-semibold">
                                    <?= strtoupper(escape_html($t['PaymentMethod'])) ?>
                                </span>
                            </td>

                            <td class="text-end fw-bold text-dark">
                                <?= format_rupiah($t['TotalAmount']) ?>
                            </td>

                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Lihat Nota" onclick="openTrxDetail(<?= json_attr($t) ?>)">
                                    <i data-lucide="eye" class="icon-xs"></i>
                                </button>
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
            Menampilkan <strong class="text-dark"><?= count($trx_list) ?></strong> transaksi
        </small>
    </div>

</div>

<!-- Modal Detail Nota Transaksi -->
<div class="modal fade" id="modalDetailTrx" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start d-print-none">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="receipt-text" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Detail Nota Transaksi</h5>
                        <p class="text-secondary mb-0 small">Rincian barang dan pembayaran</p>
                    </div>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pt-2 pb-4" id="pos-receipt-printable">
                <div class="text-center mb-3 pb-2 border-bottom">
                    <div class="fw-bold text-dark text-uppercase"><?= escape_html($store_info['StoreName'] ?? 'WarungKu') ?></div>
                    <?php if (!empty($store_info['Address'])): ?>
                        <small class="text-secondary d-block" style="font-size: 0.75rem;"><?= escape_html($store_info['Address']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="small text-secondary mb-3 pb-2 border-bottom">
                    <div class="fw-semibold text-dark" id="detail-trx-id">#TRX</div>
                    <div id="detail-trx-meta" style="font-size: 0.75rem;"></div>
                </div>

                <div id="detail-trx-items" class="d-flex flex-column gap-2 mb-3 pb-2 border-bottom"></div>

                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-secondary">Metode Pembayaran</span>
                    <span class="fw-semibold text-uppercase" id="detail-trx-method"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-dark mb-1">
                    <span>Total Belanja</span>
                    <span id="detail-trx-total"></span>
                </div>
                <div class="d-flex justify-content-between small text-secondary mb-1">
                    <span>Uang Diterima</span>
                    <span id="detail-trx-paid"></span>
                </div>
                <div class="d-flex justify-content-between small text-secondary">
                    <span>Kembalian</span>
                    <span id="detail-trx-change"></span>
                </div>

                <div class="text-center mt-3 pt-2 border-top">
                    <small class="text-secondary" style="font-size: 0.725rem;">Terima kasih telah berbelanja!</small>
                </div>
            </div>

            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between d-print-none">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="window.print()">
                    <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i data-lucide="printer" class="icon-xs"></i>
                    </span>
                    <span>Cetak Nota</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>