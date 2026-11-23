<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Dashboard Harian -->
<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1">Dashboard Harian</h4>
    <p class="text-muted mb-0 small">Ringkasan aktivitas operasional warung hari ini, rekonsiliasi kas, dan inventaris.</p>
</div>

<!-- Alert Notifikasi -->
<?php include __DIR__ . '/../../partials/alert.php'; ?>

<!-- 4 Kartu Metrik Finansial & Kasir Harian -->
<div class="row g-3 mb-4">
    <!-- Card 1: Omzet Hari Ini -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Omzet Hari Ini</span>
                <div class="metric-icon-box">
                    <i data-lucide="banknote" class="icon-sm text-primary"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark">Rp 0</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">0 transaksi • Rata-rata Rp 0</small>
        </div>
    </div>

    <!-- Card 2: Uang Tunai di Laci Kasir -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Uang Tunai di Laci</span>
                <div class="metric-icon-box">
                    <i data-lucide="wallet" class="icon-sm text-success"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-success">Rp 0</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">0 pembayaran tunai kasir</small>
        </div>
    </div>

    <!-- Card 3: Non-Tunai / QRIS -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">QRIS / Non-Tunai</span>
                <div class="metric-icon-box">
                    <i data-lucide="qr-code" class="icon-sm text-primary"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-primary">Rp 0</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">0 transaksi QRIS digital</small>
        </div>
    </div>

    <!-- Card 4: Estimasi Laba Bersih -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-modern p-3 h-100 metric-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Estimasi Laba Bersih</span>
                <div class="metric-icon-box">
                    <i data-lucide="trending-up" class="icon-sm text-success"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="metric-value text-dark">Rp 0</span>
                <span class="badge-status success">0%</span>
            </div>
            <small class="text-muted mt-2 d-block" style="font-size: 0.725rem;">Margin bersih setelah modal</small>
        </div>
    </div>
</div>

<!-- Section: Transaksi Terakhir & Inventaris -->
<div class="row g-3">
    <!-- 5 Transaksi Terkini Hari Ini -->
    <div class="col-12 col-lg-7">
        <div class="card-modern h-100">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold text-dark mb-0">Transaksi Terbaru Hari Ini</h6>
                    <small class="text-muted">Catatan penjualan kasir masuk</small>
                </div>
                <a href="/owner/transaksi" class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-btn-sm text-decoration-none">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Faktur</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Metode</th>
                            <th class="text-end">Total</th>
                            <th class="text-center pe-4" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi hari ini.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel Status Inventaris -->
    <div class="col-12 col-lg-5">
        <div class="card-modern h-100 d-flex flex-column justify-content-between">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold text-dark mb-0">Status Inventaris</h6>
                <a href="/owner/restock" class="btn btn-sm btn-outline-secondary rounded-btn-sm text-decoration-none">
                    Restock
                </a>
            </div>

            <div class="p-3 d-flex flex-column gap-3 my-auto">
                <div class="d-flex flex-column align-items-center justify-content-center py-4 text-center">
                    <p class="text-muted small mb-0">Data inventaris akan muncul di sini.</p>
                </div>
            </div>

            <div class="p-2 border-top bg-light text-center">
                <small class="text-muted" style="font-size: 0.75rem;">Produk aktif dalam katalog warung</small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
