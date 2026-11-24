<?php
require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'owner') {
    redirect('/login');
}

$store_id = (int)($_SESSION['store_id'] ?? 0);

/**
 * Ambil dan validasi input kasir dari form (dipakai oleh tambah & edit).
 */
function get_cashier_input(): array
{
    $data = [
        'full_name'    => trim($_POST['full_name'] ?? ''),
        'user_name'    => trim($_POST['user_name'] ?? ''),
        'email'        => trim($_POST['email'] ?? ''),
        'phone_number' => trim($_POST['phone_number'] ?? ''),
    ];

    if ($data['full_name'] === '' || $data['user_name'] === '') {
        throw new Exception('Nama lengkap dan username wajib diisi.');
    }

    return $data;
}

// Menangani Form Aksi (Tambah, Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    try {
        // 1. Tambah Kasir Baru
        if ($action === 'tambah') {
            $in = get_cashier_input();

            user_add_cashier($koneksi, $store_id, $in['full_name'], $in['user_name'], $in['email'], $in['phone_number']);
            set_alert('success', 'Akun kasir berhasil ditambahkan!');
        }

        // 2. Edit Kasir
        elseif ($action === 'edit') {
            $user_id   = (int)($_POST['user_id'] ?? 0);
            $is_active = (int)($_POST['is_active'] ?? 1);

            if ($user_id <= 0) {
                throw new Exception('Data kasir tidak lengkap.');
            }
            $in = get_cashier_input();

            user_update_cashier($koneksi, $user_id, $store_id, $in['full_name'], $in['user_name'], $in['email'], $in['phone_number'], $is_active);
            set_alert('success', 'Data akun kasir berhasil diperbarui!');
        }
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
    }

    redirect('/owner/akun-kasir');
}

// Ambil Data untuk Tampilan
$user_list  = user_get_by_store($koneksi, $store_id);
$count_user = user_count_metrics($koneksi, $store_id);

$page_title = 'Manajemen Akun Kasir';
$page_name  = 'akun-kasir';
include __DIR__ . '/../../views/owner/akun-kasir/index.php';
