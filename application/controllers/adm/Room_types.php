<?php
/**
 * Room Types Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Room_types extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Room_types_model');
	}
	
	public function index() {
		$data['content_title'] = 'Tipe Kamar';

		if(check_roles('1')){
			$this->twiggy_display('adm/room_types/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Room_types_model->get_data()->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {

				$data[] = array(
					'no'            => $no,
					'id'            => $get_row->room_type_id,
					'code'          => $get_row->room_type_code,
					'name'          => $get_row->room_type_name,
					'year'          => $get_row->room_type_year,
					'facilities'    => $get_row->room_type_facilities,
					'pax'           => $get_row->room_type_pax,
					'group'         => $get_row->room_type_group,
					'price_weekday' => number_format($get_row->room_type_price_weekday),
					'price_weekend' => number_format($get_row->room_type_price_weekend)
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
		$price_weekday    = "";
		$price_weekend    = "";

		if($id != 'new') {
			$title         = "Edit";
			$where         = array('room_type_id' => $id);
			$get_data      = $this->Room_types_model->get_data($where)->row_array();
			$price_weekday = number_format(check_array_key($get_data, 'room_type_price_weekday'));
			$price_weekend = number_format(check_array_key($get_data, 'room_type_price_weekend'));
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['get_data']      = $get_data;
		$data['price_weekday'] = $price_weekday;
		$data['price_weekend'] = $price_weekend;

		$this->twiggy_display('adm/room_types/edit', $data);
	}

	public function save() {
		// post
		$id            = $this->input->post('id');
		$code          = $this->input->post('code');
		$name          = $this->input->post('name');
		// $year       = $this->input->post('year');
		$year          = '0000';
		$facilities    = $this->input->post('facilities');
		$pax           = $this->input->post('pax');
		$group         = $this->input->post('group');
		$price_weekday = $this->input->post('price_weekday');
		$price_weekend = $this->input->post('price_weekend');

		$action     = $this->input->post('action');

		$data_save = array(
			'room_type_code'          => $code,
			'room_type_name'          => $name,
			'room_type_year'          => $year,
			'room_type_facilities'    => $facilities,
			'room_type_pax'    		  => $pax,
			'room_type_group'         => $group,
			'room_type_price_weekday' => trims($price_weekday),
			'room_type_price_weekend' => trims($price_weekend),
		);

		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Room_types_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Room_types_model->update($id, $data_save);
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

	public function delete() {
		$id = $this->input->post('id');

		foreach($id as $row) {
			$delete_type = $this->Room_types_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('room_type_code' => $id);

		$check = $this->Room_types_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
