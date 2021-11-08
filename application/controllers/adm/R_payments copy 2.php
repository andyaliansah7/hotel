<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_payments extends BaseController
{
	/**
	 * Constructor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Reports_model');
		$this->load->model('Payment_methods_model');
		$this->load->model('Checkin_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title'] = 'Laporan - Kasir';

		$data['shift_data']       = $this->Checkin_model->get_data_shift()->result();
		
		$this->twiggy_display('adm/r_payments/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1      = $this->input->post('date_1');
		$date_2      = $this->input->post('date_2');
		$shift       = $this->input->post('shift');
		$data        = [];
		$data2       = [];
		$data_guestgroup = [];
		$data_deposit = [];
		
		$where_header_1 = '(total_room_1 > 0 or total_service_1 > 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 != 1 and payment_method_1 != 2 and payment_method_1 != 3';
		$where_header_2 = '(total_consumption_1 > 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 != 1 and payment_method_1 != 2 and payment_method_1 != 3';
		if(isset($shift) && $shift != '')
		{
			$where_header_1 = '(total_room_1 > 0 or total_service_1 > 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and shift_id = "'.$shift.'" and payment_method_1 != 1 and payment_method_1 != 2 and payment_method_1 != 3';
			$where_header_2 = '(total_consumption_1 > 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and shift_id = "'.$shift.'" and payment_method_1 != 1 and payment_method_1 != 2 and payment_method_1 != 3';
		}
		$order_header   = 'payment_date ASC';
		$get_data_1     = $this->Reports_model->get_data_payment('', $where_header_1, '', $order_header)->result();
		$get_data_2     = $this->Reports_model->get_data_payment('', $where_header_2, '', $order_header)->result();

		// echo json_encode($get_data_1);
		// die();

		$total_kartu = 0;
		$total_tunai = 0;
		$total_trans = 0;
		$total_all_method = 0;

		$total_kartu_fee = 0;
		$total_tunai_fee = 0;
		$total_trans_fee = 0;
		$total_all_method_fee = 0;

		$total_kartu_dep = 0;
		$total_tunai_dep = 0;
		$total_trans_dep = 0;
		$total_all_method_dep = 0;

		$total_kartu2 = 0;
		$total_tunai2 = 0;
		$total_trans2 = 0;
		$total_all_method2 = 0;

		if($get_data_1)
		{	
			$no = 1;
			foreach($get_data_1 as $get_row)
			{	
				$where_1 = array('payment_method_id' => $get_row->payment_method_1);
				$getme_1  = $this->Payment_methods_model->get_data($where_1)->row();
				if($getme_1){
					$ptype_1  = $getme_1->payment_method_type;
				}

				$where_2 = array('payment_method_id' => $get_row->payment_method_2);
				$getme_2  = $this->Payment_methods_model->get_data($where_2)->row();
				if($getme_2){
					$ptype_2  = $getme_2->payment_method_type;
				}

				$where_3 = array('payment_method_id' => $get_row->payment_method_3);
				$getme_3  = $this->Payment_methods_model->get_data($where_3)->row();
				if($getme_3){
					$ptype_3  = $getme_3->payment_method_type;
				}

				$total_paid_kartu_1 = 0;
				$total_paid_kartu_2 = 0;
				$total_paid_kartu_3 = 0;
				$total_paid_tunai_1 = 0;
				$total_paid_tunai_2 = 0;
				$total_paid_tunai_3 = 0;
				$total_paid_trans_1 = 0;
				$total_paid_trans_2 = 0;
				$total_paid_trans_3 = 0;

				$total_dep_kartu = 0;
				$total_dep_tunai = 0;
				$total_dep_trans = 0;
				
				if($ptype_1 == 'Kartu'){
					$total_paid_kartu_1 = $get_row->total_room_1 + $get_row->total_service_1;
				}

				if($ptype_2 == 'Kartu'){
					$total_paid_kartu_2 = $get_row->total_room_2 + $get_row->total_service_2;
				}

				if($ptype_3 == 'Kartu'){
					$total_paid_kartu_3 = $get_row->total_room_3 + $get_row->total_service_3;
				}

				if($ptype_1 == 'Tunai'){
					$total_paid_tunai_1 = $get_row->total_room_1 + $get_row->total_service_1;
				}

				if($ptype_2 == 'Tunai'){
					$total_paid_tunai_2 = $get_row->total_room_2 + $get_row->total_service_2;
				}

				if($ptype_3 == 'Tunai'){
					$total_paid_tunai_3 = $get_row->total_room_3 + $get_row->total_service_3;
				}

				if($ptype_1 == 'Transfer'){
					$total_paid_trans_1 = $get_row->total_room_1 + $get_row->total_service_1;
				}

				if($ptype_2 == 'Transfer'){
					$total_paid_trans_2 = $get_row->total_room_2 + $get_row->total_service_2;
				}

				if($ptype_3 == 'Transfer'){
					$total_paid_trans_3 = $get_row->total_room_3 + $get_row->total_service_3;
				}

				$where_dep_kartu = array('a.payment_id' => $get_row->header_id, 'a.payment_method_type' => 'Kartu');
				$getda_dep_kartu = $this->Reports_model->get_data_deposit('*, IF(deposit_amount = "0", total_deposit_2, deposit_amount) as total_dep_kartu', $where_dep_kartu)->row();
				
				$where_dep_tunai = array('a.payment_id' => $get_row->header_id, 'a.payment_method_type' => 'Tunai');
				$getda_dep_tunai = $this->Reports_model->get_data_deposit('*, IF(deposit_amount = "0", total_deposit_2, deposit_amount) as total_dep_tunai', $where_dep_tunai)->row();
				
				$where_dep_trans = array('a.payment_id' => $get_row->header_id, 'a.payment_method_type' => 'Transfer');
				$getda_dep_trans = $this->Reports_model->get_data_deposit('*, IF(deposit_amount = "0", total_deposit_2, deposit_amount) as total_dep_trans', $where_dep_trans)->row();
				


				if($getda_dep_kartu){
					$total_dep_kartu = abs($getda_dep_kartu->total_dep_kartu);
					if($getda_dep_kartu->total_dep_kartu == 0){
						$total_dep_kartu = ($getda_dep_trans->total_dep_kartu);
					}
				}
				if($getda_dep_tunai){
					$total_dep_tunai = abs($getda_dep_tunai->total_dep_tunai);
					if($getda_dep_tunai->total_dep_tunai == 0){
						$total_dep_tunai = ($getda_dep_tunai->total_dep_tunai);
					}
				}
				if($getda_dep_trans){
					$total_dep_trans = abs($getda_dep_trans->total_dep_trans);
					if($getda_dep_trans->total_dep_trans == 0){
						$total_dep_trans = ($getda_dep_trans->total_dep_trans);
					}
				}
				
				
				
				$kartu_b = ($total_paid_kartu_1 + $total_paid_kartu_2 + $total_paid_kartu_3) - ($total_dep_tunai + $total_dep_trans) + $total_dep_kartu;
				$tunai_b = ($total_paid_tunai_1 + $total_paid_tunai_2 + $total_paid_tunai_3) - ($total_dep_kartu + $total_dep_trans) + $total_dep_tunai;
				$trans_b = ($total_paid_trans_1 + $total_paid_trans_2 + $total_paid_trans_3) - ($total_dep_kartu + $total_dep_tunai) + $total_dep_trans;

				// $kartu_b = ($total_paid_kartu_1 + $total_paid_kartu_2 + $total_paid_kartu_3);
				// $tunai_b = ($total_paid_tunai_1 + $total_paid_tunai_2 + $total_paid_tunai_3);
				// $trans_b = ($total_paid_trans_1 + $total_paid_trans_2 + $total_paid_trans_3);

				if($total_dep_kartu > 0){
				$kartu_b = ($total_paid_kartu_1 + $total_paid_kartu_2 + $total_paid_kartu_3 - $total_dep_kartu) - ($total_dep_tunai + $total_dep_trans) + ($total_dep_kartu);
				}
				if($total_dep_kartu > 0){
				$tunai_b = ($total_paid_tunai_1 + $total_paid_tunai_2 + $total_paid_tunai_3 - $total_dep_tunai) - ($total_dep_kartu + $total_dep_trans) + ($total_dep_tunai);
				}
				if($total_dep_trans > 0){
				$trans_b = ($total_paid_trans_1 + $total_paid_trans_2 + $total_paid_trans_3 - $total_dep_trans) - ($total_dep_kartu + $total_dep_tunai) + ($total_dep_trans);
				}

				$kartu = ($kartu_b < 0 ? 0 : $kartu_b);
				$tunai = ($tunai_b < 0 ? 0 : $tunai_b);
				$trans = ($trans_b < 0 ? 0 : $trans_b);
				$t_all = $kartu + $tunai + $trans;

				$ggroup = '';
				$ggroup_get = $this->Reports_model->get_data_payment_detail2('', array('b.header_id' => $get_row->header_id))->row();
				
				if($ggroup_get){
					$ggroup = $ggroup_get->guest_group_name;
				}
				$data[] = array(
					'no'       => $no,
					'guest'    => ($get_row->guest_id == 0 ? 'Bukan Tamu Menginap' : $get_row->guest_name),
					'number'   => $get_row->payment_number,
					'date'     => $get_row->payment_date,
					'kartu'    => number_format($kartu),
					'tunai'    => number_format($tunai),
					'transfer' => number_format($trans),
					'total'    => number_format($t_all),
					'desc'     => $get_row->description,
					'ggroup'   => $ggroup
				);
                $no++;
                
				$total_kartu += $kartu;
				$total_tunai += $tunai;
				$total_trans += $trans;
				$total_all_method += $t_all;
			}
		}

		if($get_data_2)
		{	
			$no = 1;
			foreach($get_data_2 as $get_row)
			{	
				// $ptype_1 = '';
				// $ptype_2 = '';
				// $ptype_3 = '';

				$where_1 = array('payment_method_id' => $get_row->payment_method_1);
				$getme_1  = $this->Payment_methods_model->get_data($where_1)->row();
				if($getme_1){
					$ptype_1  = $getme_1->payment_method_type;
				}

				$where_2 = array('payment_method_id' => $get_row->payment_method_2);
				$getme_2  = $this->Payment_methods_model->get_data($where_2)->row();
				if($getme_2){
					$ptype_2  = $getme_2->payment_method_type;
				}

				$where_3 = array('payment_method_id' => $get_row->payment_method_3);
				$getme_3  = $this->Payment_methods_model->get_data($where_3)->row();
				if($getme_3){
					$ptype_3  = $getme_3->payment_method_type;
				}

				$total_paid_kartu_1 = 0;
				$total_paid_kartu_2 = 0;
				$total_paid_kartu_3 = 0;
				$total_paid_tunai_1 = 0;
				$total_paid_tunai_2 = 0;
				$total_paid_tunai_3 = 0;
				$total_paid_trans_1 = 0;
				$total_paid_trans_2 = 0;
				$total_paid_trans_3 = 0;

				if($get_row->payment_method_type_1 == 'Kartu'){
					$total_paid_kartu_1 = $get_row->total_consumption_1;
				}

				if($get_row->payment_method_type_2 == 'Kartu'){
					$total_paid_kartu_2 = $get_row->total_consumption_2;
				}

				if($get_row->payment_method_type_3 == 'Kartu'){
					$total_paid_kartu_3 = $get_row->total_consumption_3;
				}

				if($get_row->payment_method_type_1 == 'Tunai'){
					$total_paid_tunai_1 = $get_row->total_consumption_1;
				}

				if($get_row->payment_method_type_2 == 'Tunai'){
					$total_paid_tunai_2 = $get_row->total_consumption_2;
				}

				if($get_row->payment_method_type_3 == 'Tunai'){
					$total_paid_tunai_3 = $get_row->total_consumption_3;
				}

				if($get_row->payment_method_type_1 == 'Transfer'){
					$total_paid_trans_1 = $get_row->total_consumption_1;
				}

				if($get_row->payment_method_type_2 == 'Transfer'){
					$total_paid_trans_2 = $get_row->total_consumption_2;
				}

				if($get_row->payment_method_type_3 == 'Transfer'){
					$total_paid_trans_3 = $get_row->total_consumption_3;
				}

				$kartu = $total_paid_kartu_1 + $total_paid_kartu_2 + $total_paid_kartu_3;
				$tunai = $total_paid_tunai_1 + $total_paid_tunai_2 + $total_paid_tunai_3;
				$trans = $total_paid_trans_1 + $total_paid_trans_2 + $total_paid_trans_3;
				$t_all = $kartu + $tunai + $trans;

				$data2[] = array(
					'no'       => $no,
					'guest'    => ($get_row->guest_id == 0 ? 'Bukan Tamu Menginap' : $get_row->guest_name),
					'number'   => $get_row->payment_number,
					'date'     => $get_row->payment_date,
					'kartu'    => number_format($kartu),
					'tunai'    => number_format($tunai),
					'transfer' => number_format($trans),
					'total'    => number_format($t_all),
					'desc'     => $get_row->description,
				);
                $no++;
                
				$total_kartu2 += $kartu;
				$total_tunai2 += $tunai;
				$total_trans2 += $trans;
				$total_all_method2 += $t_all;
			}
		}
		
		$where_guestgroup = array('guest_group_fee >' => 0);
		$order_guestgroup = 'guest_group_name ASC';
		$get_guestgroup   = $this->Reports_model->get_guest_group('', $where_guestgroup, $order_guestgroup)->result();

		$total_guestgroup = 0;

		if($get_guestgroup)
		{	
			$no = 1;
			foreach($get_guestgroup as $get_row)
			{	
				$select_kartu_1    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_kartu_2    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_kartu_3    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_tunai_1    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_tunai_2    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_tunai_3    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_transfer_1 = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_transfer_2 = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_transfer_3 = 'a.*, b.*, c.*, SUM(a.paid) as total';

					$where_kartu_1    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_kartu_2    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_kartu_3    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_tunai_1    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_tunai_2    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_tunai_3    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_transfer_1 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_transfer_2 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_transfer_3 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				if(isset($shift) && $shift != '')
				{
					$where_kartu_1    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_kartu_2    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_kartu_3    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_tunai_1    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_tunai_2    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_tunai_3    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_transfer_1 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_transfer_2 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
					$where_transfer_3 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				}

				$total_kartu_1  = $this->Reports_model->get_data_payment_detail($select_kartu_1, $where_kartu_1)->row()->total;
				$total_kartu_2  = $this->Reports_model->get_data_payment_detail($select_kartu_2, $where_kartu_2)->row()->total;
				$total_kartu_3  = $this->Reports_model->get_data_payment_detail($select_kartu_3, $where_kartu_3)->row()->total;
				$kartu    = ($total_kartu_1) * ($get_row->guest_group_fee/100);

				$total_tunai_1  = $this->Reports_model->get_data_payment_detail($select_tunai_1, $where_tunai_1)->row()->total;
				$total_tunai_2  = $this->Reports_model->get_data_payment_detail($select_tunai_2, $where_tunai_2)->row()->total;
				$total_tunai_3  = $this->Reports_model->get_data_payment_detail($select_tunai_3, $where_tunai_3)->row()->total;
				$tunai    = ($total_tunai_1) * ($get_row->guest_group_fee/100);				
				
				$total_transfer_1  = $this->Reports_model->get_data_payment_detail($select_transfer_1, $where_transfer_1)->row()->total;
				$total_transfer_2  = $this->Reports_model->get_data_payment_detail($select_transfer_2, $where_transfer_2)->row()->total;
				$total_transfer_3  = $this->Reports_model->get_data_payment_detail($select_transfer_3, $where_transfer_3)->row()->total;
				$transfer    = ($total_transfer_1) * ($get_row->guest_group_fee/100);
				
				$t_all = $kartu + $tunai + $transfer;
				$data_guestgroup[] = array(
					'no'               => $no,
					'guest_group_id'   => $get_row->guest_group_id,
					'guest_group_name' => $get_row->guest_group_name,
					'guest_group_fee'  => number_format($get_row->guest_group_fee). " %",
					'total_kartu'      => number_format($kartu),
					'total_tunai'      => number_format($tunai),
					'total_transfer'   => number_format($transfer),
					'total'            => number_format($t_all),
					'desc'             => ''
				);
				$no++;

				
				$total_kartu_fee += $kartu;
				$total_tunai_fee += $tunai;
				$total_trans_fee += $transfer;
				$total_all_method_fee += $t_all;
				
				
			}
		}

		$where_deposit = array('a.deposit_date >=' => $date_1, 'a.deposit_date <=' => $date_2, '(select IF(deposit_amount = "0", total_deposit_2, deposit_amount)) !=' => 0);
		if(isset($shift) && $shift != '')
		{
			// $where_deposit = array('a.deposit_date >=' => $date_1, 'a.deposit_date <=' => $date_2, 'a.shift_id' => $shift, '(select IF(deposit_amount = "0", total_deposit_2, deposit_amount)) !=' => 0);
			$where_deposit = array('a.deposit_date >=' => $date_1, 'a.deposit_date <=' => $date_2, '(select IF(a.shift_id = "0", d.shift_id, a.shift_id)) =' => $shift, '(select IF(deposit_amount = "0", total_deposit_2, deposit_amount)) !=' => 0);
		}
		$order_deposit = 'a.payment_date ASC';
		$get_deposit   = $this->Reports_model->get_data_deposit2('*, count(deposit_id) as depid', $where_deposit)->result();

		// echo json_encode($get_deposit);
		// die();
		$total_deposit = 0;

		if($get_deposit)
		{	
			$no = 1;
			foreach($get_deposit as $get_row)
			{	
				$kartu_dep    = 0;
				$tunai_dep    = 0;
				$transfer_dep = 0;

				$pm_type = $get_row->payment_method_type;
				$dp_amnt = abs($get_row->deposit_amount);
				// $dp_amnt = abs($get_row->dpm);
				if($get_row->deposit_amount == 0 or $get_row->depid > 1){
					$pm_type = $this->Reports_model->get_data_deposit('', array('a.payment_id' => $get_row->payment_id, 'a.payment_method_id !=' => 0))->row()->payment_method_type;
					$dp_amnt = $get_row->total_deposit_2;
				}
				

				if($pm_type == 'Kartu'){
					$kartu_dep = $dp_amnt;
				}

				if($pm_type == 'Tunai'){
					$tunai_dep = $dp_amnt;
				}

				if($pm_type == 'Transfer'){
					$transfer_dep = $dp_amnt;
				}

				$t_all_dep = $kartu_dep + $tunai_dep + $transfer_dep;
				$data_deposit[] = array(
					'no'             => $no,
					'id'             => $get_row->deposit_id,
					'guest_name'     => $get_row->guest_name,
					'amount'         => number_format($dp_amnt),
					'total_kartu'    => number_format($kartu_dep),
					'total_tunai'    => number_format($tunai_dep),
					'total_transfer' => number_format($transfer_dep),
					'total'          => number_format($t_all_dep),
					'desc'           => $get_row->deposit_description
				);
				$no++;

				
				$total_kartu_dep += $kartu_dep;
				$total_tunai_dep += $tunai_dep;
				$total_trans_dep += $transfer_dep;
				$total_all_method_dep += $t_all_dep;
				
			}
		}

		$response = [
			'date_1'                 => change_format_date($date_1, 'd/m/Y'),
			'date_2'                 => change_format_date($date_2, 'd/m/Y'),
			'data'                   => $data,
			'data2'                  => $data2,
			'data_guestgroup'        => $data_guestgroup,
			'data_deposit'           => $data_deposit,
			'total_kartu'            => number_format($total_kartu),
			'total_tunai'            => number_format($total_tunai),
			'total_trans'            => number_format($total_trans),
			'total_all_method'       => number_format($total_all_method),
			'total_kartu2'           => number_format($total_kartu2),
			'total_tunai2'           => number_format($total_tunai2),
			'total_trans2'           => number_format($total_trans2),
			'total_all_method2'      => number_format($total_all_method2),
			'total_kartu_fee'        => number_format($total_kartu_fee),
			'total_tunai_fee'        => number_format($total_tunai_fee),
			'total_trans_fee'        => number_format($total_trans_fee),
			'total_all_method_fee'   => number_format($total_all_method_fee),
			'total_kartu_dep'        => number_format($total_kartu_dep),
			'total_tunai_dep'        => number_format($total_tunai_dep),
			'total_trans_dep'        => number_format($total_trans_dep),
			'total_all_method_dep'   => number_format($total_all_method_dep),
			'total_kartu_grand'      => number_format(($total_kartu + $total_kartu2 + $total_kartu_dep) - ($total_kartu_fee)),
			'total_tunai_grand'      => number_format(($total_tunai + $total_tunai2 + $total_tunai_dep) - ($total_tunai_fee)),
			'total_trans_grand'      => number_format(($total_trans + $total_trans2 + $total_trans_dep) - ($total_trans_fee)),
			'total_all_method_grand' => number_format(($total_all_method + $total_all_method2 + $total_all_method_dep) - ($total_all_method_fee)),
		];

		output_json($response);
	}

}

?>
