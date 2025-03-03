<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MedicalRecords_model extends CI_Model
{
    private $table = 'rekam_medis';
    private $primaryKey = 'rekam_medis_id';

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
        $fields = "rekam_medis.rekam_medis_id, rekam_medis.pasien_id, pasien.pasien_nm, 
                   rekam_medis.dokter_id, dokter.dokter_nm, rekam_medis.appointment_id, rekam_medis.diagnosa_kode, diagnosa.diagnosa_nm, rekam_medis.rekam_medis_notes, rekam_medis.is_active, rekam_medis.created_at, rekam_medis.created_by, rekam_medis.updated_at, rekam_medis.updated_by";

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('pasien', 'pasien.pasien_id = rekam_medis.pasien_id', 'left');
        $this->db->join('dokter', 'dokter.dokter_id = rekam_medis.dokter_id', 'left');
        $this->db->join('appointment', 'appointment.appointment_id = rekam_medis.appointment_id', 'left');
        $this->db->join('diagnosa', 'diagnosa.diagnosa_kode = rekam_medis.diagnosa_kode', 'left');
        $this->db->where('rekam_medis.is_deleted', 0);

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
    public function updateNote($id, $note, $updated_by)
    {
        if (!$this->show($id)) {
            return false;
        }

        $this->db->where($this->primaryKey, $id);
        return $this->db->update($this->table, [
            'rekam_medis_notes' => $note,
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
