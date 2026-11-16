<div class="topbar-header px-4 d-flex align-items-center justify-content-between">
    <!-- Sisi Kiri: Tombol Toggle Mobile & Identitas Toko -->
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-circle d-md-none" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <i data-lucide="menu"></i>
        </button>

        <div>
            <span class="fw-semibold text-dark font-sm"><?= htmlspecialchars($_SESSION['store_name'] ?? 'WarungKu') ?></span>
        </div>
    </div>

    <!-- Sisi Kanan: Tombol Buka Modal Notifikasi -->
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-circle position-relative" data-bs-toggle="modal" data-bs-target="#modalNotification" title="Pusat Notifikasi">
            <i data-lucide="bell" class="icon-xl"></i>
        </button>
    </div>
</div>

