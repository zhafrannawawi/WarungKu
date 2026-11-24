<?php
require_once __DIR__ . '/../../config/app.php';

$error_text = '';
$old_input  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_name    = trim($_POST['store_name'] ?? '');
    $store_address = trim($_POST['store_address'] ?? '');
    $full_name     = trim($_POST['full_name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $phone_number  = trim($_POST['phone_number'] ?? '');
    $user_name     = trim($_POST['user_name'] ?? '');
    $password      = $_POST['user_password'] ?? '';
    $pass_again    = $_POST['password_again'] ?? '';

    $old_input = [
        'store_name'    => $store_name,
        'store_address' => $store_address,
        'full_name'     => $full_name,
        'email'         => $email,
        'phone_number'  => $phone_number,
        'user_name'     => $user_name,
    ];

    if ($store_name === '' || $full_name === '' || $user_name === '' || $email === '' || $phone_number === '') {
        $error_text = 'Semua data bertanda bintang (*) wajib diisi.';
    } elseif (strlen($password) < 6) {
        $error_text = 'Password minimal 6 karakter.';
    } elseif ($password !== $pass_again) {
        $error_text = 'Konfirmasi password tidak cocok.';
    } else {
        $is_saved = user_add_owner($koneksi, $store_name, $store_address, $full_name, $email, $phone_number, $user_name, $password);
        if ($is_saved) {
            redirect('/login?registered=1');
        } else {
            $error_text = 'Gagal mendaftar. Username mungkin sudah dipakai.';
        }
    }
}

$page_title = 'Daftar Warung Baru';
include __DIR__ . '/../../views/auth/register/index.php';
