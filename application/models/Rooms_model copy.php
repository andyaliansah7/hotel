<?php
/**
 * Rooms Model
 * Modif Core Model with Namespace
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use core\Model;

class Rooms_model extends Model {

	public function get_data($where='') {
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

	public function get_available_room($room_type_id, $date_in, $date_out, $id='new', $b_id='', $type='B') {
		$sql = $this->db;

		$sql->select('*');
        $sql->from('m_room a');
        $sql->join('m_room_type b', 'b.room_type_id = a.room_type_id', 'inner');
		if($id == 'new'){
			$sql->where('room_active = "1" and room_id NOT IN (SELECT room_id FROM t_transaction where ("'.$date_in.'" < date_out AND "'.$date_out.'" >= date_in) AND status != 2 AND status != 1) AND a.room_type_id = "'.$room_type_id.'"');
		}else{
			if($b_id == ''){
				$sql->where('room_active = "1" and room_id NOT IN (SELECT room_id FROM t_transaction where ("'.$date_in.'" < date_out AND "'.$date_out.'" >= date_in) AND status != 2 AND status != 1 AND id != "'.$id.'") AND a.room_type_id = "'.$room_type_id.'"');
			}else{
				$sql->where('room_active = "1" and room_id NOT IN (SELECT room_id FROM t_transaction where ("'.$date_in.'" < date_out AND "'.$date_out.'" >= date_in) AND status != 2 AND status != 1 AND id != "'.$id.'" AND id != "'.$b_id.'") AND a.room_type_id = "'.$room_type_id.'"');
			}
		}
		$sql->order_by('room_number');

		$get = $sql->get();

		return $get;
	}

	public function save($data) {
		return $this->db->insert('m_room', $data);
	}

	public function update($id, $data) {
		$this->db->where('room_id', $id);
		return $this->db->update('m_room', $data);
	}

	public function delete($id) {
		$this->db->where('room_id', $id);
		return $this->db->delete('m_room');
	}

	public function check_id($where) {
		$this->db->select("*");
		$this->db->from('m_room');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

}

?>
