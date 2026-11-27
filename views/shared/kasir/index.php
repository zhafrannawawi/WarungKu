<?php include __DIR__ . '/../../partials/header.php'; ?>

<!-- Page Header: Transaksi Kasir -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i data-lucide="shopping-cart" class="icon-sm"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0">Transaksi Kasir</h4>
            </div>
            <p class="text-secondary mb-0 small">Pilih barang pada daftar produk untuk mencatat belanjaan dan pembayaran pelanggan.</p>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-white border-0 text-secondary small fw-medium rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 pe-none">
            <i data-lucide="calendar" width="16" height="16"></i>
            <span><?= escape_html($tanggal_hari_ini) ?></span>
        </span>
        <a href="<?= $role_prefix ?>/transaksi" class="btn bg-white border-0 text-secondary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold text-decoration-none">
            <i data-lucide="receipt" width="16" height="16"></i>
            <span>Riwayat Transaksi</span>
        </a>
    </div>
</div>

<?php include __DIR__ . '/../../partials/alert.php'; ?>

<div class="row g-3 align-items-start">
    <div class="col-12 col-lg-8">
        <!-- Toolbar Filter Langsung di Atas Grid -->
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
            <div class="input-group shadow-sm rounded-3 flex-grow-1">
                <span class="input-group-text bg-white border-0 text-secondary ps-3">
                    <i data-lucide="search" width="16" height="16"></i>
                </span>
                <input type="text" id="pos-product-search" class="form-control bg-white border-0 py-2 shadow-none" placeholder="Cari nama produk..." oninput="filterPosProducts()">
            </div>

            <select id="pos-filter-category" class="form-select border-0 bg-white text-dark fw-medium rounded-3 shadow-sm ps-3 py-2 w-auto" style="padding-right: 2.25rem; min-width: 180px;" onchange="filterPosProducts()">
                <option value="all">Semua Kategori</option>
                <?php foreach ($category_list as $c): ?>
                    <option value="<?= escape_html($c['CategoryName']) ?>"><?= escape_html($c['CategoryName']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Grid Kartu Produk -->
        <?php if (empty($product_list)): ?>
            <div class="bg-white border rounded-4 shadow-sm p-5 text-center text-secondary">
                <i data-lucide="package-x" width="28" height="28" class="mb-2"></i>
                <div class="small">Belum ada produk aktif terdaftar.</div>
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
                        <div class="col-6 col-md-4 px-1 pos-product-row"
                            data-name="<?= escape_html(strtolower($p['ProductName'])) ?>"
                            data-category="<?= escape_html($cat_name) ?>">
                            <div class="bg-white border rounded-4 shadow-sm pos-item-card p-3 h-100 d-flex flex-column justify-content-between <?= $habis ? 'is-empty' : '' ?>"
                                data-product-id="<?= escape_html($p['ProductID']) ?>"
                                <?= $habis ? '' : 'onclick="posAddToCart(' . json_attr($item_payload) . ')"' ?>>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                                        <span class="pos-cat-tag text-truncate"><?= escape_html($cat_name) ?></span>
                                        <?php if (!$habis): ?><span class="pos-item-add"><i data-lucide="plus" class="icon-xs"></i></span><?php endif; ?>
                                    </div>
                                    <div class="fw-semibold text-dark pos-item-name" style="font-size: 0.85rem; line-height: 1.3;">
                                        <?= escape_html($p['ProductName']) ?>
                                    </div>
                                    <small class="text-secondary d-block text-truncate mt-1" style="font-size: 0.7rem;">
                                        per <?= escape_html($p['Unit']) ?><?= !empty($p['Location']) ? ' · ' . escape_html($p['Location']) : '' ?>
                                    </small>
                                </div>

                                <div class="d-flex align-items-center justify-content-between gap-1 pt-3 mt-auto">
                                    <span class="fw-bold text-dark" style="font-size: 0.925rem;"><?= format_rupiah($p['SellingPrice']) ?></span>
                                    <span class="pos-stock-dot fw-semibold <?= $habis ? 'text-danger' : ($menipis ? 'text-warning' : 'text-success') ?>">
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
        <form method="POST" action="<?= $kasir_url ?>" id="pos-checkout-form" class="bg-white border rounded-4 shadow-sm d-flex flex-column pos-cart-sticky">
            <input type="hidden" name="cart_items" id="pos-cart-input" value="[]">

            <!-- Header Card Kanan -->
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-inline-flex">
                        <i data-lucide="shopping-bag" width="18" height="18"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Pesanan</h6>
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold" id="pos-cart-count">0 item</span>
                </div>
                <button type="button" id="pos-btn-clear" class="btn btn-sm btn-link text-danger text-decoration-none p-0 small fw-medium d-none" onclick="posClearCart()">
                    Kosongkan
                </button>
            </div>

            <!-- Daftar Item Pesanan (Flat List, Scroll Internal) -->
            <div class="pos-cart-items px-3" id="pos-cart-list">
                <div class="text-center text-secondary py-5 small">
                    <i data-lucide="shopping-bag" width="32" height="32" class="d-block mx-auto mb-2 opacity-50"></i>
                    Belum ada barang dipilih
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="p-3 border-top bg-light rounded-bottom-4 mt-auto">
                <input type="hidden" name="payment_method" id="pos-payment-input" value="cash">

                <!-- Segmented Toggle Metode Bayar -->
                <label class="form-label small fw-semibold text-secondary mb-1">Metode Pembayaran</label>
                <div class="btn-group btn-group-sm w-100 mb-3 shadow-sm rounded-3" role="group">
                    <button type="button" id="btn-pay-cash" class="btn btn-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-1" onclick="posSetPayment('cash')">
                        <i data-lucide="banknote" class="icon-xs"></i> Tunai
                    </button>
                    <button type="button" id="btn-pay-qris" class="btn btn-outline-secondary bg-white fw-semibold py-2 d-flex align-items-center justify-content-center gap-1" onclick="posSetPayment('qris')">
                        <i data-lucide="qr-code" class="icon-xs"></i> QRIS
                    </button>
                </div>

                <!-- Input Tunai & Pecahan Cepat -->
                <div id="pos-cash-box" class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Uang Diterima</label>
                    <div class="input-group mb-2 shadow-sm rounded-3">
                        <span class="input-group-text bg-white border-0 text-secondary fw-semibold ps-3">Rp</span>
                        <input type="text" inputmode="numeric" name="amount_paid" id="pos-amount-paid" class="form-control bg-white border-0 shadow-none fw-bold fs-5 py-2" placeholder="0" oninput="posFormatCashInput(this)">
                    </div>
                    <div id="pos-quick-cash" class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-3 py-1 flex-fill small fw-medium" onclick="posQuickCash('exact')">Uang Pas</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-3 py-1 flex-fill small fw-medium" onclick="posQuickCash(20000)">20rb</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-3 py-1 flex-fill small fw-medium" onclick="posQuickCash(50000)">50rb</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bg-white rounded-3 py-1 flex-fill small fw-medium" onclick="posQuickCash(100000)">100rb</button>
                    </div>
                </div>

                <!-- Ringkasan Total & Kembalian -->
                <div class="pt-3 border-top mb-3">
                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <span class="text-secondary small fw-medium">Total Tagihan</span>
                        <span class="fw-bold text-primary fs-4" id="pos-total-display">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-medium">Kembalian</span>
                        <span class="fw-semibold text-success small" id="pos-change-display">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="pos-submit-btn" class="btn btn-primary w-100 rounded-3 shadow-sm fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" disabled>
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
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start d-print-none">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                            <i data-lucide="check-circle" class="icon-sm"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Transaksi Berhasil!</h5>
                            <p class="text-secondary mb-0 small">Pembayaran telah dicatat</p>
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
                        <div class="d-flex justify-content-between">
                            <span>No: <strong class="text-dark">#<?= escape_html($receipt_data['TransactionID']) ?></strong></span>
                            <span><?= date('d/m/Y H:i', strtotime($receipt_data['TransactionDate'])) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Kasir: <?= escape_html($receipt_data['CashierName'] ?? '-') ?></span>
                            <span>Bayar: <strong class="text-uppercase text-dark"><?= escape_html($receipt_data['PaymentMethod']) ?></strong></span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3 pb-2 border-bottom">
                        <?php foreach ($receipt_data['Items'] as $rit): ?>
                            <div class="d-flex justify-content-between align-items-start small">
                                <div>
                                    <span class="fw-semibold text-dark d-block"><?= escape_html($rit['ProductName'] ?? $rit['ProductID']) ?></span>
                                    <small class="text-secondary"><?= (int)$rit['Quantity'] ?> <?= escape_html($rit['Unit'] ?? 'pcs') ?> × <?= format_rupiah($rit['SellingPriceAtTransaction']) ?></small>
                                </div>
                                <span class="fw-semibold text-dark"><?= format_rupiah($rit['Subtotal']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between fw-bold text-dark mb-1">
                        <span>TOTAL</span>
                        <span><?= format_rupiah($receipt_data['TotalAmount']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mb-1">
                        <span>Dibayar</span>
                        <span><?= format_rupiah($receipt_data['AmountPaid']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>Kembalian</span>
                        <span><?= format_rupiah($receipt_data['ChangeAmount']) ?></span>
                    </div>

                    <div class="text-center mt-3 pt-2 border-top">
                        <small class="text-secondary" style="font-size: 0.725rem;">Terima kasih telah berbelanja!</small>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between d-print-none">
                    <a href="<?= $kasir_url ?>" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium text-decoration-none">Transaksi Baru</a>
                    <button type="button" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" onclick="window.print()">
                        <i data-lucide="printer" width="16" height="16"></i>
                        <span>Cetak Nota</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Peringatan Batas Stok Kasir -->
<div class="modal fade" id="modalPosStockLimit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning-emphasis rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="alert-triangle" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Batas Stok Tercapai</h5>
                        <p class="text-secondary mb-0 small">Jumlah melebihi persediaan</p>
                    </div>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-2 pb-4">
                <p class="text-dark small mb-0" id="pos-stock-limit-msg"></p>
            </div>
            <div class="modal-footer border-top bg-light px-4 py-3">
                <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4 py-2 fw-semibold" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>