<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'WarungKu') ?> — WarungKu</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- 1. CSS Main (Global untuk seluruh auth) -->
    <link rel="stylesheet" href="/assets/css/main.css">

    <!-- 2. CSS Spesifik Halaman (Dari folder view masing-masing) -->
    <?php if (!empty($page_css)): ?>
        <style>
            <?= $page_css ?>
        </style>
    <?php endif; ?>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-body-app">

    <div class="auth-container auth-container-<?= htmlspecialchars($page_name ?? '') ?>">
        <div class="auth-card auth-card-<?= htmlspecialchars($page_name ?? '') ?> shadow-sm overflow-hidden">
            <?= $page_content ?? '' ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 1. JS Main (Global) -->
    <script src="/assets/js/main.js"></script>

    <!-- 2. JS Spesifik Halaman (Dari folder view masing-masing) -->
    <?php if (!empty($page_js)): ?>
        <script>
            <?= $page_js ?>
        </script>
    <?php endif; ?>

    <script>lucide.createIcons();</script>
</body>
</html>
