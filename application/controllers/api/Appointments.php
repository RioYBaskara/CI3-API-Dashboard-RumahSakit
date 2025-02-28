<?php

/**
 * Product class.
 * 
 * @extends REST_Controller
 */
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

/**
 * @property MedicalRecords_model $MedicalRecords_model
 * @property Patients_model $Patients_model
 * @property Appointments_model $Appointments_model
 * @property Diagnoses_model $Diagnoses_model
 * @property Doctors_model $Doctors_model
 * @property Department_model $Department_model
 * @property input $input
 * @property authorization_token $authorization_token
 * @property form_validation $form_validation
 */
class Appointments extends REST_Controller
{
    private $Allowed_fields = ['pasien_id', 'dokter_id', 'departemen_id', 'appointment_date', 'is_active'];

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->library('form_validation');
        $this->load->model('master/Appointments_model');
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
    public function index_get($id = 0)
    {
        if (!$this->authenticate())
            return;

        if (!empty($id)) {
            $data = $this->Appointments_model->show($id);

            if ($data) {
                $this->response([
                    'status' => true,
                    'message' => 'Data retrieved successfully',
                    'data' => $data
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data not found',
                    'error' => 'No data exists with the given ID'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $data = $this->Appointments_model->show();

            $this->response([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $data ?: []
            ], REST_Controller::HTTP_OK);
        }
    }

    public function check_pasien_id($pasien_id)
    {
        $this->load->model('master/Patients_model');
        $pasien = $this->Patients_model->show($pasien_id);

        if (!$pasien) {
            $this->form_validation->set_message('check_pasien_id', 'The {field} does not exist in the pasien table.');
            return FALSE;
        }

        return TRUE;
    }

    public function check_dokter_id($dokter_id)
    {
        $this->load->model('master/Doctors_model');
        $dokter = $this->Doctors_model->show($dokter_id);

        if (!$dokter) {
            $this->form_validation->set_message('check_dokter_id', 'The {field} does not exist in the dokter table.');
            return FALSE;
        }

        return TRUE;
    }

    public function check_departemen_id($departemen_id)
    {
        $this->load->model('master/Department_model');
        $departemen = $this->Department_model->show($departemen_id);

        if (!$departemen) {
            $this->form_validation->set_message('check_departemen_id', 'The {field} does not exist in the departemen table.');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function index_post()
    {
        $user = $this->authenticate();
        if (!$user)
            return;

        $input = $this->input->post();
        if (empty($input)) {
            $input = json_decode($this->input->raw_input_stream, true);
        }

        if (!$input || !is_array($input)) {
            $this->response([
                'status' => false,
                'message' => 'Invalid Request',
                'error' => 'Request body must be a valid JSON object or form-data'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $this->form_validation->set_data($input);

        // validasi
        $this->form_validation->set_rules('pasien_id', 'pasien_id', 'required|numeric|callback_check_pasien_id');
        $this->form_validation->set_rules('dokter_id', 'dokter_id', 'required|numeric|callback_check_dokter_id');
        $this->form_validation->set_rules('departemen_id', 'departemen_id', 'required|numeric|callback_check_departemen_id');
        $this->form_validation->set_rules('appointment_date', 'appointment_date', 'required');
        $this->form_validation->set_rules('is_active', 'is_active', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request!',
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // list field yang boleh diinput
        $allowed_data = $this->Allowed_fields;

        $data = array_intersect_key($input, array_flip($allowed_data));

        // logging
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['created_by'] = $user->username;
        $data['is_deleted'] = 0;

        $insert_id = $this->Appointments_model->insert($data);

        if ($insert_id) {
            $this->response([
                'status' => true,
                'message' => 'Data created successfully',
                'data' => array_merge(['id' => $insert_id], $input)
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Failed to insert data'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function index_put($id)
    {
        $user = $this->authenticate();
        if (!$user)
            return;

        if (empty($id) || !is_numeric($id)) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Invalid data ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $dataExists = $this->Appointments_model->show($id);
        if (!$dataExists) {
            $this->response([
                'status' => false,
                'message' => 'Not Found',
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $input = $this->put();
        if (empty($input)) {
            $input = json_decode($this->input->raw_input_stream, true);
        }

        if (!$input || !is_array($input)) {
            $this->response([
                'status' => false,
                'message' => 'Invalid Request',
                'error' => 'Request body must be a valid JSON object or form-data'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $this->form_validation->set_data($input);

        // validasi
        $this->form_validation->set_rules('pasien_id', 'pasien_id', 'required|numeric|callback_check_pasien_id');
        $this->form_validation->set_rules('dokter_id', 'dokter_id', 'required|numeric|callback_check_dokter_id');
        $this->form_validation->set_rules('departemen_id', 'departemen_id', 'required|numeric|callback_check_departemen_id');
        $this->form_validation->set_rules('appointment_date', 'appointment_date', 'required');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request!',
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // list field yang boleh diinput
        $allowed_data = $this->Allowed_fields;

        $data = array_intersect_key($input, array_flip($allowed_data));

        $noChange = true;
        foreach ($data as $key => $value) {
            if (isset($dataExists[$key]) && $dataExists[$key] != $value) {
                $noChange = false;
                break;
            }
        }

        if ($noChange) {
            $this->response([
                'status' => true,
                'message' => 'No changes detected',
                'data' => $input
            ], REST_Controller::HTTP_OK);
            return;
        }

        // logging
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['updated_by'] = $user->username;

        $updateStatus = $this->Appointments_model->update($data, $id);

        if ($updateStatus) {
            $this->response([
                'status' => true,
                'message' => 'Data updated successfully',
                'data' => array_merge(['id' => $id], $input)
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Failed to update data'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * PATCH method.
     *
     * @return Response
     */
    public function cancel_patch($id)
    {
        $user = $this->authenticate();
        if (!$user)
            return;

        if (empty($id) || !is_numeric($id)) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Invalid data ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $dataExists = $this->Appointments_model->show($id);

        if (!$dataExists) {
            $this->response([
                "status" => false,
                "message" => "Not found",
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        if ($dataExists['appointment_status'] === 'Cancelled') {
            $this->response([
                "status" => false,
                "message" => "Appointment is already cancelled"
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        if ($dataExists['appointment_status'] !== 'Pending') {
            $this->response([
                "status" => false,
                "message" => "Only pending appointments can be cancelled"
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        $this->Appointments_model->updateStatus($id, 'Cancelled', $user->username);

        $this->response([
            "status" => true,
            "message" => "Appointment cancelled successfully",
            "data" => [
                "appointment_id" => $id,
                "appointment_status" => "Cancelled"
            ]
        ], REST_Controller::HTTP_OK);
    }

    public function complete_patch($id)
    {
        $user = $this->authenticate();
        if (!$user)
            return;

        if (empty($id) || !is_numeric($id)) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Invalid data ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $dataExists = $this->Appointments_model->show($id);

        if (!$dataExists) {
            $this->response([
                "status" => false,
                "message" => "Not found",
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        if ($dataExists['appointment_status'] === 'Completed') {
            $this->response([
                "status" => false,
                "message" => "Appointment is already Completed"
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        if ($dataExists['appointment_status'] !== 'Pending') {
            $this->response([
                "status" => false,
                "message" => "Only pending appointments can be completed"
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        $this->Appointments_model->updateStatus($id, 'completed', $user->username);

        $this->response([
            "status" => true,
            "message" => "Appointment completed successfully",
            "data" => [
                "appointment_id" => $id,
                "appointment_status" => "Completed"
            ]
        ], REST_Controller::HTTP_OK);
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function index_delete($id = null)
    {
        $user = $this->authenticate();
        if (!$user)
            return;

        if (empty($id) || !is_numeric($id)) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Invalid product ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $dataExists = $this->Appointments_model->show($id);
        if (!$dataExists) {
            $this->response([
                'status' => false,
                'message' => 'Not Found',
                'error' => 'Product not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $deleteStatus = $this->Appointments_model->delete($id, $user->username);

        if ($deleteStatus) {
            $this->response([
                'status' => true,
                'message' => 'Product deleted successfully',
                'data' => ['id' => $id]
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Failed to delete product'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}