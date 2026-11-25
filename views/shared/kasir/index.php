<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header (Seragam dengan halaman lain) -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Transaksi Kasir</h4>
        <p class="text-muted mb-0 small">Pilih barang pada daftar produk untuk mencatat belanjaan dan pembayaran pelanggan.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="btn btn-sm bg-white border text-muted rounded-btn-sm d-inline-flex align-items-center gap-1 pe-none">
            <i data-lucide="calendar" class="icon-xs"></i> <?= htmlspecialchars($tanggal_hari_ini) ?>
        </span>
        <a href="<?= $role_prefix ?>/transaksi" class="btn btn-sm btn-outline-secondary rounded-btn-sm d-flex align-items-center gap-1 bg-white text-decoration-none">
            <i data-lucide="receipt" class="icon-xs"></i> Riwayat Transaksi
        </a>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<div class="row g-3 align-items-start">
    <div class="col-12 col-lg-8">
        <!-- Toolbar Filter Langsung di Atas Grid -->
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
            <div class="position-relative flex-grow-1">
                <i data-lucide="search" class="search-icon-pos icon-sm"></i>
                <input type="text" id="pos-product-search" class="search-input-box" placeholder="Cari nama produk..." oninput="filterPosProducts()">
            </div>

            <select id="pos-filter-category" class="form-select form-select-sm border filter-select bg-white" style="height: 35px;" onchange="filterPosProducts()">
                <option value="all">Semua Kategori</option>
                <?php foreach ($category_list as $c): ?>
                    <option value="<?= htmlspecialchars($c['CategoryName']) ?>"><?= htmlspecialchars($c['CategoryName']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Grid Kartu Produk -->
        <?php if (empty($product_list)): ?>
            <div class="card-modern p-5 text-center text-muted">
                Belum ada produk aktif terdaftar.
            </div>
        <?php else: ?>
            <div class="pos-catalog-scroll">
                <div class="row g-2 mx-0">
                    <?php foreach ($product_list as $p):
                        $stok     = (int)$p['Stock'];
                        $habis    = $stok <= 0;
                        $menipis  = !$habis && $stok <= (int)$p['MinimumStock'];
                        $cat_name = $p['CategoryName'] ?: 'Umum';
                        $item_payload = [
                            'id'    => $p['ProductID'],
                            'name'  => $p['ProductName'],
                            'price' => (float)$p['SellingPrice'],
                            'unit'  => $p['Unit'],
                            'stock' => $stok,
                        ];
                    ?>
                        <div class="col-6 col-xl-4 px-1 pos-product-row"
                            data-name="<?= htmlspecialchars(strtolower($p['ProductName'])) ?>"
                            data-category="<?= htmlspecialchars($cat_name) ?>">
                            <div class="card-modern pos-item-card p-3 h-100 d-flex flex-column justify-content-between <?= $habis ? 'is-empty' : '' ?>"
                                data-product-id="<?= htmlspecialchars($p['ProductID']) ?>"
                                <?= $habis ? '' : 'onclick="posAddToCart(' . htmlspecialchars(json_encode($item_payload), ENT_QUOTES, 'UTF-8') . ')"' ?>>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                                        <span class="pos-cat-tag text-truncate"><?= htmlspecialchars($cat_name) ?></span>
                                        <?php if (!$habis): ?><span class="pos-item-add"><i data-lucide="plus" class="icon-xs"></i></span><?php endif; ?>
                                    </div>
                                    <div class="fw-semibold text-dark pos-item-name" style="font-size: 0.85rem; line-height: 1.3;">
                                        <?= htmlspecialchars($p['ProductName']) ?>
                                    </div>
                                    <small class="text-muted d-block text-truncate mt-1" style="font-size: 0.7rem;">
                                        per <?= htmlspecialchars($p['Unit']) ?><?= !empty($p['Location']) ? ' · ' . htmlspecialchars($p['Location']) : '' ?>
                                    </small>
                                </div>

                                <div class="d-flex align-items-center justify-content-between gap-1 pt-3 mt-auto">
                                    <span class="fw-bold text-dark" style="font-size: 0.925rem;">Rp <?= number_format($p['SellingPrice'], 0, ',', '.') ?></span>
                                    <span class="pos-stock-dot <?= $habis ? 'text-danger' : ($menipis ? 'text-warning' : 'text-success') ?>">
                                        ● <?= $habis ? 'Habis' : $stok ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ==================== CARD KANAN: RINGKASAN PESANAN & PEMBAYARAN ==================== -->
    <div class="col-12 col-lg-4">
        <form method="POST" action="<?= $kasir_url ?>" id="pos-checkout-form" class="card-modern pos-cart-sticky">
            <input type="hidden" name="cart_items" id="pos-cart-input" value="[]">

            <!-- Header Card Kanan -->
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">Pesanan</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary" id="pos-cart-count">0 item</span>
                </div>
                <button type="button" id="pos-btn-clear" class="btn btn-sm btn-link text-danger text-decoration-none p-0 small fw-medium d-none" onclick="posClearCart()">
                    Kosongkan
                </button>
            </div>

            <!-- Daftar Item Pesanan (Flat List, Scroll Internal) -->
            <div class="pos-cart-items px-3" id="pos-cart-list">
                <div class="text-center text-muted py-5 small">
                    <i data-lucide="shopping-bag" class="icon-lg d-block mx-auto mb-2 opacity-50"></i>
                    Belum ada barang dipilih
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="p-3 border-top bg-light rounded-bottom mt-auto">
                <input type="hidden" name="payment_method" id="pos-payment-input" value="cash">

                <!-- Segmented Toggle Metode Bayar -->
                <div class="btn-group btn-group-sm w-100 mb-3" role="group">
                    <button type="button" id="btn-pay-cash" class="btn btn-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-1" onclick="posSetPayment('cash')">
                        <i data-lucide="banknote" class="icon-xs"></i> Tunai
                    </button>
                    <button type="button" id="btn-pay-qris" class="btn btn-outline-secondary bg-white fw-semibold py-2 d-flex align-items-center justify-content-center gap-1" onclick="posSetPayment('qris')">
                        <i data-lucide="qr-code" class="icon-xs"></i> QRIS
                    </button>
                </div>

                <!-- Input Tunai & Pecahan Cepat -->
                <div id="pos-cash-box" class="mb-3">
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-white text-muted fw-semibold">Rp</span>
                        <input type="text" inputmode="numeric" name="amount_paid" id="pos-amount-paid" class="form-control fw-bold fs-5 py-2" placeholder="0" oninput="posFormatCashInput(this)">
                    </div>
                    <div id="pos-quick-cash" class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-btn-sm py-1 flex-fill small" onclick="posQuickCash('exact')">Uang Pas</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-btn-sm py-1 flex-fill small" onclick="posQuickCash(20000)">20rb</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-btn-sm py-1 flex-fill small" onclick="posQuickCash(50000)">50rb</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-btn-sm py-1 flex-fill small" onclick="posQuickCash(100000)">100rb</button>
                    </div>
                </div>

                <!-- Ringkasan Total & Kembalian -->
                <div class="pt-2 border-top mb-3">
                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <span class="text-muted small fw-medium">Total Tagihan</span>
                        <span class="fw-bold text-dark fs-5" id="pos-total-display">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Kembalian</span>
                        <span class="fw-semibold text-muted small" id="pos-change-display">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="pos-submit-btn" class="btn btn-primary w-100 rounded-btn-sm fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" disabled>
                    <i data-lucide="banknote" class="icon-sm"></i>
                    <span>Transaksi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($receipt_data): ?>
    <!-- Modal Struk Belanja Siap Cetak -->
    <div class="modal fade" id="modalPosReceipt" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="modal-header border-bottom px-4 py-3 bg-white d-print-none">
                    <div class="d-flex align-items-center gap-2 text-success">
                        <i data-lucide="check-circle" class="icon-md"></i>
                        <h6 class="modal-title fw-bold mb-0">Transaksi Berhasil!</h6>
                    </div>
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
                        <div class="d-flex justify-content-between">
                            <span>No: <strong class="text-dark">#<?= htmlspecialchars($receipt_data['TransactionID']) ?></strong></span>
                            <span><?= date('d/m/Y H:i', strtotime($receipt_data['TransactionDate'])) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Kasir: <?= htmlspecialchars($receipt_data['CashierName'] ?? '-') ?></span>
                            <span>Bayar: <strong class="text-uppercase text-dark"><?= htmlspecialchars($receipt_data['PaymentMethod']) ?></strong></span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3 pb-2 border-bottom">
                        <?php foreach ($receipt_data['Items'] as $rit): ?>
                            <div class="d-flex justify-content-between align-items-start small">
                                <div>
                                    <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($rit['ProductName'] ?? $rit['ProductID']) ?></span>
                                    <small class="text-muted"><?= (int)$rit['Quantity'] ?> <?= htmlspecialchars($rit['Unit'] ?? 'pcs') ?> × Rp <?= number_format($rit['SellingPriceAtTransaction'], 0, ',', '.') ?></small>
                                </div>
                                <span class="fw-semibold text-dark">Rp <?= number_format($rit['Subtotal'], 0, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between fw-bold text-dark mb-1">
                        <span>TOTAL</span>
                        <span>Rp <?= number_format($receipt_data['TotalAmount'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Dibayar</span>
                        <span>Rp <?= number_format($receipt_data['AmountPaid'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Kembalian</span>
                        <span>Rp <?= number_format($receipt_data['ChangeAmount'], 0, ',', '.') ?></span>
                    </div>

                    <div class="text-center mt-3 pt-2 border-top">
                        <small class="text-muted" style="font-size: 0.725rem;">Terima kasih telah berbelanja!</small>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light px-4 py-2 d-flex justify-content-between d-print-none">
                    <a href="<?= $kasir_url ?>" class="btn btn-sm btn-outline-secondary rounded-btn-sm text-decoration-none">Transaksi Baru</a>
                    <button type="button" class="btn btn-sm btn-primary rounded-btn-sm d-inline-flex align-items-center gap-1 fw-semibold" onclick="window.print()">
                        <i data-lucide="printer" class="icon-xs"></i> Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Peringatan Batas Stok Kasir -->
<div class="modal fade" id="modalPosStockLimit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-warning-subtle text-warning-emphasis rounded-3">
                        <i data-lucide="alert-triangle" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0">Batas Stok Tercapai</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Jumlah melebihi persediaan</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-dark small mb-0" id="pos-stock-limit-msg"></p>
            </div>
            <div class="modal-footer border-top bg-light px-4 py-2">
                <button type="button" class="btn btn-sm btn-primary px-4 rounded-btn-sm fw-semibold" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>