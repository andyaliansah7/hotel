<?php
/**
 * Guests Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Guests extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Guests_model');
	}
	
	public function index() {
		$data['content_title'] = 'Tamu';

		if(check_roles('1')){
			$this->twiggy_display('adm/guests/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Guests_model->get_data()->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {
				
				// $button_booking = '<a data-toggle="tooltip" data-placement="left" title="Booking" href="' .site_url('adm/booking/edit/') .$get_row->guest_id. '" class="btn btn-warning btn-sm"><i class="fas fa-history"></i></i></a>';
				// $button_checkin  = '<a data-toggle="tooltip" data-placement="left" title="Check-in" href="' .site_url('adm/checkin/edit/') .$get_row->guest_id. '" class="btn btn-success btn-sm"><i class="fas fa-sign-in-alt"></i></a>';
				
				// $button_action = '<div class="btn-group" role="group" aria-label="Basic example">'.$button_booking.$button_checkin.'</div>';


				$data[] = array(
					'no'              => $no,
					'id'              => $get_row->guest_id,
					'identity_number' => $get_row->guest_identity_number,
					'name'            => $get_row->guest_name,
					'phone'           => $get_row->guest_phone,
					'address'         => $get_row->guest_address,
					// 'button_action'   => $button_action
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
			$where    = array('guest_id' => $id);
			$get_data = $this->Guests_model->get_data($where)->row_array();
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['get_data']      = $get_data;

		$this->twiggy_display('adm/guests/edit', $data);
	}

	public function save() {
		// post
		$id              = $this->input->post('id');
		$identity_number = $this->input->post('identity_number');
		$name            = $this->input->post('name');
		$phone           = $this->input->post('phone');
		$address         = $this->input->post('address');
		$action          = $this->input->post('action');

		$data_save = array(
			'guest_identity_number' => $identity_number,
			'guest_name'            => $name,
			'guest_phone'           => $phone,
			'guest_address'         => $address			
		);

		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Guests_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Guests_model->update($id, $data_save);
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
			$delete_type = $this->Guests_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('guest_identity_number' => $id);

		$check = $this->Guests_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
