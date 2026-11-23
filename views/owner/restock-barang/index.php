<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Riwayat Restock Barang</h4>
            <p class="text-muted mb-0 small">Catat pembelian barang masuk dan pantau penambahan stok dagangan.</p>
        </div>
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRestock">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Catat Restock Baru
        </button>
    </div>

    <!-- Alert Notifikasi -->
    <?php include __DIR__ . '/../../partials/alert.php'; ?>

    <!-- Tabel Riwayat Restock -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal Masuk</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Masuk</th>
                            <th>Harga Beli (Modal)</th>
                            <th>Petugas Catat</th>
                            <th class="pe-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($restock_list)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat restock barang.</td></tr>
                        <?php else: ?>
                            <?php foreach ($restock_list as $r): ?>
                                <tr>
                                    <td class="ps-4 text-muted small"><?= date('d/m/Y H:i', strtotime($r['RestockDate'])) ?></td>
                                    <td class="fw-bold text-dark"><?= $r['ProductName'] ?></td>
                                    <td><span class="badge bg-success-subtle text-success border">+<?= $r['Quantity'] ?></span></td>
                                    <td>Rp <?= number_format($r['PurchasePrice'], 0, ',', '.') ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $r['CashierName'] ?: 'Owner' ?></span></td>
                                    <td class="pe-4 text-muted small"><?= $r['Note'] ?: '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/modal-restock.php'; ?>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
