<!-- Modal Tambah & Edit Produk (Terpadu) -->
<div class="modal fade" id="modalFormProduk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/produk" id="form-produk" class="modal-content border-0 shadow">
            <input type="hidden" name="action" id="prod-action" value="tambah">
            <input type="hidden" name="product_id" id="prod-id">

            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="modalFormProdukLabel">Tambah Produk Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="prod-name" class="form-control" placeholder="Contoh: Beras Ramos 5kg" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Kategori</label>
                        <select name="category_id" id="prod-category" class="form-select" required>
                            <?php foreach ($category_list as $c): ?>
                                <option value="<?= $c['CategoryID'] ?>"><?= htmlspecialchars($c['CategoryName']) ?></option>
                            <?php endforeach; ?>
                            <?php if (empty($category_list)): ?>
                                <option value="0"></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Satuan<span class="text-danger">*</span></label>
                        <input type="text" name="unit" id="prod-unit" class="form-control" placeholder="pcs / kg / bks" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Harga Beli Satuan <span class="text-danger">*</span></label>
                        <input type="number" name="purchase_price" class="form-control" placeholder="Contoh: 12000" min="0" step="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="selling_price" id="prod-selling-price" class="form-control" placeholder="15.0000" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Stok Barang <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="prod-stock" class="form-control" value="0" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Batas Min. Stok <span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" id="prod-min-stock" class="form-control" value="5" required>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted">Lokasi / Rak (Opsional)</label>
                    <input type="text" name="location" id="prod-location" class="form-control" placeholder="Rak A1, Etalase Depan">
                </div>
            </div>

            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i data-lucide="save" class="icon-sm"></i> Simpan Produk</button>
            </div>
        </form>
    </div>
</div>