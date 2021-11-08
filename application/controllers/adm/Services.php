<?php
/**
 * Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Services extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Services_model');
		$this->load->model('Cs_groups_model');
	}
	
	public function index() {
		$data['content_title'] = 'Layanan';

		if(check_roles('1')){
			$this->twiggy_display('adm/services/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Services_model->get_data(array('cs_type' => 'S'))->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {
				
				$group = '-';
				if($get_row->cs_group_parent_name != ''){
					$group = $get_row->cs_group_parent_name ."(". $get_row->cs_group_name .")";
				}

				$data[] = array(
					'no'    => $no,
					'id'    => $get_row->cs_id,
					'code'  => $get_row->cs_code,
					'name'  => $get_row->cs_name,
					'group' => $group,
					'price' => number_format($get_row->cs_price)
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
			$title    = "Edit";
			$where    = array('cs_id' => $id);
			$get_data = $this->Services_model->get_data($where)->row_array();
			$price    = number_format(check_array_key($get_data, 'cs_price'));
		}

		$group_data            = $this->Cs_groups_model->get_data()->result();
		$data['group_data']    = $group_data;
		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['get_data']      = $get_data;
		$data['price']         = $price;

		$this->twiggy_display('adm/services/edit', $data);
	}

	public function save() {
		// post
		$id     = $this->input->post('id');
		$code   = $this->input->post('code');
		$name   = $this->input->post('name');
		$group  = $this->input->post('group');
		$price  = $this->input->post('price');
		$action = $this->input->post('action');

		$data_save = array(
			'cs_code'     => $code,
			'cs_name'     => $name,
			'cs_type'     => 'S',
			'cs_group_id' => $group,
			'cs_price'    => trims($price)
		);

		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Services_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Services_model->update($id, $data_save);
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
			$delete_type = $this->Services_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('cs_code' => $id);

		$check = $this->Services_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
