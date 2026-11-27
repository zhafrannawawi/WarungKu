<?php
require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product_id = trim($_POST['product_id'] ?? '');
        $qty        = (int)($_POST['quantity'] ?? 0);
        $price      = (float)($_POST['purchase_price'] ?? 0);
        $note       = trim($_POST['note'] ?? '');

        if ($product_id === '' || $qty <= 0) {
            throw new Exception('Pilih produk dan masukkan jumlah barang yang valid.');
        }

        $saved = restock_add($koneksi, $store_id, $product_id, (string)$_SESSION['user_id'], $qty, $price, $note);
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

// Ringkasan Metrik Restock
$today         = date('Y-m-d');
$this_month    = date('Y-m');
$today_list    = [];
$total_beli_hr = 0;
$unit_masuk_hr = 0;
$jml_bulan_ini = 0;

foreach ($restock_list as $r) {
    $tanggal = substr($r['RestockDate'], 0, 10);
    if ($tanggal === $today) {
        $today_list[]   = $r;
        $total_beli_hr += (float)$r['PurchasePrice'] * (int)$r['Quantity'];
        $unit_masuk_hr += (int)$r['Quantity'];
    }
    if (substr($tanggal, 0, 7) === $this_month) {
        $jml_bulan_ini++;
    }
}

$low_stock = [];
foreach ($product_list as $p) {
    if ((int)$p['Stock'] <= (int)$p['MinimumStock']) {
        $low_stock[] = $p;
    }
}

$page_title = 'Restock Barang';
$page_name  = 'restock';
include __DIR__ . '/../../views/owner/restock-barang/index.php';
