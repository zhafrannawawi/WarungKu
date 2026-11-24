<?php
http_response_code(404);
$requested = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-body-tertiary">
    <main class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="card border-0 shadow-sm text-center" style="max-width: 480px; width: 100%;">
            <div class="card-body p-4 p-md-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-4" style="width: 88px; height: 88px;">
                    <i data-lucide="search-x" style="width: 44px; height: 44px;"></i>
                </div>

                <h1 class="display-1 fw-bold text-primary mb-0">404</h1>
                <h2 class="h4 mb-3">Halaman Tidak Ditemukan</h2>
                <p class="text-body-secondary mb-5">
                    Maaf, halaman yang kamu cari tidak ada atau sudah dipindahkan.
                </p>

                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="/" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                        <i data-lucide="home" width="18" height="18"></i> Ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>