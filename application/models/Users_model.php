<?php
/**
 * Users Model
 *
 * Modif Core Model with Namespace
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Users_model extends Model {

	public function get_data_advance($id = "", $username = "", $password = "", $role = "")
	{
		$sql = $this->db;

		$sql->select("*");
		$sql->from("c_users u");
		$sql->join('c_roles r', 'r.role_id = u.role', 'inner');
		
		if ($id != "")
		{
			$sql->where("id", $id);
		}

		if ($username != "")
		{
			$sql->where("username", $username);
		}

		if ($password != "")
		{
			$sql->where("password", md5($password));
		}

		if ($role != "")
		{
			$sql->where("role", $role);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_role($id = "")
	{
		$sql = $this->db;

		$sql->select("*");
		$sql->from("c_roles");
		
		if ($id != "")
		{
			$sql->where("role_id", $id);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data)
	{
		return $this->db->insert("c_users", $data);
	}

	public function update($id, $data)
	{
		$this->db->where("id", $id);
		return $this->db->update("c_users", $data);
	}

	public function delete($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete("c_users");
	}

	public function check_id($where)
	{
		$this->db->select("*");
		$this->db->from("c_users");
		$this->db->where($where);

		$query = $this->db->get();
		return $query->result();
	}

}

?>
