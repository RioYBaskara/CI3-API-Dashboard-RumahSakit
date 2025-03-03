<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoices_model extends CI_Model
{
    private $table = 'invoice';
    private $primaryKey = 'invoice_id';

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
        $fields = "invoice.invoice_id, invoice.pasien_id, pasien.pasien_nm, invoice.invoice_amount, invoice.invoice_date, invoice.invoice_status, invoice.is_active, invoice.created_at, invoice.created_by, invoice.updated_at, invoice.updated_by";

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('pasien', 'pasien.pasien_id = invoice.pasien_id', 'left');
        $this->db->where('invoice.is_deleted', 0);

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
            'invoice_status' => $status,
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
