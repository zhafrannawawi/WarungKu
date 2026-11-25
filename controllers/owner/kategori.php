<?php
// ============================================================
// CONTROLLER: Kategori Produk
// Mengelola tampilan dan aksi data kategori produk
// ============================================================

// 1. KEAMANAN & SESI (Wajib Login sebagai Owner)
require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

// 2. PROSES AKSI FORM (POST: Tambah, Edit, Hapus)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    try {
        // Aksi 1: Tambah Kategori
        if ($action === 'tambah') {
            $name = trim($_POST['category_name'] ?? '');
            if ($name === '') {
                throw new Exception('Nama kategori tidak boleh kosong.');
            }
            category_add($koneksi, $store_id, $name);
            set_alert('success', 'Kategori baru berhasil ditambahkan!');
        }
        // Aksi 2: Edit Kategori
        elseif ($action === 'edit') {
            $id        = trim($_POST['category_id'] ?? '');
            $name      = trim($_POST['category_name'] ?? '');
            $is_active = (int)($_POST['is_active'] ?? 1);
            if ($id === '' || $name === '') {
                throw new Exception('Data kategori tidak lengkap.');
            }
            category_update($koneksi, $id, $store_id, $name, $is_active);
            set_alert('success', 'Kategori berhasil diperbarui!');
        }
        // Aksi 3: Hapus Kategori
        elseif ($action === 'hapus') {
            $id = trim($_POST['category_id'] ?? '');
            if ($id === '') {
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

// 3. AMBIL DATA DARI DATABASE & HITUNG RINGKASAN
$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik Kategori
$total_kategori         = count($category_list);
$total_produk_terdaftar = 0;
$kategori_terbanyak     = '-';
$max_produk             = -1;

foreach ($category_list as $c) {
    $total_produk_terdaftar += (int)$c['ProductCount'];
    if ((int)$c['ProductCount'] > $max_produk && (int)$c['ProductCount'] > 0) {
        $max_produk         = (int)$c['ProductCount'];
        $kategori_terbanyak = $c['CategoryName'];
    }
}

// 4. TAMPILKAN HALAMAN VIEW
$page_title = 'Kategori Produk';
$page_name  = 'kategori';
include __DIR__ . '/../../views/owner/kategori/index.php';
