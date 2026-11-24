<!-- Modal Tambah & Edit Kategori (Terpadu) -->
<div class="modal fade" id="modalFormKategori" tabindex="-1" aria-labelledby="modalFormKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/owner/kategori" id="form-kategori" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <input type="hidden" name="action" id="cat-action" value="tambah">
            <input type="hidden" name="category_id" id="cat-id">
            
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="tag" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalFormKategoriLabel">Tambah Kategori Baru</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Atur pengelompokan produk warung</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" id="cat-name" class="form-control" placeholder="Contoh: Sembako, Minuman, Snack" required autofocus>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="check" class="icon-sm"></i>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>
