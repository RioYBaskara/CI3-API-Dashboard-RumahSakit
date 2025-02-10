<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

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
				'status' => 'error',
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
				'access_token' => $token,
				'message' => 'Registration successful! You can now log in.'
			], REST_Controller::HTTP_OK);
		}

		return $this->response([
			'status' => 'error',
			'message' => 'Failed to create account. Please try again.'
		], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function login_post()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {
			return $this->response([
				'status' => 'error',
				'message' => 'Validation rules violated',
				'errors' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$username = trim($this->input->post('username', true));
		$password = $this->input->post('password');

		$user = $this->user_model->get_user_by_username($username);

		if ($user && password_verify($password, $user->password)) {
			$token_data = ['uid' => $user->id, 'username' => $user->username];
			$token = $this->authorization_token->generateToken($token_data);

			return $this->response([
				'status' => true,
				'access_token' => $token,
				'message' => 'Login successful!',
				'username' => $user->username
			], REST_Controller::HTTP_OK);
		}

		return $this->response([
			'status' => 'error',
			'message' => 'Invalid username or password.'
		], REST_Controller::HTTP_UNAUTHORIZED);
	}

	public function logout_post()
	{
		$token = $this->authorization_token->validateToken();

		if (!$token) {
			return $this->response([
				'status' => 'error',
				'message' => 'Invalid token or user not logged in.'
			], REST_Controller::HTTP_UNAUTHORIZED);
		}

		return $this->response([
			'status' => true,
			'message' => 'Logout successful!'
		], REST_Controller::HTTP_OK);
	}
}
