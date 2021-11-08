<?php
/**
 * Deposit Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Deposit_model extends Model {

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
		$sql->from('t_deposit a');
		$sql->join('m_guest b', 'b.guest_id = a.guest_id', 'inner');
		// $sql->join('m_payment_method c', 'c.payment_method_id = a.payment_method_id', 'left');
		$sql->order_by('deposit_date', 'desc');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('t_deposit', $data);
	}

	public function update($id, $data) {
		$this->db->where('deposit_id', $id);
		return $this->db->update('t_deposit', $data);
	}

	public function update_byPaymentId($id, $data) {
		$this->db->where('payment_id', $id);
		return $this->db->update('t_deposit', $data);
	}

	public function delete($id) {
		$this->db->where('deposit_id', $id);
		return $this->db->delete('t_deposit');
	}

}

?>
