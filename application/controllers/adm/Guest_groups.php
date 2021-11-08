<?php
/**
 * Guest Groups Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Guest_groups extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Guest_groups_model');
	}
	
	public function index() {
		$data['content_title'] = 'Grup Tamu';

		if(check_roles('1')){
			$this->twiggy_display('adm/guest_groups/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Guest_groups_model->get_data()->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {

				$data[] = array(
					'no'       => $no,
					'id'       => $get_row->guest_group_id,
					'code'     => $get_row->guest_group_code,
					'name'     => $get_row->guest_group_name,
					'discount' => $get_row->guest_group_discount. ' %',
					'fee' => $get_row->guest_group_fee. ' %'
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
			$where    = array('guest_group_id' => $id);
			$get_data = $this->Guest_groups_model->get_data($where)->row_array();
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['get_data']      = $get_data;

		$this->twiggy_display('adm/guest_groups/edit', $data);
	}

	public function save() {
		// post
		$id       = $this->input->post('id');
		$code     = $this->input->post('code');
		$name     = $this->input->post('name');
		$discount = $this->input->post('discount');
		$fee      = $this->input->post('fee');
		$check    = $this->input->post('status');
		$action   = $this->input->post('action');

		$status = ($check == "on" ? "1" : "0");

		$data_save = array(
			'guest_group_code'     => $code,
			'guest_group_name'     => $name,
			'guest_group_discount' => $discount,
			'guest_group_fee' => $fee,
			'get_price' => $status
		);

		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Guest_groups_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Guest_groups_model->update($id, $data_save);
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
			$delete_type = $this->Guest_groups_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('guest_group_code' => $id);

		$check = $this->Guest_groups_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
