<?php
/**
 * Payments Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Payments extends BaseController
{
	/**
	 * Constructor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Payments_model');
		$this->load->model('Guests_model');
		$this->load->model('Guest_groups_model');
		$this->load->model('Payment_methods_model');
		$this->load->model('Consumption_services_model');
		$this->load->model('Checkin_model');
		$this->load->model('Deposit_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
		$data['content_title'] = 'Pembayaran';
		
		if(check_roles('1')){
			$this->twiggy_display('adm/payments/index', $data);
		}else{
			redirect("Error");
		}
	}
	 
	public function get_data_header()
	{	
		$data = [];
		$get_data = $this->Payments_model->payment_header()->result();

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{	
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

				$ptype_1 = ($get_row->total_paid_1 == 0 ? '' : $ptype_1);
				$ptype_2 = ($get_row->total_paid_2 == 0 ? '' : $ptype_2);
				$ptype_3 = ($get_row->total_paid_3 == 0 ? '' : $ptype_3);

				$pmethod = rtrim($ptype_1.", ".$ptype_2.", ".$ptype_3, ", ");
				$total = $get_row->total_paid_1 + $get_row->total_paid_2 + $get_row->total_paid_3;

				$data[] = array(
					'no'      => $no,
					'id'      => $get_row->header_id,
					'number'  => $get_row->payment_number,
					'date'    => indonesian_date($get_row->payment_date),
					'guest'   => ($get_row->guest_id == 0 ? 'Bukan Tamu Menginap' : $get_row->guest_name),
					'pmethod' => $pmethod,
					'total'   => "Rp. " .number_format($total),
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

 	public function edit($id = 'new')
	{
		$title = "Tambah Pembayaran";
		$header_data = array(null);

		$prefix           = "INV";
		$datenow          = date("mY");
		$number_generator = $this->Payments_model->payment_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		$date_text   = change_format_date(date_now(), 'd/m/Y');

	    if($id != 'new')
	    {
			$title       = "Edit Pembayaran";
			$where       = array('header_id' => $id);
			$header_data = $this->Payments_model->payment_header($where)->row_array();
			$autonumber  = $header_data['payment_number'];
			$date_text   = change_format_date($header_data['payment_date'], 'd/m/Y');
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['header_data']   = $header_data;
		$data['guest_data']    = $this->Guests_model->get_data()->result();
		$data['paymethod_data']   = $this->Payment_methods_model->get_data()->result();
		$data['shift_data']       = $this->Checkin_model->get_data_shift()->result();
		
		$data['autonumber'] = $autonumber;
		$data['date_text']  = $date_text;

		if(check_roles('1')){
			$this->twiggy_display('adm/payments/edit', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data_detail()
	{	
		$id = $this->input->post("id");
		$where       = array('header_id' => $id);
		// $order       = array('material_name' => 'asc');

		$data = [];
		$get_data = $this->Payments_model->payment_detail($where)->result();
		$detail_cs = [];
		$no = 0;

		$guest_name = '';
		$guest_group = '';
		$guest_telp = '';
		$room = '';
		$room_type = '';
		$date1 = '';
		$date2 = '';
		$discount_guest_group = 0;

		$has_paid = "0";

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{

				if($get_row->transaction_type == 'T'){
				$where      = array('id' => $get_row->transaction_id);
				$t          = $this->Payments_model->checkin_data('*', $where)->row_array();
				
				$g = $this->Guest_groups_model->get_data(array('guest_group_id' => $t['guest_group_id']))->row_array();
				// $discount_guest_group = $get_row->price * ($g['guest_group_discount']/100);
				$discount_guest_group = 0;

				$guest_group = $g['guest_group_name'];

				$guest_name  = $t['guest_name'];
				$guest_telp  = $t['guest_phone'];
				$room        = $t['room_number'];
				$room_type   = $t['room_type_name'];
				$date1       = $t['date_in'];
				$date2       = $t['date_out'];
				
				$where_detail = array('header_id !=' => $get_row->header_id, 'transaction_id' => $get_row->transaction_id, 'transaction_type' => 'T');
				$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;
			}

			if($get_row->transaction_type == 'C'){
				$detail_cs  = $this->Consumption_services_model->cs_detail(array('cs_detail_header_id' => $get_row->transaction_id))->result();

				$header_cs = $this->Consumption_services_model->cs_header(array('cs_header_id' => $get_row->transaction_id))->row();
				$t_id      = $header_cs->transaction_id;
				$g_id      = $header_cs->cs_header_on_behalf_id;
				
				$t          = $this->Payments_model->checkin_data('*', array('id' => $t_id))->row_array();
				$discount_guest_group = 0;
				if($g_id != 0){
					$guest_name = $t['guest_name'];
					$guest_telp = $t['guest_phone'];
					$room       = $t['room_number'];
					$room_type  = $t['room_type_name'];
				}else{
					$guest_name = $header_cs->cs_header_on_behalf_name;
					$guest_telp = '';
					$room       = 'Bukan Tamu Menginap';
					$room_type  = '';
				}

				$where_detail = array('header_id !=' => $get_row->header_id, 'transaction_id' => $get_row->transaction_id, 'transaction_type' => 'C');
				$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;
			}
				$paid = ($get_row->paid < 0 ? 0 : $get_row->paid);

				$has_paid_check = ($has_paid < 0 ? 0 : $has_paid);
				$data[] = array(
					'no'         => $no,
					'id'         => $get_row->transaction_id,
					'code'       => $get_row->transaction_code,
					'number'     => $get_row->transaction_number,
					'type'       => $get_row->transaction_type,
					'guest_group'=> $guest_group,
					'guest_name' => $guest_name,
					'guest_telp' => ($guest_telp != '' ? "(".$guest_telp.")" :''),
					'room'       => $room,
					'room_type'  => $room_type,
					'date_range' => ($date1 != '' ? indonesian_date($date1):'').' - '.($date2 != '' ? indonesian_date($date2):''),
					'detail_cs'  => $detail_cs,
					'price'      => number_format($get_row->price),
					// 'discount'   => number_format($get_row->discount),
					'discount'   => number_format($get_row->discount + $discount_guest_group),
					'deposit'    => number_format($get_row->deposit),
					'total'      => number_format(($get_row->price) - ($get_row->discount + $get_row->deposit + $has_paid_check)),
					// 'total'      => number_format($get_row->total),
					// 'total'      => number_format($has_paid),
					'has_paid'   => ($has_paid_check < 0 ? 0 : number_format($has_paid_check)),
					'paid'       => ($get_row->paid < 0 ? 0 : number_format($get_row->paid)),
					'typecolor'  => ($get_row->transaction_type == "T" ? "bg-info" : "bg-warning"),
					'typeicon'   => ($get_row->transaction_type == "T" ? "Kamar" : "Produk/Layanan"),
					'btncolor'   => '',
					'btnicon'    => '',
				);
				$no++;
			}
		}

		output_json($data);
	}

	public function get_embed()
	{
		$data = [];
		$data['content_title'] = 'Data Transaksi';
		$this->twiggy_display('adm/payments/embed', $data);
	}

	public function get_data_embed()
	{
		$data = [];
		$response = [];
		$guest    = $this->input->post('guest');
		$search   = $this->input->post('search');
		$id   = $this->input->post('id');

		$get_data  = $this->Payments_model->get_all_transaction($guest, $search, $id)->result();
		$detail_cs = [];
		$no = 0;

		$guest_group = '';
		$guest_name = '';
		$guest_telp = '';
		$room = '';
		$room_type = '';
		$date1 = '';
		$date2 = '';
		$discount_guest_group = 0;
		$has_paid = 0;
		foreach($get_data as $get_row)
		{	
			if($get_row->type == 'T'){
				$where      = array('id' => $get_row->id);
				$t          = $this->Payments_model->checkin_data('*', $where)->row_array();
				
				$g = $this->Guest_groups_model->get_data(array('guest_group_id' => $t['guest_group_id']))->row_array();
				$discount_guest_group = $get_row->price * ($g['guest_group_discount']/100);

				$guest_group = $g['guest_group_name'];

				$guest_name = $t['guest_name'];
				$guest_telp = $t['guest_phone'];
				$room       = $t['room_number'];
				$room_type  = $t['room_type_name'];
				$date1      = $t['date_in'];
				$date2      = $t['date_out'];

				$where_detail = array('transaction_id' => $get_row->id, 'transaction_type' => 'T');
				$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;
				if($id != 'new'){
					$where_detail = array('header_id !=' => $id, 'transaction_id' => $get_row->id, 'transaction_type' => 'T');
					$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;
				}
			}

			if($get_row->type == 'C'){
				$detail_cs  = $this->Consumption_services_model->cs_detail(array('cs_detail_header_id' => $get_row->id))->result();

				$header_cs = $this->Consumption_services_model->cs_header(array('cs_header_id' => $get_row->id))->row();
				$t_id      = $header_cs->transaction_id;
				$g_id      = $header_cs->cs_header_on_behalf_id;
				
				$t          = $this->Payments_model->checkin_data('*', array('id' => $t_id))->row_array();
				$discount_guest_group = 0;
				if($g_id != 0){
					$guest_name = $t['guest_name'];
					$guest_telp = $t['guest_phone'];
					$room       = $t['room_number'];
					$room_type  = $t['room_type_name'];
				}else{
					$guest_name = $header_cs->cs_header_on_behalf_name;
					$guest_telp = '';
					$room       = 'Bukan Tamu Menginap';
					$room_type  = '';
				}

				$where_detail = array('transaction_id' => $get_row->id, 'transaction_type' => 'C');
				$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;
				if($id != 'new'){
					$where_detail = array('header_id !=' => $id, 'transaction_id' => $get_row->id, 'transaction_type' => 'C');
					$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;
				}
			}
			$paid = ($get_row->paid < 0 ? 0 : $get_row->paid);

			$total_show = number_format($get_row->total - $get_row->deposit - $paid);

			if($id != 'new'){
				$pay_detail  = $this->Payments_model->payment_detail(array('header_id' => $id, 'transaction_id' => $get_row->id, 'transaction_type' => $get_row->type))->row_array(); 
				$paid        = $pay_detail['paid'];
				$pay_total   = ($pay_detail['total'] <= 0 ? $get_row->total : $pay_detail['total']);
				$total_show  = number_format($pay_total);
			}

			$has_paid_check = ($has_paid < 0 ? 0 : $has_paid);

			$data[] = array(
				'no'         => $no,
				'id'         => $get_row->id,
				'code'       => $get_row->code,
				'number'     => $get_row->number,
				'type'       => $get_row->type,
				'guest_group' => $guest_group,
				'guest_name' => $guest_name,
				'guest_telp' => ($guest_telp != '' ? "(".$guest_telp.")" :''),
				'room'       => $room,
				'room_type'  => $room_type,
				'date_range' => ($date1 != '' ? indonesian_date($date1):'').' - '.($date2 != '' ? indonesian_date($date2):''),
				'detail_cs'  => $detail_cs,
				'price'      => number_format($get_row->price),
				// 'discount'   => number_format($get_row->discount),
				'discount'   => number_format($get_row->discount + $discount_guest_group),
				'deposit'    => number_format($get_row->deposit),
				'total'      => number_format(($get_row->price) - ($get_row->discount + $discount_guest_group + $get_row->deposit + $has_paid_check)),
				// 'total'      => $total_show,
				'paid'       => number_format($paid),
				'typecolor'  => ($get_row->type == "T" ? "bg-info" : "bg-warning"),
				'typeicon'   => ($get_row->type == "T" ? "Kamar" : "Produk/Layanan"),
				'btncolor'   => '',
				'btnicon'    => '',
			);

			$no++;
		}

		$response = [
            'data'         => $data,
            'recordsTotal' => count($data)
        ];

		output_json($response);	
	}

	public function get_guest()
	{
		$data     = [];
		$id       = $this->input->post('id');
		$guest_id = $this->input->post('guest_id');

		$where = array('guest_id' => $guest_id);
		$where_deposit = array('guest_id' => $guest_id, 'payment_id' => 0);

		if($id != "new"){
			$where_deposit = array('guest_id' => $guest_id);
		}

		$guest_identity_number = '';
		$guest_phone           = '';
		$guest_address         = '';
		$guest_deposit         = 0;

		$get_data = $this->Guests_model->get_data($where)->row();
		if($get_data){
			$guest_identity_number = $get_data->guest_identity_number;
			$guest_phone           = $get_data->guest_phone;
			$guest_address         = $get_data->guest_address;
			$guest_deposit         = $this->Payments_model->guest_deposit('COALESCE(SUM(deposit_amount_1 + deposit_amount_2 + deposit_amount_3),0) as amount', $where_deposit)->row()->amount;
			$deposit_kartu         = $this->Payments_model->guest_deposit('COALESCE(SUM(deposit_kartu),0) as amount', $where_deposit)->row()->amount;
			$deposit_tunai         = $this->Payments_model->guest_deposit('COALESCE(SUM(deposit_tunai),0) as amount', $where_deposit)->row()->amount;
			$deposit_trans         = $this->Payments_model->guest_deposit('COALESCE(SUM(deposit_trans),0) as amount', $where_deposit)->row()->amount;
		}

		$no       = 0;
		$get_deposit = $this->Payments_model->guest_deposit('', $where_deposit)->result();
		foreach($get_deposit as $get_row)
		{	
			$data[] = array(
				'id'               => $get_row->deposit_id,
				'date'             => indonesian_date($get_row->deposit_date),
				'deposit_amount_1' => number_format($get_row->deposit_amount_1),
				'deposit_amount_2' => number_format($get_row->deposit_amount_2),
				'deposit_amount_3' => number_format($get_row->deposit_amount_3),
				'deposit_kartu'    => number_format($get_row->deposit_kartu),
				'deposit_tunai'    => number_format($get_row->deposit_tunai),
				'deposit_trans'    => number_format($get_row->deposit_trans),
				'total_amount'     => number_format($get_row->deposit_amount_1 + $get_row->deposit_amount_2 + $get_row->deposit_amount_3),
				'btncolor'         => '',
				'btnicon'          => '',
			);
			$no++;
		}

		$response = [
			'id_number'     => $guest_identity_number,
			'phone'         => $guest_phone,
			'address'       => $guest_address,
			'deposit_list'  => $data,
			'deposit'       => $guest_deposit,
			'deposit_kartu' => $deposit_kartu,
			'deposit_tunai' => $deposit_tunai,
			'deposit_trans' => $deposit_trans,
		];

		echo json_encode($response);
	}

	public function get_deposit_by_payment()
	{
		$data     = [];
		$id       = $this->input->post('id');
		$no       = 0;

		$where_deposit = array('payment_id' => $id);
		$get_deposit   = $this->Payments_model->guest_deposit('', $where_deposit)->result();

		foreach($get_deposit as $get_row)
		{	
			$data[] = array(
				'id'               => $get_row->deposit_id,
				'date'             => indonesian_date($get_row->deposit_date),
				'deposit_amount_1' => number_format($get_row->deposit_amount_1),
				'deposit_amount_2' => number_format($get_row->deposit_amount_2),
				'deposit_amount_3' => number_format($get_row->deposit_amount_3),
				'deposit_kartu'    => number_format($get_row->deposit_kartu),
				'deposit_tunai'    => number_format($get_row->deposit_tunai),
				'deposit_trans'    => number_format($get_row->deposit_trans),
				'total_amount'     => number_format($get_row->deposit_amount_1 + $get_row->deposit_amount_2 + $get_row->deposit_amount_3),
				'btncolor'         => '',
				'btnicon'          => '',
			);
			$no++;
		}

		output_json($data);
	}

	public function save()
	{	
		$id                  = $this->input->post('id');
		$date                = $this->input->post("date");
		$guest               = $this->input->post("guest");
		$type_room           = $this->input->post("type_room");
		$type_cons           = $this->input->post("type_cons");
		$total_price         = $this->input->post("total_price");
		$total_consumption   = 0;
		$total_service       = 0;
		$total_discount      = $this->input->post("total_discount");
		$total_deposit       = $this->input->post("total_deposit");
		$total_deposit_2     = $this->input->post("total_deposit_master");
		$total_tax           = 0;
		$total_amount        = $this->input->post("total_amount");
		$total_paid_1        = $this->input->post("total_paid_1");
		$total_paid_2        = $this->input->post("total_paid_2");
		$total_paid_3        = $this->input->post("total_paid_3");
		$total_room_1        = $this->input->post("total_room_1");
		$total_room_2        = $this->input->post("total_room_2");
		$total_room_3        = $this->input->post("total_room_3");
		$total_service_1     = $this->input->post("total_service_1");
		$total_service_2     = $this->input->post("total_service_2");
		$total_service_3     = $this->input->post("total_service_3");
		$total_consumption_1 = $this->input->post("total_consumption_1");
		$total_consumption_2 = $this->input->post("total_consumption_2");
		$total_consumption_3 = $this->input->post("total_consumption_3");
		$payment_method_1    = $this->input->post("payment_method_1");
		$payment_method_2    = $this->input->post("payment_method_2");
		$payment_method_3    = $this->input->post("payment_method_3");
		$description         = $this->input->post("description");
		$shift_id            = $this->input->post('shift');

		$total_deposit_kartu = $this->input->post('total_deposit_kartu');
		$total_deposit_tunai = $this->input->post('total_deposit_tunai');
		$total_deposit_trans = $this->input->post('total_deposit_trans');

		$user_id             = logged_user('id');
		
		$pmethod2 = ($payment_method_2 == 0 || $total_paid_2 == 0 ? $payment_method_1 : $payment_method_2);
		$pmethod3 = ($payment_method_3 == 0 || $total_paid_3 == 0 ? $payment_method_1 : $payment_method_3);

		$ptotal2  = ($payment_method_2 == 0 ? 0 : $total_paid_2);
		$ptotal3  = ($payment_method_3 == 0 ? 0 : $total_paid_3);

		$pmethod_type1 = $this->Payment_methods_model->get_data(array('payment_method_id' => $payment_method_1))->row()->payment_method_type;
		$pmethod_type2 = $this->Payment_methods_model->get_data(array('payment_method_id' => $pmethod2))->row()->payment_method_type;
		$pmethod_type3 = $this->Payment_methods_model->get_data(array('payment_method_id' => $pmethod3))->row()->payment_method_type;

		$vuedata = $this->input->post('vuedata');
		$depdata = $this->input->post('depdata');


		$header_id   = $id;

		date_default_timezone_set('Asia/Jakarta');
		$date_now = date('Y-m-d');

		$prefix           = "INV";
		$datenow          = date("mY");
		$number_generator = $this->Payments_model->payment_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		date_default_timezone_set('Asia/Jakarta');
		$timestamp = date('Y-m-d H:i:s');
		
		$header_data = [
			'guest_id'              => $guest,
			'payment_number'        => $autonumber,
			'type_room'             => $type_room,
			'type_consumption'      => $type_cons,
			'payment_date'          => change_format_date($date),
			'total_price'           => trims($total_price),
			'total_consumption'     => trims($total_consumption),
			'total_service'         => trims($total_service),
			'total_discount'        => trims($total_discount),
			'total_deposit'         => trims($total_deposit),
			'total_deposit_2'       => trims($total_deposit_2),
			'total_tax'             => trims($total_tax),
			'total_amount'          => trims($total_amount),
			'total_paid_1'          => trims($total_paid_1),
			'total_paid_2'          => trims($ptotal2),
			'total_paid_3'          => trims($ptotal3),
			'total_room_1'          => trims($total_room_1),
			'total_room_2'          => trims($total_room_2),
			'total_room_3'          => trims($total_room_3),
			'total_service_1'       => trims($total_service_1),
			'total_service_2'       => trims($total_service_2),
			'total_service_3'       => trims($total_service_3),
			'total_consumption_1'   => trims($total_consumption_1),
			'total_consumption_2'   => trims($total_consumption_2),
			'total_consumption_3'   => trims($total_consumption_3),
			'total_deposit_kartu'   => trims($total_deposit_kartu),
			'total_deposit_tunai'   => trims($total_deposit_tunai),
			'total_deposit_trans'   => trims($total_deposit_trans),
			'payment_method_1'      => $payment_method_1,
			'payment_method_2'      => $pmethod2,
			'payment_method_3'      => $pmethod3,
			'payment_method_type_1' => $pmethod_type1,
			'payment_method_type_2' => $pmethod_type2,
			'payment_method_type_3' => $pmethod_type3,
			'description'           => $description,
			'user_id'               => $user_id,
			'shift_id'              => $shift_id,
			'timestamp'             => $timestamp
		];

		$header_data_update = [
			'guest_id' => $guest,
			// 'payment_number'        => $autonumber,
			'type_room'             => $type_room,
			'type_consumption'      => $type_cons,
			'payment_date'          => change_format_date($date),
			'total_price'           => trims($total_price),
			'total_consumption'     => trims($total_consumption),
			'total_service'         => trims($total_service),
			'total_discount'        => trims($total_discount),
			'total_deposit'         => trims($total_deposit),
			'total_deposit_2'       => trims($total_deposit_2),
			'total_tax'             => trims($total_tax),
			'total_amount'          => trims($total_amount),
			'total_paid_1'          => trims($total_paid_1),
			'total_paid_2'          => trims($ptotal2),
			'total_paid_3'          => trims($ptotal3),
			'total_room_1'          => trims($total_room_1),
			'total_room_2'          => trims($total_room_2),
			'total_room_3'          => trims($total_room_3),
			'total_service_1'       => trims($total_service_1),
			'total_service_2'       => trims($total_service_2),
			'total_service_3'       => trims($total_service_3),
			'total_consumption_1'   => trims($total_consumption_1),
			'total_consumption_2'   => trims($total_consumption_2),
			'total_consumption_3'   => trims($total_consumption_3),
			'total_deposit_kartu'   => trims($total_deposit_kartu),
			'total_deposit_tunai'   => trims($total_deposit_tunai),
			'total_deposit_trans'   => trims($total_deposit_trans),
			'payment_method_1'      => $payment_method_1,
			'payment_method_2'      => $pmethod2,
			'payment_method_3'      => $pmethod3,
			'payment_method_type_1' => $pmethod_type1,
			'payment_method_type_2' => $pmethod_type2,
			'payment_method_type_3' => $pmethod_type3,
			'description'           => $description,
			'user_id'               => $user_id,
			'shift_id'              => $shift_id,
			'timestamp'             => $timestamp
		];

		// $total_paid = (trims($total_paid_1) + trims($total_paid_2) + trims($total_paid_3));
		$total_paid = (trims($total_paid_1) + trims($total_paid_2) + trims($total_paid_3) + trims($total_deposit_2));
		$total_paid_no_min = (trims($total_paid_1) + trims($total_paid_2) + trims($total_paid_3));

		$t_paid1_no_min = trims($total_paid_1);
		$t_paid2_no_min = trims($total_paid_2);
		$t_paid3_no_min = trims($total_paid_3);
		$detail_data = [];
		$deposit_data = [];
		$t_paid = 0;
		
		// if save $id = new else update data
		if($id == "new")
		{
			// save header
			$save_header = $this->Payments_model->save_header($header_data);

			if($save_header)
			{
				$header_id = $this->db->insert_id();
				$data_save = array(
					'deposit_date'        => change_format_date($date),
					'payment_date'        => change_format_date($date),
					'guest_id'            => $guest,
					'payment_method_id'   => 0,
					'payment_method_type' => 0,
					// 'deposit_amount'      => '-'.trims($total_deposit_2),
					'deposit_amount'      => 0,
					'deposit_description' => '',
					'payment_id'          => $header_id,
				);
				// $save = $this->Deposit_model->save($data_save);
				$save = true;
			}

			if($save)
			{
				foreach($vuedata as $row)
				{

					if($row['type'] == 'C'){
						$table = 't_cs_header';
						$total_paid -= (trims($row['total']));
						if($total_paid > 0){
							$formula_total_paid = trims($row['total']) + trims($row['paid']);
							$data  = array(
								'cs_header_paid'    => ($formula_total_paid < 0 ? 0 : $formula_total_paid),
							);
							$t_paid = trims($row['total']) + trims($row['paid']);
						}else{
							$formula_total_paid = trims($row['total']) + trims($row['paid']) + ($total_paid);
							$data  = array(
								'cs_header_paid'    => ($formula_total_paid < 0 ? 0 : $formula_total_paid),
							);
							$t_paid = trims($row['total']) + ($total_paid);
						}

						$where = array('cs_header_id' => $row['id']);
						$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
						
					}

					if($row['type'] == 'T'){
						$table = 't_transaction';
						$total_paid -= (trims($row['total']));
						if($total_paid > 0){
							$formula_total_paid = trims($row['total']) + trims($row['paid']);
							$data  = array(
								'total_paid'    => ($formula_total_paid < 0 ? 0 : $formula_total_paid),
							);
							$t_paid = trims($row['total']) + trims($row['paid']);
						}else{
							$formula_total_paid = trims($row['total']) + trims($row['paid']) + ($total_paid);
							$data  = array(
								'total_paid'    => ($formula_total_paid < 0 ? 0 : $formula_total_paid),
							);
							$t_paid = trims($row['total']) + ($total_paid);
						}

						$where = array('id' => $row['id']);
						$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
					}


					$detail_data[] = [
						'header_id'          => $header_id,
						'transaction_id'     => $row['id'],
						'transaction_code'   => $row['code'],
						'transaction_number' => $row['number'],
						'transaction_type'   => $row['type'],
						'price'              => trims($row['price']),
						'discount'           => trims($row['discount']),
						'deposit'            => trims($row['deposit']),
						'total'              => trims($row['total']),
						'paid'               => ($t_paid < 0 ? 0 : $t_paid),
						// 'paid_1'             => ($t_paid1_no_min - $t_paid1),
						// 'paid_2'             => ($t_paid2_no_min - $t_paid2),
						// 'paid_3'             => ($t_paid3_no_min - $t_paid3)
					];
						
				}
				
				$save_detail = $this->Payments_model->save_detail($detail_data, true);
				
				if($save_detail)
				{	

					// Update Deposit
					if($depdata){
						foreach($depdata as $row)
						{
							$data_save = array(
								'payment_id'   => $header_id,
								'payment_date' => change_format_date($date),
							);

							$save = $this->Deposit_model->update($row['id'], $data_save);
						}
					}

					// $where_deposit = array('guest_id' => $guest, 'payment_id' => '0');
					// $guest_deposit = $this->Payments_model->guest_deposit('', $where_deposit)->result();

					// if($guest_deposit){

					// 	foreach($guest_deposit as $row_deposit)
					// 	{
					// 		$data_save = array(
					// 			'payment_id'   => $header_id,
					// 			'payment_date' => change_format_date($date),
					// 		);
					
					// 		$save = $this->Deposit_model->update($row_deposit->deposit_id, $data_save);
					// 		// $save = true;
					// 	}
					// }
					

					$msg    = "Berhasil menyimpan data";
					$status = "success";
				}
				else
				{
					$msg    = "Gagal menyimpan data";
					$status = "error";	
				}
			}
			
		}else{

			$delete_type = $this->Payments_model->delete_detail($id);

				if($delete_type){
				// 	$delete_deposit = $this->Payments_model->delete_deposit($id);
				// }
					$save_header = $this->Payments_model->update_header($id, $header_data_update);

					if($save_header)
					{	
						foreach($vuedata as $row)
						{
							
							if($row['type'] == 'C'){
								$total_paid -= (trims($row['total']));
								if($total_paid > 0){
									$t_paid = trims($row['total']) + trims($row['has_paid']);
								}else{
									$t_paid = trims($row['total']) + ($total_paid);								
									// $t_paid = trims($row['total']) + trims($row['paid']) + ($total_paid);
								}
							}

							if($row['type'] == 'T'){
								$total_paid -= (trims($row['total']));
								if($total_paid > 0){
									$t_paid = trims($row['total']) + trims($row['has_paid']);
								}else{
									$t_paid = trims($row['total']) + ($total_paid);								
									// $t_paid = trims($row['total']) + trims($row['paid']) + ($total_paid);
								}
							}

							$detail_data[] = [
								'header_id'          => $id,
								'transaction_id'     => $row['id'],
								'transaction_code'   => $row['code'],
								'transaction_number' => $row['number'],
								'transaction_type'   => $row['type'],
								'price'              => trims($row['price']),
								'discount'           => trims($row['discount']),
								'deposit'            => trims($row['deposit']),
								'total'              => trims($row['total']),
								'paid'               => ($t_paid < 0 ? 0 : $t_paid),
							];
							
						}
					
						$save_detail = $this->Payments_model->save_detail($detail_data, true);
						
						if($save_detail)
						{
							// $where    = array('header_id' => $id);
							$get_data = $this->Payments_model->payment_detail()->result();
					
							foreach($get_data as $get_row)
							{
								if($get_row->transaction_type == 'C'){
									$table        = 't_cs_header';
									$where_detail = array('transaction_id' => $get_row->transaction_id, 'transaction_type' => 'C');

									$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;

									$update_data = array(
										'cs_header_paid	' => $has_paid,
									);

									$update_where  = array('cs_header_id' => $get_row->transaction_id);
									$update_status = $this->Payments_model->update_status_paid($table, $update_data, $update_where);
								}

								if($get_row->transaction_type == 'T'){
									$table        = 't_transaction';
									$where_detail = array('transaction_id' => $get_row->transaction_id, 'transaction_type' => 'T');

									$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;

									$update_data = array(
										'total_paid' => $has_paid,
									);

									$update_where  = array('id' => $get_row->transaction_id);
									$update_status = $this->Payments_model->update_status_paid($table, $update_data, $update_where);
								}
							}


							// Update Deposit
							if($depdata){
								$data_turnoff = array(
									'payment_id'   => 0,
									'payment_date' => '0000-00-00'
								);
								$turnoff = $this->Deposit_model->update_byPaymentId($header_id, $data_turnoff);
								
								if($turnoff){
									
										foreach($depdata as $row)
										{
											$data_save = array(
												'payment_id'   => $header_id,
												'payment_date' => change_format_date($date),
											);

											$save = $this->Deposit_model->update($row['id'], $data_save);
										}
									
								}
							}
							
							$msg    = "Berhasil menyimpan data";
							$status = "success";
							
						}
						else
						{
							$msg    = "Gagal menyimpan data";
							$status = "error";	
						}
					
					}
				}
			else
			{
				$msg    = "Gagal menyimpan data";
				$status = "error";	
			}
		}

		$response = [
			'message' => $msg,
			'status'  => $status,
			'id'      => $header_id
		];

		output_json($response);
	}

	public function delete()
	{
		$id = $this->input->post('id');
		
		foreach($id as $row)
		{	

			$where       = array('header_id' => $row);

			$get_data = $this->Payments_model->payment_detail($where)->result();
			foreach($get_data as $get_row)
			{
				
				if($get_row->transaction_type == 'C'){
					$table        = 't_cs_header';
					$where_detail = array('header_id !=' => $get_row->header_id, 'transaction_id' => $get_row->transaction_id, 'transaction_type' => 'C');

					$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;

					$update_data = array(
						'cs_header_paid	' => $has_paid,
					);

					$update_where  = array('cs_header_id	' => $get_row->transaction_id);
					$update_status = $this->Payments_model->update_status_paid($table, $update_data, $update_where);
				}

				if($get_row->transaction_type == 'T'){
					$table        = 't_transaction';
					$where_detail = array('header_id !=' => $get_row->header_id, 'transaction_id' => $get_row->transaction_id, 'transaction_type' => 'T');

					$has_paid = $this->Payments_model->payment_detail_advance('coalesce(sum(paid),0) as has_paid', $where_detail)->row()->has_paid;

					$update_data = array(
						'total_paid' => $has_paid,
					);

					$update_where  = array('id' => $get_row->transaction_id);
					$update_status = $this->Payments_model->update_status_paid($table, $update_data, $update_where);
				}

			}

			$delete_header = $this->Payments_model->delete_header($row);
			
			if($delete_header){
				$delete_type = $this->Payments_model->delete_detail($row);
			}

			// if($delete_type){
			// 	$delete_deposit = $this->Payments_model->delete_deposit($row);
			// }

			if($delete_type){
				$update_deposit = $this->Payments_model->update_deposit($row);
			}
				
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function print_out($id)
	{	
		$where_header = array('purchase_order_header_id' => $id);
		$where_detail = array('purchase_order_detail_header_id' => $id);

		$header = $this->Payments_model->payment_header($where_header)->row_array();
		$detail = $this->Payments_model->payment_header($where_detail)->result();

		$data['content_title'] = 'Print Purchase Order';
		$data['header'] = $header;
		$data['detail'] = $detail;

		$this->twiggy_display('adm/payments/print_out', $data);
	}

}

?>
