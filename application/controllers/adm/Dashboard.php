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
		date_default_timezone_set('Asia/Jakarta');

		$datee = $this->input->post('filter_date');

		// date_default_timezone_set('Asia/Jakarta');
		
		// die();
		$date_now = date_now();
		if(isset($datee) && $datee != ''){
			$date_now = $datee;
		}
		
		// echo '<div style="color:red; margin-left:500px;">'.$date_now.'</div>';
		// $date_now = '2021-03-01';
		// die();

		$suite           = $this->get_suite($date_now);
		$executive       = $this->get_executive($date_now);
		$superior_double = $this->get_superior_double($date_now);
		$deluxe_double_5 = $this->get_deluxe_double_5($date_now);
		$deluxe_double_3 = $this->get_deluxe_double_3($date_now);
		$superior_twin   = $this->get_superior_twin($date_now);
		$deluxe_twin_1   = $this->get_deluxe_twin_1($date_now);
		$deluxe_twin_2   = $this->get_deluxe_twin_2($date_now);
		$deluxe_twin_3   = $this->get_deluxe_twin_3($date_now);

		$data = [
			'datee'           => $date_now,
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

	public function get_suite($date_now)
	{	
		

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 10))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_executive($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 5))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_superior_double($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 8))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_deluxe_double_5($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 7, '(select LEFT(room_number , 1)) =' => '5'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_deluxe_double_3($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 7, '(select LEFT(room_number , 1)) =' => '3'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_superior_twin($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 9))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_deluxe_twin_1($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 6, '(select LEFT(room_number , 1)) =' => '1'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_deluxe_twin_2($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 6, '(select LEFT(room_number , 1)) =' => '2'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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

	public function get_deluxe_twin_3($date_now)
	{	

		$data = [];
		$get_data = $this->Dashboards_model->get_room(array('a.room_type_id =' => 6, '(select LEFT(room_number , 1)) =' => '3'))->result();
		

		if($get_data) {
			foreach($get_data as $get_row) {

				$status   = $get_row->room_active;
				$bg_color = "bg-warning";

				if($status == 1){
					$get_data_check = $this->Dashboards_model->get_transaction(array('a.room_id =' => $get_row->room_id), $date_now)->row();
					$bg_color = "";

					if($get_data_check){
						$get_data_payment = $this->Dashboards_model->get_payment(array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => "T"))->row();
						if($get_data_payment){
							if($get_data_payment->payment_method_1 == 2 || $get_data_payment->payment_method_2 == 2 || $get_data_payment->payment_method_3 == 2){
								$bg_color = "bg-complimentary";
							}
						}elseif($get_data_check->guest_group_id == 8){
							$bg_color = "bg-agoda-0";
						}elseif($get_data_check->guest_group_id == 13){
							$bg_color = "bg-agoda-ps";
						}elseif($get_data_check->guest_group_id == 5){
							$bg_color = "bg-gedung-cch";
						}elseif($get_data_check->guest_group_id == 14){
							$bg_color = "bg-git-gov";
						}elseif($get_data_check->guest_group_id == 16){
							$bg_color = "bg-git-ins";
						}elseif($get_data_check->guest_group_id == 12){
							$bg_color = "bg-git-group";
						}elseif($get_data_check->guest_group_id == 15){
							$bg_color = "bg-late";
						}elseif($get_data_check->guest_group_id == 6){
							$bg_color = "bg-pegi";
						}elseif($get_data_check->guest_group_id == 10){
							$bg_color = "bg-pegi-mobile";
						}elseif($get_data_check->guest_group_id == 11){
							$bg_color = "ticket";
						}elseif($get_data_check->guest_group_id == 7){
							$bg_color = "traveloka";
						}elseif($get_data_check->guest_group_id == 1){
							$bg_color = "fit";
						}else{
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
