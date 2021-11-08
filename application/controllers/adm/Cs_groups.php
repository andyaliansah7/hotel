<?php
/**
 * Cs_groups Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Cs_groups extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Cs_groups_model');
	}
	
	public function index() {
		$data['content_title'] = 'Grup';

		if(check_roles('1')){
			$this->twiggy_display('adm/cs_groups/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Cs_groups_model->get_data()->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {

				$data[] = array(
					'no'     => $no,
					'id'     => $get_row->cs_group_id,
					'parent' => $get_row->cs_group_parent_name,
					'child'  => $get_row->cs_group_name
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
			$where    = array('cs_group_id' => $id);
			$get_data = $this->Cs_groups_model->get_data($where)->row_array();
		}

		$parent_data = $this->Cs_groups_model->get_data_parent()->result();
		$data['id']             = $id;
		$data['content_title']  = $title;
		$data['get_data']       = $get_data;
		$data['parent_data'] = $parent_data;

		$this->twiggy_display('adm/Cs_groups/edit', $data);
	}

	public function save() {
		// post
		$id          = $this->input->post('id');
		$child_name  = $this->input->post('child_name');
		$parent_name = $this->input->post('parent_name');
		$action      = $this->input->post('action');

		$data_save = array(
			'cs_group_parent_id' => $parent_name,
			'cs_group_name'      => $child_name
		);

		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Cs_groups_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Cs_groups_model->update($id, $data_save);
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
			$delete_type = $this->Cs_groups_model->delete($row);
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

		$check = $this->Cs_groups_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
