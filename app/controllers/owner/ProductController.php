<?php
class OwnerProductController extends Controller
{
    public function index()
    {
        $product_model = $this->model('ProductModel');

        $this->view('owner', 'product', [
            'page_title'   => 'Product List',
            'product_list' => $product_model->get_all(),
        ]);
    }
}