<?php
class OwnerRestockController extends Controller
{
    public function index()
    {
        $product_model = $this->model('ProductModel');

        $this->view('owner', 'restock', [
            'page_title'   => 'Restock Barang',
            'product_list' => $product_model->get_all(),
        ]);
    }
}