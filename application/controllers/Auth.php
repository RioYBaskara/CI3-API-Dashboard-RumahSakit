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
        redirect('dashboard');
    }

    public function logout()
    {
        setcookie("access_token", "", time() - 999999999, "/");

        redirect('auth/login');
    }
}
