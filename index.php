<?php
// ============================================================
// WarungKu - Pintu Masuk Utama & Router
// ============================================================
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = trim($uri, '/');

// Jika file statis nyata (css, js, gambar) diakses via php -S
if ($path !== '' && file_exists(__DIR__ . '/' . $path) && !is_dir(__DIR__ . '/' . $path)) {
    return false;
}

// Daftar rute URL Bersih -> Controller (Owner, Cashier, & Shared)
$routes = [
    // Auth & Profil Akun (F001, F002, F003)
    'login'                   => __DIR__ . '/controllers/auth/login.php',
    'logout'                  => __DIR__ . '/controllers/auth/logout.php',
    'register'                => __DIR__ . '/controllers/auth/register.php',
    'profile/update'          => __DIR__ . '/controllers/auth/profile.php',

    // Khusus Owner (F004, F005, F010, F012, F014)
    'owner/dashboard'         => __DIR__ . '/controllers/owner/dashboard.php',
    'owner/akun-kasir'        => __DIR__ . '/controllers/owner/akun-kasir.php',
    'owner/kategori'          => __DIR__ . '/controllers/owner/kategori.php',
    'owner/restock'           => __DIR__ . '/controllers/owner/restock-barang.php',
    'owner/store'             => __DIR__ . '/controllers/owner/toko.php',
    'owner/laporan/laba-rugi' => __DIR__ . '/controllers/owner/laporan_laba_rugi.php',
    'owner/laporan/stok'      => __DIR__ . '/controllers/owner/laporan_stok.php',
    'owner/laporan/terlaris'  => __DIR__ . '/controllers/owner/laporan_terlaris.php',

    // Khusus Kasir (F013)
    'cashier/dashboard'       => __DIR__ . '/controllers/cashier/dashboard.php',

    // Shared: Dipakai Bersama Owner & Kasir (F006/F007, F008, F009)
    'owner/produk'            => __DIR__ . '/controllers/shared/daftar-produk.php',
    'cashier/produk'          => __DIR__ . '/controllers/shared/daftar-produk.php',
    'owner/transaksi'         => __DIR__ . '/controllers/shared/transaksi.php',
    'cashier/transaksi'       => __DIR__ . '/controllers/shared/transaksi.php',
    'owner/kasir'             => __DIR__ . '/controllers/shared/kasir.php',
    'cashier/kasir'           => __DIR__ . '/controllers/shared/kasir.php',
];

if ($path !== '') {
    if (isset($routes[$path]) && file_exists($routes[$path])) {
        require $routes[$path];
        exit;
    }
    http_response_code(404);
    require __DIR__ . '/views/404.php';
    exit;
}

require_once __DIR__ . '/config/app.php';
redirect(empty($_SESSION['user_id']) ? '/login' : dashboard_url());
