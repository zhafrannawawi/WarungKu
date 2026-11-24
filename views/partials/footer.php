        </main>
    </div>
</div>

<!-- Modal Partial Global -->
<?php include __DIR__ . '/modals/modal-notifikasi.php'; ?>
<?php include __DIR__ . '/modals/modal-logout.php'; ?>
<?php include __DIR__ . '/modals/modal-profil-warung.php'; ?>
<?php include __DIR__ . '/modals/modal-ganti-password.php'; ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS Utama -->
<script src="/assets/js/main.js?v=<?= filemtime(__DIR__ . '/../../assets/js/main.js') ?>"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>
