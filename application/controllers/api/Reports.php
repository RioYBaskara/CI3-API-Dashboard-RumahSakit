<?php

/**
 * Product class.
 * 
 * @extends REST_Controller
 */
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

/**
 * @property Reports_model $Reports_model
 * @property input $input
 * @property authorization_token $authorization_token
 * @property form_validation $form_validation
 */
class Reports extends REST_Controller
{

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('api/Reports_model');
    }

    private function authenticate()
    {
        $decodedToken = $this->authorization_token->validateToken();

        if (!$decodedToken['status']) {
            $this->response([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized',
                'error' => $decodedToken['message']
            ], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }

        return $decodedToken['data'];
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function tes_get()
    {
        if (!$this->authenticate())
            return;

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $this->response([
            'tes' => 'Masuk',
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function summary_get()
    {
        if (!$this->authenticate())
            return;

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if (empty($start_date) || empty($end_date)) {
            $this->response([
                'status' => false,
                'message' => 'start_date and end_date are required.',
                'error' => 'Bad Request'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $summary_data = $this->Reports_model->get_summary_data($start_date, $end_date);

        if ($summary_data) {
            $this->response([
                'status' => true,
                'message' => 'Summary data retrieved successfully.',
                'date_range' => [
                    'start_date' => $start_date,
                    'end_date' => $end_date
                ],
                'data' => $summary_data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No data found for the given date range.',
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function patient_visits_get()
    {
        $filter = $this->input->get('filter');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if (empty($filter) || empty($start_date) || empty($end_date)) {
            $this->response([
                'status' => false,
                'message' => 'filter, start_date, and end_date are required.',
                'error' => 'Bad Request'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $report_data = $this->Reports_model->get_patient_visits_report($filter, $start_date, $end_date);

        if ($report_data) {
            $this->response([
                'status' => true,
                'message' => 'Patient visit report retrieved successfully',
                'filter' => $filter,
                'date_range' => "$start_date to $end_date",
                'total' => $report_data['total'],
                'data' => $report_data['data']
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No data found for the given date range.',
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function patient_visit_department_get()
    {
        $filter = $this->input->get('filter');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if (empty($filter) || empty($start_date) || empty($end_date)) {
            $this->response([
                'status' => false,
                'message' => 'filter, start_date, and end_date are required.',
                'error' => 'Bad Request'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $report_data = $this->Reports_model->get_patient_visits_by_department($filter, $start_date, $end_date);

        if ($report_data) {
            $this->response([
                'status' => true,
                'message' => 'Patient visit by department report retrieved successfully',
                'filter' => $filter,
                'date_range' => "$start_date to $end_date",
                'total' => $report_data['total'],
                'data' => $report_data['data']
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No data found for the given date range.',
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function top_diagnoses_get()
    {
        $filter = $this->input->get('filter');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if (empty($filter) || empty($start_date) || empty($end_date)) {
            $this->response([
                'status' => false,
                'message' => 'filter, start_date, and end_date are required.',
                'error' => 'Bad Request'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $report_data = $this->Reports_model->get_top_diagnoses($filter, $start_date, $end_date);

        if ($report_data) {
            $this->response([
                'status' => true,
                'message' => 'Success fetching most common diagnoses report',
                'date_range' => "$start_date to $end_date",
                'filter' => $filter,
                'data' => $report_data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No data found for the given date range.',
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function revenue_get()
    {
        $filter = $this->input->get('filter');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if (empty($filter) || empty($start_date) || empty($end_date)) {
            $this->response([
                'status' => false,
                'message' => 'filter, start_date, and end_date are required.',
                'error' => 'Bad Request'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $report_data = $this->Reports_model->get_revenue_report($filter, $start_date, $end_date);

        if ($report_data) {
            $this->response([
                'status' => true,
                'message' => 'Success fetching revenue report',
                'date_range' => "$start_date to $end_date",
                'filter' => $filter,
                'total_revenue' => $report_data['total_revenue'],
                'data' => $report_data['data']
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No data found for the given date range.',
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}