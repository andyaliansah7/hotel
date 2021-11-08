<?php
/**
 * Checkin Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Checkin_model extends Model {

	public function checkin_autonumber()
	{	
		$sql = $this->db;

		$sql->select('RIGHT(t_transaction.transaction_number, 4) as serial_number', FALSE);
		$sql->where('MONTH(transaction_date) = MONTH(CURRENT_DATE())');
		$sql->where('transaction_type = "C"');
		$sql->order_by('transaction_number', 'DESC');
		$sql->limit(1);    
		$query = $sql->get('t_transaction');  
		if($query->num_rows() <> 0){         
			$data = $query->row();      
			$serial_number = intval($data->serial_number) + 1;    
		}
		else {          
			$serial_number = 0001;    
		}

		$serial_number_generate = str_pad($serial_number, 4, "0", STR_PAD_LEFT);
		$result = $serial_number_generate;
		return $result;
	}

	public function get_data($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('t_transaction a');
        $sql->join('m_guest b', 'b.guest_id = a.guest_id', 'left');
        $sql->join('m_guest_group c', 'c.guest_group_id = a.guest_group_id', 'left');
        $sql->join('m_room_type d', 'd.room_type_id = a.room_type_id', 'left');
        $sql->join('m_room e', 'e.room_id = a.room_id', 'left');
		$sql->order_by('transaction_number', 'desc');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_shift($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_shift');
		$sql->order_by('shift_id', 'asc');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_consumption($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('t_consumption');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('t_transaction', $data);
	}

	public function update($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('t_transaction', $data);
	}

	public function delete($id) {
		$this->db->where('id', $id);
		return $this->db->delete('t_transaction');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('t_transaction');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
