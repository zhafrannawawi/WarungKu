<?php
// 1. Kumpulkan semua pesan notifikasi ke dalam array
$alerts = [];

if (!empty($success_message)) {
    $alerts[] = [
        'type' => 'success',
        'icon' => 'check-circle',
        'text' => $success_message
    ];
}

if (!empty($error_message)) {
    $alerts[] = [
        'type' => 'danger',
        'icon' => 'alert-circle',
        'text' => $error_message
    ];
}

if (!empty($warning_message)) {
    $alerts[] = [
        'type' => 'warning',
        'icon' => 'alert-triangle',
        'text' => $warning_message
    ];
}

if (!empty($info_message)) {
    $alerts[] = [
        'type' => 'info',
        'icon' => 'info',
        'text' => $info_message
    ];
}
?>

<!-- 2. Tampilkan alert jika ada pesan -->
<?php if (!empty($alerts)): ?>
    <div class="alert-fixed-container">
        <?php foreach ($alerts as $item): ?>
            <div class="alert alert-<?= $item['type'] ?> alert-dismissible fade show shadow-sm d-flex align-items-center mb-2" role="alert">
                <i data-lucide="<?= $item['icon'] ?>" class="icon-sm me-2 flex-shrink-0"></i>
                <div class="small fw-semibold me-2"><?= htmlspecialchars($item['text']) ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 3. Hilangkan otomatis setelah 4 detik -->
    <script>
        setTimeout(function() {
            var alertList = document.querySelectorAll('.alert-fixed-container .alert');
            alertList.forEach(function(alertEl) {
                if (window.bootstrap && bootstrap.Alert) {
                    bootstrap.Alert.getOrCreateInstance(alertEl).close();
                } else {
                    alertEl.remove();
                }
            });
        }, 4000);
    </script>
<?php endif; ?>
