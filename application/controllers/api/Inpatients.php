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
 * @property Diagnoses_model $Diagnoses_model
 * @property Doctors_model $Doctors_model
 * @property Department_model $Department_model
 * @property Inpatients_model $Inpatients_model
 * @property Rooms_model $Rooms_model
 * @property input $input
 * @property authorization_token $authorization_token
 * @property form_validation $form_validation
 */
class Inpatients extends REST_Controller
{
    private $Allowed_fields = ['pasien_id', 'kamar_id', 'rawat_inap_masuk', 'rawat_inap_keluar', 'is_active'];

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
        $this->load->model('api/Inpatients_model');
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
            $data = $this->Inpatients_model->show($id);

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
            $data = $this->Inpatients_model->show();

            $this->response([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $data ?: []
            ], REST_Controller::HTTP_OK);
        }
    }

    public function check_pasien_id($pasien_id)
    {
        $this->load->model('api/Patients_model');
        $pasien = $this->Patients_model->show($pasien_id);

        if (!$pasien) {
            $this->form_validation->set_message('check_pasien_id', 'The {field} does not exist in the pasien table.');
            return FALSE;
        }

        return TRUE;
    }

    public function check_kamar_id($kamar_id)
    {
        $this->load->model('api/Rooms_model');
        $kamar = $this->Rooms_model->show($kamar_id);

        if (!$kamar) {
            $this->form_validation->set_message('check_kamar_id', 'The {field} does not exist in the kamar table.');
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
        $this->form_validation->set_rules('pasien_id', 'Pasien ID', 'required|numeric|callback_check_pasien_id');
        $this->form_validation->set_rules('kamar_id', 'Kamar ID', 'required|numeric|callback_check_kamar_id');
        $this->form_validation->set_rules('rawat_inap_masuk', 'Tanggal Masuk Rawat Inap', 'required');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request!',
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $pasien_id = $input['pasien_id'];
        if ($this->Inpatients_model->isPatientStillAdmitted($pasien_id)) {
            $this->response([
                'status' => false,
                'message' => 'Patient is still admitted',
                'error' => 'Patient has not been discharged from the previous inpatient care'
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        // list field yang boleh diinput
        $allowed_data = $this->Allowed_fields;

        $data = array_intersect_key($input, array_flip($allowed_data));

        // logging
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['created_by'] = $user->username;
        $data['is_deleted'] = 0;

        $insert_id = $this->Inpatients_model->insert($data);

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

        $dataExists = $this->Inpatients_model->show($id);
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
        $this->form_validation->set_rules('pasien_id', 'Pasien ID', 'required|numeric|callback_check_pasien_id');
        $this->form_validation->set_rules('kamar_id', 'Kamar ID', 'required|numeric|callback_check_kamar_id');
        $this->form_validation->set_rules('rawat_inap_masuk', 'Tanggal Masuk Rawat Inap', 'required');
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

        if (empty($dataExists['rawat_inap_keluar']) && isset($data['rawat_inap_keluar'])) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'rawat_inap_keluar cannot be updated because the patient has not been discharged yet'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

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

        $updateStatus = $this->Inpatients_model->update($data, $id);

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
    public function discharge_patch($id)
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

        $dataExists = $this->Inpatients_model->show($id);

        if (!$dataExists) {
            $this->response([
                "status" => false,
                "message" => "Not found",
                'error' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        if ($dataExists['rawat_inap_keluar'] != null) {
            $this->response([
                "status" => false,
                "message" => "The patient has been discharged"
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        $currentDateTime = date('Y-m-d H:i:s');

        $this->Inpatients_model->updateKeluar($id, $currentDateTime, $user->username);

        $this->response([
            "status" => true,
            "message" => "Patient discharged successfully",
            "data" => [
                "rawat_inap_id" => $id,
                "rawat_inap_keluar" => $currentDateTime
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

        $dataExists = $this->Inpatients_model->show($id);
        if (!$dataExists) {
            $this->response([
                'status' => false,
                'message' => 'Not Found',
                'error' => 'Product not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $deleteStatus = $this->Inpatients_model->delete($id, $user->username);

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