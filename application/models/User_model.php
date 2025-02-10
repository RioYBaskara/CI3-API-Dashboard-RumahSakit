<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * create_user function.
	 * 
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return int|bool Inserted user ID on success, false on failure
	 */
	public function create_user($username, $email, $password)
	{
		$data = array(
			'username' => $username,
			'email' => $email,
			'password' => $this->hash_password($password),
			'created_at' => date('Y-m-d H:i:s'),
		);

		$this->db->insert('users', $data);
		return $this->db->insert_id() ?: false;
	}

	/**
	 * resolve_user_login function.
	 * 
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function resolve_user_login($username, $password)
	{
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('username', $username);
		$hash = $this->db->get()->row('password');

		return $hash ? $this->verify_password_hash($password, $hash) : false;
	}

	/**
	 * get_user_by_username function.
	 * 
	 * @param string $username
	 * @return object|null The user object or null if not found
	 */
	public function get_user_by_username($username)
	{
		$this->db->from('users');
		$this->db->where('username', $username);
		return $this->db->get()->row();
	}

	/**
	 * get_user_id_from_username function.
	 * 
	 * @param string $username
	 * @return int|null User ID or null if not found
	 */
	public function get_user_id_from_username($username)
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');
	}

	/**
	 * get_user function.
	 * 
	 * @param int $user_id
	 * @return object|null The user object or null if not found
	 */
	public function get_user($user_id)
	{
		$this->db->from('users');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
	}

	/**
	 * hash_password function.
	 * 
	 * @param string $password
	 * @return string
	 */
	private function hash_password($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	/**
	 * verify_password_hash function.
	 * 
	 * @param string $password
	 * @param string $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash)
	{
		return password_verify($password, $hash);
	}
}