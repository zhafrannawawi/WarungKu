<?php
require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    redirect('/login');
}

$store_id = $_SESSION['store_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'tambah') {
            $name = trim($_POST['category_name'] ?? '');
            if ($name === '') {
                throw new Exception('Nama kategori tidak boleh kosong.');
            }
            category_add($koneksi, $store_id, $name);
            set_alert('success', 'Kategori berhasil ditambahkan!');
        } elseif ($action === 'edit') {
            $id   = (int)($_POST['category_id'] ?? 0);
            $name = trim($_POST['category_name'] ?? '');
            if ($id <= 0 || $name === '') {
                throw new Exception('Data kategori tidak lengkap.');
            }
            category_update($koneksi, $id, $store_id, $name);
            set_alert('success', 'Kategori berhasil diperbarui!');
        } elseif ($action === 'hapus') {
            $id = (int)($_POST['category_id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID kategori tidak valid.');
            }
            category_delete($koneksi, $id, $store_id);
            set_alert('success', 'Kategori berhasil dihapus!');
        }
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
    }

    redirect('/owner/kategori');
}

$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik Kategori
$total_kategori = count($category_list);
$total_produk_terdaftar = 0;
$kategori_terbanyak = '-';
$max_produk = -1;

foreach ($category_list as $c) {
    $total_produk_terdaftar += (int)$c['ProductCount'];
    if ((int)$c['ProductCount'] > $max_produk && (int)$c['ProductCount'] > 0) {
        $max_produk = (int)$c['ProductCount'];
        $kategori_terbanyak = $c['CategoryName'];
    }
}

$page_title = 'Kategori Produk';
include __DIR__ . '/../../views/owner/kategori/index.php';
