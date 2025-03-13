<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $this->load->view('auth/login_view');
    }

    public function dashboardtesting()
    {
        if (!isset($_COOKIE['access_token'])) {
            redirect('auth/login');
            exit;
        }

        $this->load->view('auth/dashboard_view');
    }

    public function dashboard()
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

    public function logout()
    {
        setcookie("access_token", "", time() - 3600, "/");

        redirect('auth/login');
    }
}
