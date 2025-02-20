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
        } else {
            $query = $this->db->select($fields)
                ->get("products")
                ->result();
        }

        return $query;
    }


    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function update($data, $id)
    {
        $data = $this->db->update('products', $data, array('id' => $id));
        //echo $this->db->last_query();
        return ($this->db->affected_rows() >= 0);
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function delete($id)
    {
        $this->db->delete('products', array('id' => $id));
        return $this->db->affected_rows();
    }
}
