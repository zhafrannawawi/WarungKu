<?php
// ============================================================
// CONTROLLER: Daftar Produk
// Mengelola tampilan, penambahan, pengeditan, dan penghapusan produk
// ============================================================

// 1. KEAMANAN & SESI (Wajib Login sebagai Owner)
require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'owner') {
    redirect('/login');
}
$store_id = (string)($_SESSION['store_id'] ?? '');

// 2. HELPER VALIDASI INPUT FORM
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

// 3. PROSES AKSI FORM (POST: Tambah, Edit, Hapus)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    try {
        // Aksi 1: Tambah Produk Baru
        if ($action === 'tambah') {
            $in = get_product_input();
            product_add(
                $koneksi,
                $store_id,
                $in['category_id'],
                $in['name'],
                $in['unit'],
                $in['purchase_price'],
                $in['selling_price'],
                $in['stock'],
                $in['min_stock'],
                $in['location']
            );
            set_alert('success', 'Produk baru berhasil ditambahkan!');
        }
        // Aksi 2: Edit Produk
        elseif ($action === 'edit') {
            $product_id = trim($_POST['product_id'] ?? '');
            if ($product_id === '') {
                throw new Exception('Data produk tidak valid.');
            }
            $in = get_product_input();
            product_update(
                $koneksi,
                $product_id,
                $store_id,
                $in['category_id'],
                $in['name'],
                $in['unit'],
                $in['purchase_price'],
                $in['selling_price'],
                $in['stock'],
                $in['min_stock'],
                $in['location']
            );
            set_alert('success', 'Data produk berhasil diperbarui!');
        }
        // Aksi 3: Hapus Produk
        elseif ($action === 'hapus') {
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

// 4. AMBIL DATA DARI DATABASE & HITUNG RINGKASAN
$product_list  = product_get_all($koneksi, $store_id);
$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik Produk
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

// 5. TAMPILKAN HALAMAN VIEW
$page_title = 'Daftar Produk';
$page_name  = 'product';
include __DIR__ . '/../../views/owner/daftar-produk/index.php';
