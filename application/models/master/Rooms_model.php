<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rooms_model extends CI_Model
{
    private $table = 'kamar';
    private $primaryKey = 'kamar_id';

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
        $fields = "kamar.kamar_id, kamar.departemen_id, departemen.departemen_nm, kamar.kamar_nm, kamar.kamar_kapasitas, 
               kamar.created_at, kamar.created_by, kamar.updated_at, kamar.updated_by";

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('departemen', 'departemen.departemen_id = kamar.departemen_id', 'left');
        $this->db->where('kamar.is_deleted', 0);

        if (!empty($id)) {
            $this->db->where('kamar.' . $this->primaryKey, $id);
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
