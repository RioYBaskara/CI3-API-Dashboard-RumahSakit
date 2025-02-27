<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
    private $table = 'products';
    private $primaryKey = 'id';

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
        $fields = "{$this->primaryKey}, name, price, created_at, created_by, updated_at, updated_by";

        $this->db->select($fields);
        $this->db->where('is_deleted', 0);

        if (!empty($id)) {
            return $this->db->get_where($this->table, [$this->primaryKey => $id])->row_array() ?: null;
        }

        return $this->db->get($this->table)->result();
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
