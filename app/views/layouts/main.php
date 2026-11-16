<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WarungKu | <?= htmlspecialchars($page_title ?? '-')?></title>

    <!-- 1. CSS Main (Global) -->
    <link rel="stylesheet" href="/assets/css/main.css">

    <!-- 2. CSS Spesifik Halaman (Dari folder view masing-masing) -->
    <?php if (!empty($page_css)): ?>
        <style>
            <?= $page_css ?>
        </style>
    <?php endif; ?>
</head>
<body>
    <?php require APP_PATH . '/views/partials/' . $role_name . '/header.php'; ?>

    <div class="wrapper" id="wrapper">
        <?php require APP_PATH . '/views/partials/' . $role_name . '/sidebar.php'; ?>
        <main><?= $page_content ?? '' ?></main>
    </div>

    <?php require APP_PATH . '/views/partials/footer.php'; ?>

    <!-- 1. JS Main (Global) -->
    <script src="/assets/js/main.js"></script>

    <!-- 2. JS Spesifik Halaman (Dari folder view masing-masing) -->
    <?php if (!empty($page_js)): ?>
        <script>
            <?= $page_js ?>
        </script>
    <?php endif; ?>
</body>
</html>
