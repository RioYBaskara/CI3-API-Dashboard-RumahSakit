<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($fasyankes_kode = null)
    {
        if (!isset($_COOKIE['access_token'])) {
            redirect('auth/login');
            exit;
        }

        $this->load->model('Fasyankes_model');

        if (!$fasyankes_kode) {
            $oldest_fasyankes = $this->Fasyankes_model->getOldestFasyankes();
            if ($oldest_fasyankes) {
                redirect("dashboard/{$oldest_fasyankes['fasyankes_kode']}");
            } else {
                show_error('Tidak ada data fasyankes yang tersedia.', 404, 'Data Tidak Ditemukan');
                redirect('dashboard/void');
            }
        }

        $data['fasyankes_list'] = $this->Fasyankes_model->getAllFasyankes();

        $data['active_fasyankes'] = $this->Fasyankes_model->getFasyankesByKode($fasyankes_kode);

        $this->load->view('dashboard/template/header_view', $data);
        $this->load->view('dashboard/template/sidebar_view', $data);
        $this->load->view('dashboard/wrapper_view', $data);
        $this->load->view('dashboard/template/footer_view', $data);
    }

    public function void()
    {
        if (!isset($_COOKIE['access_token'])) {
            redirect('auth/login');
            exit;
        }

        $this->load->model('Fasyankes_model');

        $data['fasyankes_list'] = $this->Fasyankes_model->getAllFasyankes();

        $this->load->view('dashboard/void/header_view', $data);
        $this->load->view('dashboard/void/sidebar_view', $data);
        $this->load->view('dashboard/void/wrapper_view', $data);
        $this->load->view('dashboard/void/footer_view', $data);
    }
}
