<?php
class CashierDashboardController extends Controller
{
    public function index()
    {
        $this->view('cashier', 'dashboard', ['page_title' => 'Cashier Dashboard']);
    }
}