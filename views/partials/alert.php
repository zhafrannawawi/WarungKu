<?php
$alert = get_alert();
if ($alert):
?>
    <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show shadow-sm d-flex align-items-center position-fixed end-0 me-3 py-3 px-3" role="alert" style="z-index:1080; top:88px; min-width:280px; max-width:90vw">
        <div class="fw-semibold small flex-grow-1"><i class=""></i><?= $alert['message'] ?></div>
        <button type="button" class="btn-close position-static ms-3 p-1" style="font-size:.65rem" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    <script>
        setTimeout(function() {
            var alertEl = document.querySelector('.alert');
            if (alertEl && window.bootstrap && bootstrap.Alert) {
                bootstrap.Alert.getOrCreateInstance(alertEl).close();
            }
        }, 4000);
    </script>
<?php endif; ?>