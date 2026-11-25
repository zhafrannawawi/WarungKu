<!-- Modal Tambah & Edit Produk (Terpadu) -->
<div class="modal fade" id="modalFormProduk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFormProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/produk" id="form-produk" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <input type="hidden" name="action" id="prod-action" value="tambah">
            <input type="hidden" name="product_id" id="prod-id">

            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="package" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalFormProdukLabel">Tambah Produk Baru</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Lengkapi informasi data barang</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body Form -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="prod-name" class="form-control" placeholder="Contoh: Beras Ramos Setra 5kg" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="prod-category" class="form-select" required>
                            <?php foreach ($category_list as $c): ?>
                                <option value="<?= htmlspecialchars($c['CategoryID']) ?>"><?= htmlspecialchars($c['CategoryName']) ?></option>
                            <?php endforeach; ?>
                            <?php if (empty($category_list)): ?>
                                <option value="">Belum ada kategori</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="unit" id="prod-unit" class="form-control" placeholder="pcs / kg / bks" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Harga Beli Satuan <span class="text-danger">*</span></label>
                        <input type="number" name="purchase_price" id="prod-purchase-price" class="form-control" placeholder="Rp" min="0" step="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Harga Jual Satuan <span class="text-danger">*</span></label>
                        <input type="number" name="selling_price" id="prod-selling-price" class="form-control" placeholder="Rp" min="0" step="1" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Stok Fisik <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="prod-stock" class="form-control" value="0" min="0" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Batas Min. Stok <span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" id="prod-min-stock" class="form-control" value="5" min="0" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Lokasi / Rak Penyimpanan (Opsional)</label>
                    <input type="text" name="location" id="prod-location" class="form-control" placeholder="Contoh: Rak A-02">
                </div>

                <div class="mb-2 d-none" id="prod-status-group">
                    <label class="form-label small fw-semibold text-muted mb-1">Status Produk</label>
                    <select name="is_active" id="prod-is-active" class="form-select">
                        <option value="1">Aktif (Dijual di Kasir)</option>
                        <option value="0">Non-Aktif Sementara</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="check" class="icon-sm"></i>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>
