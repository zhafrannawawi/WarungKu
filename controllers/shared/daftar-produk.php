<?php
// ============================================================
// SHARED CONTROLLER: Daftar Produk (F006 Owner CRUD & F007 Kasir Read-Only)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth();
$is_owner = ($_SESSION['role'] ?? '') === 'owner';

// Helper Validasi Input Form (Khusus Owner)
function get_product_input(): array
{
    $data = [
        'category_id'    => trim($_POST['category_id'] ?? ''),
        'name'           => trim($_POST['name'] ?? ''),
        'unit'           => trim($_POST['unit'] ?? ''),
        'purchase_price' => (float)($_POST['purchase_price'] ?? 0),
        'selling_price'  => (float)($_POST['selling_price'] ?? 0),
        'stock'          => (int)($_POST['stock'] ?? 0),
        'min_stock'      => (int)($_POST['min_stock'] ?? 0),
        'location'       => trim($_POST['location'] ?? ''),
    ];

    if ($data['name'] === '') {
        throw new Exception('Nama produk wajib diisi.');
    }
    if ($data['unit'] === '') {
        throw new Exception('Satuan produk wajib diisi.');
    }
    if ($data['purchase_price'] < 0 || $data['selling_price'] < 0) {
        throw new Exception('Harga tidak boleh bernilai negatif.');
    }
    if ($data['stock'] < 0 || $data['min_stock'] < 0) {
        throw new Exception('Stok tidak boleh bernilai negatif.');
    }
    if ($data['category_id'] === '') {
        throw new Exception('Kategori wajib dipilih.');
    }

    return $data;
}

// Proses Aksi CRUD (Hanya Diizinkan untuk Owner — F006)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$is_owner) {
        redirect('/cashier/produk');
    }

    $action = trim($_POST['action'] ?? '');

    try {
        if ($action === 'tambah') {
            product_add($koneksi, $store_id, get_product_input());
            set_alert('success', 'Produk baru berhasil ditambahkan!');
        } elseif ($action === 'edit') {
            $product_id = trim($_POST['product_id'] ?? '');
            $is_active  = (int)($_POST['is_active'] ?? 1);
            if ($product_id === '') {
                throw new Exception('Data produk tidak valid.');
            }
            product_update($koneksi, $product_id, $store_id, get_product_input(), $is_active);
            set_alert('success', 'Data produk berhasil diperbarui!');
        } elseif ($action === 'hapus') {
            $product_id = trim($_POST['product_id'] ?? '');
            if ($product_id === '') {
                throw new Exception('ID produk tidak valid.');
            }
            product_delete($koneksi, $product_id, $store_id);
            set_alert('success', 'Produk berhasil dihapus!');
        }
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
    }

    redirect('/owner/produk');
}

// Ambil Data Produk & Kategori
$product_list  = product_get_all($koneksi, $store_id);
$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik
$total_produk       = count($product_list);
$total_nilai_stok   = 0;
$stok_menipis       = 0;
$produk_kritis_nama = '-';

foreach ($product_list as $p) {
    $total_nilai_stok += ((float)$p['Stock'] * (float)$p['PurchasePrice']);

    if ((int)$p['Stock'] <= (int)$p['MinimumStock']) {
        $stok_menipis++;
        if ($produk_kritis_nama === '-') {
            $produk_kritis_nama = $p['ProductName'];
        }
    }
}
$total_kategori = count($category_list);

$page_title = 'Daftar Produk';
$page_name  = 'product';
include __DIR__ . '/../../views/shared/daftar-produk/index.php';
