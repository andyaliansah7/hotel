<?php
/**
 * Dashboards Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Dashboards_model extends Model {

	public function get_top_client() {
		$sql = "
		SELECT *, COUNT(*) count
				FROM
				master_trainees a
				INNER JOIN master_companies b ON b.company_id = a.trainee_company_id
				GROUP BY trainee_company_id
				ORDER BY count desc
				LIMIT 5
		";

		return $this->db->query($sql);
	}

	public function get_room($where='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_room a');
        $sql->join('m_room_type b', 'b.room_type_id = a.room_type_id', 'inner');
		$sql->order_by('room_number');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_transaction($where='', $where_date='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('t_transaction a');
        $sql->join('m_guest b', 'b.guest_id = a.guest_id', 'inner');
        $sql->join('m_guest_group c', 'c.guest_group_id = a.guest_group_id', 'left');
        $sql->join('m_room_type d', 'd.room_type_id = a.room_type_id', 'left');
        $sql->join('m_room e', 'e.room_id = a.room_id', 'left');
		$sql->order_by('transaction_number', 'desc');

		if ($where != "") {
			$sql->where($where);
		}

		if ($where_date != "") {
			$sql->where('"'.$where_date.'" BETWEEN date_in AND date_out');
		}

		$sql->order_by('id', 'desc');

		$get = $sql->get();

		return $get;
	}

	public function get_transaction_detail($where='', $where_date='')
	{
    	$sql = $this->db;

		$sql->select('*');
		$sql->from('t_payment_detail a');
        $sql->join('t_payment_header b', 'b.header_id = a.header_id', 'inner');
        $sql->join('t_transaction c', 'c.id = a.transaction_id and a.transaction_type = "T"', 'right');
		// $sql->join('m_guest_group d', 'd.guest_group_id = c.guest_group_id', 'left');

    	if ($where != "") {
			$sql->where($where);
		}

		if ($where_date != "") {
			$sql->where('"'.$where_date.'" BETWEEN date_in AND date_out');
		}

		$sql->order_by('id', 'desc');

		$get = $sql->get();

		return $get;
	}
	
	public function get_payment($where='', $where_date='')
	{
    	$sql = $this->db;

		$sql->select('*');
		$sql->from('t_payment_detail a');
        $sql->join('t_payment_header b', 'b.header_id = a.header_id', 'inner');
        $sql->join('t_transaction c', 'c.id = a.transaction_id and a.transaction_type = "T"', 'inner');
		// $sql->join('m_guest_group d', 'd.guest_group_id = c.guest_group_id', 'left');

    	if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

}

?>
