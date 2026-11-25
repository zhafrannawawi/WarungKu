<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Riwayat Transaksi</h4>
        <p class="text-muted mb-0 small">Daftar nota penjualan pelanggan dan rincian barang belanjaan.</p>
    </div>

    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center gap-1">
            <input type="date" name="from" value="<?= htmlspecialchars($from_date) ?>" class="form-control form-control-sm border rounded-btn-sm bg-white" style="width: auto;">
            <span class="text-muted small">s/d</span>
            <input type="date" name="to" value="<?= htmlspecialchars($to_date) ?>" class="form-control form-control-sm border rounded-btn-sm bg-white" style="width: auto;">
        </div>
        <button type="submit" class="btn btn-sm btn-primary rounded-btn-sm d-flex align-items-center gap-1 fw-semibold">
            <i data-lucide="filter" class="icon-xs"></i> Filter
        </button>
    </form>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Transaksi</span>
                <div class="metric-icon-box metric-icon-blue"><i data-lucide="receipt" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-dark"><?= number_format($total_trx, 0, ',', '.') ?> Nota</span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Omzet Rp <?= number_format($total_omzet, 0, ',', '.') ?></small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Pembayaran Tunai</span>
                <div class="metric-icon-box metric-icon-green"><i data-lucide="wallet" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-success">Rp <?= number_format($total_tunai, 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Uang tunai diterima</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Pembayaran QRIS</span>
                <div class="metric-icon-box metric-icon-purple"><i data-lucide="qr-code" class="icon-sm"></i></div>
            </div>
            <span class="metric-value text-primary">Rp <?= number_format($total_qris, 0, ',', '.') ?></span>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Non-tunai digital</small>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-modern">
    <div class="p-3 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div class="position-relative topbar-search-wrapper w-100-mobile">
            <i data-lucide="search" class="search-icon-pos icon-sm"></i>
            <input type="text" id="trx-table-search" class="search-input-box" placeholder="Cari nomor faktur..." oninput="filterTrxTable()">
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <select id="trx-filter-method" class="form-select form-select-sm border filter-select" onchange="filterTrxTable()">
                <option value="all">Semua Metode</option>
                <option value="cash">Tunai (Cash)</option>
                <option value="qris">QRIS</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">No. Faktur</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Kasir</th>
                    <th>Metode</th>
                    <th class="text-end">Total Belanja</th>
                    <th class="text-center pe-4" style="width: 100px;">Detail</th>
                </tr>
            </thead>
            <tbody id="trx-table-body">
                <?php if (empty($trx_list)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi pada periode ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($trx_list as $t): ?>
                        <tr class="trx-row" data-faktur="<?= htmlspecialchars(strtolower($t['TransactionID'])) ?>" data-method="<?= htmlspecialchars($t['PaymentMethod']) ?>">
                            <td class="ps-4"><span class="trx-faktur">#<?= htmlspecialchars($t['TransactionID']) ?></span></td>
                            <td>
                                <div class="trx-waktu">
                                    <span class="trx-tanggal"><?= date('d M Y', strtotime($t['TransactionDate'])) ?></span>
                                    <span class="trx-jam"><?= date('H:i', strtotime($t['TransactionDate'])) ?> WIB</span>
                                </div>
                            </td>
                            <td class="fw-medium text-dark"><?= htmlspecialchars($t['CashierName'] ?? '-') ?></td>
                            <td>
                                <span class="badge <?= $t['PaymentMethod'] === 'qris' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> border fw-semibold">
                                    <?= strtoupper(htmlspecialchars($t['PaymentMethod'])) ?>
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($t['TotalAmount'], 0, ',', '.') ?></td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-btn-sm p-1" title="Lihat Nota" onclick="openTrxDetail(<?= htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8') ?>)">
                                    <i data-lucide="eye" class="icon-xs"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex align-items-center justify-content-between bg-white rounded-bottom">
        <small class="text-muted">Menampilkan <strong><?= count($trx_list) ?></strong> transaksi</small>
    </div>
</div>

<!-- Modal Detail Nota Transaksi -->
<div class="modal fade" id="modalDetailTrx" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3 bg-white d-print-none">
                <h6 class="modal-title fw-bold text-dark mb-0">Detail Nota Transaksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="pos-receipt-printable">
                <div class="text-center mb-3 pb-2 border-bottom">
                    <div class="fw-bold text-dark text-uppercase"><?= htmlspecialchars($store_info['StoreName'] ?? 'WarungKu') ?></div>
                    <?php if (!empty($store_info['Address'])): ?>
                        <small class="text-muted d-block" style="font-size: 0.75rem;"><?= htmlspecialchars($store_info['Address']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="small text-muted mb-3 pb-2 border-bottom">
                    <div class="fw-semibold text-dark" id="detail-trx-id">#TRX</div>
                    <div id="detail-trx-meta" style="font-size: 0.75rem;"></div>
                </div>
                <div id="detail-trx-items" class="d-flex flex-column gap-2 mb-3 pb-2 border-bottom"></div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Metode Pembayaran</span>
                    <span class="fw-semibold text-uppercase" id="detail-trx-method"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-dark mb-1">
                    <span>Total Belanja</span>
                    <span id="detail-trx-total"></span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Uang Diterima</span>
                    <span id="detail-trx-paid"></span>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>Kembalian</span>
                    <span id="detail-trx-change"></span>
                </div>
                <div class="text-center mt-3 pt-2 border-top">
                    <small class="text-muted" style="font-size: 0.725rem;">Terima kasih telah berbelanja!</small>
                </div>
            </div>
            <div class="modal-footer border-top bg-light px-4 py-2 d-flex justify-content-between d-print-none">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-sm btn-primary rounded-btn-sm d-inline-flex align-items-center gap-1 fw-semibold" onclick="window.print()">
                    <i data-lucide="printer" class="icon-xs"></i> Cetak Nota
                </button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
