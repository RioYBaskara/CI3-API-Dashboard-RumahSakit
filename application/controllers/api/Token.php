<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User class.
 * 
 * @extends REST_Controller
 */
require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Token extends REST_Controller
{

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
		$this->load->model('user_model');
	}

	/**
	 * register function.
	 * 
	 * @access public
	 * @return void
	 */

	public function reGenToken_post()
	{
		$username = trim($this->input->post('username', true));

		if (empty($username)) {
			return $this->response([
				'status' => false,
				'message' => 'Username is required to regenerate token.'
			], REST_Controller::HTTP_BAD_REQUEST); // 400
		}

		$user_id = $this->user_model->get_user_id_from_username($username);

		if (empty($user_id)) {
			return $this->response([
				'status' => false,
				'message' => 'User not found.'
			], REST_Controller::HTTP_NOT_FOUND); // 404
		}

		$token_data = [
			'uid' => $user_id,
			'username' => $username
		];

		$token = $this->authorization_token->generateToken($token_data);

		if (!$token || is_array($token)) {
			return $this->response([
				'status' => false,
				'message' => 'Failed to generate token.'
			], REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // 500
		}

		return $this->response([
			'status' => true,
			'access_token' => $token
		], REST_Controller::HTTP_OK); // 200
	}

}