<div class="row g-0">

    <!-- Kolom Kiri: Visual Branding (Desktop) -->
    <div class="col-lg-5 d-none d-lg-flex register-brand-panel">
        <div>
            <!-- Header Brand: Logo + Tulisan WarungKu -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="/assets/image/logo.png" alt="Logo WarungKu" class="register-brand-logo">
                <span class="register-brand-name">WarungKu</span>
            </div>

            <h4 class="fw-bold text-white mb-2 lh-sm">Solusi Cerdas Kelola Warung</h4>
            <p class="text-white-50 small mb-4">Kelola kasir, pantau stok barang, dan rekap omzet harian dengan mudah.</p>

            <div class="brand-features mt-3">
                <div class="brand-feature-item">
                    <div class="brand-feature-icon">
                        <i data-lucide="zap" class="text-white icon-sm"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-white small">Transaksi Kasir Cepat</div>
                        <div class="text-white-50" style="font-size: 0.775rem;">Layani pelanggan lebih gesit tanpa ribet hitung manual.</div>
                    </div>
                </div>

                <div class="brand-feature-item">
                    <div class="brand-feature-icon">
                        <i data-lucide="package" class="text-white icon-sm"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-white small">Pantau Stok Otomatis</div>
                        <div class="text-white-50" style="font-size: 0.775rem;">Ketahui sisa barang dagangan secara real-time.</div>
                    </div>
                </div>

                <div class="brand-feature-item">
                    <div class="brand-feature-icon">
                        <i data-lucide="trending-up" class="text-white icon-sm"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-white small">Laporan Laba Harian</div>
                        <div class="text-white-50" style="font-size: 0.775rem;">Lihat omzet dan keuntungan bersih setiap hari.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="brand-testimonial-box mt-3">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i data-lucide="store" class="text-warning icon-sm"></i>
                <span class="text-white fw-semibold small">Dirancang Khusus Warung & UMKM</span>
            </div>
            <p class="text-white-50 mb-0" style="font-size: 0.75rem;">Aplikasi simpel, ringan, dan mudah digunakan siapa saja.</p>
        </div>
    </div>

    <!-- Kolom Kanan: Form Pendaftaran -->
    <div class="col-lg-7 register-form-panel">
        
        <!-- Header Mobile -->
        <div class="d-lg-none d-flex align-items-center gap-2 mb-3">
            <img src="/assets/image/logo.png" alt="Logo WarungKu" class="register-brand-logo">
            <span class="fs-5 fw-bold text-primary">WarungKu</span>
        </div>

        <div class="mb-3">
            <h4 class="fw-bold text-dark mb-1">Daftar Warung Baru</h4>
            <p class="text-muted small mb-0">Isi informasi warung dan akun pemilik untuk mulai.</p>
        </div>

        <?php require APP_PATH . '/views/components/alerts/alert_message.php'; ?>

        <form method="post" action="/register">

            <!-- Section 1: Informasi Warung -->
            <div class="mb-3">
                <div class="form-section-clean">
                    <span class="form-section-pill">1</span>
                    <span class="form-section-text">Informasi Warung</span>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label for="store_name" class="form-label text-muted">
                            Nama Warung <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="store_name" name="store_name"
                               class="form-control form-control-standard"
                               value="<?= htmlspecialchars($old_input['store_name'] ?? '') ?>"
                               placeholder="Contoh: Warung Berkah" required>
                    </div>
                    <div class="col-6">
                        <label for="store_address" class="form-label text-muted">
                            Alamat <span class="fw-normal text-muted">(Opsional)</span>
                        </label>
                        <input type="text" id="store_address" name="store_address"
                               class="form-control form-control-standard"
                               value="<?= htmlspecialchars($old_input['store_address'] ?? '') ?>"
                               placeholder="Jl. Merdeka No. 10">
                    </div>
                </div>
            </div>

            <hr class="my-2 text-muted opacity-25">

            <!-- Section 2: Data Pemilik Warung -->
            <div class="mb-3">
                <div class="form-section-clean">
                    <span class="form-section-pill">2</span>
                    <span class="form-section-text">Data Pemilik</span>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label for="full_name" class="form-label text-muted">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name"
                               class="form-control form-control-standard"
                               value="<?= htmlspecialchars($old_input['full_name'] ?? '') ?>"
                               placeholder="Budi Santoso" required>
                    </div>
                    <div class="col-6">
                        <label for="user_name" class="form-label text-muted">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="user_name" name="user_name"
                               class="form-control form-control-standard"
                               value="<?= htmlspecialchars($old_input['user_name'] ?? '') ?>"
                               placeholder="budisantoso" required>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label for="email" class="form-label text-muted">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                               class="form-control form-control-standard"
                               value="<?= htmlspecialchars($old_input['email'] ?? '') ?>"
                               placeholder="nama@email.com" required>
                    </div>
                    <div class="col-6">
                        <label for="phone_number" class="form-label text-muted">
                            No. WhatsApp/HP <span class="text-danger">*</span>
                        </label>
                        <input type="tel" id="phone_number" name="phone_number"
                               class="form-control form-control-standard"
                               value="<?= htmlspecialchars($old_input['phone_number'] ?? '') ?>"
                               placeholder="08123456789" required>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label for="user_password" class="form-label text-muted">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="user_password" name="user_password"
                               class="form-control form-control-standard"
                               placeholder="Min. 6 karakter" minlength="6" required>
                    </div>
                    <div class="col-6">
                        <label for="password_again" class="form-label text-muted">
                            Konfirmasi <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="password_again" name="password_again"
                               class="form-control form-control-standard"
                               placeholder="Ulangi password" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-standard w-100 py-2 fw-semibold mt-3">
                <i data-lucide="check-circle" class="icon-sm me-1"></i> Daftar Warung Sekarang
            </button>

        </form>

        <div class="text-center pt-3 mt-3 border-top">
            <p class="small text-muted mb-0">
                Sudah punya akun warung? 
                <a href="/login" class="text-primary text-decoration-none fw-semibold">Masuk</a>
            </p>
        </div>

    </div>

</div>
