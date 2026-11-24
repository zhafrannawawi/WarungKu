<?php
require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    redirect('/login');
}

$store_id = $_SESSION['store_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product_id = (int)($_POST['product_id'] ?? 0);
        $qty        = (int)($_POST['quantity'] ?? 0);
        $price      = (float)($_POST['purchase_price'] ?? 0);
        $note       = trim($_POST['note'] ?? '');

        if ($product_id <= 0 || $qty <= 0) {
            throw new Exception('Pilih produk dan masukkan jumlah barang yang valid.');
        }

        $saved = restock_add($koneksi, $store_id, $product_id, $_SESSION['user_id'], $qty, $price, $note);
        if (!$saved) {
            throw new Exception('Gagal mencatat restock.');
        }

        set_alert('success', 'Restock barang berhasil dicatat dan stok fisik bertambah!');
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
    }

    redirect('/owner/restock');
}

$restock_list = restock_get_all($koneksi, $store_id);
$product_list = product_get_all($koneksi, $store_id);

$page_title = 'Restock Barang';
$page_name  = 'restock';
include __DIR__ . '/../../views/owner/restock-barang/index.php';
