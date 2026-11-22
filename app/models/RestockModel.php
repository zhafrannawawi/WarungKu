<?php 
class RestockModel
{
    public function get_all()
    {
        $db    = Database::connect();
        $query = $db->prepare('SELECT * FROM tblRestock WHERE StoreId = ?');
        $query->execute([$_SESSION['store_id']]);
        return $query->fetchAll();
    }
    
}
?>