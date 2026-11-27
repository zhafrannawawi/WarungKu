<?php
require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user_id      = (string)$_SESSION['user_id'];
        $fullname     = trim($_POST['full_name'] ?? '');
        $username     = trim($_POST['user_name'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        if ($fullname === '' || $username === '') {
            throw new Exception('Nama lengkap dan username wajib diisi.');
        }

        if ($new_password !== '') {
            if (strlen($new_password) < 6) {
                throw new Exception('Password baru minimal 6 karakter.');
            }
            $current = user_find_by_id($koneksi, $user_id);
            if (!$current || !password_verify($old_password, $current['Password'])) {
                throw new Exception('Password saat ini tidak sesuai.');
            }
        }

        user_update_profile($koneksi, $user_id, $store_id, $fullname, $username, $email, $phone_number, $new_password);

        $_SESSION['full_name'] = $fullname;
        $_SESSION['user_name'] = $username;
        set_alert('success', 'Profil akun berhasil diperbarui!');
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
    }
}

redirect($_SERVER['HTTP_REFERER'] ?? dashboard_url());
