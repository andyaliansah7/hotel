<?php
/**
 * Cs_groups Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Cs_groups_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_cs_group a');
        $sql->join('m_cs_group_parent b', 'b.cs_group_parent_id = a.cs_group_parent_id', 'inner');
		$sql->order_by('b.cs_group_parent_name asc, cs_group_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_parent($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_cs_group_parent');
		$sql->order_by('cs_group_parent_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_cs_group', $data);
	}

	public function update($id, $data) {
		$this->db->where('cs_group_id', $id);
		return $this->db->update('m_cs_group', $data);
	}

	public function delete($id) {
		$this->db->where('cs_group_id', $id);
		return $this->db->delete('m_cs_group');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_cs_group');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
