<?php
class CategoryModel
{
    public function get_all($store_id)
    {
        $db = Database::connect();
        $query = $db->prepare(
            'SELECT c.CategoryID, c.StoreID, c.CategoryName, COUNT(p.ProductID) AS ProductCount, c.IsActive
         FROM tblCategory c
         LEFT JOIN tblProduct p ON p.CategoryID = c.CategoryID AND p.StoreID = c.StoreID
         WHERE c.StoreID = ?
         GROUP BY c.CategoryID, c.Name, c.Code, c.Description
         ORDER BY c.Name'
        );
        $query->execute([$store_id]);
        return $query->fetchAll();
    }
}
