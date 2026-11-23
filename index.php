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

// Daftar rute URL Bersih -> Controller
$routes = [
    'login'                  => __DIR__ . '/controllers/auth/login.php',
    'logout'                 => __DIR__ . '/controllers/auth/logout.php',
    'register'               => __DIR__ . '/controllers/auth/register.php',
    'owner/dashboard'        => __DIR__ . '/controllers/owner/dashboard.php',
    'owner/akun-kasir'       => __DIR__ . '/controllers/owner/akun-kasir.php',
    'owner/produk'           => __DIR__ . '/controllers/owner/daftar-produk.php',
    'owner/kategori'         => __DIR__ . '/controllers/owner/kategori.php',
    'owner/restock'          => __DIR__ . '/controllers/owner/restock-barang.php',
    'owner/store'            => __DIR__ . '/controllers/owner/toko.php',
    'owner/kasir'            => __DIR__ . '/controllers/owner/kasir.php',
    'owner/transaksi'        => __DIR__ . '/controllers/owner/transaksi.php',
    'owner/laporan/laba-rugi'=> __DIR__ . '/controllers/owner/laporan_laba_rugi.php',
    'owner/laporan/stok'     => __DIR__ . '/controllers/owner/laporan_stok.php',
    'owner/laporan/terlaris' => __DIR__ . '/controllers/owner/laporan_terlaris.php',
    'owner/change-password'  => __DIR__ . '/controllers/owner/change_password.php',
    'cashier/dashboard'      => __DIR__ . '/controllers/cashier/dashboard.php',
];

if (isset($routes[$path])) {
    if (file_exists($routes[$path])) {
        require $routes[$path];
        exit;
    }
    http_response_code(404);
    echo "<h1>404 - Halaman Belum Dibuat</h1><p>File controller belum tersedia.</p><a href='/'>Kembali ke Beranda</a>";
    exit;
}

// Jika URL bukan root ('') dan tidak terdaftar di rute
if ($path !== '') {
    http_response_code(404);
    echo "<h1>404 - Halaman Tidak Ditemukan</h1><a href='/'>Kembali ke Beranda</a>";
    exit;
}

// Jika URL adalah root ('/'), cek session login
require_once __DIR__ . '/config/app.php';

if (empty($_SESSION['user_id'])) {
    redirect('/login');
}

if (($_SESSION['role'] ?? '') === 'owner') {
    redirect('/owner/dashboard');
} else {
    redirect('/cashier/dashboard');
}
