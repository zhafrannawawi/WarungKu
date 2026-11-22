<?php
class OwnerRestockController extends Controller
{
    public function index()
    {
        $restock_model = $this->model('RestockModel');
        $restock_list = $restock_model->get_all($_SESSION['store_id']);
        
        $success_message = $_SESSION['success_message'] ?? '';
        $error_message   = $_SESSION['error_message'] ?? '';
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->view('owner', 'restock', [
            'page_title'   => 'Restock Barang',
            'restock_list' => $restock_model->get_all(),
        ]);
    }
}