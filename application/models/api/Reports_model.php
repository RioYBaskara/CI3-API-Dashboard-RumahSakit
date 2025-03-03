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
            'total_appointments' => $total_patient_visits,
            'total_completed_appointments' => $total_completed_appointments,
            'total_cancelled_appointments' => $total_cancelled_appointments,
            'total_medical_records' => $total_medical_records,
            'total_new_patients' => $total_new_patients,
            'total_inpatients' => $total_inpatients,
            'total_paid_invoices' => $total_paid_invoices,
            'total_paid_revenue' => $total_paid_revenue
        ];
    }

    public function get_patient_visits_report($filter, $start_date, $end_date)
    {
        $data = [];
        $total = [
            'child' => 0,
            'adult' => 0,
            'elderly' => 0,
            'total_patients' => 0
        ];

        $this->db->select('appointment.appointment_date, appointment.pasien_id, pasien.pasien_birthdate');
        $this->db->from('appointment');
        $this->db->join('pasien', 'pasien.pasien_id = appointment.pasien_id');
        $this->db->where('appointment.appointment_date >=', $start_date);
        $this->db->where('appointment.appointment_date <=', $end_date);
        $appointments = $this->db->get()->result_array();

        foreach ($appointments as $appointment) {
            $age = date_diff(date_create($appointment['pasien_birthdate']), date_create('today'))->y;

            if ($age < 18) {
                $category = 'child';
            } elseif ($age >= 18 && $age < 60) {
                $category = 'adult';
            } else {
                $category = 'elderly';
            }

            $total[$category]++;
            $total['total_patients']++;

            $date_key = date('Y-m-d', strtotime($appointment['appointment_date']));
            if ($filter === 'weekly') {
                $week_of_month = $this->getWeekOfMonth($appointment['appointment_date']);
                $month_year = date('F Y', strtotime($appointment['appointment_date']));
                $date_key = "Week $week_of_month, $month_year";
            } elseif ($filter === 'monthly') {
                $date_key = date('F Y', strtotime($appointment['appointment_date']));
            }

            if (!isset($data[$date_key])) {
                $data[$date_key] = [
                    'child' => 0,
                    'adult' => 0,
                    'elderly' => 0,
                    'total_patients' => 0
                ];
            }
            $data[$date_key][$category]++;
            $data[$date_key]['total_patients']++;
        }

        $formatted_data = [];
        foreach ($data as $key => $value) {
            if ($filter === 'daily') {
                $formatted_data[] = [
                    'date' => $key,
                    'child' => $value['child'],
                    'adult' => $value['adult'],
                    'elderly' => $value['elderly'],
                    'total_patients' => $value['total_patients']
                ];
            } elseif ($filter === 'weekly') {
                $formatted_data[] = [
                    'week' => $key,
                    'child' => $value['child'],
                    'adult' => $value['adult'],
                    'elderly' => $value['elderly'],
                    'total_patients' => $value['total_patients']
                ];
            } elseif ($filter === 'monthly') {
                $formatted_data[] = [
                    'month' => $key,
                    'child' => $value['child'],
                    'adult' => $value['adult'],
                    'elderly' => $value['elderly'],
                    'total_patients' => $value['total_patients']
                ];
            }
        }

        return [
            'total' => $total,
            'data' => $formatted_data
        ];
    }

    private function getWeekOfMonth($date)
    {
        $week_of_year = date('W', strtotime($date));

        $first_day_of_month = date('Y-m-01', strtotime($date));
        $first_week_of_month = date('W', strtotime($first_day_of_month));

        $week_of_month = $week_of_year - $first_week_of_month + 1;

        return $week_of_month;
    }
}
