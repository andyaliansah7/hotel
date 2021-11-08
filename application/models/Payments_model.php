<?php
/**
 * Payments Model
 *
 * Modif Core Model with Namespace
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Payments_model extends Model {

	public function payment_autonumber()
	{	
		$sql = $this->db;

		$sql->select('RIGHT(t_payment_header.payment_number, 4) as serial_number', FALSE);
		$sql->where('MONTH(payment_date) = MONTH(CURRENT_DATE())');
		$sql->order_by('payment_number', 'DESC');
		$sql->limit(1);    
		$query = $sql->get('t_payment_header');  
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

	public function get_all_transaction($guest='', $search='', $id='new') {
		$sql = $this->db;

		$sql->select("
			t.id as id,
			t.on_behalf as on_behalf,
			CONCAT('T', t.id) as code,
			t.transaction_number as number,
			'T' as type,
			t.total_price as price,
			t.discount as discount,
			t.deposit as deposit,
			t.total as total,
			t.total_paid as paid
		");
        $sql->from("t_transaction t");
		$sql->where("transaction_type", "C");

		if ($id == 'new')
		{
			$sql->where("(total - deposit) <> total_paid");
		}

		if ($guest != '')
		{
			$sql->where('t.on_behalf', $guest);
		}

		if ($search != '')
		{
			$sql->like('t.transaction_number', $search);
			// $sql->or_like('cs_name', $search);
		}

		$query1 = $sql->get_compiled_select();
		
		$sql->select("
			c.cs_header_id as id,
			c.cs_header_on_behalf_id as on_behalf,
			CONCAT('C', c.cs_header_id) as code,
			c.cs_header_number as number,
			'C' as type,
			c.cs_header_total as price,
			'0' as discount,
			'0' as deposit,
			c.cs_header_total as total,
			c.cs_header_paid as paid,
		");
        $sql->from('t_cs_header c');
		if ($id == 'new')
		{
			$sql->where("cs_header_total <> cs_header_paid");
		}
		
		if ($guest != '')
		{
			$sql->where('c.cs_header_on_behalf_id', $guest);
		}

		if ($search != '')
		{
			$sql->like('c.cs_header_number', $search);
			// $sql->or_like('cs_name', $search);
		}

		$query2 = $sql->get_compiled_select();

		$get = $sql->query($query1 . ' UNION ' . $query2);

		return $get;
	}

	public function checkin_data($select='*', $where='') {
		$sql = $this->db;

		$sql->select($select);
        $sql->from('t_transaction a');
        $sql->join('m_guest b', 'b.guest_id = a.guest_id', 'inner');
        $sql->join('m_guest_group c', 'c.guest_group_id = a.guest_group_id', 'left');
        $sql->join('m_room_type d', 'd.room_type_id = a.room_type_id', 'inner');
        $sql->join('m_room e', 'e.room_id = a.room_id', 'inner');
		$sql->order_by('transaction_number', 'desc');

		if ($where != "") {
			$sql->where($where);
		}

		$get = $sql->get();

		return $get;
	}

	public function cs_detail($where='', $order='')
	{
		$sql = $this->db;

		$sql->select('*');
	    $sql->from('t_cs_detail a');
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

	public function payment_header($where='', $order='')
	{
    	$sql = $this->db;

		$sql->select('*');
		$sql->from('t_payment_header a');
        $sql->join('m_guest b', 'b.guest_id = a.guest_id', 'left');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function payment_detail($where='', $order='')
	{
		$sql = $this->db;

		$sql->select('*');
	    $sql->from('t_payment_detail');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function payment_detail_advance($select='*', $where='', $order='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_payment_detail');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function guest_deposit($select='*', $where='', $order='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_deposit');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function save_header($data, $batch = false)
	{
		if($batch)
		{
			return $this->db->insert_batch('t_payment_header', $data);
		}

		return $this->db->insert('t_payment_header', $data);
	}

	public function save_detail($data, $batch = false)
	{
		if($batch)
		{
			return $this->db->insert_batch('t_payment_detail', $data);
		}

		return $this->db->insert('t_payment_detail', $data);
	}

	public function update_header($id, $data) {
		$this->db->where('header_id', $id);
		return $this->db->update('t_payment_header', $data);
	}

	public function update_status_paid($table, $data, $where) {
		$this->db->where($where);
		return $this->db->update($table, $data);
	}

	public function delete_header($id)
	{
		$this->db->where("header_id", $id);
		return $this->db->delete("t_payment_header");
	}

	public function delete_detail($id)
	{
		$this->db->where("header_id", $id);
		return $this->db->delete("t_payment_detail");
	}

	public function update_deposit($id) {
		$this->db->set('payment_id', 0);
		$this->db->set('payment_date', `deposit_date`);
		$this->db->where('payment_id', $id);
		return $this->db->update('t_deposit');
	}

	public function delete_deposit($id) {
		$this->db->where('payment_id', $id);
		$this->db->where('deposit_amount', 0);
		return $this->db->delete('t_deposit');
	}

}

?>
