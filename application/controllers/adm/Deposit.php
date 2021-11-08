<?php
/**
 * Deposit Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Deposit extends BaseController {

	/**
	 * Constructor CodeIgniter
	 */
	public function __construct() {
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Deposit_model');
		$this->load->model('Guests_model');
		$this->load->model('Payment_methods_model');
		$this->load->model('Checkin_model');
	}
	
	public function index() {
		$data['content_title'] = 'Deposit';

		if(check_roles('1')){
			$this->twiggy_display('adm/deposit/index', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data() {
		$data = [];
		$get_data = $this->Deposit_model->get_data(array('deposit_amount_1 !=' => 0))->result();
		
		if($get_data) {
			$no=1;
			foreach($get_data as $get_row) {

				$where_1 = array('payment_method_id' => $get_row->payment_method_1);
				$getme_1 = $this->Payment_methods_model->get_data($where_1)->row();
				$ptype_1 = '';
				if($getme_1){
					$ptype_1  = $getme_1->payment_method_name;
				}

				$where_2 = array('payment_method_id' => $get_row->payment_method_2);
				$getme_2 = $this->Payment_methods_model->get_data($where_2)->row();
				$ptype_2 = '';
				if($getme_2){
					$ptype_2  = $getme_2->payment_method_name;
				}

				$where_3 = array('payment_method_id' => $get_row->payment_method_3);
				$getme_3 = $this->Payment_methods_model->get_data($where_3)->row();
				$ptype_3 = '';
				if($getme_3){
					$ptype_3  = $getme_3->payment_method_name;
				}

				$ptype_1 = ($get_row->deposit_amount_1 == 0 ? '' : $ptype_1);
				$ptype_2 = ($get_row->deposit_amount_2 == 0 ? '' : $ptype_2);
				$ptype_3 = ($get_row->deposit_amount_3 == 0 ? '' : $ptype_3);

				$pmethod = rtrim($ptype_1.", ".$ptype_2.", ".$ptype_3, ", ");
				$total = $get_row->deposit_amount_1 + $get_row->deposit_amount_2 + $get_row->deposit_amount_3;

				$data[] = array(
					'no'             => $no,
					'id'             => $get_row->deposit_id,
					'date'           => indonesian_date($get_row->deposit_date),
					'guest'          => $get_row->guest_name,
					'payment_method' => $pmethod,
					'amount'         => "Rp. " .number_format($total),
					'description'    => $get_row->deposit_description,
					'date_use'       => ($get_row->payment_id == 0 ? '-' : indonesian_date($get_row->payment_date)),
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
		$amount_1   = 0;
		$amount_2   = 0;
		$amount_3   = 0;

		if($id != 'new') {
			$title    = "Edit";
			$where    = array('deposit_id' => $id);
			$get_data = $this->Deposit_model->get_data($where)->row_array();
			$amount_1   = number_format(check_array_key($get_data, 'deposit_amount_1'));
			$amount_2   = number_format(check_array_key($get_data, 'deposit_amount_2'));
			$amount_3   = number_format(check_array_key($get_data, 'deposit_amount_3'));
		}

		$data['id']             = $id;
		$data['content_title']  = $title;
		$data['get_data']       = $get_data;
		$data['amount_1']       = $amount_1;
		$data['amount_2']       = $amount_2;
		$data['amount_3']       = $amount_3;
		$data['guest_data']     = $this->Guests_model->get_data()->result();
		$data['paymethod_data'] = $this->Payment_methods_model->get_data()->result();
		$data['shift_data']     = $this->Checkin_model->get_data_shift()->result();

		$this->twiggy_display('adm/deposit/edit', $data);
	}

	public function save() {
		// post
		$id                  = $this->input->post('id');
		$payment_id          = $this->input->post('payment_id');
		$identity_number     = $this->input->post('identity_number');
		$identity_number_new = $this->input->post('identity_number_new');
		$guest_name          = $this->input->post('guest_name');
		$guest_phone         = $this->input->post('guest_phone');
		$guest_address       = $this->input->post('guest_address');
		$date                = $this->input->post('date');
		$amount_1            = $this->input->post("amount_1");
		$amount_2            = $this->input->post("amount_2");
		$amount_3            = $this->input->post("amount_3");
		$payment_method_1    = $this->input->post("payment_method_1");
		$payment_method_2    = $this->input->post("payment_method_2");
		$payment_method_3    = $this->input->post("payment_method_3");
		$description         = $this->input->post('description');
		$shift               = $this->input->post('shift');

		$pmethod2 = ($payment_method_2 == 0 || $amount_2 == 0 ? $payment_method_1 : $payment_method_2);
		$pmethod3 = ($payment_method_3 == 0 || $amount_3 == 0 ? $payment_method_1 : $payment_method_3);
		$pamount2 = ($payment_method_2 == 0 ? 0 : $amount_2);
		$pamount3 = ($payment_method_3 == 0 ? 0 : $amount_3);

		$pmethod_type1 = $this->Payment_methods_model->get_data(array('payment_method_id' => $payment_method_1))->row()->payment_method_type;
		$pmethod_type2 = $this->Payment_methods_model->get_data(array('payment_method_id' => $pmethod2))->row()->payment_method_type;
		$pmethod_type3 = $this->Payment_methods_model->get_data(array('payment_method_id' => $pmethod3))->row()->payment_method_type;

		$kartu = 0;
		$tunai = 0;
		$trans = 0;

		$total_kartu_1 = 0;
		$total_kartu_2 = 0;
		$total_kartu_3 = 0;

		$total_tunai_1 = 0;
		$total_tunai_2 = 0;
		$total_tunai_3 = 0;

		$total_trans_1 = 0;
		$total_trans_2 = 0;
		$total_trans_3 = 0;

		if($pmethod_type1 == "Kartu"){
			$total_kartu_1 = trims($amount_1);
		}
		if($pmethod_type2 == "Kartu"){
			$total_kartu_2 = trims($pamount2);
		}
		if($pmethod_type3 == "Kartu"){
			$total_kartu_3 = trims($pamount3);
		}

		if($pmethod_type1 == "Tunai"){
			$total_tunai_1 = trims($amount_1);
		}
		if($pmethod_type2 == "Tunai"){
			$total_tunai_2 = trims($pamount2);
		}
		if($pmethod_type3 == "Tunai"){
			$total_tunai_3 = trims($pamount3);
		}

		if($pmethod_type1 == "Transfer"){
			$total_trans_1 = trims($amount_1);
		}
		if($pmethod_type2 == "Transfer"){
			$total_trans_2 = trims($pamount2);
		}
		if($pmethod_type3 == "Transfer"){
			$total_trans_3 = trims($pamount3);
		}

		$kartu = $total_kartu_1 + $total_kartu_2 + $total_kartu_3;
		$tunai = $total_tunai_1 + $total_tunai_2 + $total_tunai_3;
		$trans = $total_trans_1 + $total_trans_2 + $total_trans_3;

		$action = $this->input->post('action');

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

		if($payment_id == "0" || $payment_id == ""){
			$data_save = array(
				'guest_id'              => $guest_id,
				'deposit_date'          => change_format_date($date),
				'payment_date'          => '0000-00-00',
				'payment_method_1'      => $payment_method_1,
				'payment_method_2'      => $pmethod2,
				'payment_method_3'      => $pmethod3,
				'payment_method_type_1' => $pmethod_type1,
				'payment_method_type_2' => $pmethod_type2,
				'payment_method_type_3' => $pmethod_type3,
				'deposit_amount_1'      => trims($amount_1),
				'deposit_amount_2'      => trims($pamount2),
				'deposit_amount_3'      => trims($pamount3),
				'deposit_kartu'         => trims($kartu),
				'deposit_tunai'         => trims($tunai),
				'deposit_trans'         => trims($trans),
				'deposit_description'   => $description,
				'shift_id'              => $shift
			);
		}else{
			$data_save = array(
				'guest_id'              => $guest_id,
				'deposit_date'          => change_format_date($date),
				'payment_method_1'      => $payment_method_1,
				'payment_method_2'      => $pmethod2,
				'payment_method_3'      => $pmethod3,
				'payment_method_type_1' => $pmethod_type1,
				'payment_method_type_2' => $pmethod_type2,
				'payment_method_type_3' => $pmethod_type3,
				'deposit_amount_1'      => trims($amount_1),
				'deposit_amount_2'      => trims($pamount2),
				'deposit_amount_3'      => trims($pamount3),
				'deposit_kartu'         => trims($kartu),
				'deposit_tunai'         => trims($tunai),
				'deposit_trans'         => trims($trans),
				'deposit_description'   => $description,
				'shift_id'              => $shift
			);
		}
		
		if($id == 'new') {
			$convert = convert_button($action, $id);
			$save = $this->Deposit_model->save($data_save);
		} else {
			$convert = convert_button($action, $id);
			$save = $this->Deposit_model->update($id, $data_save);
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
			$delete_type = $this->Deposit_model->delete($row);
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function check_id() {
		$id  = $this->input->post('id');
		$where = array('deposit_code' => $id);

		$check = $this->Deposit_model->check_id($where);

		if ($check) {
			$response = array('status' => true);
		} else {
			$response = array('status' => false);
		}

		output_json($response);
	}

}

?>
