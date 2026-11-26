<!-- Modal Tambah & Edit Kategori (Terpadu) -->
<div class="modal fade" id="modalFormKategori" tabindex="-1" aria-labelledby="modalFormKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form method="POST" action="/owner/kategori" id="form-kategori" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <input type="hidden" name="action" id="cat-action" value="tambah">
            <input type="hidden" name="category_id" id="cat-id">

            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="tag" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalFormKategoriLabel">Tambah Kategori Baru</h6>
                        <small class="text-secondary">Atur pengelompokan produk warung</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="cat-name" class="form-label small fw-semibold text-secondary mb-2">
                        Nama Kategori <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="category_name"
                           id="cat-name"
                           class="form-control rounded-3 bg-light py-2 px-3 shadow-none"
                           placeholder="Contoh: Sembako, Minuman, Snack"
                           autocomplete="off"
                           required
                           autofocus>
                    <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                        Gunakan nama singkat dan jelas agar mudah dicari di kasir.
                    </small>
                </div>

                <div class="mb-1 d-none" id="cat-status-group">
                    <label for="cat-is-active" class="form-label small fw-semibold text-secondary mb-2">Status Kategori</label>
                    <select name="is_active" id="cat-is-active" class="form-select rounded-3 bg-light py-2 px-3 shadow-none">
                        <option value="1">Aktif (Tampil di Kasir)</option>
                        <option value="0">Non-Aktif Sementara</option>
                    </select>
                    <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                        Kategori non-aktif tidak muncul di halaman kasir.
                    </small>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
                    <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                        <i data-lucide="check" class="icon-xs"></i>
                    </span>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>