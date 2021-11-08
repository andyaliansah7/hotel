<?php
/**
 * Guest Groups Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Guest_groups_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_guest_group');
		$sql->order_by('guest_group_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_guest_group', $data);
	}

	public function update($id, $data) {
		$this->db->where('guest_group_id', $id);
		return $this->db->update('m_guest_group', $data);
	}

	public function delete($id) {
		$this->db->where('guest_group_id', $id);
		return $this->db->delete('m_guest_group');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_guest_group');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
