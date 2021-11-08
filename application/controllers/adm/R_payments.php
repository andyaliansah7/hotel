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
		$this->load->model('Guests_model');
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
		$date_1          = $this->input->post('date_1');
		$date_2          = $this->input->post('date_2');
		$shift           = $this->input->post('shift');
		$data            = [];
		$data2           = [];
		$data_guestgroup = [];
		$data_deposit    = [];
		
		$where_header_1 = '(type_room != "" or total_service_1 != 0 or total_service_2 != 0 or total_service_3 != 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 != 1 and payment_method_1 != 3';
		$where_header_2 = '(total_consumption_1 != 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and payment_method_1 != 1 and payment_method_1 != 3';
		if(isset($shift) && $shift != '')
		{
			$where_header_1 = '(type_room != "" or total_service_1 != 0 or total_service_2 != 0 or total_service_3 != 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and shift_id = "'.$shift.'" and payment_method_1 != 1 and payment_method_1 != 3';
			$where_header_2 = '(total_consumption_1 != 0) and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and shift_id = "'.$shift.'" and payment_method_1 != 1 and payment_method_1 != 3';
		}
		$order_header   = 'payment_date ASC';
		$get_data_1     = $this->Reports_model->get_data_payment('', $where_header_1, '', $order_header)->result();
		$get_data_2     = $this->Reports_model->get_data_payment('', $where_header_2, '', $order_header)->result();

		// echo json_encode($get_data_2);
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
					if($get_row->payment_method_1 == 2){
						$total_paid_tunai_1 = 0;
					}
				}

				if($ptype_2 == 'Tunai'){
					$total_paid_tunai_2 = $get_row->total_room_2 + $get_row->total_service_2;
					if($get_row->payment_method_2 == 2){
						$total_paid_tunai_2 = 0;
					}
				}

				if($ptype_3 == 'Tunai'){
					$total_paid_tunai_3 = $get_row->total_room_3 + $get_row->total_service_3;
					if($get_row->payment_method_3 == 2){
						$total_paid_tunai_3 = 0;
					}
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

				
				$kartu = 0;
				$tunai = 0;
				$trans = 0;

				$ttl_kartu = $total_paid_kartu_1 + $total_paid_kartu_2 + $total_paid_kartu_3;
				$ttl_tunai = $total_paid_tunai_1 + $total_paid_tunai_2 + $total_paid_tunai_3;
				$ttl_trans = $total_paid_trans_1 + $total_paid_trans_2 + $total_paid_trans_3;
				
				$kartu = ($ttl_kartu) + ($get_row->total_deposit_kartu);
				$tunai = ($ttl_tunai) + ($get_row->total_deposit_tunai);
				$trans = ($ttl_trans) + ($get_row->total_deposit_trans);

				$total_deposit1 = $get_row->total_deposit_tunai + $get_row->total_deposit_trans;
				$total_deposit2 = $get_row->total_deposit_kartu + $get_row->total_deposit_trans;
				$total_deposit3 = $get_row->total_deposit_kartu + $get_row->total_deposit_tunai;

				if($get_row->total_deposit_kartu == 0 && $ttl_kartu > 0 && $ttl_tunai != 0 && $ttl_trans != 0){
					$kartu = ($ttl_kartu) - abs($total_deposit1);
				}

				if($get_row->total_deposit_tunai != 0 && $ttl_kartu != 0){
					$kartu = ($ttl_kartu) - abs($total_deposit1);
				}

				if($get_row->total_deposit_trans != 0 && $ttl_kartu != 0){
					$kartu = ($ttl_kartu) - abs($total_deposit1);
				}
				
				// if($get_row->total_deposit_kartu == 0 && $ttl_kartu > 0 && ($tunai != 0 || $trans != 0)){
				// 	$kartu = ($ttl_kartu) - abs($total_deposit1);
				// }

				
				// Tunai 
				if($get_row->total_deposit_tunai == 0 && $ttl_tunai > 0 && $ttl_kartu != 0 && $ttl_trans != 0){
					$tunai = ($ttl_tunai) - abs($total_deposit2);
				}

				if($get_row->total_deposit_kartu != 0 && $ttl_tunai != 0){
					$kartu = ($ttl_tunai) - abs($total_deposit2);
				}

				if($get_row->total_deposit_trans != 0 && $ttl_tunai != 0){
					$kartu = ($ttl_tunai) - abs($total_deposit2);
				}

				// if($get_row->total_deposit_tunai == 0 && $ttl_tunai > 0 && ($kartu != 0 || $trans != 0)){
				// 	$tunai = $ttl_tunai - $get_row->total_deposit_kartu - $get_row->total_deposit_trans;
				// }



				if($get_row->total_deposit_trans == 0 && $ttl_trans > 0 && $ttl_kartu != 0 && $ttl_tunai != 0){
					$trans = ($ttl_trans) - abs($total_deposit3);
				}

				if($get_row->total_deposit_kartu != 0 && $ttl_trans != 0){
					$kartu = ($ttl_trans) - abs($total_deposit3);
				}

				if($get_row->total_deposit_tunai != 0 && $ttl_trans != 0){
					$kartu = ($ttl_trans) - abs($total_deposit3);
				}

				// if($get_row->total_deposit_trans == 0 && $ttl_trans > 0 && ($kartu != 0 || $tunai != 0)){
				// 	$trans = ($ttl_trans) - abs($total_deposit3);
				// }




				if($get_row->total_deposit_kartu < 0){
					if($ttl_kartu > 0 && $get_row->total_deposit_kartu != 0){
						$kartu = ($ttl_kartu) - abs($total_deposit1);
					}
					elseif($ttl_kartu == 0 && $get_row->total_deposit_kartu != 0)
					{
						$kartu = abs($get_row->total_deposit_kartu);
					}
					else
					{
						$kartu = ($ttl_kartu) + abs($get_row->total_deposit_kartu) - abs($total_deposit1);
					}
				}

				if($get_row->total_deposit_tunai < 0){
					if($ttl_tunai > 0 && $get_row->total_deposit_tunai != 0){
						$tunai = ($ttl_tunai) - abs($total_deposit2);
					}
					elseif($ttl_tunai == 0 && $get_row->total_deposit_tunai != 0)
					{
						$tunai = abs($get_row->total_deposit_tunai);
					}
					else
					{
						$tunai = ($ttl_tunai) + abs($get_row->total_deposit_tunai) - abs($total_deposit2);
					}
				}

				if($get_row->total_deposit_trans < 0){
					if($ttl_trans > 0 && $get_row->total_deposit_trans != 0){
						$trans = ($ttl_trans) - abs($total_deposit3);
					}
					elseif($ttl_trans == 0 && $get_row->total_deposit_trans != 0)
					{
						$trans = abs($get_row->total_deposit_trans);
					}
					else
					{
						$trans = ($ttl_trans) + abs($get_row->total_deposit_trans) - abs($total_deposit3);
					}
				}
				
				$where_dll = array('cs_code' => 'DLL');
				$check_dll = $this->Reports_model->get_consumption_service('', $where_dll)->row();
				if($check_dll){
					$f_kartu = $kartu;
					$f_tunai = $tunai;
					$f_trans = $trans;
				}else{
					$f_kartu = abs($kartu);
					$f_tunai = abs($tunai);
					$f_trans = abs($trans);
				}
							
				// $kartu = ($kartu_b < 0 ? 0 : $kartu_b);
				// $tunai = ($tunai_b < 0 ? 0 : $tunai_b);
				// $trans = ($trans_b < 0 ? 0 : $trans_b);
				// $t_all = abs($kartu) + abs($tunai) + abs($trans);
				$t_all = $f_kartu + $f_tunai + $f_trans;

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
					'kartu'    => number_format($f_kartu),
					'tunai'    => number_format($f_tunai),
					'transfer' => number_format($f_trans),
					'total'    => number_format($t_all),
					'desc'     => $get_row->description,
					'ggroup'   => $ggroup
				);
                $no++;
                
				// $total_kartu += abs($kartu);
				// $total_tunai += abs($tunai);
				// $total_trans += abs($trans);
				// $total_all_method += abs($t_all);

				$total_kartu += ($f_kartu);
				$total_tunai += ($f_tunai);
				$total_trans += ($f_trans);
				$total_all_method += ($t_all);
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
					if($get_row->payment_method_1 == 2){
						$total_paid_tunai_1 = 0;
					}
				}

				if($get_row->payment_method_type_2 == 'Tunai'){
					$total_paid_tunai_2 = $get_row->total_consumption_2;
					if($get_row->payment_method_2 == 2){
						$total_paid_tunai_2 = 0;
					}
				}

				if($get_row->payment_method_type_3 == 'Tunai'){
					$total_paid_tunai_3 = $get_row->total_consumption_3;
					if($get_row->payment_method_3 == 2){
						$total_paid_tunai_3 = 0;
					}
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

		// $where_deposit = array('a.deposit_date >=' => $date_1, 'a.deposit_date <=' => $date_2);
		// if(isset($shift) && $shift != '')
		// {
		// 	$where_deposit = array('a.deposit_date >=' => $date_1, 'a.deposit_date <=' => $date_2, 'a.shift_id' => $shift);
		// }

		$order_deposit = 'a.payment_date ASC';
		$get_deposit   = $this->Reports_model->get_deposit_advance($date_1, $date_2, $shift)->result();

		// echo json_encode($get_deposit);
		// die();
		$total_deposit = 0;

		if($get_deposit)
		{	
			$no = 1;
			foreach($get_deposit as $get_row)
			{	

				$kartu = abs($get_row->total_kartu);
				$tunai = abs($get_row->total_tunai);
				$trans = abs($get_row->total_trans);

				if($get_row->type == "P"){
					$kartu = - abs($get_row->total_kartu);
					$tunai = - abs($get_row->total_tunai);
					$trans = - abs($get_row->total_trans);
				}

				$guest_name = '';
				$get_data_guest = $this->Guests_model->get_data(array('guest_id' => $get_row->guest_id))->row();
				if($get_data_guest){
					$guest_name = $get_data_guest->guest_name;
				}
				$total_dep = ($kartu) + ($tunai) + ($trans);
				$data_deposit[] = array(
					'no'             => $no,
					'id'             => $get_row->id,
					'guest_name'     => $guest_name,
					'amount'         => number_format($total_dep),
					'total_kartu'    => number_format($kartu),
					'total_tunai'    => number_format($tunai),
					'total_transfer' => number_format($trans),
					'total'          => number_format($total_dep),
					'desc'           => $get_row->description
				);
				$no++;

				
				$total_kartu_dep += $kartu;
				$total_tunai_dep += $tunai;
				$total_trans_dep += $trans;
				$total_all_method_dep += $total_dep;
				
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

		$data_save = array(
			'date'  => $date_1,
			'kartu' => ($total_kartu + $total_kartu2 + $total_kartu_dep) - ($total_kartu_fee),
			'tunai' => ($total_tunai + $total_tunai2 + $total_tunai_dep) - ($total_tunai_fee),
			'trans' => ($total_trans + $total_trans2 + $total_trans_dep) - ($total_trans_fee),
			'total' => ($total_all_method + $total_all_method2 + $total_all_method_dep) - ($total_all_method_fee),
			'shift' => $shift
		);

		

		if($date_1 == $date_2 && $shift != '') {
			
			$get_dayrecap = $this->Reports_model->get_dayrecap('*', array('date' => $date_1, 'shift' => $shift))->row();
			if($get_dayrecap){
				$save = $this->Reports_model->update_dayrecap($get_dayrecap->id, $data_save);
			}else{
				$save = $this->Reports_model->save_dayrecap($data_save);
			}

			$data_py   = [];
			$select_py = 'payment_method_1, payment_method_type_1, payment_method_2, payment_method_type_2, payment_method_3, payment_method_type_3, SUM(total_paid_1) as ttl, SUM(total_paid_2) as ttl2, SUM(total_paid_3) as ttl3';
			$where_py  = array('payment_date' => $date_1, 'shift_id' => $shift);
			$group_py  = 'payment_date';
			$get_py    = $this->Reports_model->get_data_payment_lite($select_py, $where_py, $group_py)->result();

			if($get_py)
			{	
				$no = 1;
				foreach($get_py as $get_row)
				{	

					$data_py[] = array(
						'date'           => $date_1,
						'method_id'      => $get_row->payment_method_1,
						'total'          => $get_row->ttl,
						'shift'          => $shift,
					);

					$data_py[] = array(
						'date'           => $date_1,
						'method_id'      => $get_row->payment_method_2,
						'total'          => $get_row->ttl2,
						'shift'          => $shift,
					);

					$data_py[] = array(
						'date'           => $date_1,
						'method_id'      => $get_row->payment_method_3,
						'total'          => $get_row->ttl3,
						'shift'          => $shift,
					);
					
				}

				// echo json_encode($data_py);

				$get_dayrecap2 = $this->Reports_model->get_dayrecap2('*', array('date' => $date_1, 'shift' => $shift))->row();
				if($get_dayrecap2){
					$delete = $this->Reports_model->delete_dayrecap2(array('date' => $date_1, 'shift' => $shift));
				}

				$save2 = $this->Reports_model->save_dayrecap2($data_py);
			}
		
		}

		output_json($response);
	}

}

?>
