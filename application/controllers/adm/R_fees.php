<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_fees extends BaseController
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
		$this->load->model('Checkin_model');
		$this->load->model('Payment_methods_model');
		$this->load->model('Consumption_services_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title']  = 'Laporan - Fee';
        // $data['shift_data']     = $this->Checkin_model->get_data_shift()->result();
        // $data['paymethod_data'] = $this->Payment_methods_model->get_data()->result();
		
		$this->twiggy_display('adm/r_fees/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1    = $this->input->post('date_1');
		$date_2    = $this->input->post('date_2');
		$group     = $this->input->post('group');
		$data      = [];
		$where     = [];

		if(isset($date_1) && $date_1 != '')
		{
			$where['cs_header_date >='] = $date_1;
		}

		if(isset($date_2) && $date_2 != '')
		{
			$where['cs_header_date <='] = $date_2;
		}

		if(isset($group) && $group != '')
		{
			$where['cs_detail_item_type'] = $group;
		}
		
		$where_guestgroup = array('guest_group_fee >' => 0);
		$order_guestgroup = 'guest_group_name ASC';
		$get_guestgroup   = $this->Reports_model->get_guest_group('', $where_guestgroup, $order_guestgroup)->result();

		// echo json_encode($get_guestgroup);
		// die();
		$total_quantity = 0;
		$total_amount = 0;

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
				
				// if(isset($shift) && $shift != '')
				// {
				// 	$where_kartu_1    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_kartu_2    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_kartu_3    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Kartu" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_tunai_1    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_tunai_2    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_tunai_3    = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Tunai" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_transfer_1 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_1 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_transfer_2 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_2 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// 	$where_transfer_3 = 'c.guest_group_id = "'.$get_row->guest_group_id.'" and b.payment_method_type_3 = "Transfer" and payment_date >= "'.$date_1.'" and payment_date <= "'.$date_2.'" and b.shift_id = "'.$shift.'" and payment_method_1 <> "1" and payment_method_1 <> "2" and payment_method_1 <> "3"';
				// }

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
				$data[] = array(
					'no'             => $no,
					'guest_group_id' => $get_row->guest_group_id,
					'item'           => $get_row->guest_group_name,
					'qty'            => number_format($get_row->guest_group_fee). " %",
					'total_kartu'    => number_format($kartu),
					'total_tunai'    => number_format($tunai),
					'total_transfer' => number_format($transfer),
					'total'          => number_format($t_all),
					'desc'           => ''
				);
				$no++;

				
				$total_quantity += $get_row->guest_group_fee;
				$total_amount   += $t_all;
				
				
			}
		}


		$response = [
			'date_1'         => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2'         => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'data'           => $data,
			'total_quantity' => number_format($total_quantity) ."%",
			'total_amount'   => number_format($total_amount),
		];

		output_json($response);
	}

}

?>
