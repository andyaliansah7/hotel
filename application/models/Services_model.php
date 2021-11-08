<?php
/**
 * Services Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Services_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_cs a');
		$sql->join('m_cs_group b', 'b.cs_group_id = a.cs_group_id', 'left');
		$sql->join('m_cs_group_parent c', 'b.cs_group_parent_id = c.cs_group_parent_id', 'left');
		$sql->order_by('cs_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_cs', $data);
	}

	public function update($id, $data) {
		$this->db->where('cs_id', $id);
		return $this->db->update('m_cs', $data);
	}

	public function delete($id) {
		$this->db->where('cs_id', $id);
		return $this->db->delete('m_cs');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_cs');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
