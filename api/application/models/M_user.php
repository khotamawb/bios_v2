<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{

	public function getAllUser()
	{
		return $this->db->select('username,fullname,last_login')->from('tbl_users_api')->order_by('id', 'desc')->get()->result();
	}

	public function getDetailUser($id)
	{
		return $this->db->select('username,fullname,last_login,created_at')->from('tbl_users_api')->where('id', $id)->get()->row();
	}

	public function createUser($data)
	{
		$data['password'] = crypt($data['password']);
		$data['level'] = "Member";
		$this->db->insert('users', $data);
		return array('status' => 201, 'message' => 'Data has been created');
	}

	public function updateUser($params, $id)
	{
		$data['username']   = $params['username'];
		$data['fullname']   = $params['fullname'];
		$data['level']      = $params['level'];
		$data['updated_at'] = date("Y-m-d H:i:s");

		$this->db->where('id', $id)->update('tbl_users_api', $data);
		return array('status' => 201, 'message' => 'Data has been updated');
	}

	public function deleteUser($id)
	{
		$this->db->where('id', $id)->delete('tbl_users_api');
		return array('status' => 201, 'message' => 'Data has been deleted');
	}

	public function updateUserPassword($params, $id)
	{
		$q = $this->db
			->select('password,id')
			->from('tbl_users_api')
			->where('id', $id)->get()->row();
		if ($q == "") {
			return array('status' => 404, 'message' => 'Username not found.');
		}
		$hashPassword = $q->password;
		if ($params['new_pass'] != $params['conf_pass']) {
			return array('status' => 401, 'message' => " New and Conf pass are not match");
		}
		if (hash_equals($hashPassword, crypt($params['old_pass'], $hashPassword))) {
			$data['password'] = crypt($params['new_pass']);
			$data['updated_at'] = date("Y-m-d H:i:s");
			$this->db->where('id', $id)->update('tbl_users_api', $data);
		} else {
			return array('status' => 401, 'message' => 'The Old password is not same');
		}

		return array('status' => 201, 'message' => " User's password has been updated");
	}
}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */