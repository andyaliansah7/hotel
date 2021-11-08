<?php
/**
 * Special Rates Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Special_rates extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Special_rates_model');
		$this->load->model('Room_types_model');
	}
	
	public function index() {
		$data['content_title'] = 'Harga Khusus';

		if(check_roles('1')){
			$this->twiggy_display('adm/special_rates/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Special_rates_model->get_data()->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {

				$data[] = array(
					'no'        => $no,
					'id'        => $get_row->special_rate_id,
					'date'      => indonesian_date($get_row->special_rate_date),
					'room_type' => $get_row->room_type_name,
					'price'     => number_format($get_row->special_rate_price),
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
		$price    = "";

		if($id != 'new') {
			$title         = "Edit";
			$where         = array('special_rate_id' => $id);
			$get_data      = $this->Special_rates_model->get_data($where)->row_array();
			$price         = number_format(check_array_key($get_data, 'special_rate_price'));
		}

		$data['id']             = $id;
		$data['content_title']  = $title;
		$data['get_data']       = $get_data;
		$data['price']          = $price;
		$data['room_type_data'] = $this->Room_types_model->get_data()->result();

		$this->twiggy_display('adm/special_rates/edit', $data);
	}

	public function save() {
		// post
		$id        = $this->input->post('id');
		$date      = $this->input->post('date');
		$room_type = $this->input->post('room_type');
		$price     = $this->input->post('price');

		$action     = $this->input->post('action');

		$data_save = array(
			'special_rate_date'  => $date,
			'room_type_id'       => $room_type,
			'special_rate_price' => trims($price)
		);

		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Special_rates_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Special_rates_model->update($id, $data_save);
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
			$delete_type = $this->Special_rates_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('special_rate_code' => $id);

		$check = $this->Special_rates_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
