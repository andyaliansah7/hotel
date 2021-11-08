<?php
/**
 * Special Rates Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Special_rates_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
		$sql->from('m_special_rate a');
		$sql->join('m_room_type b', 'b.room_type_id = a.room_type_id', 'inner');
		$sql->order_by('special_rate_date');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_special_rate', $data);
	}

	public function update($id, $data) {
		$this->db->where('special_rate_id', $id);
		return $this->db->update('m_special_rate', $data);
	}

	public function delete($id) {
		$this->db->where('special_rate_id', $id);
		return $this->db->delete('m_special_rate');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_special_rate');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
