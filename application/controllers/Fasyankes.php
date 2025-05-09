<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Fasyankes_model $Fasyankes_model
 * @property input $input
 * @property session $session
 * @property authorization_token $authorization_token
 * @property upload $upload
 * @property form_validation $form_validation
 */

class Fasyankes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Fasyankes_model');
        $this->load->library('form_validation');
        $this->load->library('Authorization_Token');
    }

    public function create()
    {
        $user_data = $this->authorization_token->validateToken();

        if (!$user_data['status']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Token tidak valid atau kadaluarsa.'
            ]);
            return;
        }

        $username = $user_data['data']->username;

        $this->form_validation->set_rules('fasyankes_kode', 'Kode Fasyankes', 'required|integer|trim|is_unique[fasyankes.fasyankes_kode]', [
            'is_unique' => 'Kode Fasyankes sudah terdaftar!'
        ]);
        $this->form_validation->set_rules('fasyankes_tipe', 'Tipe Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_nm', 'Nama Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_alamat', 'Alamat Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_kepala', 'Kepala Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_url_api', 'URL API Fasyankes', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
        } else {
            $image = 'default.jpg';

            if (!empty($_FILES['fasyankes_image']['name'])) {
                $config['upload_path'] = FCPATH . 'private/assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'fasyankes_' . time();
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('fasyankes_image')) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => $this->upload->display_errors()
                    ]);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    $image = $upload_data['file_name'];
                }
            }

            $data = [
                'fasyankes_kode' => $this->input->post('fasyankes_kode'),
                'fasyankes_tipe' => $this->input->post('fasyankes_tipe'),
                'fasyankes_nm' => $this->input->post('fasyankes_nm'),
                'fasyankes_alamat' => $this->input->post('fasyankes_alamat'),
                'fasyankes_kepala' => $this->input->post('fasyankes_kepala'),
                'fasyankes_image' => $image,
                'fasyankes_url_api' => $this->input->post('fasyankes_url_api'),
                'is_active' => $this->input->post('active_st'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $username
            ];

            if ($this->Fasyankes_model->insertFasyankes($data)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Fasyankes berhasil ditambahkan.',
                    'redirect' => base_url('dashboard')
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan fasyankes.'
                ]);
            }
        }
    }

    public function update()
    {
        $user_data = $this->authorization_token->validateToken();

        if (!$user_data['status']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Token tidak valid atau kadaluarsa.'
            ]);
            return;
        }

        $username = $user_data['data']->username;

        $fasyankes_kode = $this->input->post('fasyankes_kode');

        $this->form_validation->set_rules('fasyankes_tipe', 'Tipe Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_nm', 'Nama Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_alamat', 'Alamat Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_kepala', 'Kepala Fasyankes', 'required|trim');
        $this->form_validation->set_rules('fasyankes_url_api', 'URL API Fasyankes', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
        } else {
            $old_fasyankes = $this->Fasyankes_model->getFasyankesByKode($fasyankes_kode);
            $old_image = $old_fasyankes['fasyankes_image'];

            $image = $old_image;

            if ($this->input->post('delete_image')) {
                $image = 'default.jpg';

                if ($old_image && $old_image != 'default.jpg') {
                    unlink(FCPATH . 'private/assets/img/' . $old_image);
                }
            }

            if (!empty($_FILES['fasyankes_image']['name'])) {
                $config['upload_path'] = FCPATH . 'private/assets/img/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'fasyankes_' . time();
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('fasyankes_image')) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => $this->upload->display_errors()
                    ]);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    $image = $upload_data['file_name'];

                    if ($old_image && $old_image != 'default.jpg') {
                        unlink(FCPATH . 'private/assets/img/' . $old_image);
                    }
                }
            }

            $data = [
                'fasyankes_tipe' => $this->input->post('fasyankes_tipe'),
                'fasyankes_nm' => $this->input->post('fasyankes_nm'),
                'fasyankes_alamat' => $this->input->post('fasyankes_alamat'),
                'fasyankes_kepala' => $this->input->post('fasyankes_kepala'),
                'fasyankes_image' => $image,
                'fasyankes_url_api' => $this->input->post('fasyankes_url_api'),
                'is_active' => $this->input->post('active_st'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $username
            ];

            if ($this->Fasyankes_model->updateFasyankes($fasyankes_kode, $data)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Fasyankes berhasil diupdate.',
                    'redirect' => base_url('dashboard/' . $fasyankes_kode)
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal mengupdate fasyankes.'
                ]);
            }
        }
    }
    public function delete()
    {
        $user_data = $this->authorization_token->validateToken();

        if (!$user_data['status']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Token tidak valid atau kadaluarsa.'
            ]);
            return;
        }

        $username = $user_data['data']->username;

        $fasyankes_kode = $this->input->post('fasyankes_kode');

        $fasyankes = $this->Fasyankes_model->getFasyankesByKode($fasyankes_kode);
        $image = $fasyankes['fasyankes_image'];

        if ($image && $image !== 'default.jpg') {
            unlink(FCPATH . 'private/assets/img/' . $image);
        }

        if ($this->Fasyankes_model->deleteFasyankes($fasyankes_kode, $username)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Fasyankes berhasil dihapus.',
                'redirect' => base_url('dashboard')
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus fasyankes.'
            ]);
        }
    }
}