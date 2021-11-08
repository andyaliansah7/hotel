<?php
/**
 * Rooms Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Rooms extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Checkin_model');
		$this->load->model('Rooms_model');
		$this->load->model('Room_types_model');
	}
	
	public function index() {
		$data['content_title'] = 'Kamar';

		if(check_roles('1')){
			$this->twiggy_display('adm/rooms/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Rooms_model->get_data()->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {
				$active = $get_row->room_active;
				$status = ($active == 1 ? 'Aktif' : 'Non-Aktif');
				$bgcolor = ($active == 1 ? 'bg-success' : '');
				$bgicon  = ($active == 1 ? 'fas fa-check' : 'fas fa-times');

				$data[] = array(
					'no'          => $no,
					'id'          => $get_row->room_id,
					'number'      => $get_row->room_number,
					'room_type'   => $get_row->room_type_name. ' - '. $get_row->room_type_facilities,
					'status'      => $status,
					'room_active' => $active,
					'bgcolor'     => $bgcolor,
					'bgicon'      => $bgicon
				);
			$no++;
			}
		}

		$response = [ 
			'data'         => $data,
			'recordsTotal' => count($data)
		];

		output_json($response);
	}

	public function edit($id='new') {
		$title    = "Tambah";
		$get_data = array();

		if($id != 'new') {
			$title    = "Edit";
			$where    = array('room_id' => $id);
			$get_data = $this->Rooms_model->get_data($where)->row_array();
		}

		$room_type_data = $this->Room_types_model->get_data()->result();
		$data['id']             = $id;
		$data['content_title']  = $title;
		$data['get_data']       = $get_data;
		$data['room_type_data'] = $room_type_data;

		$this->twiggy_display('adm/rooms/edit', $data);
	}

	public function save() {
		// post
		$id        = $this->input->post('id');
		$number    = $this->input->post('number');
		$room_type = $this->input->post('room_type');
		$action    = $this->input->post('action');

		if($id == 'new') {
			$data_save = array(
				'room_number'  => $number,
				'room_type_id' => $room_type,
				'room_status'  => 'Kosong'			
			);
			$convert = convert_button($action, $id);
			$save = $this->Rooms_model->save($data_save);
		} else {
			$data_save = array(
				'room_number'  => $number,
				'room_type_id' => $room_type		
			);
			$convert = convert_button($action, $id);
			$save = $this->Rooms_model->update($id, $data_save);
		}

		if($save) {
			$response = array(
				'status'  => 'success',
				'message' => 'Berhasil menyimpan data',
				'id'      => $convert
			);
		}

		else {
			$response = array(
				'status'  => 'error',
				'message' => 'Gagal menyimpan data',
				'id'      => $convert
			);
		}

		output_json($response);
	}

	public function room_active() {
		date_default_timezone_set('Asia/Jakarta');
		$id   = $this->input->post('id');
		$data = $this->input->post('data');
		$date_now = date('Y-m-d');

		$is_active = ($data == "0" ? "1" : "0");

		if($is_active == "0"){
			$where_header = '"'.$date_now.'" between date_in and date_out';
			$get_data     = $this->Checkin_model->get_data($where_header)->result();


			if(count($get_data) > 0){
				$msg = "Kamar sedang digunakan.";
				$status = "error";
			}else{
				$data = array(
					'room_active' => $is_active,
				);
				$update = $this->Rooms_model->update($id, $data);
				$msg = "Berhasil disimpan.";
				$status = "success";
			}
		}else{
			$data = array(
				'room_active' => $is_active,
			);
			$update = $this->Rooms_model->update($id, $data);
			$msg = "Berhasil disimpan.";
			$status = "success";
		}
		
		$response = array(
			'message' => $msg,
			'status'  => $status
		);

		output_json($response);
	}

	public function delete() {
		$id = $this->input->post('id');

		foreach($id as $row) {
			$delete_type = $this->Rooms_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('room_number' => $id);

		$check = $this->Rooms_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
