<?php
/**
 * Guests Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Guests_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_guest');
		$sql->order_by('guest_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_guest', $data);
	}

	public function update($id, $data) {
		$this->db->where('guest_id', $id);
		return $this->db->update('m_guest', $data);
	}

	public function delete($id) {
		$this->db->where('guest_id', $id);
		return $this->db->delete('m_guest');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_guest');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
