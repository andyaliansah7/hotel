<?php
/**
 * Room Types Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Room_types_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_room_type a');
		$sql->order_by('room_type_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_room_type', $data);
	}

	public function update($id, $data) {
		$this->db->where('room_type_id', $id);
		return $this->db->update('m_room_type', $data);
	}

	public function delete($id) {
		$this->db->where('room_type_id', $id);
		return $this->db->delete('m_room_type');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_room_type');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
