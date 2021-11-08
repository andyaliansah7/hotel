<?php
/**
 * Items Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Items_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_item a');
        $sql->order_by('item_name asc');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_item', $data);
	}

	public function update($id, $data) {
		$this->db->where('item_id', $id);
		return $this->db->update('m_item', $data);
	}

	public function delete($id) {
		$this->db->where('item_id', $id);
		return $this->db->delete('m_item');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_item');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
