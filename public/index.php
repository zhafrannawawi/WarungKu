<?php
// ============================================================
// public/index.php  -  pintu masuk tunggal (router sederhana)
// ============================================================

// [1] Jika dijalankan dengan `php -S`, sajikan file asli
//     (css, js, gambar) langsung tanpa lewat router.
if (php_sapi_name() === 'cli-server') {
    $real_file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($real_file)) {
        return false;
    }
}

// [2] Lokasi folder, supaya mudah dipakai di file lain.
define('APP_PATH', __DIR__ . '/../app');
define('PUBLIC_PATH', __DIR__);

// [3] Muat file inti yang dibutuhkan semua controller.
require APP_PATH . '/core/Database.php';
require APP_PATH . '/core/Controller.php';

// [4] Baca URL: "/owner/product" menjadi ['owner', 'product'].
$url_path  = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$url_parts = explode('/', $url_path);

// [5] Halaman utama diarahkan ke login.
if ($url_path === '') {
    header('Location: /login');
    exit;
}

// [6] Bersihkan input: hanya huruf kecil a-z yang diizinkan.
//     Mencegah URL seperti "../../config".
$role_name = preg_replace('/[^a-z]/', '', strtolower($url_parts[0]));
$page_name = preg_replace('/[^a-z]/', '', strtolower($url_parts[1] ?? 'dashboard'));

// [7] Halaman umum (tanpa login): /login dan /register.
//     Controller-nya mengatur sesinya sendiri.
if (in_array($role_name, ['login', 'register'], true)) {
    require APP_PATH . '/controllers/auth/' . ucfirst($role_name) . 'Controller.php';

    $class_name = 'Auth' . ucfirst($role_name) . 'Controller'; // AuthLoginController
    $controller = new $class_name();
    $controller->index();
    exit;
}

// [8] Tentukan file dan nama class controller.
//     /owner/product -> controllers/owner/ProductController.php
//                       class OwnerProductController
$file_path  = APP_PATH . '/controllers/' . $role_name . '/' . ucfirst($page_name) . 'Controller.php';
$class_name = ucfirst($role_name) . ucfirst($page_name) . 'Controller';

// [9] Tolak jika role tidak dikenal atau halamannya tidak ada.
if (!in_array($role_name, ['owner', 'cashier'], true) || !file_exists($file_path)) {
    http_response_code(404);
    echo 'Page not found';
    exit;
}

// [10] Mulai sesi sesuai role (owner_session / cashier_session).
session_name($role_name . '_session');
session_start();

// [11] Belum login? Kirim ke halaman login.
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// [12] BARU: pastikan role di sesi sama dengan role di URL.
//      Mencegah kasir membuka /owner/... dengan trik cookie.
if (($_SESSION['role'] ?? '') !== $role_name) {
    http_response_code(403);
    echo 'Akses ditolak';
    exit;
}

// [13] Semua lolos: jalankan controller.
require $file_path;
$controller = new $class_name();
$controller->index();