<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fasyankes_model extends CI_Model
{
    public function insertFasyankes($data)
    {
        return $this->db->insert('fasyankes', $data);
    }

    public function updateFasyankes($fasyankes_kode, $data)
    {
        $this->db->where('fasyankes_kode', $fasyankes_kode);
        return $this->db->update('fasyankes', $data);
    }

    public function getAllFasyankes()
    {
        $this->db->where('is_deleted', 0);
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('fasyankes')->result_array();
    }

    public function getFasyankesByKode($kode)
    {
        return $this->db->get_where('fasyankes', ['fasyankes_kode' => $kode, 'is_deleted' => 0])->row_array();
    }

    public function deleteFasyankes($fasyankes_kode, $deleted_by)
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deleted_by
        ];
        $this->db->where('fasyankes_kode', $fasyankes_kode);
        return $this->db->update('fasyankes', $data);
    }
}