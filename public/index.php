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

// [5] Bersihkan input: hanya huruf kecil a-z yang diizinkan.
$role_name = preg_replace('/[^a-z]/', '', strtolower($url_parts[0] ?? ''));
$page_name = preg_replace('/[^a-z]/', '', strtolower($url_parts[1] ?? 'dashboard'));

$action_name = preg_replace('/[^a-z0-9_]/', '', strtolower($url_parts[2] ?? 'index'));
if ($action_name === '') {
    $action_name = 'index';
}

// [6] Jika sudah punya sesi aktif dan membuka root, login, atau register -> lempar langsung ke dashboard
if ($url_path === '' || in_array($role_name, ['login', 'register'], true)) {
    foreach (['owner', 'cashier'] as $active_role) {
        if (isset($_COOKIE[$active_role . '_session'])) {
            session_name($active_role . '_session');
            session_start();
            if (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === $active_role) {
                header('Location: /' . $active_role . '/dashboard');
                exit;
            }
            session_write_close();
        }
    }
}

// [7] Jika belum login dan membuka root '/', arahkan ke /login
if ($url_path === '') {
    header('Location: /login');
    exit;
}

// [8] Halaman umum: /login, /register, dan /logout.
//     Controller-nya mengatur sesinya sendiri.
if (in_array($role_name, ['login', 'register', 'logout'], true)) {
    require APP_PATH . '/controllers/auth/' . ucfirst($role_name) . 'Controller.php';

    $class_name = 'Auth' . ucfirst($role_name) . 'Controller';
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

// [13] Cegah browser menyimpan cache halaman (agar tombol 'Go Forward' tidak bisa masuk setelah logout).
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// [14] Semua lolos: jalankan controller.
require $file_path;
$controller = new $class_name();

// Baca nama function dari URL bagian ke-3 (contoh: /owner/akun-kasir/save -> 'save')
// Hanya huruf kecil a-z dan underscore yang diizinkan
$action_name = preg_replace('/[^a-z_]/', '', strtolower($url_parts[2] ?? 'index'));
if ($action_name === '' || !method_exists($controller, $action_name)) {
    $action_name = 'index';
}

$controller->$action_name();
