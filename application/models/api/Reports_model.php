<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends CI_Model
{
    /**
     * CONSTRUCTOR | LOAD DB
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_summary_data($start_date, $end_date)
    {
        // total kunjungan pasien
        $this->db->where('appointment_date >=', $start_date);
        $this->db->where('appointment_date <=', $end_date);
        $total_patient_visits = $this->db->count_all_results('appointment');

        // total appointment yang completed
        $this->db->where('appointment_date >=', $start_date);
        $this->db->where('appointment_date <=', $end_date);
        $this->db->where('appointment_status', 'Completed');
        $total_completed_appointments = $this->db->count_all_results('appointment');

        // total appointment yang cancelled
        $this->db->where('appointment_date >=', $start_date);
        $this->db->where('appointment_date <=', $end_date);
        $this->db->where('appointment_status', 'Cancelled');
        $total_cancelled_appointments = $this->db->count_all_results('appointment');

        // total rekam medis
        $this->db->where('created_at >=', $start_date);
        $this->db->where('created_at <=', $end_date);
        $total_medical_records = $this->db->count_all_results('rekam_medis');

        // total pasien baru
        $this->db->where('created_at >=', $start_date);
        $this->db->where('created_at <=', $end_date);
        $total_new_patients = $this->db->count_all_results('pasien');

        // total rawat inap
        $this->db->where('rawat_inap_masuk >=', $start_date);
        $this->db->where('rawat_inap_masuk <=', $end_date);
        $total_inpatients = $this->db->count_all_results('rawat_inap');

        // total paid invoices
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->where('invoice_status', 'Paid');
        $total_paid_invoices = $this->db->count_all_results('invoice');

        // total paid revenue
        $this->db->select_sum('invoice_amount');
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->where('invoice_status', 'Paid');
        $total_paid_revenue_result = $this->db->get('invoice')->row();
        $total_paid_revenue = $total_paid_revenue_result->invoice_amount ?? 0;

        return [
            'total_patient_visits' => $total_patient_visits,
            'total_completed_appointments' => $total_completed_appointments,
            'total_cancelled_appointments' => $total_cancelled_appointments,
            'total_medical_records' => $total_medical_records,
            'total_new_patients' => $total_new_patients,
            'total_inpatients' => $total_inpatients,
            'total_paid_invoices' => $total_paid_invoices,
            'total_paid_revenue' => $total_paid_revenue
        ];
    }
}
