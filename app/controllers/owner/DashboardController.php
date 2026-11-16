<?php
class OwnerDashboardController extends Controller
{
    public function index()
    {
        $this->view('owner', 'dashboard', ['page_title' => 'Owner Dashboard']);
    }
}