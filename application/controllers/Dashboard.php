<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!isset($_COOKIE['access_token'])) {
            redirect('auth/login');
            exit;
        }

        $this->load->view('dashboard/template/header_view');
        $this->load->view('dashboard/template/sidebar_view');
        $this->load->view('dashboard/wrapper_view');
        $this->load->view('dashboard/template/footer_view');
    }
}
