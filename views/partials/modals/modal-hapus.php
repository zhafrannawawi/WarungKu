<!-- Modal Konfirmasi Hapus Terpadu -->
<div class="modal fade" id="modalHapusGlobal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 410px;">
        <form id="delete-modal-form" method="POST" action="" class="modal-content border-0 shadow rounded-4 overflow-hidden position-relative">
            <input type="hidden" name="action" value="hapus">
            <input type="hidden" id="delete-modal-id" name="" value="">

            <!-- Tombol Tutup -->
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Tutup"></button>

            <!-- Modal Body (Hero & Konten) -->
            <div class="modal-body p-4 text-center">
                <!-- Danger Icon Badge -->
                <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-danger-subtle text-danger" style="width: 58px; height: 58px; border: 4px solid #fef2f2;">
                    <i data-lucide="trash-2" style="width: 24px; height: 24px;"></i>
                </div>

                <!-- Judul & Deskripsi -->
                <h5 class="fw-bold text-dark mb-1" id="delete-modal-title">Konfirmasi Hapus</h5>
                <p class="text-secondary small mb-3">
                    Tindakan ini akan menghapus data terpilih dari daftar aktif warung Anda.
                </p>

                <!-- Kartu Item yang Akan Dihapus -->
                <div class="p-3 bg-light border rounded-3 text-start d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                        <div class="sidebar-user-avatar" style="width: 38px; height: 38px;">
                            <i data-lucide="layers" class="icon-sm"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-semibold text-dark text-truncate" id="delete-modal-item-name" style="font-size: 0.875rem;"></div>
                            <small class="text-muted d-block text-truncate" id="delete-modal-item-type" style="font-size: 0.75rem;"></small>
                        </div>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill flex-shrink-0" style="font-size: 0.7rem;">
                        Akan Dihapus
                    </span>
                </div>

                <!-- Kotak Catatan -->
                <div class="py-2 px-3 bg-warning-subtle text-dark border border-warning-subtle rounded-3 d-flex align-items-center gap-2 text-start mb-0">
                    <i data-lucide="alert-triangle" class="text-warning flex-shrink-0" style="width: 18px; height: 18px;"></i>
                    <span id="delete-modal-note" style="font-size: 0.8125rem; line-height: 1.35;"></span>
                </div>
            </div>

            <!-- Modal Footer (Tombol Aksi) -->
            <div class="modal-footer border-0 bg-white px-4 pb-4 pt-0 d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border text-secondary flex-fill py-2 rounded-3 fw-medium" data-bs-dismiss="modal" style="font-size: 0.875rem;">
                    Batal
                </button>
                <button type="submit" class="btn btn-danger flex-fill py-2 rounded-3 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 shadow-sm" style="font-size: 0.875rem;">
                    <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                    <span>Ya, Hapus</span>
                </button>
            </div>
        </form>
    </div>
</div>
