<!-- Modal Catat Restock -->
<div class="modal fade" id="modalRestock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/restock" class="modal-content border-0 shadow rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="modal-header border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="package-plus" width="20" height="20"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0">Catat Barang Masuk (Restock)</h6>
                        <small class="text-secondary">Tambah stok dan perbarui harga beli produk</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Pilih Produk <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select rounded-3 py-2" required>
                        <?php foreach ($product_list as $p): ?>
                            <option value="<?= $p['ProductID'] ?>"><?= $p['ProductName'] ?> (Stok saat ini: <?= $p['Stock'] ?> <?= $p['Unit'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-secondary">Jumlah Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control rounded-3 py-2" min="1" placeholder="0" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-secondary">Harga Beli Satuan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary fw-semibold rounded-start-3">Rp</span>
                            <input type="number" name="purchase_price" class="form-control rounded-end-3 py-2" placeholder="0" required>
                        </div>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold text-secondary">
                        Catatan <span class="text-secondary fw-normal">(Supplier / Keterangan, opsional)</span>
                    </label>
                    <textarea name="note" class="form-control rounded-3" rows="2" placeholder="Contoh: Kulakan dari Agen Sembako Makmur"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 gap-2">
                <button type="button" class="btn bg-white border text-secondary rounded-3 px-3 py-2 fw-semibold" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
                    <i data-lucide="check" width="16" height="16"></i>
                    <span>Simpan Restock</span>
                </button>
            </div>

        </form>
    </div>
</div>