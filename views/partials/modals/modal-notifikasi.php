<!-- Modal Notifikasi Stok Menipis (F011 — Owner & Kasir) -->
<div class="modal fade" id="modalNotification" tabindex="-1" aria-labelledby="modalNotificationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-warning-subtle text-warning-emphasis rounded-3">
                        <i data-lucide="bell" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalNotificationLabel">Notifikasi Stok Barang</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Produk dengan stok di bawah atau sama dengan batas minimum</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" style="max-height: 360px; overflow-y: auto;">
                <?php if (empty($notif_low_stock)): ?>
                    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center px-4">
                        <div class="p-3 rounded-circle bg-success-subtle text-success mb-2">
                            <i data-lucide="check-circle" class="icon-lg"></i>
                        </div>
                        <p class="text-dark fw-semibold small mb-1">Semua Stok Produk Aman</p>
                        <p class="text-muted small mb-0" style="font-size: 0.775rem;">Tidak ada produk yang berada di bawah batas minimum saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($notif_low_stock as $np): ?>
                            <div class="list-group-item px-4 py-3 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <span class="fw-semibold text-dark small d-block"><?= escape_html($np['ProductName']) ?></span>
                                    <small class="text-muted" style="font-size: 0.725rem;">
                                        Batas min: <?= (int)$np['MinimumStock'] ?> <?= escape_html($np['Unit']) ?><?= !empty($np['Location']) ? ' • ' . escape_html($np['Location']) : '' ?>
                                    </small>
                                </div>
                                <span class="badge-status danger">
                                    <?= (int)$np['Stock'] <= 0 ? 'Habis (0)' : 'Sisa ' . (int)$np['Stock'] . ' ' . escape_html($np['Unit']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-footer border-top py-2 px-4 bg-light d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size: 0.75rem;"><?= count($notif_low_stock ?? []) ?> produk perlu perhatian</small>
                <div class="d-flex gap-2">
                    <?php if (($role ?? '') === 'owner' && !empty($notif_low_stock)): ?>
                        <a href="/owner/restock" class="btn btn-primary btn-sm rounded-btn-sm">Restock Sekarang</a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
