<?php
require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $store_name = trim($_POST['store_name'] ?? '');
        $address    = trim($_POST['address'] ?? '');

        if ($store_name === '') {
            throw new Exception('Nama toko tidak boleh kosong.');
        }

        store_update($koneksi, $store_id, $store_name, $address);

        $_SESSION['store_name'] = $store_name;
        set_alert('success', 'Profil toko berhasil diperbarui!');
    } catch (Exception $e) {
        set_alert('danger', $e->getMessage());
    }

    redirect($_SERVER['HTTP_REFERER'] ?? '/owner/dashboard');
}

redirect($_SERVER['HTTP_REFERER'] ?? '/owner/dashboard');
