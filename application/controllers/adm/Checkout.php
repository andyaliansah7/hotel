<?php
/**
 * Check-In Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Checkout extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Booking_model');
		$this->load->model('Checkin_model');
		$this->load->model('Guests_model');
		$this->load->model('Guest_groups_model');
		$this->load->model('Room_types_model');
		$this->load->model('Rooms_model');
		$this->load->model('Special_rates_model');
		$this->load->model('Consumption_services_model');
	}
	
	public function index() {
		$data['content_title'] = 'Check-Out';

		if(check_roles('1')){
			$this->twiggy_display('adm/checkout/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function process($b_id='') {
		$data['content_title'] = 'Check-In';
		$data['b_id']          = $b_id;
		$data['url']           = site_url('adm/checkin/edit/'.$b_id);


		$this->twiggy_display('adm/checkout/index', $data);
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Checkin_model->get_data(array('transaction_type' => 'C', 'status' => '2'))->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {
				
				$data[] = array(
					'no'               => $no,
					'id'               => $get_row->id,
					'number'           => $get_row->transaction_number,
					'date'             => $get_row->checkout_date,
					'guest_name'       => $get_row->guest_name,
					'guest_phone'      => $get_row->guest_phone,
					'guest_group_name' => $get_row->guest_group_name,
					'room_type'        => $get_row->room_type_name,
					'room'             => $get_row->room_number,
					'date_in'          => indonesian_date($get_row->date_in),
					'date_out'         => indonesian_date($get_row->date_out),
					'room_price'       => $get_row->room_price,
					'total'            => $get_row->total,
					'deposit'          => $get_row->deposit,
					'total_paid'       => $get_row->total_paid
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
			$where    = array('id' => $id);
			$get_data = $this->Checkin_model->get_data($where)->row_array();
		}

		$data['id']               = $id;
		$data['content_title']    = $title;
		$data['get_data']         = $get_data;
		$data['guest_data']       = $this->Guests_model->get_data()->result();
		$data['guest_group_data'] = $this->Guest_groups_model->get_data()->result();
		$data['room_type_data']   = $this->Room_types_model->get_data()->result();

		$this->twiggy_display('adm/checkin/edit', $data);
	}

	public function save() {
		// post
		$id                  = $this->input->post('id');
		$type                = $this->input->post('type');
		$identity_number     = $this->input->post('identity_number');
		$identity_number_new = $this->input->post('identity_number_new');
		$guest_name          = $this->input->post('guest_name');
		$guest_phone         = $this->input->post('guest_phone');
		$guest_address       = $this->input->post('guest_address');
		$guest_group         = $this->input->post('guest_group');
		$date_in             = $this->input->post('date_in');
		$date_out            = $this->input->post('date_out');
		$interval            = $this->input->post('interval');
		$room_type           = $this->input->post('room_type');
		$room                = $this->input->post('room');
		$room_price          = $this->input->post('room_price');
		$total_price         = $this->input->post('total_price');
		$discount            = $this->input->post('discount');
		$total               = $this->input->post('total');
		$deposit             = $this->input->post('deposit');
		$on_behalf           = $this->input->post('on_behalf');

		$action       = $this->input->post('action');

		$data_save_transaction = array();

		$prefix           = "CHE";
		$datenow          = date("mY");
		$number_generator = $this->Checkin_model->checkin_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		date_default_timezone_set('Asia/Jakarta');
		$checkin_date = date('Y-m-d H:i:s');

		$data_guest_save = array(
			'guest_identity_number' => $identity_number_new,
			'guest_name'            => $guest_name,
			'guest_phone'           => $guest_phone,
			'guest_address'         => $guest_address			
		);

		$guest_id = $identity_number;
		if($guest_id == 'register'){

			$save_guest = $this->Guests_model->save($data_guest_save);

			if($save_guest){
				$guest_id = $this->db->insert_id();
			}

		}else{
			$save_guest = $this->Guests_model->update($guest_id, $data_guest_save);
		}

		if($id == 'new') {

			$data_save_transaction = array(
				'transaction_type'   => 'C',
				'transaction_number' => $autonumber,
				'transaction_date'   => date_now(),
				'guest_id'           => $guest_id,
				'guest_group_id'     => $guest_group,
				'room_type_id'       => $room_type,
				'room_id'            => $room,
				'on_behalf'          => ($on_behalf == 'default' ? $guest_id : $on_behalf),
				'date_in'            => $date_in,
				'date_out'           => $date_out,
				'interval_stay'      => $interval,
				'room_price'         => trims($room_price),
				'total_price'        => trims($total_price),
				'discount'           => trims($discount),
				'total'              => trims($total),
				'deposit'            => trims($deposit),
				'checkin_date'   	 => $checkin_date
			);

			$convert = convert_button($action, $id);
			$save = $this->Checkin_model->save($data_save_transaction);

		} else {
			
			if($type == 'B'){

				$data_save_transaction = array(
					'b_id'  			 => $id,
					'transaction_type'   => 'C',
					'transaction_number' => $autonumber,
					'transaction_date'   => date_now(),
					'guest_id'           => $guest_id,
					'guest_group_id'     => $guest_group,
					'room_type_id'       => $room_type,
					'room_id'            => $room,
					'on_behalf'          => ($on_behalf == 'default' ? $guest_id : $on_behalf),
					'date_in'            => $date_in,
					'date_out'           => $date_out,
					'interval_stay'      => $interval,
					'room_price'         => trims($room_price),
					'total_price'        => trims($total_price),
					'discount'           => trims($discount),
					'total'              => trims($total),
					'deposit'            => trims($deposit),
					'checkin_date'   	 => $checkin_date
				);

				$data_status = array(
					'status' => '1'
				);
				
				$update_status_booking = $this->Booking_model->update($id, $data_status);
				if($update_status_booking){
					$convert = convert_button($action, $id);
					$save = $this->Checkin_model->save($data_save_transaction);
				}
				
			}else{
				$data_save_transaction = array(
					'transaction_type' => 'C',
					// 'transaction_number' => $autonumber,
					// 'transaction_date'   => date_now(),
					'guest_id'       => $guest_id,
					'guest_group_id' => $guest_group,
					'room_type_id'   => $room_type,
					'room_id'        => $room,
					'on_behalf'      => ($on_behalf == 'default' ? $guest_id : $on_behalf),
					'date_in'        => $date_in,
					'date_out'       => $date_out,
					'date_out'       => $date_out,
					'interval_stay'  => $interval,
					'room_price'     => trims($room_price),
					'total_price'    => trims($total_price),
					'discount'       => trims($discount),
					'total'          => trims($total),
					'deposit'        => trims($deposit)
				);
					
				$convert = convert_button($action, $id);
				$save = $this->Checkin_model->update($id, $data_save_transaction);
			}
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

	public function checkout_cancel_process() {
		$id = $this->input->post('id');

		$wt = 'id = "'.$id.'" and total_paid = "0"';
		$gt = $this->Checkin_model->get_data($wt)->result();

		date_default_timezone_set('Asia/Jakarta');
		$checkout_date = date('Y-m-d H:i:s');
		$message = '';
		$status = 'error';

		if(!empty($gt)){
			$data_status = array(
				'status' => '0',
				'checkout_date' => '0000-00-00 00:00:00'
			);
			
			$update_status = $this->Checkin_model->update($id, $data_status);
			if($update_status){
				$message = 'Checkout berhasil.';
				$status = 'success';
			}
		}else{
			$message = 'Checkout gagal, Nomor Transaksi ini masih memiliki tagihan!';
			$status = 'error';
		}

		$response = array(
			'message' => $message,
			'status'  => $status
		);

		output_json($response);
	}


	public function delete() {
		$id = $this->input->post('id');

		foreach($id as $row) {
			$delete_type = $this->Checkin_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_identity_number() {
		$id  = $this->input->post('id');
		$where = array('guest_id' => $id);
		
		$data = array();
		$status = false;
		
		$guest = $this->Guests_model->get_data($where)->row();

		if ($guest)
		{
			$data = array(
				'id_number' => $guest->guest_identity_number,
				'name'    => $guest->guest_name,
				'phone'   => $guest->guest_phone,
				'address' => $guest->guest_address,
				'status'  => true
			);
		}

		output_json($data);
	}

	public function check_room_available()
	{
		$room_type_id = $this->input->post('room_type_id');
		$date_in      = $this->input->post('date_in');
		$date_out     = $this->input->post('date_out');
		$id           = $this->input->post('id');
		
		
		$data = [];
		$room_available = $this->Rooms_model->get_available_room($room_type_id, $date_in, $date_out, $id)->result();

		$price_per_night     = 0;
		$price_total         = 0;
		$check_in_day        = change_format_date($date_in, 'D');
		$interval            = ($date_in == $date_out) ? '1' : date_diff(date_create($date_in), date_create($date_out))->format('%a');
		$where_special_price = array('a.room_type_id' => $room_type_id, 'special_rate_date' => $date_in);
		$where_normal_price  = array('a.room_type_id' => $room_type_id);
		$special_price       = $this->Special_rates_model->get_data($where_special_price)->row();
		$normal_price        = $this->Room_types_model->get_data($where_normal_price)->row();

		if(!empty($special_price)){
			$price_per_night = $special_price->special_rate_price;
		}else{
			if($check_in_day == 'Sat' || $check_in_day == 'Sun'){
				$price_per_night = $normal_price->room_type_price_weekend;
			}else{
				$price_per_night = $normal_price->room_type_price_weekday;
			}
		}
		
		$price_total = ($price_per_night * $interval);
		
		$response = array(
			'room_available'  => $room_available,
			'interval'        => $interval,
			'price_per_night' => $price_per_night,
			'price_total'     => $price_total
		);

		echo json_encode($response);
	}

	public function room_data()
	{
		$id  = $this->input->post('id');
		$where = array('a.room_type_id' => $id);
		
		
		$room_available = $this->Rooms_model->get_data($where)->result();

		echo json_encode($room_available);
	}

	public function guest_group_data()
	{
		$id  = $this->input->post('id');
		$where = array('guest_group_id' => $id);
		
		
		$guest_group = $this->Guest_groups_model->get_data($where)->row()->guest_group_discount;

		echo json_encode($guest_group);
	}

}

?>
