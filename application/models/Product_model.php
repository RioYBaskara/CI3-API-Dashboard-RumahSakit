<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
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
        $fields = "id, name, price, created_at, updated_at";

        if (!empty($id)) {
            $query = $this->db->select($fields)
                ->get_where("products", ['id' => $id])
                ->row_array();
            return $query ?: null;
        }

        return $this->db->select($fields)
            ->get("products")
            ->result();
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        if ($this->db->insert('products', $data)) {
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

        $this->db->where('id', $id);
        $this->db->update('products', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function delete($id)
    {
        if (!$this->show($id)) {
            return false;
        }

        $this->db->delete('products', ['id' => $id]);
        return $this->db->affected_rows() > 0;
    }
}
