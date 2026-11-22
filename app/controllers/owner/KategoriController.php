<?php
class OwnerKategoriController extends Controller
{
    public function index()
    {
        $category_model = $this->model('CategoryModel');
        $category_list = $category_model->get_all($_SESSION['store_id']);
        
        $success_message = $_SESSION['success_message'] ?? '';
        $error_message   = $_SESSION['error_message'] ?? '';
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->view('owner', 'kategori', [
            'page_title'   => 'Kategori Barang',
            'kategori_list' => $category_list,
        ]);
    }
}