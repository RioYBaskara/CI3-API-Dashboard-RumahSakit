<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inpatients_model extends CI_Model
{
    private $table = 'rawat_inap';
    private $primaryKey = 'rawat_inap_id';

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
        $fields = "rawat_inap.rawat_inap_id, rawat_inap.pasien_id, pasien.pasien_nm, rawat_inap.kamar_id, kamar.kamar_nm, kamar.departemen_id, departemen.departemen_nm, rawat_inap.rawat_inap_masuk, rawat_inap.rawat_inap_keluar, rawat_inap.is_active, rawat_inap.created_at, rawat_inap.created_by, rawat_inap.updated_at, rawat_inap.updated_by";

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('pasien', 'pasien.pasien_id = rawat_inap.pasien_id', 'left');
        $this->db->join('kamar', 'kamar.kamar_id = rawat_inap.kamar_id', 'left');
        $this->db->join('departemen', 'departemen.departemen_id = kamar.departemen_id', 'left');
        $this->db->where('rawat_inap.is_deleted', 0);

        if (!empty($id)) {
            $this->db->where($this->table . '.' . $this->primaryKey, $id);
            return $this->db->get()->row_array() ?: null;
        }

        return $this->db->get()->result();
    }

    /**
     * Cek apakah pasien masih dalam rawat inap (rawat_inap_keluar masih kosong)
     *
     * @param int $pasien_id
     * @return bool
     */
    public function isPatientStillAdmitted($pasien_id)
    {
        $this->db->where('pasien_id', $pasien_id);
        $this->db->where('rawat_inap_keluar IS NULL');
        $this->db->where('is_deleted', 0);
        $query = $this->db->get($this->table);

        return $query->num_rows() > 0;
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
    public function updateKeluar($id, $keluar, $updated_by)
    {
        if (!$this->show($id)) {
            return false;
        }

        $this->db->where($this->primaryKey, $id);
        return $this->db->update($this->table, [
            'rawat_inap_keluar' => $keluar,
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
