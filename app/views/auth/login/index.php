<div class="row g-0">

    <!-- Kolom Kiri: Visual Branding Senada Register (Desktop) -->
    <div class="col-lg-5 d-none d-lg-flex login-brand-panel">
        <div>
            <!-- Header Brand: Logo + Tulisan WarungKu -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="/assets/image/logo.png" alt="Logo WarungKu" class="login-brand-logo">
                <span class="login-brand-name">WarungKu</span>
            </div>

            <h4 class="fw-bold text-white mb-2 lh-sm">Selamat Datang Kembali</h4>
            <p class="text-white-50 small mb-4">Masuk untuk melanjutkan operasional warung, shift kasir, dan pantau omzet toko.</p>

            <div class="mt-4">
                <div class="login-feature-item">
                    <div class="login-feature-icon">
                        <i data-lucide="shield-check" class="text-white icon-sm"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-white small">Akses Aman & Privat</div>
                        <div class="text-white-50" style="font-size: 0.775rem;">Data penjualan toko Anda terjaga dengan aman.</div>
                    </div>
                </div>

                <div class="login-feature-item">
                    <div class="login-feature-icon">
                        <i data-lucide="zap" class="text-white icon-sm"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-white small">Ringan & Cepat</div>
                        <div class="text-white-50" style="font-size: 0.775rem;">Siap melayani antrean transaksi pelanggan.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-mini-info">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i data-lucide="store" class="text-warning icon-sm"></i>
                <span class="text-white fw-semibold small">Dirancang Khusus Warung & UMKM</span>
            </div>
            <p class="text-white-50 mb-0" style="font-size: 0.75rem;">Aplikasi simpel untuk memajukan usaha Anda.</p>
        </div>
    </div>

    <!-- Kolom Kanan: Form Login (Tengah Vertikal) -->
    <div class="col-lg-7 login-form-panel">
        
        <!-- Header Mobile Saja -->
        <div class="d-lg-none d-flex align-items-center gap-2 mb-3">
            <img src="/assets/image/logo.png" alt="Logo WarungKu" class="login-brand-logo">
            <span class="fs-5 fw-bold text-primary">WarungKu</span>
        </div>

        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Masuk ke WarungKu</h4>
            <p class="text-muted small mb-0">Masukkan username dan password akun Anda.</p>
        </div>

        <?php if ($error_text): ?>
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3" role="alert">
                <i data-lucide="alert-circle" class="icon-xs me-1"></i>
                <?= htmlspecialchars($error_text) ?>
            </div>
        <?php endif; ?>

        <?php if ($success_text): ?>
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-3" role="alert">
                <i data-lucide="check-circle" class="icon-xs me-1"></i>
                <?= htmlspecialchars($success_text) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/login">
            
            <div class="mb-3">
                <label for="user_name" class="form-label text-muted">Username</label>
                <div class="position-relative">
                    <input type="text" id="user_name" name="user_name"
                           class="form-control form-control-standard"
                           placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="user_password" class="form-label text-muted">Password</label>
                <div class="position-relative">
                    <input type="password" id="user_password" name="user_password"
                           class="form-control form-control-standard pe-5"
                           placeholder="Masukkan password" required>
                    <button type="button" class="btn-toggle-pass" onclick="togglePasswordVisibility()" title="Tampilkan/Sembunyikan Password">
                        <i data-lucide="eye" id="eye-icon" class="icon-xs"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-standard w-100 py-2 fw-semibold shadow-sm mb-3">
                <i data-lucide="log-in" class="icon-sm me-1"></i> Masuk Sekarang
            </button>

        </form>

        <div class="text-center pt-3 border-top">
            <p class="text-muted mb-0" style="font-size: 0.775rem;">
                Belum punya akun warung? 
                <a href="/register" class="text-primary text-decoration-none fw-semibold">Daftar Warung Baru</a>
            </p>
        </div>

    </div>

</div>
