<!-- Modal Catat Restock -->
<div class="modal fade" id="modalRestock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/restock" class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold">Catat Barang Masuk (Restock)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Pilih Produk <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select" required>
                        <?php foreach ($product_list as $p): ?>
                            <option value="<?= $p['ProductID'] ?>"><?= $p['ProductName'] ?> (Stok saat ini: <?= $p['Stock'] ?> <?= $p['Unit'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Jumlah Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Harga Beli Satuan <span class="text-danger">*</span></label>
                        <input type="number" name="purchase_price" class="form-control" placeholder="Rp" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted">Catatan (Supplier / Keterangan)</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Kulakan dari Agen Sembako Makmur"></textarea>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Restock</button>
            </div>
        </form>
    </div>
</div>
