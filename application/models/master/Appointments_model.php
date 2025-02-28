<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Appointments_model extends CI_Model
{
    private $table = 'appointment';
    private $primaryKey = 'appointment_id';

    /**
     * CONSTRUCTOR | LOAD DB
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function show($id = 0)
    {
        $fields = "appointment.appointment_id, appointment.pasien_id, pasien.pasien_nm, 
                   appointment.dokter_id, dokter.dokter_nm, appointment.departemen_id, 
                   departemen.departemen_nm, appointment.appointment_date, appointment.appointment_status,
                   appointment.created_at, appointment.created_by, appointment.updated_at, appointment.updated_by";

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('pasien', 'pasien.pasien_id = appointment.pasien_id', 'left');
        $this->db->join('dokter', 'dokter.dokter_id = appointment.dokter_id', 'left');
        $this->db->join('departemen', 'departemen.departemen_id = appointment.departemen_id', 'left');
        $this->db->where('appointment.is_deleted', 0);

        if (!empty($id)) {
            $this->db->where($this->table . '.' . $this->primaryKey, $id);
            return $this->db->get()->row_array() ?: null;
        }

        return $this->db->get()->result();
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function update($data, $id)
    {
        if (!$this->show($id)) {
            return false;
        }

        $this->db->where($this->primaryKey, $id);
        $this->db->update($this->table, $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * UPDATE | PATCH method.
     *
     * @return Response
     */
    public function updateStatus($id, $status, $updated_by)
    {
        if (!$this->show($id)) {
            return false;
        }

        $this->db->where($this->primaryKey, $id);
        return $this->db->update($this->table, [
            'appointment_status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $updated_by
        ]);
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function delete($id, $deleted_by)
    {
        if (!$this->show($id)) {
            return false;
        }

        $data = [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deleted_by
        ];

        $this->db->where($this->primaryKey, $id);
        $this->db->update($this->table, $data);

        return $this->db->affected_rows() > 0;
    }
}
