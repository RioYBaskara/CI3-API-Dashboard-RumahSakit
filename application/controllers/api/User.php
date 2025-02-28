<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

/**
 * @property user_model $user_model
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
class User extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
		$this->load->model('user_model');
	}

	public function register_post()
	{
		$this->form_validation->set_rules(
			'username',
			'Username',
			'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]',
			array('is_unique' => 'This username already exists. Please choose another one.')
		);
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

		if ($this->form_validation->run() === false) {
			return $this->response([
				'status' => false,
				'message' => 'Validation rules violated',
				'errors' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$username = trim($this->input->post('username', true));
		$email = trim($this->input->post('email', true));
		$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

		$user_id = $this->user_model->create_user($username, $email, $password);

		if ($user_id) {
			$token_data = ['uid' => $user_id, 'username' => $username];
			$token = $this->authorization_token->generateToken($token_data);

			return $this->response([
				'status' => true,
				// 'access_token' => $token,
				'message' => 'Registration successful! You can now log in.'
			], REST_Controller::HTTP_OK);
		}

		return $this->response([
			'status' => false,
			'message' => 'Failed to create account. Please try again.'
		], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function login_post()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == false) {
			$errors = explode("\n", strip_tags(validation_errors()));
			$errors = array_filter($errors);

			$this->response([
				'status' => false,
				'message' => 'Validation rules violated',
				'errors' => $errors
			], REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if ($this->user_model->resolve_user_login($username, $password)) {
				$user_id = $this->user_model->get_user_id_from_username($username);
				$user = $this->user_model->get_user($user_id);

				$token_data['uid'] = $user_id;
				$token_data['username'] = $user->username;
				$token_data['email'] = $user->email;
				$tokenData = $this->authorization_token->generateToken($token_data);

				setcookie("access_token", $tokenData, [
					"expires" => time() + (60 * 60),
					"path" => "/",
					"httponly" => true,
					"samesite" => "Strict"
				]);

				$this->response([
					'status' => true,
					'message' => 'Login success!',
					'user' => [
						'id' => $user_id,
						'username' => $user->username,
						'email' => $user->email,
					]
				], REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Wrong username or password.'
				], REST_Controller::HTTP_UNAUTHORIZED);
			}
		}
	}

	public function logout_post()
	{
		setcookie("access_token", "", time() - 3600, "/");
		$this->response([
			'status' => true,
			'message' => 'Logout successful!'
		], REST_Controller::HTTP_OK);
	}

	public function me_get()
	{
		$user_data = $this->authorization_token->validateToken();

		if ($user_data['status']) {
			$this->response([
				'status' => true,
				'data' => $user_data['data'],
				'cookie' => get_cookie('access_token')
			], REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Unauthorized'
			], REST_Controller::HTTP_UNAUTHORIZED);
		}
	}
}
