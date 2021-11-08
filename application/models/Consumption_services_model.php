<?php
/**
 * Consumption Services Model
 *
 * Modif Core Model with Namespace
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Consumption_services_model extends Model {

	public function cs_autonumber()
	{	
		$sql = $this->db;

		$sql->select('RIGHT(t_cs_header.cs_header_number, 4) as serial_number', FALSE);
		$sql->order_by('cs_header_number', 'DESC');    
		$sql->limit(1);    
		$query = $sql->get('t_cs_header');  
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

	public function cs_master($search='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_cs');
		$sql->order_by('cs_code');
		$sql->order_by('cs_name');

		if ($search != '')
		{
			$sql->like('cs_code', $search);
			$sql->or_like('cs_name', $search);
		}

		$get = $sql->get();

		return $get;
	}

	public function cs_header($where='', $order='')
	{
    	$sql = $this->db;

		$sql->select('*');
		$sql->from('t_cs_header a');
		$sql->join('t_transaction b', 'b.id = a.transaction_id', 'left');
		$sql->join('m_room_type c', 'c.room_type_id = b.room_type_id', 'left');
        $sql->join('m_room d', 'd.room_id = b.room_id', 'left');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
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

	public function cs_detail_advance($select='*', $where='', $order='')
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

	public function cs_detail_advance2($select='*', $where='', $order='')
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

		$sql->group_by('cs_detail_item_id');

		$get = $sql->get();

		return $get;
	}

	public function save_header($data, $batch = false)
	{
		if($batch)
		{
			return $this->db->insert_batch('t_cs_header', $data);
		}

		return $this->db->insert('t_cs_header', $data);
	}

	public function save_detail($data, $batch = false)
	{
		if($batch)
		{
			return $this->db->insert_batch('t_cs_detail', $data);
		}

		return $this->db->insert('t_cs_detail', $data);
	}

	public function update_header($id, $data) {
		$this->db->where('cs_header_id', $id);
		return $this->db->update('t_cs_header', $data);
	}

	public function delete_header($id)
	{
		$this->db->where('cs_header_id', $id);
		return $this->db->delete('t_cs_header');
	}

	public function delete_detail($id)
	{
		$this->db->where('cs_detail_header_id', $id);
		return $this->db->delete('t_cs_detail');
	}

}

?>
