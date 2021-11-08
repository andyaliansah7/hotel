<?php
/**
 * Payment Methods Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Payment_methods_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_payment_method');
		$sql->order_by('payment_method_name');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_payment_method', $data);
	}

	public function update($id, $data) {
		$this->db->where('payment_method_id', $id);
		return $this->db->update('m_payment_method', $data);
	}

	public function delete($id) {
		$this->db->where('payment_method_id', $id);
		return $this->db->delete('m_payment_method');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_payment_method');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
