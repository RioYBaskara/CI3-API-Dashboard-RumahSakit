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
class Department extends REST_Controller
{
    private $Allowed_fields = ['departemen_nm', 'is_active'];

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
        $this->load->model('master/Department_model');
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
            $data = $this->Department_model->show($id);

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
            $data = $this->Department_model->show();

            $this->response([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $data ?: []
            ], REST_Controller::HTTP_OK);
        }
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
        $this->form_validation->set_rules('departemen_nm', 'departemen_nm', 'required|max_length[100]');
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

        $insert_id = $this->Department_model->insert($data);

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

        $dataExists = $this->Department_model->show($id);
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
        $this->form_validation->set_rules('departemen_nm', 'departemen_nm', 'required|max_length[100]');
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

        $updateStatus = $this->Department_model->update($data, $id);

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

        $dataExists = $this->Department_model->show($id);
        if (!$dataExists) {
            $this->response([
                'status' => false,
                'message' => 'Not Found',
                'error' => 'Product not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $deleteStatus = $this->Department_model->delete($id, $user->username);

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