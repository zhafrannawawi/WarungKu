<?php
// ============================================================
// MODEL USER / KASIR (Fungsi-fungsi Database Pengguna)
// ============================================================

function user_find_by_username(PDO $koneksi, string $username)
{
    $stmt = $koneksi->prepare("SELECT * FROM tbluser WHERE Username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function user_find_phone_number_by_username(PDO $koneksi, string $username)
{
    $stmt = $koneksi->prepare("SELECT PhoneNumber FROM tbluser WHERE Username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function user_find_verify(PDO $koneksi, string $email, string $phone_number)
{
    $stmt = $koneksi->prepare("SELECT * FROM tbluser WHERE Email = ? AND PhoneNumber = ?");
    $stmt->execute([$email, $phone_number]);
    return $stmt->fetch();
}

function user_update_password(PDO $koneksi, int $user_id, string $new_password)
{
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("UPDATE tbluser SET Password = ? WHERE UserID = ?");
    return $stmt->execute([$hash, $user_id]);
}

function user_add_owner(PDO $koneksi, string $store_name, string $store_address, string $fullname, string $email, string $phone_number, string $username, string $password)
{
    try {
        $koneksi->beginTransaction();

        // 1. Simpan Toko
        $stmt_store = $koneksi->prepare("INSERT INTO tblstore (StoreName, Address, CreatedAt) VALUES (?, ?, NOW())");
        $stmt_store->execute([$store_name, $store_address]);
        $store_id = $koneksi->lastInsertId();

        // 2. Simpan Owner
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt_user = $koneksi->prepare(
            "INSERT INTO tbluser (StoreID, Username, Email, Password, Fullname, PhoneNumber, Role, IsActive, CreatedAt)
             VALUES (?, ?, ?, ?, ?, ?, 'owner', 1, NOW())"
        );
        $stmt_user->execute([$store_id, $username, $email, $hash, $fullname, $phone_number]);

        $koneksi->commit();
        return true;
    } catch (Exception $e) {
        $koneksi->rollBack();
        return false;
    }
}

function user_get_by_store(PDO $koneksi, int $store_id)
{
    $stmt = $koneksi->prepare("SELECT * FROM tbluser WHERE StoreID = ? ORDER BY Role DESC, CreatedAt DESC");
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

function user_count_metrics(PDO $koneksi, int $store_id)
{
    $stmt = $koneksi->prepare(
        "SELECT COUNT(*) as total, 
                SUM(Role = 'owner') as owner_count, 
                SUM(Role = 'kasir') as cashier_count 
         FROM tbluser WHERE StoreID = ?"
    );
    $stmt->execute([$store_id]);
    return $stmt->fetch();
}

function user_add_cashier(PDO $koneksi, int $store_id, string $fullname, string $username, string $email, string $phone_number)
{
    // Cek username apakah sudah ada di toko ini
    $check_username = $koneksi->prepare("SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ?");
    $check_username->execute([$username, $store_id]);
    if ($check_username->fetch()) {
        throw new Exception('Gagal: Username sudah dipakai akun lain.');
    }

    // Cek nomor telepon (hanya jika diisi)
    if ($phone_number !== '') {
        $check_phone = $koneksi->prepare("SELECT UserID FROM tbluser WHERE PhoneNumber = ? AND StoreID = ?");
        $check_phone->execute([$phone_number, $store_id]);
        if ($check_phone->fetch()) {
            throw new Exception('Gagal: Nomor telepon/WhatsApp sudah dipakai akun lain.');
        }
    }

    $stmt = $koneksi->prepare(
        "INSERT INTO tbluser (StoreID, Username, Email, Password, Fullname, PhoneNumber, Role, IsActive, CreatedAt)
         VALUES (?, ?, ?, ?, ?, ?, 'kasir', 1, NOW())"
    );
    return $stmt->execute([$store_id, $username, $email, $phone_number, $fullname, $phone_number]);
}

function user_update_cashier(PDO $koneksi, int $user_id, int $store_id, string $fullname, string $username, string $email, string $phone_number, int $is_active)
{
    // Owner tidak bisa dinonaktifkan
    $check_role = $koneksi->prepare("SELECT Role FROM tbluser WHERE UserID = ? AND StoreID = ?");
    $check_role->execute([$user_id, $store_id]);
    if ($check_role->fetchColumn() === 'owner') {
        $is_active = 1;
    }

    // Cek username bentrok dengan akun kasir/owner lain
    $check_username = $koneksi->prepare("SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ? AND UserID != ?");
    $check_username->execute([$username, $store_id, $user_id]);
    if ($check_username->fetch()) {
        throw new Exception('Gagal: Username sudah dipakai akun lain.');
    }

    // Cek nomor telepon bentrok dengan akun lain (hanya jika diisi)
    if ($phone_number !== '') {
        $check_phone = $koneksi->prepare("SELECT UserID FROM tbluser WHERE PhoneNumber = ? AND StoreID = ? AND UserID != ?");
        $check_phone->execute([$phone_number, $store_id, $user_id]);
        if ($check_phone->fetch()) {
            throw new Exception('Gagal: Nomor telepon/WhatsApp sudah dipakai akun lain.');
        }
    }

    $stmt = $koneksi->prepare(
        "UPDATE tbluser SET Fullname = ?, Username = ?, Email = ?, PhoneNumber = ?, IsActive = ? 
         WHERE UserID = ? AND StoreID = ?"
    );
    return $stmt->execute([$fullname, $username, $email, $phone_number, $is_active, $user_id, $store_id]);
}
