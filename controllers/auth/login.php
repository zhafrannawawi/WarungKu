<?php
require_once __DIR__ . '/../../config/app.php';

// Jika sudah login, langsung lempar ke dashboard masing-masing
if (!empty($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'owner') {
        redirect('/owner/dashboard');
    } else {
        redirect('/cashier/dashboard');
    }
}

$error_text   = '';
$success_text = isset($_GET['registered']) ? 'Registrasi berhasil, silakan masuk.' : '';
$modal_error  = '';
$reset_user_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';

    // 1. Aksi Login Biasa
    if ($action === 'login') {
        $username = trim($_POST['user_name'] ?? '');
        $password = $_POST['user_password'] ?? '';

        $user_data = user_find_by_username($koneksi, $username);

        if (!$user_data) {
            $error_text = 'Username atau password salah.';
        } elseif (!$user_data['IsActive']) {
            $error_text = 'Akun Anda berstatus non-aktif. Hubungi pemilik warung.';
        } elseif (!empty($user_data['Password']) && password_verify($password, $user_data['Password'])) {
            // Login sukses via password akun -> langsung ke dashboard
            $_SESSION['user_id']   = $user_data['UserID'];
            $_SESSION['user_name'] = $user_data['Username'];
            $_SESSION['full_name'] = $user_data['Fullname'];
            $_SESSION['role']      = $user_data['Role'];
            $_SESSION['store_id']  = $user_data['StoreID'];

            $store = store_get($koneksi, $user_data['StoreID']);
            $_SESSION['store_name'] = $store['StoreName'] ?? 'WarungKu';

            if ($user_data['Role'] === 'owner') {
                redirect('/owner/dashboard');
            } else {
                redirect('/cashier/dashboard');
            }
        } elseif ($password !== '' && $password === ($user_data['PhoneNumber'] ?? '')) {
            // Login pertama via nomor HP -> munculkan modal buat password baru
            $reset_user_id = $user_data['UserID'];
        } else {
            $error_text = 'Username atau password salah.';
        }
    }

    // 2. Aksi Lupa Password: Cek Email & Nomor HP
    elseif ($action === 'verify_reset') {
        $email = trim($_POST['reset_email'] ?? '');
        $phone = trim($_POST['reset_phone'] ?? '');

        $user_data = user_find_verify($koneksi, $email, $phone);
        if ($user_data) {
            $reset_user_id = $user_data['UserID'];
        } else {
            $modal_error = 'Email atau No. WhatsApp tidak cocok dengan akun manapun.';
        }
    }

    // 3. Aksi Simpan Password Baru
    elseif ($action === 'save_new_password') {
        $user_id    = trim($_POST['reset_user_id'] ?? '');
        $new_pass   = $_POST['new_password'] ?? '';
        $pass_again = $_POST['confirm_new_password'] ?? '';

        if ($user_id === '') {
            $modal_error = 'Sesi reset tidak valid.';
        } elseif (strlen($new_pass) < 6) {
            $modal_error = 'Password baru minimal 6 karakter.';
            $reset_user_id = $user_id;
        } elseif ($new_pass !== $pass_again) {
            $modal_error = 'Konfirmasi password baru tidak cocok.';
            $reset_user_id = $user_id;
        } else {
            user_update_password($koneksi, $user_id, $new_pass);
            $success_text = 'Password berhasil diubah! Silakan masuk dengan password baru.';
        }
    }
}

// Tampilkan View
$page_title = 'Masuk';
include __DIR__ . '/../../views/auth/login/index.php';
