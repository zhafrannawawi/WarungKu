<?php
class Controller
{
    public function model($model_name)
    {
        require_once APP_PATH . '/models/' . $model_name . '.php';
        return new $model_name();
    }

    public function view($role_name, $page_name, $data = [], $layout_name = 'main')
    {
        // Auto-inject data toko ke semua view yang pakai layout main
        if ($layout_name === 'main' && !empty($_SESSION['store_id'])) {
            require_once APP_PATH . '/models/StoreModel.php';
            $store_model = new StoreModel();
            $data['store_data'] = $store_model->get_store($_SESSION['store_id']);
        }

        extract($data);

        // Path folder view halaman
        $view_dir = APP_PATH . '/views/' . $role_name . '/' . $page_name;

        // Baca CSS spesifik jika ada di folder view: style/style.css
        $css_file = $view_dir . '/style/style.css';
        $page_css = file_exists($css_file) ? file_get_contents($css_file) : '';

        // Baca JS spesifik jika ada di folder view: script/script.js
        $js_file  = $view_dir . '/script/script.js';
        $page_js  = file_exists($js_file) ? file_get_contents($js_file) : '';

        // Render isi view halaman
        ob_start();
        require $view_dir . '/index.php';
        $page_content = ob_get_clean();

        // Render layout utama
        require APP_PATH . '/views/layouts/' . $layout_name . '.php';
    }
}
