<?php
// ============================================================
// MODEL USER / KASIR (Fungsi-fungsi Database Pengguna)
// ============================================================

function user_find_by_username(PDO $koneksi, string $username)
{
    $stmt = $koneksi->prepare("SELECT * FROM tbluser WHERE Username = ? AND DeletedAt IS NULL");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function user_find_phone_number_by_username(PDO $koneksi, string $username)
{
    $stmt = $koneksi->prepare("SELECT PhoneNumber FROM tbluser WHERE Username = ? AND DeletedAt IS NULL");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function user_find_verify(PDO $koneksi, string $email, string $phone_number)
{
    $stmt = $koneksi->prepare("SELECT * FROM tbluser WHERE Email = ? AND PhoneNumber = ? AND DeletedAt IS NULL");
    $stmt->execute([$email, $phone_number]);
    return $stmt->fetch();
}

function user_update_password(PDO $koneksi, string $user_id, string $new_password)
{
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("UPDATE tbluser SET Password = ? WHERE UserID = ? AND DeletedAt IS NULL");
    return $stmt->execute([$hash, $user_id]);
}

function user_add_owner(PDO $koneksi, string $store_name, string $store_address, string $fullname, string $email, string $phone_number, string $username, string $password)
{
    try {
        $koneksi->beginTransaction();

        // 1. Simpan Toko
        $store_id   = generate_id($koneksi, 'tblstore', 'StoreID', 'STR-');
        $stmt_store = $koneksi->prepare("INSERT INTO tblstore (StoreID, StoreName, Address, CreatedAt) VALUES (?, ?, ?, NOW())");
        $stmt_store->execute([$store_id, $store_name, $store_address]);

        // 2. Simpan Owner
        $user_id   = generate_id($koneksi, 'tbluser', 'UserID', 'USR-');
        $hash      = password_hash($password, PASSWORD_DEFAULT);
        $stmt_user = $koneksi->prepare(
            "INSERT INTO tbluser (UserID, StoreID, Username, Email, Password, Fullname, PhoneNumber, Role, IsActive, CreatedAt)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'owner', 1, NOW())"
        );
        $stmt_user->execute([$user_id, $store_id, $username, $email, $hash, $fullname, $phone_number]);

        $koneksi->commit();
        return true;
    } catch (Exception $e) {
        $koneksi->rollBack();
        return false;
    }
}

function user_get_by_store(PDO $koneksi, string $store_id): array
{
    $sql = "SELECT u.*,
                   COALESCE((SELECT COUNT(*) FROM tbltransaction t WHERE t.UserID = u.UserID), 0) AS TotalTransaksi
            FROM tbluser u
            WHERE u.StoreID = ? AND u.Role = 'kasir' AND u.DeletedAt IS NULL
            ORDER BY u.CreatedAt DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

function user_count_metrics(PDO $koneksi, string $store_id): array
{
    $stmt = $koneksi->prepare(
        "SELECT COUNT(*) AS total_kasir,
                COALESCE(SUM(IsActive = 1), 0) AS active_kasir,
                COALESCE(SUM(IsActive = 0), 0) AS inactive_kasir,
                COALESCE((SELECT COUNT(*) FROM tbltransaction t JOIN tbluser uk ON t.UserID = uk.UserID
                          WHERE t.StoreID = ? AND uk.Role = 'kasir'
                            AND YEAR(t.TransactionDate) = YEAR(CURDATE())
                            AND MONTH(t.TransactionDate) = MONTH(CURDATE())), 0) AS trx_bulan_ini
         FROM tbluser
         WHERE StoreID = ? AND Role = 'kasir' AND DeletedAt IS NULL"
    );
    $stmt->execute([$store_id, $store_id]);
    return $stmt->fetch();
}

function user_add_cashier(PDO $koneksi, string $store_id, string $fullname, string $username, string $email, string $phone_number)
{
    $check_username = $koneksi->prepare("SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ? AND DeletedAt IS NULL");
    $check_username->execute([$username, $store_id]);
    if ($check_username->fetch()) {
        throw new Exception('Gagal: Username sudah dipakai akun lain.');
    }

    if ($phone_number !== '') {
        $check_phone = $koneksi->prepare("SELECT UserID FROM tbluser WHERE PhoneNumber = ? AND StoreID = ? AND DeletedAt IS NULL");
        $check_phone->execute([$phone_number, $store_id]);
        if ($check_phone->fetch()) {
            throw new Exception('Gagal: Nomor telepon/WhatsApp sudah dipakai akun lain.');
        }
    }

    $user_id = generate_id($koneksi, 'tbluser', 'UserID', 'USR-');
    $stmt    = $koneksi->prepare(
        "INSERT INTO tbluser (UserID, StoreID, Username, Email, Password, Fullname, PhoneNumber, Role, IsActive, CreatedAt)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'kasir', 1, NOW())"
    );
    return $stmt->execute([$user_id, $store_id, $username, $email, $phone_number, $fullname, $phone_number]);
}

function user_update_cashier(PDO $koneksi, string $user_id, string $store_id, string $fullname, string $username, string $email, string $phone_number, int $is_active)
{
    $check_username = $koneksi->prepare("SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ? AND UserID != ? AND DeletedAt IS NULL");
    $check_username->execute([$username, $store_id, $user_id]);
    if ($check_username->fetch()) {
        throw new Exception('Gagal: Username sudah dipakai akun lain.');
    }

    if ($phone_number !== '') {
        $check_phone = $koneksi->prepare("SELECT UserID FROM tbluser WHERE PhoneNumber = ? AND StoreID = ? AND UserID != ? AND DeletedAt IS NULL");
        $check_phone->execute([$phone_number, $store_id, $user_id]);
        if ($check_phone->fetch()) {
            throw new Exception('Gagal: Nomor telepon/WhatsApp sudah dipakai akun lain.');
        }
    }

    $stmt = $koneksi->prepare(
        "UPDATE tbluser SET Fullname = ?, Username = ?, Email = ?, PhoneNumber = ?, IsActive = ? 
         WHERE UserID = ? AND StoreID = ? AND Role = 'kasir' AND DeletedAt IS NULL"
    );
    return $stmt->execute([$fullname, $username, $email, $phone_number, $is_active, $user_id, $store_id]);
}

function user_delete(PDO $koneksi, string $user_id, string $store_id): bool
{
    $stmt = $koneksi->prepare("UPDATE tbluser SET DeletedAt = NOW() WHERE UserID = ? AND StoreID = ? AND Role = 'kasir'");
    return $stmt->execute([$user_id, $store_id]);
}


function user_find_by_id(PDO $koneksi, string $user_id)
{
    $stmt = $koneksi->prepare("SELECT * FROM tbluser WHERE UserID = ? AND DeletedAt IS NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function user_update_profile(PDO $koneksi, string $user_id, string $store_id, string $fullname, string $username, string $email, string $phone_number, string $new_password = ''): bool
{
    $check_username = $koneksi->prepare("SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ? AND UserID != ? AND DeletedAt IS NULL");
    $check_username->execute([$username, $store_id, $user_id]);
    if ($check_username->fetch()) {
        throw new Exception('Gagal: Username sudah dipakai akun lain.');
    }

    if ($phone_number !== '') {
        $check_phone = $koneksi->prepare("SELECT UserID FROM tbluser WHERE PhoneNumber = ? AND StoreID = ? AND UserID != ? AND DeletedAt IS NULL");
        $check_phone->execute([$phone_number, $store_id, $user_id]);
        if ($check_phone->fetch()) {
            throw new Exception('Gagal: Nomor telepon/WhatsApp sudah dipakai akun lain.');
        }
    }

    if ($new_password !== '') {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare(
            "UPDATE tbluser SET Fullname = ?, Username = ?, Email = ?, PhoneNumber = ?, Password = ? 
             WHERE UserID = ? AND StoreID = ? AND DeletedAt IS NULL"
        );
        return $stmt->execute([$fullname, $username, $email, $phone_number, $hash, $user_id, $store_id]);
    }

    $stmt = $koneksi->prepare(
        "UPDATE tbluser SET Fullname = ?, Username = ?, Email = ?, PhoneNumber = ? 
         WHERE UserID = ? AND StoreID = ? AND DeletedAt IS NULL"
    );
    return $stmt->execute([$fullname, $username, $email, $phone_number, $user_id, $store_id]);
}
