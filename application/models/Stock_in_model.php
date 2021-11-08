<?php
/**
 * Consumption Services Model
 *
 * Modif Core Model with Namespace
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Stock_in_model extends Model {

	public function autonumber()
	{	
		$sql = $this->db;

		$sql->select('RIGHT(t_stockin_header.number, 4) as serial_number', FALSE);
		$sql->order_by('number', 'DESC');    
		$sql->limit(1);    
		$query = $sql->get('t_stockin_header');  
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

	public function item_master($search='') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_item');
		$sql->order_by('item_name');

		if ($search != '')
		{
			$sql->like('item_name', $search);
		}

		$get = $sql->get();

		return $get;
	}

	public function stockin_header($where='', $order='')
	{
    	$sql = $this->db;

		$sql->select('*');
		$sql->from('t_stockin_header');

    	if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function stockin_detail($where='', $order='')
	{
		$sql = $this->db;

		$sql->select('*');
	    $sql->from('t_stockin_detail a');
	    $sql->join('t_stockin_header b', 'b.header_id = a.header_id', 'inner');
		$sql->join('m_item c', 'c.item_id = a.item_id', 'left');

		if ($where != '') {
			$sql->where($where);
		}

		if ($order != '') {
			$sql->order_by($order);
		}

		$get = $sql->get();

		return $get;
	}

	public function stockin_detail_advance($select='*', $where='', $order='')
	{
		$sql = $this->db;

		$sql->select($select);
	    $sql->from('t_stockin_detail a');
		$sql->join('t_stockin_header b', 'b.header_id = a.header_id', 'inner');

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
			return $this->db->insert_batch('t_stockin_header', $data);
		}

		return $this->db->insert('t_stockin_header', $data);
	}

	public function save_detail($data, $batch = false)
	{
		if($batch)
		{
			return $this->db->insert_batch('t_stockin_detail', $data);
		}

		return $this->db->insert('t_stockin_detail', $data);
	}

	public function update_header($id, $data) {
		$this->db->where('header_id', $id);
		return $this->db->update('t_stockin_header', $data);
	}

	public function delete_header($id)
	{
		$this->db->where('header_id', $id);
		return $this->db->delete('t_stockin_header');
	}

	public function delete_detail($id)
	{
		$this->db->where('header_id', $id);
		return $this->db->delete('t_stockin_detail');
	}

}

?>
