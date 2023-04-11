<?php
error_reporting(E_ALL & ~E_NOTICE);
defined('BASEPATH') or exit('No direct script access allowed');

class M_auth extends CI_Model
{

	// var $clientService = "frontend-client";
	// var $authKey       = "simplerest";

	public function checkAuthClient()
	{
		$q = $this->db->select('client_service,auth_key')->from('tbl_api_authorization')->where('id', '1')->get()->row();
		$clientServiceX = $q->client_service;
		$authKeyX       = $q->auth_key;

		$clientService = $this->input->get_request_header('Client-Service', TRUE);
		$authKey       = $this->input->get_request_header('Auth-Key', TRUE);
		if ($clientService == $clientServiceX && $authKey == $authKeyX) {
			return true;
		} else {
			$this->output
				->set_content_type('application/json')
				->set_status_header(401)
				->set_output(json_encode(array('status' => 401, 'message' => 'Unauthorized')));
		}
	}

	public function login($username, $password)
	{
		$q = $this->db
			->select('password,id')
			->from('tbl_api_users')
			->where('username', $username)->get()->row();
		if ($q == "") {
			return array('status' => 404, 'message' => 'Username not found.');
		} else {
			$hashPassword = $q->password;
			$id = $q->id;

			if (hash_equals($hashPassword, crypt($password, $hashPassword))) {
				$lastLogin = date("Y-m-d H:i:s");
				$token = crypt(substr(md5(rand()), 0, 7));
				$expiredAt = date("Y-m-d H:i:s", strtotime('+12 hours'));
				$this->db->trans_start();
				$this->db->where('id', $id)->update('tbl_api_users', array('last_login' => $lastLogin));
				$this->db->insert('tbl_api_users_authentication', array('users_id' => $id, 'token' => $token, 'expired_at' => $expiredAt));
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return array('status' => 500, 'message' => 'Internal Server Error.');
				} else {
					$this->db->trans_commit();
					return array('status' => 200, 'message' => 'Succesfully Login!', 'User-ID' => $id, 'Authorization' => $token);
				}
			} else {
				return array('status' => 404, 'message' => 'Wrong Password.');
			}
		}
	}

	public function logout()
	{
		$usersId = $this->input->get_request_header('User-ID', TRUE);
		$token       = $this->input->get_request_header('Authorization', TRUE);
		$this->db->where('users_id', $users_id)->where('token', $token)->delete('tbl_api_users_authentication');
		return array('status' => 200, 'message' => 'Succesfully Logout!');
	}

	public function auth()
	{
		$usersId = $this->input->get_request_header('User-ID', TRUE);
		$token = $this->input->get_request_header('Authorization', TRUE);
		$q = $this->db->select('expired_at')->from('tbl_api_users_authentication')->where('users_id', $usersId)->where('token', $token)->get()->row();
		if ($q == "") {
			$this->output
				->set_content_type('application/json')
				->set_status_header(401)
				->set_output(json_encode(array('status' => 401, 'message' => 'Unauthorized')));
		} else {
			if ($q->expired_at < date("Y-m-d H:i:s")) {
				$this->output
					->set_content_type('application/json')
					->set_status_header(401)
					->set_output(json_encode(array('status' => 401, 'message' => 'Your session has been expired')));
			} else {
				$updatedAt = date("Y-m-d H:i:s");
				$expiredAt = date("Y-m-d H:i:s", strtotime('+12 hours'));
				$this->db->where('users_id', $usersId)->where('token', $token)->update('tbl_api_users_authentication', array('expired_at' => $expiredAt));
				return array('status' => 200, 'message' => 'Authorized');
			}
		}
	}

	public function isAdmin()
	{
		$usersId = $this->input->get_request_header('User-ID', TRUE);
		$q = $this->db->select('level')->from('tbl_api_users')->where('id', $usersId)->get()->row();
		if ($q == "") {
			$this->output
				->set_content_type('application/json')
				->set_status_header(401)
				->set_output(json_encode(array('status' => 401, 'message' => 'Unauthorized')));
		} else {
			if ($q->level == "Admin") {
				return true;
			} else {
				$this->output
					->set_content_type('application/json')
					->set_status_header(401)
					->set_output(json_encode(array('status' => 401, 'message' => 'You are not allowed to access this service')));
			}
		}
	}
}

/* End of file m_auth.php */
/* Location: ./application/models/m_auth.php */