<?php
// ============================================================
// MODEL TOKO (Fungsi-fungsi Database Profil Toko/Warung)
// ============================================================

function store_get(PDO $koneksi, int $store_id)
{
    $stmt = $koneksi->prepare("SELECT StoreID, StoreName, Address, CreatedAt FROM tblstore WHERE StoreID = ?");
    $stmt->execute([$store_id]);
    return $stmt->fetch();
}

function store_update(PDO $koneksi, int $store_id, string $store_name, string $address)
{
    $stmt = $koneksi->prepare("UPDATE tblstore SET StoreName = ?, Address = ? WHERE StoreID = ?");
    return $stmt->execute([$store_name, $address, $store_id]);
}
