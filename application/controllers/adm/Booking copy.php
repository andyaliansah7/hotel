<?php
/**
 * Check-In Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Booking extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Booking_model');
		$this->load->model('Guests_model');
		$this->load->model('Guest_groups_model');
		$this->load->model('Room_types_model');
		$this->load->model('Rooms_model');
		$this->load->model('Special_rates_model');
	}
	
	public function index() {
		$data['content_title'] = 'Booking Kamar';

		if(check_roles('1')){
			$this->twiggy_display('adm/booking/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Booking_model->get_data(array('transaction_type' => 'B', 'status <>' => '1'))->result();

		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {
				
				$data[] = array(
					'no'               => $no,
					'id'               => $get_row->id,
					'number'           => $get_row->transaction_number,
					'date'             => indonesian_date($get_row->transaction_date),
					'guest_name'       => $get_row->guest_name,
					'guest_phone'      => $get_row->guest_phone,
					'guest_group_name' => $get_row->guest_group_name,
					'room_type'        => $get_row->room_type_name,
					'room'             => $get_row->room_number,
					'date_in'          => ($get_row->date_in  == '0000-00-00' ? '' : indonesian_date($get_row->date_in)),
					'date_out'         => ($get_row->date_out == '0000-00-00' ? '' : indonesian_date($get_row->date_out)),
					'room_price'       => $get_row->room_price,
					'total'            => $get_row->total,
					'deposit'          => $get_row->deposit,
					'date_in_verify'   => $get_row->date_in,
					'date_out_verify'  => $get_row->date_out,
					'status'           => $get_row->status
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
			$get_data = $this->Booking_model->get_data($where)->row_array();
		}

		$data['id']               = $id;
		$data['content_title']    = $title;
		$data['get_data']         = $get_data;
		$data['guest_data']       = $this->Guests_model->get_data()->result();
		$data['guest_group_data'] = $this->Guest_groups_model->get_data()->result();
		$data['room_type_data']   = $this->Room_types_model->get_data()->result();

		$this->twiggy_display('adm/booking/edit', $data);
	}

	public function save() {
		// post
		$id                  = $this->input->post('id');
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
		$shift_id            = 0;
		$user_id 			 = logged_user('id');

		$action       = $this->input->post('action');

		$data_save_transaction = array();

		$prefix           = "BOO";
		$datenow          = date("mY");
		$number_generator = $this->Booking_model->booking_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

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
				'transaction_type'   => 'B',
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
				'user_id'            => $user_id,
				'shift_id'           => $shift_id
			);

			$convert = convert_button($action, $id);
			$save = $this->Booking_model->save($data_save_transaction);

		} else {
			
			$data_save_transaction = array(
				'transaction_type' => 'B',
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
				'deposit'        => trims($deposit),
				'user_id'            => $user_id,
				'shift_id'           => $shift_id
			);
				
			$convert = convert_button($action, $id);
			$save = $this->Booking_model->update($id, $data_save_transaction);
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

	public function duplicate()
	{
		$id      = $this->input->post('id');
		$user_id = logged_user('id');

		$prefix           = "BOO";
		$datenow          = date("mY");
		$number_generator = $this->Booking_model->booking_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		$where    = array('id' => $id);
		$get_data = $this->Booking_model->get_data($where)->row();

		$data_save_transaction = array(
			'transaction_type'   => 'B',
			'transaction_number' => $autonumber,
			'transaction_date'   => date_now(),
			'guest_id'           => $get_data->guest_id,
			'guest_group_id'     => ($get_data->guest_group_id == 0 ? 0 : $get_data->guest_group_id),
			'room_type_id'       => 0,
			'room_id'            => 0,
			'on_behalf'          => $get_data->on_behalf,
			'date_in'            => '0000-00-00',
			'date_out'           => '0000-00-00',
			'interval_stay'      => 0,
			'room_price'         => 0,
			'total_price'        => 0,
			'discount'           => 0,
			'total'              => 0,
			'deposit'            => 0,
			'user_id'            => $user_id,
			'shift_id'           => $get_data->shift_id
		);

		$save = $this->Booking_model->save($data_save_transaction);

		if($save) {
			$response = array(
				'status'  => 'success',
				'message' => 'Berhasil menyimpan data'
			);
		}

		else {
			$response = array(
				'status'  => 'error',
				'message' => 'Gagal menyimpan data'
			);
		}

		output_json($response);
	}

	public function delete() {
		$id = $this->input->post('id');

		foreach($id as $row) {
			$delete_type = $this->Booking_model->delete($row);
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
				'name'      => $guest->guest_name,
				'phone'     => $guest->guest_phone,
				'address'   => $guest->guest_address,
				'status'    => true
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
	
	public function check_room_available_backup()
	{
		date_default_timezone_set('Asia/Jakarta');

		$room_type_id = $this->input->post('room_type_id');
		$date_in      = $this->input->post('date_in');
		$date_out     = $this->input->post('date_out');
		$id           = $this->input->post('id');
		
		$sum_weekday = 0;
		$sum_weekend = 0;
		
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
		
			// $date_3 = date('Y-m-d', strtotime($date_out. '+1 day'));
	
			// $daterange = new DatePeriod(new DateTime($date_in), new DateInterval('P1D'), new DateTime($date_3));
			
			// $no = 1;
			// foreach($daterange as $date){
				
			// 	if($date->format("D") == 'Sat'){
			// 		$sum_weekend++;
			// 	}else{
			// 		$sum_weekday++;
			// 	}
				
			// }

			if($check_in_day == 'Sat'){
				$price_per_night = $normal_price->room_type_price_weekend;
				// $price_total     = ($price_per_night * $interval);
			}else{
				$price_per_night = $normal_price->room_type_price_weekday;
				// $price_total     = ($price_per_night * $interval);
			}

		}

		// if($sum_weekday == '1' && $sum_weekend == '1'){
		// 	if($check_in_day == 'Sat' || $check_in_day == 'Sun'){
		// 		$price_per_night = $normal_price->room_type_price_weekend;
		// 		// $price_total     = ($price_per_night * $interval);
		// 	}else{
		// 		$price_per_night = $normal_price->room_type_price_weekday;
		// 		// $price_total     = ($price_per_night * $interval);
		// 	}
		// }

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
