<?php
// ============================================================
// SHARED CONTROLLER: Kasir POS (F008 — Pencatatan Transaksi & Pembayaran)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id    = require_auth();
$role        = $_SESSION['role'] ?? 'kasir';
$role_prefix = $role === 'owner' ? '/owner' : '/cashier';
$kasir_url   = $role_prefix . '/kasir';
$user_id     = (string)($_SESSION['user_id'] ?? '');

// Proses Checkout Keranjang Belanja (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $items_json     = $_POST['cart_items'] ?? '[]';
        $items          = json_decode($items_json, true);
        $payment_method = trim($_POST['payment_method'] ?? 'cash');
        $amount_paid    = (float)preg_replace('/\D/', '', (string)($_POST['amount_paid'] ?? '0'));

        if (!is_array($items) || empty($items)) {
            throw new Exception('Keranjang belanja masih kosong.');
        }

        $new_trx_id = transaction_create($koneksi, $store_id, $user_id, $payment_method, $amount_paid, $items);
        redirect($kasir_url . '?receipt=' . urlencode($new_trx_id));
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
        redirect($kasir_url);
    }
}

// Ambil Katalog Produk Aktif & Kategori
$all_products  = product_get_all($koneksi, $store_id);
$product_list  = array_values(array_filter($all_products, fn($p) => (int)$p['IsActive'] === 1));
$category_list = array_values(array_filter(category_get_all($koneksi, $store_id), fn($c) => (int)$c['IsActive'] === 1));

// Cek jika baru selesai transaksi untuk memunculkan Modal Struk
$receipt_data = null;
if (!empty($_GET['receipt'])) {
    $receipt_data = transaction_get_receipt($koneksi, trim((string)$_GET['receipt']), $store_id);
}

$tanggal_hari_ini = tanggal_id();

$page_title = 'Kasir';
$page_name  = 'kasir';
include __DIR__ . '/../../views/shared/kasir/index.php';
