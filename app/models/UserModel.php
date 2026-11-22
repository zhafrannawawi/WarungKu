<?php
class UserModel
{
    // GET
    public function find_user($user_name)
    {
        $db = Database::connect();
        $query = $db->prepare('SELECT * FROM tbluser WHERE Username = ?');
        $query->execute([$user_name]);
        return $query->fetch();
    }

    public function get_store_name($store_id)
    {
        $db = Database::connect();
        $query = $db->prepare('SELECT StoreName FROM tblstore WHERE StoreID = ?');
        $query->execute([$store_id]);
        return $query->fetch();
    }

    public function find_user_verify($email, $phone_number)
    {
        $db = Database::connect();
        $query = $db->prepare('SELECT * FROM tbluser WHERE Email = ? AND PhoneNumber = ?');
        $query->execute([$email, $phone_number]);
        return $query->fetch();
    }

    public function get_user_by_store($store_id)
    {
        $db = Database::connect();
        $query = $db->prepare('SELECT * FROM tbluser WHERE StoreID = ? ORDER BY Role DESC, CreatedAt DESC');
        $query->execute([$store_id]);
        return $query->fetchAll();
    }

    public function count_user($store_id)
    {
        $db = Database::connect();
        $query = $db->prepare('SELECT COUNT(*) as total, SUM(Role = "owner") as owner_count, SUM(Role = "kasir") as cashier_count FROM tbluser WHERE StoreID = ?');
        $query->execute([$store_id]);
        return $query->fetch();
    }

    public function get_store_phone_numbers($store_id, $exclude_user_id = 0)
{
    $db = Database::connect();
    $query = $db->prepare(
        'SELECT PhoneNumber FROM tblUser
         WHERE StoreID = ? AND UserID <> ? AND PhoneNumber IS NOT NULL AND PhoneNumber <> ""'
    );
    $query->execute([$store_id, $exclude_user_id]);
    return $query->fetchAll(PDO::FETCH_COLUMN);
}
    // END GET

    // STORE
    public function update_password($user_id, $new_password)
    {
        $db = Database::connect();
        $hash_pass = password_hash($new_password, PASSWORD_DEFAULT);
        $query = $db->prepare('UPDATE tbluser SET Password = ? WHERE UserID = ?');
        return $query->execute([$hash_pass, $user_id]);
    }

    public function add_owner($store_name, $store_address, $full_name, $email, $phone_number, $user_name, $password)
    {
        $db = Database::connect();

        try {
            $db->beginTransaction();

            // 1. Simpan toko
            $query_store = $db->prepare('INSERT INTO tblstore (StoreName, Address, CreatedAt) VALUES (?, ?, NOW())');
            $query_store->execute([$store_name, $store_address]);
            $store_id = $db->lastInsertId();

            // 2. Simpan user owner
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            $query_user = $db->prepare(
                'INSERT INTO tbluser (StoreID, Username, Email, Password, Fullname, PhoneNumber, Role, IsActive, CreatedAt)
                 VALUES (?, ?, ?, ?, ?, ?, "owner", 1, NOW())'
            );
            $query_user->execute([$store_id, $user_name, $email, $hash_pass, $full_name, $phone_number]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public function update_cashier($user_id, $store_id, $full_name, $user_name, $phone_number, $is_active)
    {
        $db = Database::connect();
        $check = $db->prepare('SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ? AND UserID != ?');
        $check->execute([$user_name, $store_id, $user_id]);
        if ($check->fetch()) {
            return false;
        }
        $query = $db->prepare(
            'UPDATE tbluser SET Fullname = ?, Username = ?, PhoneNumber = ?, IsActive = ? WHERE UserID = ? AND StoreID = ?'
        );
        return $query->execute([$full_name, $user_name, $phone_number, $is_active, $user_id, $store_id]);
    }

    public function delete_cashier($user_id, $store_id)
    {
        $db = Database::connect();
        $query = $db->prepare('DELETE FROM tbluser WHERE UserID = ? AND StoreID = ? AND Role = "kasir"');
        return $query->execute([$user_id, $store_id]);
    }

    public function add_cashier($store_id, $full_name, $user_name, $email, $phone_number)
    {
        $db = Database::connect();
        $check = $db->prepare('SELECT UserID FROM tbluser WHERE Username = ? AND StoreID = ?');
        $check->execute([$user_name, $store_id]);
        if ($check->fetch()) {
            return false;
        }
        $db = Database::connect();
        $query = $db->prepare(
            'INSERT INTO tbluser (StoreID, Username, Email, Password, Fullname, PhoneNumber, Role, IsActive, CreatedAt)
             VALUES (?, ?, ?, ?, ?, ?, "kasir", 1, NOW())'
        );
        return $query->execute([$store_id, $user_name, $email, $phone_number, $full_name, $phone_number]);
    }
    // END STORE
}
