<?php
/**
 * Reports Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Reports_model extends Model {

	public function get_dayrecap($select='*', $where='', $group='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('r_dayrecap');

		if ($where != "") {
			$sql->where($where);
		}

		if ($group != "") {
			$sql->group_by($group);
		}
		
		$sql->order_by('date asc');

		$get = $sql->get();

		return $get;
	}

	public function get_dayrecap2($select='*', $where='', $group='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('r_dayrecap2');

		if ($where != "") {
			$sql->where($where);
		}

		if ($group != "") {
			$sql->group_by($group);
		}
		
		$sql->order_by('date asc');

		$get = $sql->get();

		return $get;
	}

	public function get_deposit2($select='*', $where='', $group='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('t_deposit');

		if ($where != "") {
			$sql->where($where);
		}

		if ($group != "") {
			$sql->group_by($group);
		}
		
		// $sql->order_by('date asc');

		$get = $sql->get();

		return $get;
	}

	public function save_dayrecap($data) {
		return $this->db->insert('r_dayrecap', $data);
	}

	public function update_dayrecap($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('r_dayrecap', $data);
	}

	public function save_dayrecap2($data) {
		return $this->db->insert_batch('r_dayrecap2', $data);
	}

	public function delete_dayrecap2($where) {
		$this->db->where($where);
		return $this->db->delete('r_dayrecap2');
	}

	public function get_checkin($select='*', $where='') {
		$sql = $this->db;

		$sql->select($select);
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

	public function get_guest_group($select='*', $where='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('m_guest_group');

		if ($where != "") {
			$sql->where($where);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_room_type($select='*', $where='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('m_room_type a');
		
		if ($where != "") {
			$sql->where($where);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_room($select='*', $where='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('m_room a');
        $sql->join('m_room_type b', 'b.room_type_id = a.room_type_id', 'inner');
		$sql->order_by('room_number');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_payment_method($select='*', $where='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('m_payment_method');
		$sql->order_by('payment_method_name');

		if ($where != "") {
			$sql->where($where);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_consumption_service_m($select='*', $where='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('m_cs');
		$sql->order_by('cs_name');

		if ($where != "") {
			$sql->where($where);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_consumption_service($select='*', $where='', $order='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_cs_detail a');
	    $sql->join('t_cs_header b', 'b.cs_header_id = a.cs_detail_header_id', 'inner');
	    $sql->join('m_cs c', 'c.cs_id = a.cs_detail_item_id', 'inner');
		// $sql->join('t_transaction d', 'd.id = b.transaction_id', 'inner');
		// $sql->join('t_payment e', 'e.transaction_id = d.id', 'inner');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment($select='*', $where='', $group='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('t_payment_header a');
		$sql->join('m_guest b', 'b.guest_id = a.guest_id', 'left');

		if ($where != "") {
			$sql->where($where);
		}

		if ($group != "") {
			$sql->group_by($group);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment_lite($select='*', $where='', $group='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('t_payment_header');

		if ($where != "") {
			$sql->where($where);
		}

		if ($group != "") {
			$sql->group_by($group);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment_monthrecap($select='*', $where='', $group='', $order='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('t_payment_header a');
		$sql->join('m_guest b', 'b.guest_id = a.guest_id', 'left');

		if ($where != "") {
			$sql->where($where);
		}

		if ($group != "") {
			$sql->group_by($group);
		}

		if ($order != "") {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment_detail_right($select='*', $where='', $order='')
	{
    	$sql = $this->db;

		$sql->select($select);
		$sql->from('t_payment_detail a');
        $sql->join('t_payment_header b', 'b.header_id = a.header_id', 'inner');
        $sql->join('t_transaction c', 'c.id = a.transaction_id and a.transaction_type = "T"', 'right');
		$sql->join('m_room_type d', 'd.room_type_id = c.room_type_id', 'left');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment_detail_right2($select='*', $where='', $order='')
	{
    	$sql = $this->db;

		$sql->select($select);
		$sql->from('t_payment_detail a');
        $sql->join('t_payment_header b', 'b.header_id = a.header_id', 'inner');
        $sql->join('t_transaction c', 'c.id = a.transaction_id and a.transaction_type = "T"', 'right');
		$sql->join('m_room_type d', 'd.room_type_id = c.room_type_id', 'left');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment_detail($select='*', $where='', $order='')
	{
    	$sql = $this->db;

		$sql->select($select);
		$sql->from('t_payment_detail a');
        $sql->join('t_payment_header b', 'b.header_id = a.header_id', 'inner');
        $sql->join('t_transaction c', 'c.id = a.transaction_id and a.transaction_type = "T"', 'inner');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_payment_detail2($select='*', $where='', $order='')
	{
    	$sql = $this->db;

		$sql->select($select);
		$sql->from('t_payment_detail a');
        $sql->join('t_payment_header b', 'b.header_id = a.header_id', 'inner');
        $sql->join('t_transaction c', 'c.id = a.transaction_id and a.transaction_type = "T"', 'left');
		$sql->join('m_guest_group d', 'd.guest_group_id = c.guest_group_id', 'left');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_deposit($select='*', $where='') {
		$sql = $this->db;

		$sql->select($select);
		$sql->from('t_deposit a');
		$sql->join('m_guest b', 'b.guest_id = a.guest_id', 'inner');
		$sql->join('m_payment_method c', 'c.payment_method_id = a.payment_method_id', 'left');
		$sql->join('t_payment_header d', 'd.header_id = a.payment_id', 'left');
		$sql->order_by('deposit_date');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_data_deposit_main($select='*', $where='') {
		$sql = $this->db;

		$sql->select($select);
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


	public function get_data_deposit2($select='*', $where='') {
		$sql = $this->db;

		$sql->select($select);
		$sql->from('t_deposit a');
		$sql->join('m_guest b', 'b.guest_id = a.guest_id', 'inner');
		// $sql->join('m_payment_method c', 'c.payment_method_id = a.payment_method_id', 'left');
		// $sql->join('t_payment_header d', 'd.header_id = a.payment_id', 'left');
		

		if ($where != "") {
			$sql->where($where);

			// $sql->where('total_deposit_2 !=', 0);
			// $sql->where('deposit_amount !=', 0);

		}

		// $sql->group_by('a.guest_id');
		// $sql->group_by('a.deposit_date');
		// $sql->group_by('a.payment_id');



		$get = $sql->get();

		return $get;
	}

	public function get_deposit_advance($date_1='', $date_2='', $shift='') {
		$sql = $this->db;

		$sql->select("
			deposit_id as id,
			guest_id as guest_id,
			deposit_date as date,
			deposit_kartu as total_kartu,
			deposit_tunai as total_tunai,
			deposit_trans as total_trans,
			deposit_description as description,
			shift_id as shift,
			'D' as type
		");
        $sql->from("t_deposit");


		if ($date_1 != '' && $date_2 != '')
		{
			$sql->where('deposit_date >=', $date_1);
			$sql->where('deposit_date <=', $date_2);
		}

		if ($shift != '')
		{
			$sql->where('shift_id', $shift);
		}

		$sql->group_start();
                $sql->where('deposit_date != payment_date');
                // $sql->where('payment_id == 0');
                // $sql->or_group_start();
                //         $sql->where('b', 'b');
                //         $sql->where('c', 'c');
                // -$sql>group_end();
        $sql->group_end();

		$query1 = $sql->get_compiled_select();
		
		$sql->select("
			header_id as id,
			guest_id as guest_id,
			payment_date as date,
			total_deposit_kartu as total_kartu,
			total_deposit_tunai as total_tunai,
			total_deposit_trans as total_trans,
			'' as description,
			shift_id as shift,
			'P' as type
		");
        $sql->from('t_payment_header');

		if ($date_1 != '' && $date_2 != '')
		{
			$sql->where('payment_date >=', $date_1);
			$sql->where('payment_date <=', $date_2);
		}

		if ($shift != '')
		{
			$sql->where('shift_id', $shift);
		}

		$sql->where('total_deposit_2 !=', 0);
		// $sql->or_where('total_deposit_tunai !=', 0);
		// $sql->or_where('total_deposit_trans !=', 0);

		$query2 = $sql->get_compiled_select();

		$get = $sql->query($query1 . ' UNION ' . $query2);

		return $get;
	}

	public function payment_header($where='', $paymethod='', $shift='', $order='')
	{
    	$sql = $this->db;

		$sql->select('*');
		$sql->from('t_payment_header a');
        $sql->join('m_guest b', 'b.guest_id = a.guest_id', 'left');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($shift != '') {
			$sql->where('shift_id', $shift);
		}

		if ($paymethod != '') {
			$sql->group_start();
				$sql->where('payment_method_1', $paymethod);
                $sql->or_group_start();
				$sql->where('payment_method_2', $paymethod);
				$sql->where('payment_method_3', $paymethod);
                $sql->group_end();
        	$sql->group_end();
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function get_deposit($where='', $paymethod='', $order='') {
		$sql = $this->db;

		$sql->select('*');
		$sql->from('t_deposit a');
		$sql->join('m_guest b', 'b.guest_id = a.guest_id', 'inner');
		// $sql->join('m_payment_method c', 'c.payment_method_id = a.payment_method_id', 'left');

		if ($where != "") {
			$sql->where($where);
		}

		// if ($paymethod != '') {
		// 	$sql->where('a.payment_method_id', $paymethod);
		// }

		if ($paymethod != '') {
			$sql->group_start();
				$sql->where('payment_method_1', $paymethod);
                $sql->or_group_start();
				$sql->where('payment_method_2', $paymethod);
				$sql->where('payment_method_3', $paymethod);
                $sql->group_end();
        	$sql->group_end();
		}

		$sql->order_by('deposit_date', 'asc');

		$get = $sql->get();

		return $get;
	}

	public function cs_detail($select='*', $where='', $order='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_cs_detail a');
	    $sql->join('t_cs_header b', 'b.cs_header_id = a.cs_detail_header_id', 'inner');
	    $sql->join('m_cs c', 'c.cs_id = a.cs_detail_item_id', 'inner');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function cs_detail_2($select='*', $where='', $order='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_cs_detail a');
	    $sql->join('t_cs_header b', 'b.cs_header_id = a.cs_detail_header_id', 'inner');
	    $sql->join('m_cs c', 'c.cs_id = a.cs_detail_item_id', 'inner');
	    $sql->join('t_transaction d', 'd.id = b.transaction_id', 'left');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function cs_detail_3($select='*', $where='', $order='', $group='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_cs_detail a');
	    $sql->join('t_cs_header b', 'b.cs_header_id = a.cs_detail_header_id', 'left');
	    $sql->join('m_cs c', 'c.cs_id = a.cs_detail_item_id', 'left');
	    $sql->join('t_transaction d', 'd.id = b.transaction_id', 'left');

		if ($where != '') {
			$sql->where($where);
		}

		if ($group != '') {
			$sql->group_by($group);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

}

?>
