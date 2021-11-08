<?php
/**
 * Users Controllers
 *
 * Modif Core Model with Namespace
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Users extends BaseController
{
	/**
	 * Construcktor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();

		// load model
		$this->load->model('Users_model');
		$this->auth->check_auth();
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */
	public function index()
	{
		$data['content_title'] = 'Pengguna';
		$this->twiggy_display('adm/users/index', $data);
	}

	/**
	 * Get data
	 *
	 * @return JSON
	 */
	public function get_data()
	{
		$data = [];
		$logged_id   = $this->session->userdata('user_id');
		$logged_role = $this->session->userdata('role');

		if($logged_role == 1){
			$get_data = $this->Users_model->get_data_advance()->result();
		}else{
			$get_data = $this->Users_model->get_data_advance($logged_id, '', '', $logged_role)->result();
		}

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{	
				if($get_row->role == 1){
					$role = ' <span class="badge badge-success float-right">'.$get_row->role_name.'</span>';
				}
				if($get_row->role == 2){
					$role = ' <span class="badge badge-primary float-right">'.$get_row->role_name.'</span>';
				}
				if($get_row->role == 3){
					$role = ' <span class="badge badge-warning float-right">'.$get_row->role_name.'</span>';
				}
				$data[] = array(
					'no'            => $no,
					'id'            => $get_row->id,
					'fullname_role' => $get_row->fullname.$role,
					'username'      => $get_row->username
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

	/**
	 * Halaman Edit/Add
	 *
	 * @return HTML
	 */
	public function edit($id = 'new')
	{
		// data
		$get_data = array();

		// check bila $id tidak sama dengan new
		// maka get data untuk dimunculkan di modal html
		if($id != 'new')
		{
			$get_data = $this->Users_model->get_data_advance($id)->row_array();
		}

		$data['content_title'] = 'Edit';
		$data['id'] = $id;
		$data['get_data'] = $get_data;
		if(check_roles('1')){
			$data['role_data'] = $this->Users_model->get_data_role()->result();
		}else{
			$data['role_data'] = $this->Users_model->get_data_role(logged_user('role'))->result();
		}
		$this->twiggy_display('adm/users/edit', $data);
	}

	/**
	 * Save
	 */
	public function save()
	{
		// post
		$id       = $this->input->post('id');
		$username = $this->input->post('username');
		$fullname = $this->input->post('fullname');
		$password = $this->input->post('password');
		$role     = $this->input->post('role');
		$action   = $this->input->post('action');

		$data_save = array(
			'username' => $username,
			'password' => md5($password),
			'fullname' => $fullname,
			'role'     => $role,
		);

		// save data ketika $id = new
		// update data ketika != new
		if($id == 'new')
		{
			// swicth untuk action
			// konversi dari helper
			$convert = convert_button($action, $id);
			$save = $this->Users_model->save($data_save);
		}
		else
		{
			// swicth untuk action
			// konversi dari helper
			$convert = convert_button($action, $id);
			$save = $this->Users_model->update($id, $data_save);
		}

		// response json untuk notifikasi
		if($save)
		{
			$response = array(
				'status'  => 'success',
				'message' => 'Berhasil menyimpan data',
				'id'      => $convert
			);
		}
		else
		{
			$response = array(
				'status'  => 'error',
				'message' => 'Gagal menyimpan data',
				'id'      => $convert
			);
		}

		output_json($response);
	}

	/**
	 * Delete
	 */
	public function delete()
	{
		$id = $this->input->post('id');

		foreach($id as $row)
		{
			$delete_type = $this->Users_model->delete($row);
		}

		$response = array(
			'message' => 'Data berhasil di hapus',
			'status'  => 'success'
		);

		output_json($response);
	}

	// Check Data Id
	public function check_id()
	{
		$id = $this->input->post('id');
		$where = array('username' => $id);
		$check = $this->Users_model->check_id($where);
		// Jika Status True
		if ($check) {
			$response = array('status' => true);
		}else{
			$response = array('status' => false);
		}
		output_json($response);
	}


}
?>
