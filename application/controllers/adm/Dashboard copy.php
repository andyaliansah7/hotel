<?php
/**
 * Users Controllers
 *
 * Modif Core Model with Namespace
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Dashboard extends BaseController
{
	/**
	 * Construcktor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->check_auth();
		$this->load->model('Dashboards_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */
	public function index()
	{	
		$data['content_title'] = 'Dashboard';

		$suite           = $this->get_suite();
		$executive       = $this->get_executive();
		$superior_double = $this->get_superior_double();
		$deluxe_double_5 = $this->get_deluxe_double_5();
		$deluxe_double_3 = $this->get_deluxe_double_3();
		$superior_twin   = $this->get_superior_twin();
		$deluxe_twin_1   = $this->get_deluxe_twin_1();
		$deluxe_twin_2   = $this->get_deluxe_twin_2();
		$deluxe_twin_3   = $this->get_deluxe_twin_3();

		$data = [
			'suite'           => $suite,
			'executive'       => $executive,
			'superior_double' => $superior_double,
			'deluxe_double_5' => $deluxe_double_5,
			'deluxe_double_3' => $deluxe_double_3,
			'superior_twin'   => $superior_twin,
			'deluxe_twin_1'   => $deluxe_twin_1,
			'deluxe_twin_2'   => $deluxe_twin_2,
			'deluxe_twin_3'   => $deluxe_twin_3,
		];

		$this->twiggy_display('adm/dashboard/index', $data);
	}

	public function get_suite()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 10))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_executive()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 5))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_superior_double()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 8))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_deluxe_double_5()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 7, '(select LEFT(room_number , 1)) =' => '5'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_deluxe_double_3()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 7, '(select LEFT(room_number , 1)) =' => '3'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_superior_twin()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 9))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_deluxe_twin_1()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 6, '(select LEFT(room_number , 1)) =' => '1'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_deluxe_twin_2()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 6, '(select LEFT(room_number , 1)) =' => '2'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}

	public function get_deluxe_twin_3()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 6, '(select LEFT(room_number , 1)) =' => '3'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";
					if($get_data){
						if($get_data->transaction_type == "B"){
							$status = '2';
							$bg_color = "bg-primary";
						}else{
							$status = '3';
							$bg_color = "bg-success";
						}	
					}
				}

				$data[] = array(
					'id'     => $get_row->room_id,
					'number' => $get_row->room_number,
					'type'   => $get_row->room_type_id,
					'status' => $status,
					'bg_color' => $bg_color,
				);
			}
		}

		return $data;
	}


	/**
	 * Logout
	 */
	public function logout()
	{
		$this->session->sess_destroy();

		redirect('loginweb');
	}
}
?>
