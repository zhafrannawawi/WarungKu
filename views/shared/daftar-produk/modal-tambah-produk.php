<!-- Modal Tambah & Edit Produk (Terpadu) -->
<div class="modal fade" id="modalFormProduk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFormProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="/owner/produk" id="form-produk" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <input type="hidden" name="action" id="prod-action" value="tambah">
            <input type="hidden" name="product_id" id="prod-id">

            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="package" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalFormProdukLabel">Tambah Produk Baru</h5>
                        <p class="text-secondary mb-0 small">Lengkapi informasi data barang</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body Form -->
            <div class="modal-body p-4" style="background-color: #f8f9fa;">

                <!-- Seksi 1: Informasi Produk -->
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-inline-flex">
                            <i data-lucide="tag" width="16" height="16"></i>
                        </div>
                        <span class="fw-bold text-dark">Informasi Produk</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-medium mb-1">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="prod-name" class="form-control rounded-3 px-3 py-2" placeholder="Contoh: Beras Ramos Setra 5kg" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-secondary fw-medium mb-1">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="prod-category" class="form-select rounded-3 px-3 py-2" required>
                                <?php foreach ($category_list as $c): ?>
                                    <option value="<?= escape_html($c['CategoryID']) ?>"><?= escape_html($c['CategoryName']) ?></option>
                                <?php endforeach; ?>
                                <?php if (empty($category_list)): ?>
                                    <option value="">Belum ada kategori</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-secondary fw-medium mb-1">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="prod-unit" class="form-control rounded-3 px-3 py-2" placeholder="pcs / kg / bks" required>
                        </div>
                    </div>
                </div>

                <!-- Seksi 2: Harga -->
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-inline-flex">
                            <i data-lucide="banknote" width="16" height="16"></i>
                        </div>
                        <span class="fw-bold text-dark">Harga</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-secondary fw-medium mb-1">Harga Beli Satuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary fw-semibold small rounded-start-3">Rp</span>
                                <input type="number" name="purchase_price" id="prod-purchase-price" class="form-control rounded-end-3 px-3 py-2" placeholder="0" min="0" step="1" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-secondary fw-medium mb-1">Harga Jual Satuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary fw-semibold small rounded-start-3">Rp</span>
                                <input type="number" name="selling_price" id="prod-selling-price" class="form-control rounded-end-3 px-3 py-2" placeholder="0" min="0" step="1" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seksi 3: Stok & Lokasi -->
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-inline-flex">
                            <i data-lucide="boxes" width="16" height="16"></i>
                        </div>
                        <span class="fw-bold text-dark">Stok &amp; Lokasi</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-secondary fw-medium mb-1">Stok Fisik <span class="text-danger">*</span></label>
                            <input type="number" name="stock" id="prod-stock" class="form-control rounded-3 px-3 py-2" value="0" min="0" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-secondary fw-medium mb-1">Batas Min. Stok <span class="text-danger">*</span></label>
                            <input type="number" name="min_stock" id="prod-min-stock" class="form-control rounded-3 px-3 py-2" value="5" min="0" required>
                            <small class="text-secondary d-block mt-1" style="font-size: 0.72rem;">Muncul di Peringatan Stok Menipis</small>
                        </div>
                    </div>

                    <div class="mb-0" id="prod-location-group">
                        <label class="form-label small text-secondary fw-medium mb-1">Lokasi / Rak Penyimpanan <span class="text-secondary fw-normal">(Opsional)</span></label>
                        <input type="text" name="location" id="prod-location" class="form-control rounded-3 px-3 py-2" placeholder="Contoh: Rak A-02">
                    </div>

                    <div class="mt-3 d-none" id="prod-status-group">
                        <label class="form-label small text-secondary fw-medium mb-1">Status Produk</label>
                        <select name="is_active" id="prod-is-active" class="form-select rounded-3 px-3 py-2">
                            <option value="1">Aktif (Dijual di Kasir)</option>
                            <option value="0">Non-Aktif Sementara</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-white px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                    <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                        <i data-lucide="check" class="icon-xs"></i>
                    </span>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>