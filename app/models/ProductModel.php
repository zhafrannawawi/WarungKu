<?php
class ProductModel
{
    public function get_all()
    {
        $db    = Database::connect();
        $query = $db->prepare('SELECT * FROM tblProduct WHERE StoreId = ?');
        $query->execute([$_SESSION['store_id']]);
        return $query->fetchAll();
    }
}
