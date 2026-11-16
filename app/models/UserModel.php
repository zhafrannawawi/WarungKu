<?php
class UserModel
{
    public function find_user($user_name)
    {
        $db = Database::connect();
        $query = $db->prepare('SELECT * FROM tbluser WHERE Username = ?');
        $query->execute([$user_name]);
        return $query->fetch();
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
}
