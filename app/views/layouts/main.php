<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WarungKu | <?= htmlspecialchars($page_title ?? 'Dashboard') ?></title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- 1. CSS Main (Global) -->
    <link rel="stylesheet" href="/assets/css/main.css">

    <!-- 2. CSS Spesifik Halaman (Dari folder view masing-masing) -->
    <?php if (!empty($page_css)): ?>
        <style>
            <?= $page_css ?>
        </style>
    <?php endif; ?>

    <!-- Lucide Icons & ApexCharts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body class="bg-body-app">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar Container -->
        <aside id="sidebar-container">
            <?php require APP_PATH . '/views/partials/' . $role_name . '/sidebar.php'; ?>
        </aside>

        <!-- Page Content Container -->
        <div id="page-content-wrapper" class="w-100 min-vh-100 d-flex flex-column">
            <!-- Topbar Container -->
            <header id="topbar-container">
                <?php require APP_PATH . '/views/partials/' . $role_name . '/header.php'; ?>
            </header>

            <!-- Main Dynamic Content -->
            <main class="container-fluid px-4 py-4" id="main-content">
                <?= $page_content ?? '' ?>
            </main>
        </div>
    </div>

    <!-- Modal Notifikasi Global -->
    <?php require APP_PATH . '/views/layouts/modals/modal_notification.php'; ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 1. JS Main (Global) -->
    <script src="/assets/js/main.js"></script>

    <!-- 2. JS Spesifik Halaman (Dari folder view masing-masing) -->
    <?php if (!empty($page_js)): ?>
        <script>
            <?= $page_js ?>
        </script>
    <?php endif; ?>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>