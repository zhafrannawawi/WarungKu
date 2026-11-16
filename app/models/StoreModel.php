<?php
class StoreModel
{
    public function get_store($store_id)
    {
        $db    = Database::connect();
        $query = $db->prepare('SELECT StoreName, Address FROM tblstore WHERE StoreID = ?');
        $query->execute([$store_id]);
        return $query->fetch();
    }

    public function update_store($store_id, $store_name, $address)
    {
        $db    = Database::connect();
        $query = $db->prepare('UPDATE tblstore SET StoreName = ?, Address = ? WHERE StoreID = ?');
        return $query->execute([$store_name, $address, $store_id]);
    }
}
