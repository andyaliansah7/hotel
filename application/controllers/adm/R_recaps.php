<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_recaps extends BaseController
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
		$this->load->model('Rooms_model');
		$this->load->model('Cs_groups_model');
		$this->load->model('Consumption_services_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title'] = 'Laporan - Rekap';

		// $data['shift_data']       = $this->Checkin_model->get_data_shift()->result();
		$this->twiggy_display('adm/r_recaps/index', $data);
	}

	public function get_data_detail()
	{	
		$year_filter      = $this->input->post('year_filter');
		// $date_2      = $this->input->post('date_2');
		// $shift       = $this->input->post('shift');
		
		$data_room      = [];
		$data_meeting   = [];
		$data_poolside  = [];
		$data_resto     = [];
		$data_mischarge = [];
		$data_swimming  = [];
		
		$tc_jan = $ts_jan = $tc_feb = $ts_feb = $tc_mar = $ts_mar = $tc_apr = $ts_apr = $tc_mei = $ts_mei = $tc_jun = $ts_jun = 0;
		$tc_jul = $ts_jul = $tc_agu = $ts_agu = $tc_sep = $ts_sep = $tc_okt = $ts_okt = $tc_nov = $ts_nov = $tc_des = $ts_des = 0;

		$ttl_jan = $ttl_feb = $ttl_mar = $ttl_apr = $ttl_mei = $ttl_jun = 0;
		$ttl_jul = $ttl_agu = $ttl_sep = $ttl_okt = $ttl_nov = $ttl_des = 0;

		$tc_mee_jan = $ts_mee_jan = $tc_mee_feb = $ts_mee_feb = $tc_mee_mar = $ts_mee_mar = $tc_mee_apr = $ts_mee_apr = $tc_mee_mei = $ts_mee_mei = $tc_mee_jun = $ts_mee_jun = 0;
		$tc_mee_jul = $ts_mee_jul = $tc_mee_agu = $ts_mee_agu = $tc_mee_sep = $ts_mee_sep = $tc_mee_okt = $ts_mee_okt = $tc_mee_nov = $ts_mee_nov = $tc_mee_des = $ts_mee_des = 0;

		$tc_poo_jan = $ts_poo_jan = $tc_poo_feb = $ts_poo_feb = $tc_poo_mar = $ts_poo_mar = $tc_poo_apr = $ts_poo_apr = $tc_poo_mei = $ts_poo_mei = $tc_poo_jun = $ts_poo_jun = 0;
		$tc_poo_jul = $ts_poo_jul = $tc_poo_agu = $ts_poo_agu = $tc_poo_sep = $ts_poo_sep = $tc_poo_okt = $ts_poo_okt = $tc_poo_nov = $ts_poo_nov = $tc_poo_des = $ts_poo_des = 0;
		
		$tc_res_jan = $ts_res_jan = $tc_res_feb = $ts_res_feb = $tc_res_mar = $ts_res_mar = $tc_res_apr = $ts_res_apr = $tc_res_mei = $ts_res_mei = $tc_res_jun = $ts_res_jun = 0;
		$tc_res_jul = $ts_res_jul = $tc_res_agu = $ts_res_agu = $tc_res_sep = $ts_res_sep = $tc_res_okt = $ts_res_okt = $tc_res_nov = $ts_res_nov = $tc_res_des = $ts_res_des = 0;
		
		$tc_mis_jan = $ts_mis_jan = $tc_mis_feb = $ts_mis_feb = $tc_mis_mar = $ts_mis_mar = $tc_mis_apr = $ts_mis_apr = $tc_mis_mei = $ts_mis_mei = $tc_mis_jun = $ts_mis_jun = 0;
		$tc_mis_jul = $ts_mis_jul = $tc_mis_agu = $ts_mis_agu = $tc_mis_sep = $ts_mis_sep = $tc_mis_okt = $ts_mis_okt = $tc_mis_nov = $ts_mis_nov = $tc_mis_des = $ts_mis_des = 0;
		
		$tc_swi_jan = $ts_swi_jan = $tc_swi_feb = $ts_swi_feb = $tc_swi_mar = $ts_swi_mar = $tc_swi_apr = $ts_swi_apr = $tc_swi_mei = $ts_swi_mei = $tc_swi_jun = $ts_swi_jun = 0;
		$tc_swi_jul = $ts_swi_jul = $tc_swi_agu = $ts_swi_agu = $tc_swi_sep = $ts_swi_sep = $tc_swi_okt = $ts_swi_okt = $tc_swi_nov = $ts_swi_nov = $tc_swi_des = $ts_swi_des = 0;
		
		$order_guestgroup = 'guest_group_name ASC';
		$get_guestgroup   = $this->Reports_model->get_guest_group('', '', $order_guestgroup)->result();

		if($get_guestgroup)
		{	
			$no = 1;
			foreach($get_guestgroup as $get_row)
			{	
				// $select    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select    = 'COALESCE(SUM(a.paid), 0) as total';
				$where_jan = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '1', 'YEAR(payment_date)' => $year_filter);
				$where_feb = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '2', 'YEAR(payment_date)' => $year_filter);
				$where_mar = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '3', 'YEAR(payment_date)' => $year_filter);
				$where_apr = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '4', 'YEAR(payment_date)' => $year_filter);
				$where_mei = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '5', 'YEAR(payment_date)' => $year_filter);
				$where_jun = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '6', 'YEAR(payment_date)' => $year_filter);
				$where_jul = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '7', 'YEAR(payment_date)' => $year_filter);
				$where_agu = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '8', 'YEAR(payment_date)' => $year_filter);
				$where_sep = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '9', 'YEAR(payment_date)' => $year_filter);
				$where_okt = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '10', 'YEAR(payment_date)' => $year_filter);
				$where_nov = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '11', 'YEAR(payment_date)' => $year_filter);
				$where_des = array('c.guest_group_id' => $get_row->guest_group_id, 'MONTH(payment_date)' => '12', 'YEAR(payment_date)' => $year_filter);
					
				$cou_jan   = count($this->Reports_model->get_data_payment_detail('*', $where_jan)->result()); 
				$sum_jan   = $this->Reports_model->get_data_payment_detail($select, $where_jan)->row()->total;
				$cou_feb   = count($this->Reports_model->get_data_payment_detail('*', $where_feb)->result()); 
				$sum_feb   = $this->Reports_model->get_data_payment_detail($select, $where_feb)->row()->total;
				$cou_mar   = count($this->Reports_model->get_data_payment_detail('*', $where_mar)->result()); 
				$sum_mar   = $this->Reports_model->get_data_payment_detail($select, $where_mar)->row()->total;
				$cou_apr   = count($this->Reports_model->get_data_payment_detail('*', $where_apr)->result()); 
				$sum_apr   = $this->Reports_model->get_data_payment_detail($select, $where_apr)->row()->total;
				$cou_mei   = count($this->Reports_model->get_data_payment_detail('*', $where_mei)->result()); 
				$sum_mei   = $this->Reports_model->get_data_payment_detail($select, $where_mei)->row()->total;
				$cou_jun   = count($this->Reports_model->get_data_payment_detail('*', $where_jun)->result()); 
				$sum_jun   = $this->Reports_model->get_data_payment_detail($select, $where_jun)->row()->total;
				$cou_jul   = count($this->Reports_model->get_data_payment_detail('*', $where_jul)->result()); 
				$sum_jul   = $this->Reports_model->get_data_payment_detail($select, $where_jul)->row()->total;
				$cou_agu   = count($this->Reports_model->get_data_payment_detail('*', $where_agu)->result()); 
				$sum_agu   = $this->Reports_model->get_data_payment_detail($select, $where_agu)->row()->total;
				$cou_sep   = count($this->Reports_model->get_data_payment_detail('*', $where_sep)->result()); 
				$sum_sep   = $this->Reports_model->get_data_payment_detail($select, $where_sep)->row()->total;
				$cou_okt   = count($this->Reports_model->get_data_payment_detail('*', $where_okt)->result()); 
				$sum_okt   = $this->Reports_model->get_data_payment_detail($select, $where_okt)->row()->total;
				$cou_nov   = count($this->Reports_model->get_data_payment_detail('*', $where_nov)->result()); 
				$sum_nov   = $this->Reports_model->get_data_payment_detail($select, $where_nov)->row()->total;
				$cou_des   = count($this->Reports_model->get_data_payment_detail('*', $where_des)->result()); 
				$sum_des   = $this->Reports_model->get_data_payment_detail($select, $where_des)->row()->total; 
				 
				$data_room[] = array(
					'no'         => $no,
					'group_name' => $get_row->guest_group_name,
					'cou_jan'    => ($cou_jan == 0 ? '' : $cou_jan),
					'sum_jan'    => ($sum_jan == 0 ? '' : number_format($sum_jan)),
					'cou_feb'    => ($cou_feb == 0 ? '' : number_format($cou_feb)),
					'sum_feb'    => ($sum_feb == 0 ? '' : number_format($sum_feb)),
					'cou_mar'    => ($cou_mar == 0 ? '' : number_format($cou_mar)),
					'sum_mar'    => ($sum_mar == 0 ? '' : number_format($sum_mar)),
					'cou_apr'    => ($cou_apr == 0 ? '' : number_format($cou_apr)),
					'sum_apr'    => ($sum_apr == 0 ? '' : number_format($sum_apr)),
					'cou_mei'    => ($cou_mei == 0 ? '' : number_format($cou_mei)),
					'sum_mei'    => ($sum_mei == 0 ? '' : number_format($sum_mei)),
					'cou_jun'    => ($cou_jun == 0 ? '' : number_format($cou_jun)),
					'sum_jun'    => ($sum_jun == 0 ? '' : number_format($sum_jun)),
					'cou_jul'    => ($cou_jul == 0 ? '' : number_format($cou_jul)),
					'sum_jul'    => ($sum_jul == 0 ? '' : number_format($sum_jul)),
					'cou_agu'    => ($cou_agu == 0 ? '' : number_format($cou_agu)),
					'sum_agu'    => ($sum_agu == 0 ? '' : number_format($sum_agu)),
					'cou_sep'    => ($cou_sep == 0 ? '' : number_format($cou_sep)),
					'sum_sep'    => ($sum_sep == 0 ? '' : number_format($sum_sep)),
					'cou_okt'    => ($cou_okt == 0 ? '' : number_format($cou_okt)),
					'sum_okt'    => ($sum_okt == 0 ? '' : number_format($sum_okt)),
					'cou_nov'    => ($cou_nov == 0 ? '' : number_format($cou_nov)),
					'sum_nov'    => ($sum_nov == 0 ? '' : number_format($sum_nov)),
					'cou_des'    => ($cou_des == 0 ? '' : number_format($cou_des)),
					'sum_des'    => ($sum_des == 0 ? '' : number_format($sum_des)),
				);
				$no++;

				$tc_jan += $cou_jan;
				$ts_jan += $sum_jan;
				$tc_feb += $cou_feb;
				$ts_feb += $sum_feb;
				$tc_mar += $cou_mar;
				$ts_mar += $sum_mar;
				$tc_apr += $cou_apr;
				$ts_apr += $sum_apr;
				$tc_mei += $cou_mei;
				$ts_mei += $sum_mei;
				$tc_jun += $cou_jun;
				$ts_jun += $sum_jun;
				$tc_jul += $cou_jul;
				$ts_jul += $sum_jul;
				$tc_agu += $cou_agu;
				$ts_agu += $sum_agu;
				$tc_sep += $cou_sep;
				$ts_sep += $sum_sep;
				$tc_okt += $cou_okt;
				$ts_okt += $sum_okt;
				$tc_nov += $cou_nov;
				$ts_nov += $sum_nov;
				$tc_des += $cou_des;
				$ts_des += $sum_des;
				
			}
		}

		// Pax
		$room_active            = count($this->Rooms_model->get_data(array('room_active' => '1', 'b.room_type_group !=' => 'Function Room'))->result());
		$room_nonactive         = count($this->Rooms_model->get_data(array('room_active' => '0', 'b.room_type_group !=' => 'Function Room'))->result());
		$px_jan   = ($tc_jan/$room_active) * 100;
		$px_feb   = ($tc_feb/$room_active) * 100;
		$px_mar   = ($tc_mar/$room_active) * 100;
		$px_apr   = ($tc_apr/$room_active) * 100;
		$px_mei   = ($tc_mei/$room_active) * 100;
		$px_jun   = ($tc_jun/$room_active) * 100;
		$px_jul   = ($tc_jul/$room_active) * 100;
		$px_agu   = ($tc_agu/$room_active) * 100;
		$px_sep   = ($tc_sep/$room_active) * 100;
		$px_okt   = ($tc_okt/$room_active) * 100;
		$px_nov   = ($tc_nov/$room_active) * 100;
		$px_des   = ($tc_des/$room_active) * 100;

		// Residence
		$select    = 'COALESCE(SUM(c.total), 0) as total';
		$select_cnt    = 'COALESCE(SUM(c.interval_stay), 0) as total';
		$where_jan = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '1', 'YEAR(date_in)' => $year_filter);
		$where_feb = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '2', 'YEAR(date_in)' => $year_filter);
		$where_mar = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '3', 'YEAR(date_in)' => $year_filter);
		$where_apr = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '4', 'YEAR(date_in)' => $year_filter);
		$where_mei = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '5', 'YEAR(date_in)' => $year_filter);
		$where_jun = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '6', 'YEAR(date_in)' => $year_filter);
		$where_jul = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '7', 'YEAR(date_in)' => $year_filter);
		$where_agu = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '8', 'YEAR(date_in)' => $year_filter);
		$where_sep = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '9', 'YEAR(date_in)' => $year_filter);
		$where_okt = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '10', 'YEAR(date_in)' => $year_filter);
		$where_nov = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '11', 'YEAR(date_in)' => $year_filter);
		$where_des = array('d.room_type_group' => 'Function Room', 'MONTH(date_in)' => '12', 'YEAR(date_in)' => $year_filter);
			
		$tc_rsd_jan   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_jan)->row()->total; 
		$ts_rsd_jan   = $this->Reports_model->get_data_payment_detail_right($select, $where_jan)->row()->total;
		$tc_rsd_feb   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_feb)->row()->total; 
		$ts_rsd_feb   = $this->Reports_model->get_data_payment_detail_right($select, $where_feb)->row()->total;
		$tc_rsd_mar   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_mar)->row()->total; 
		$ts_rsd_mar   = $this->Reports_model->get_data_payment_detail_right($select, $where_mar)->row()->total;
		$tc_rsd_apr   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_apr)->row()->total; 
		$ts_rsd_apr   = $this->Reports_model->get_data_payment_detail_right($select, $where_apr)->row()->total;
		$tc_rsd_mei   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_mei)->row()->total; 
		$ts_rsd_mei   = $this->Reports_model->get_data_payment_detail_right($select, $where_mei)->row()->total;
		$tc_rsd_jun   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_jun)->row()->total; 
		$ts_rsd_jun   = $this->Reports_model->get_data_payment_detail_right($select, $where_jun)->row()->total;
		$tc_rsd_jul   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_jul)->row()->total; 
		$ts_rsd_jul   = $this->Reports_model->get_data_payment_detail_right($select, $where_jul)->row()->total;
		$tc_rsd_agu   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_agu)->row()->total; 
		$ts_rsd_agu   = $this->Reports_model->get_data_payment_detail_right($select, $where_agu)->row()->total;
		$tc_rsd_sep   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_sep)->row()->total; 
		$ts_rsd_sep   = $this->Reports_model->get_data_payment_detail_right($select, $where_sep)->row()->total;
		$tc_rsd_okt   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_okt)->row()->total; 
		$ts_rsd_okt   = $this->Reports_model->get_data_payment_detail_right($select, $where_okt)->row()->total;
		$tc_rsd_nov   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_nov)->row()->total; 
		$ts_rsd_nov   = $this->Reports_model->get_data_payment_detail_right($select, $where_nov)->row()->total;
		$tc_rsd_des   = $this->Reports_model->get_data_payment_detail_right($select_cnt, $where_des)->row()->total; 
		$ts_rsd_des   = $this->Reports_model->get_data_payment_detail_right($select, $where_des)->row()->total;

		// Meeting Room
		$where_meeting = array('a.cs_group_parent_id' => '1');
		$get_meeting   = $this->Cs_groups_model->get_data($where_meeting)->result();

		if($get_meeting)
		{	
			$no = 1;
			foreach($get_meeting as $get_row)
			{	
				// $select    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_cou = 'COALESCE(SUM(a.cs_detail_quantity), 0) as total';
				$select_sum = 'COALESCE(SUM(a.cs_detail_total), 0) as total';
				$where_jan  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '1', 'YEAR(cs_header_date)' => $year_filter);
				$where_feb  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '2', 'YEAR(cs_header_date)' => $year_filter);
				$where_mar  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '3', 'YEAR(cs_header_date)' => $year_filter);
				$where_apr  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '4', 'YEAR(cs_header_date)' => $year_filter);
				$where_mei  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '5', 'YEAR(cs_header_date)' => $year_filter);
				$where_jun  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '6', 'YEAR(cs_header_date)' => $year_filter);
				$where_jul  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '7', 'YEAR(cs_header_date)' => $year_filter);
				$where_agu  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '8', 'YEAR(cs_header_date)' => $year_filter);
				$where_sep  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '9', 'YEAR(cs_header_date)' => $year_filter);
				$where_okt  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '10', 'YEAR(cs_header_date)' => $year_filter);
				$where_nov  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '11', 'YEAR(cs_header_date)' => $year_filter);
				$where_des  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '12', 'YEAR(cs_header_date)' => $year_filter);
					
				$cou_jan   = $this->Reports_model->cs_detail($select_cou, $where_jan)->row()->total; 
				$sum_jan   = $this->Reports_model->cs_detail($select_sum, $where_jan)->row()->total;
				$cou_feb   = $this->Reports_model->cs_detail($select_cou, $where_feb)->row()->total; 
				$sum_feb   = $this->Reports_model->cs_detail($select_sum, $where_feb)->row()->total;
				$cou_mar   = $this->Reports_model->cs_detail($select_cou, $where_mar)->row()->total; 
				$sum_mar   = $this->Reports_model->cs_detail($select_sum, $where_mar)->row()->total;
				$cou_apr   = $this->Reports_model->cs_detail($select_cou, $where_apr)->row()->total; 
				$sum_apr   = $this->Reports_model->cs_detail($select_sum, $where_apr)->row()->total;
				$cou_mei   = $this->Reports_model->cs_detail($select_cou, $where_mei)->row()->total; 
				$sum_mei   = $this->Reports_model->cs_detail($select_sum, $where_mei)->row()->total;
				$cou_jun   = $this->Reports_model->cs_detail($select_cou, $where_jun)->row()->total; 
				$sum_jun   = $this->Reports_model->cs_detail($select_sum, $where_jun)->row()->total;
				$cou_jul   = $this->Reports_model->cs_detail($select_cou, $where_jul)->row()->total; 
				$sum_jul   = $this->Reports_model->cs_detail($select_sum, $where_jul)->row()->total;
				$cou_agu   = $this->Reports_model->cs_detail($select_cou, $where_agu)->row()->total; 
				$sum_agu   = $this->Reports_model->cs_detail($select_sum, $where_agu)->row()->total;
				$cou_sep   = $this->Reports_model->cs_detail($select_cou, $where_sep)->row()->total; 
				$sum_sep   = $this->Reports_model->cs_detail($select_sum, $where_sep)->row()->total;
				$cou_okt   = $this->Reports_model->cs_detail($select_cou, $where_okt)->row()->total; 
				$sum_okt   = $this->Reports_model->cs_detail($select_sum, $where_okt)->row()->total;
				$cou_nov   = $this->Reports_model->cs_detail($select_cou, $where_nov)->row()->total; 
				$sum_nov   = $this->Reports_model->cs_detail($select_sum, $where_nov)->row()->total;
				$cou_des   = $this->Reports_model->cs_detail($select_cou, $where_des)->row()->total; 
				$sum_des   = $this->Reports_model->cs_detail($select_sum, $where_des)->row()->total; 
				 
				$data_meeting[] = array(
					'no'         => $no,
					'group_name' => $get_row->cs_group_name,
					'cou_jan'    => ($cou_jan == 0 ? '' : $cou_jan),
					'sum_jan'    => ($sum_jan == 0 ? '' : number_format($sum_jan)),
					'cou_feb'    => ($cou_feb == 0 ? '' : number_format($cou_feb)),
					'sum_feb'    => ($sum_feb == 0 ? '' : number_format($sum_feb)),
					'cou_mar'    => ($cou_mar == 0 ? '' : number_format($cou_mar)),
					'sum_mar'    => ($sum_mar == 0 ? '' : number_format($sum_mar)),
					'cou_apr'    => ($cou_apr == 0 ? '' : number_format($cou_apr)),
					'sum_apr'    => ($sum_apr == 0 ? '' : number_format($sum_apr)),
					'cou_mei'    => ($cou_mei == 0 ? '' : number_format($cou_mei)),
					'sum_mei'    => ($sum_mei == 0 ? '' : number_format($sum_mei)),
					'cou_jun'    => ($cou_jun == 0 ? '' : number_format($cou_jun)),
					'sum_jun'    => ($sum_jun == 0 ? '' : number_format($sum_jun)),
					'cou_jul'    => ($cou_jul == 0 ? '' : number_format($cou_jul)),
					'sum_jul'    => ($sum_jul == 0 ? '' : number_format($sum_jul)),
					'cou_agu'    => ($cou_agu == 0 ? '' : number_format($cou_agu)),
					'sum_agu'    => ($sum_agu == 0 ? '' : number_format($sum_agu)),
					'cou_sep'    => ($cou_sep == 0 ? '' : number_format($cou_sep)),
					'sum_sep'    => ($sum_sep == 0 ? '' : number_format($sum_sep)),
					'cou_okt'    => ($cou_okt == 0 ? '' : number_format($cou_okt)),
					'sum_okt'    => ($sum_okt == 0 ? '' : number_format($sum_okt)),
					'cou_nov'    => ($cou_nov == 0 ? '' : number_format($cou_nov)),
					'sum_nov'    => ($sum_nov == 0 ? '' : number_format($sum_nov)),
					'cou_des'    => ($cou_des == 0 ? '' : number_format($cou_des)),
					'sum_des'    => ($sum_des == 0 ? '' : number_format($sum_des)),
				);
				$no++;
				
				$tc_mee_jan += $cou_jan;
				$ts_mee_jan += $sum_jan;
				$tc_mee_feb += $cou_feb;
				$ts_mee_feb += $sum_feb;
				$tc_mee_mar += $cou_mar;
				$ts_mee_mar += $sum_mar;
				$tc_mee_apr += $cou_apr;
				$ts_mee_apr += $sum_apr;
				$tc_mee_mei += $cou_mei;
				$ts_mee_mei += $sum_mei;
				$tc_mee_jun += $cou_jun;
				$ts_mee_jun += $sum_jun;
				$tc_mee_jul += $cou_jul;
				$ts_mee_jul += $sum_jul;
				$tc_mee_agu += $cou_agu;
				$ts_mee_agu += $sum_agu;
				$tc_mee_sep += $cou_sep;
				$ts_mee_sep += $sum_sep;
				$tc_mee_okt += $cou_okt;
				$ts_mee_okt += $sum_okt;
				$tc_mee_nov += $cou_nov;
				$ts_mee_nov += $sum_nov;
				$tc_mee_des += $cou_des;
				$ts_mee_des += $sum_des;
				
			}
		}

		// Poolside
		$where_poolside = array('a.cs_group_parent_id' => '2');
		$get_poolside   = $this->Cs_groups_model->get_data($where_poolside)->result();

		if($get_poolside)
		{	
			$no = 1;
			foreach($get_poolside as $get_row)
			{	
				// $select    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_cou = 'COALESCE(SUM(a.cs_detail_quantity), 0) as total';
				$select_sum = 'COALESCE(SUM(a.cs_detail_total), 0) as total';
				$where_jan  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '1', 'YEAR(cs_header_date)' => $year_filter);
				$where_feb  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '2', 'YEAR(cs_header_date)' => $year_filter);
				$where_mar  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '3', 'YEAR(cs_header_date)' => $year_filter);
				$where_apr  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '4', 'YEAR(cs_header_date)' => $year_filter);
				$where_mei  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '5', 'YEAR(cs_header_date)' => $year_filter);
				$where_jun  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '6', 'YEAR(cs_header_date)' => $year_filter);
				$where_jul  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '7', 'YEAR(cs_header_date)' => $year_filter);
				$where_agu  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '8', 'YEAR(cs_header_date)' => $year_filter);
				$where_sep  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '9', 'YEAR(cs_header_date)' => $year_filter);
				$where_okt  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '10', 'YEAR(cs_header_date)' => $year_filter);
				$where_nov  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '11', 'YEAR(cs_header_date)' => $year_filter);
				$where_des  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '12', 'YEAR(cs_header_date)' => $year_filter);
					
				$cou_jan   = $this->Reports_model->cs_detail($select_cou, $where_jan)->row()->total; 
				$sum_jan   = $this->Reports_model->cs_detail($select_sum, $where_jan)->row()->total;
				$cou_feb   = $this->Reports_model->cs_detail($select_cou, $where_feb)->row()->total; 
				$sum_feb   = $this->Reports_model->cs_detail($select_sum, $where_feb)->row()->total;
				$cou_mar   = $this->Reports_model->cs_detail($select_cou, $where_mar)->row()->total; 
				$sum_mar   = $this->Reports_model->cs_detail($select_sum, $where_mar)->row()->total;
				$cou_apr   = $this->Reports_model->cs_detail($select_cou, $where_apr)->row()->total; 
				$sum_apr   = $this->Reports_model->cs_detail($select_sum, $where_apr)->row()->total;
				$cou_mei   = $this->Reports_model->cs_detail($select_cou, $where_mei)->row()->total; 
				$sum_mei   = $this->Reports_model->cs_detail($select_sum, $where_mei)->row()->total;
				$cou_jun   = $this->Reports_model->cs_detail($select_cou, $where_jun)->row()->total; 
				$sum_jun   = $this->Reports_model->cs_detail($select_sum, $where_jun)->row()->total;
				$cou_jul   = $this->Reports_model->cs_detail($select_cou, $where_jul)->row()->total; 
				$sum_jul   = $this->Reports_model->cs_detail($select_sum, $where_jul)->row()->total;
				$cou_agu   = $this->Reports_model->cs_detail($select_cou, $where_agu)->row()->total; 
				$sum_agu   = $this->Reports_model->cs_detail($select_sum, $where_agu)->row()->total;
				$cou_sep   = $this->Reports_model->cs_detail($select_cou, $where_sep)->row()->total; 
				$sum_sep   = $this->Reports_model->cs_detail($select_sum, $where_sep)->row()->total;
				$cou_okt   = $this->Reports_model->cs_detail($select_cou, $where_okt)->row()->total; 
				$sum_okt   = $this->Reports_model->cs_detail($select_sum, $where_okt)->row()->total;
				$cou_nov   = $this->Reports_model->cs_detail($select_cou, $where_nov)->row()->total; 
				$sum_nov   = $this->Reports_model->cs_detail($select_sum, $where_nov)->row()->total;
				$cou_des   = $this->Reports_model->cs_detail($select_cou, $where_des)->row()->total; 
				$sum_des   = $this->Reports_model->cs_detail($select_sum, $where_des)->row()->total; 
				 
				$data_poolside[] = array(
					'no'         => $no,
					'group_name' => $get_row->cs_group_name,
					'cou_jan'    => ($cou_jan == 0 ? '' : $cou_jan),
					'sum_jan'    => ($sum_jan == 0 ? '' : number_format($sum_jan)),
					'cou_feb'    => ($cou_feb == 0 ? '' : number_format($cou_feb)),
					'sum_feb'    => ($sum_feb == 0 ? '' : number_format($sum_feb)),
					'cou_mar'    => ($cou_mar == 0 ? '' : number_format($cou_mar)),
					'sum_mar'    => ($sum_mar == 0 ? '' : number_format($sum_mar)),
					'cou_apr'    => ($cou_apr == 0 ? '' : number_format($cou_apr)),
					'sum_apr'    => ($sum_apr == 0 ? '' : number_format($sum_apr)),
					'cou_mei'    => ($cou_mei == 0 ? '' : number_format($cou_mei)),
					'sum_mei'    => ($sum_mei == 0 ? '' : number_format($sum_mei)),
					'cou_jun'    => ($cou_jun == 0 ? '' : number_format($cou_jun)),
					'sum_jun'    => ($sum_jun == 0 ? '' : number_format($sum_jun)),
					'cou_jul'    => ($cou_jul == 0 ? '' : number_format($cou_jul)),
					'sum_jul'    => ($sum_jul == 0 ? '' : number_format($sum_jul)),
					'cou_agu'    => ($cou_agu == 0 ? '' : number_format($cou_agu)),
					'sum_agu'    => ($sum_agu == 0 ? '' : number_format($sum_agu)),
					'cou_sep'    => ($cou_sep == 0 ? '' : number_format($cou_sep)),
					'sum_sep'    => ($sum_sep == 0 ? '' : number_format($sum_sep)),
					'cou_okt'    => ($cou_okt == 0 ? '' : number_format($cou_okt)),
					'sum_okt'    => ($sum_okt == 0 ? '' : number_format($sum_okt)),
					'cou_nov'    => ($cou_nov == 0 ? '' : number_format($cou_nov)),
					'sum_nov'    => ($sum_nov == 0 ? '' : number_format($sum_nov)),
					'cou_des'    => ($cou_des == 0 ? '' : number_format($cou_des)),
					'sum_des'    => ($sum_des == 0 ? '' : number_format($sum_des)),
				);
				$no++;
				
				$tc_poo_jan += $cou_jan;
				$ts_poo_jan += $sum_jan;
				$tc_poo_feb += $cou_feb;
				$ts_poo_feb += $sum_feb;
				$tc_poo_mar += $cou_mar;
				$ts_poo_mar += $sum_mar;
				$tc_poo_apr += $cou_apr;
				$ts_poo_apr += $sum_apr;
				$tc_poo_mei += $cou_mei;
				$ts_poo_mei += $sum_mei;
				$tc_poo_jun += $cou_jun;
				$ts_poo_jun += $sum_jun;
				$tc_poo_jul += $cou_jul;
				$ts_poo_jul += $sum_jul;
				$tc_poo_agu += $cou_agu;
				$ts_poo_agu += $sum_agu;
				$tc_poo_sep += $cou_sep;
				$ts_poo_sep += $sum_sep;
				$tc_poo_okt += $cou_okt;
				$ts_poo_okt += $sum_okt;
				$tc_poo_nov += $cou_nov;
				$ts_poo_nov += $sum_nov;
				$tc_poo_des += $cou_des;
				$ts_poo_des += $sum_des;
				
			}
		}

		// Resto
		$where_resto = array('a.cs_group_parent_id' => '3');
		$get_resto   = $this->Cs_groups_model->get_data($where_resto)->result();

		if($get_resto)
		{	
			$no = 1;
			foreach($get_resto as $get_row)
			{	
				// $select    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_cou = 'COALESCE(SUM(a.cs_detail_quantity), 0) as total';
				$select_sum = 'COALESCE(SUM(a.cs_detail_total), 0) as total';
				$where_jan  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '1', 'YEAR(cs_header_date)' => $year_filter);
				$where_feb  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '2', 'YEAR(cs_header_date)' => $year_filter);
				$where_mar  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '3', 'YEAR(cs_header_date)' => $year_filter);
				$where_apr  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '4', 'YEAR(cs_header_date)' => $year_filter);
				$where_mei  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '5', 'YEAR(cs_header_date)' => $year_filter);
				$where_jun  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '6', 'YEAR(cs_header_date)' => $year_filter);
				$where_jul  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '7', 'YEAR(cs_header_date)' => $year_filter);
				$where_agu  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '8', 'YEAR(cs_header_date)' => $year_filter);
				$where_sep  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '9', 'YEAR(cs_header_date)' => $year_filter);
				$where_okt  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '10', 'YEAR(cs_header_date)' => $year_filter);
				$where_nov  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '11', 'YEAR(cs_header_date)' => $year_filter);
				$where_des  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '12', 'YEAR(cs_header_date)' => $year_filter);
					
				$cou_jan   = $this->Reports_model->cs_detail($select_cou, $where_jan)->row()->total; 
				$sum_jan   = $this->Reports_model->cs_detail($select_sum, $where_jan)->row()->total;
				$cou_feb   = $this->Reports_model->cs_detail($select_cou, $where_feb)->row()->total; 
				$sum_feb   = $this->Reports_model->cs_detail($select_sum, $where_feb)->row()->total;
				$cou_mar   = $this->Reports_model->cs_detail($select_cou, $where_mar)->row()->total; 
				$sum_mar   = $this->Reports_model->cs_detail($select_sum, $where_mar)->row()->total;
				$cou_apr   = $this->Reports_model->cs_detail($select_cou, $where_apr)->row()->total; 
				$sum_apr   = $this->Reports_model->cs_detail($select_sum, $where_apr)->row()->total;
				$cou_mei   = $this->Reports_model->cs_detail($select_cou, $where_mei)->row()->total; 
				$sum_mei   = $this->Reports_model->cs_detail($select_sum, $where_mei)->row()->total;
				$cou_jun   = $this->Reports_model->cs_detail($select_cou, $where_jun)->row()->total; 
				$sum_jun   = $this->Reports_model->cs_detail($select_sum, $where_jun)->row()->total;
				$cou_jul   = $this->Reports_model->cs_detail($select_cou, $where_jul)->row()->total; 
				$sum_jul   = $this->Reports_model->cs_detail($select_sum, $where_jul)->row()->total;
				$cou_agu   = $this->Reports_model->cs_detail($select_cou, $where_agu)->row()->total; 
				$sum_agu   = $this->Reports_model->cs_detail($select_sum, $where_agu)->row()->total;
				$cou_sep   = $this->Reports_model->cs_detail($select_cou, $where_sep)->row()->total; 
				$sum_sep   = $this->Reports_model->cs_detail($select_sum, $where_sep)->row()->total;
				$cou_okt   = $this->Reports_model->cs_detail($select_cou, $where_okt)->row()->total; 
				$sum_okt   = $this->Reports_model->cs_detail($select_sum, $where_okt)->row()->total;
				$cou_nov   = $this->Reports_model->cs_detail($select_cou, $where_nov)->row()->total; 
				$sum_nov   = $this->Reports_model->cs_detail($select_sum, $where_nov)->row()->total;
				$cou_des   = $this->Reports_model->cs_detail($select_cou, $where_des)->row()->total; 
				$sum_des   = $this->Reports_model->cs_detail($select_sum, $where_des)->row()->total; 
				 
				$data_resto[] = array(
					'no'         => $no,
					'group_name' => $get_row->cs_group_name,
					'cou_jan'    => ($cou_jan == 0 ? '' : $cou_jan),
					'sum_jan'    => ($sum_jan == 0 ? '' : number_format($sum_jan)),
					'cou_feb'    => ($cou_feb == 0 ? '' : number_format($cou_feb)),
					'sum_feb'    => ($sum_feb == 0 ? '' : number_format($sum_feb)),
					'cou_mar'    => ($cou_mar == 0 ? '' : number_format($cou_mar)),
					'sum_mar'    => ($sum_mar == 0 ? '' : number_format($sum_mar)),
					'cou_apr'    => ($cou_apr == 0 ? '' : number_format($cou_apr)),
					'sum_apr'    => ($sum_apr == 0 ? '' : number_format($sum_apr)),
					'cou_mei'    => ($cou_mei == 0 ? '' : number_format($cou_mei)),
					'sum_mei'    => ($sum_mei == 0 ? '' : number_format($sum_mei)),
					'cou_jun'    => ($cou_jun == 0 ? '' : number_format($cou_jun)),
					'sum_jun'    => ($sum_jun == 0 ? '' : number_format($sum_jun)),
					'cou_jul'    => ($cou_jul == 0 ? '' : number_format($cou_jul)),
					'sum_jul'    => ($sum_jul == 0 ? '' : number_format($sum_jul)),
					'cou_agu'    => ($cou_agu == 0 ? '' : number_format($cou_agu)),
					'sum_agu'    => ($sum_agu == 0 ? '' : number_format($sum_agu)),
					'cou_sep'    => ($cou_sep == 0 ? '' : number_format($cou_sep)),
					'sum_sep'    => ($sum_sep == 0 ? '' : number_format($sum_sep)),
					'cou_okt'    => ($cou_okt == 0 ? '' : number_format($cou_okt)),
					'sum_okt'    => ($sum_okt == 0 ? '' : number_format($sum_okt)),
					'cou_nov'    => ($cou_nov == 0 ? '' : number_format($cou_nov)),
					'sum_nov'    => ($sum_nov == 0 ? '' : number_format($sum_nov)),
					'cou_des'    => ($cou_des == 0 ? '' : number_format($cou_des)),
					'sum_des'    => ($sum_des == 0 ? '' : number_format($sum_des)),
				);
				$no++;
				
				$tc_res_jan += $cou_jan;
				$ts_res_jan += $sum_jan;
				$tc_res_feb += $cou_feb;
				$ts_res_feb += $sum_feb;
				$tc_res_mar += $cou_mar;
				$ts_res_mar += $sum_mar;
				$tc_res_apr += $cou_apr;
				$ts_res_apr += $sum_apr;
				$tc_res_mei += $cou_mei;
				$ts_res_mei += $sum_mei;
				$tc_res_jun += $cou_jun;
				$ts_res_jun += $sum_jun;
				$tc_res_jul += $cou_jul;
				$ts_res_jul += $sum_jul;
				$tc_res_agu += $cou_agu;
				$ts_res_agu += $sum_agu;
				$tc_res_sep += $cou_sep;
				$ts_res_sep += $sum_sep;
				$tc_res_okt += $cou_okt;
				$ts_res_okt += $sum_okt;
				$tc_res_nov += $cou_nov;
				$ts_res_nov += $sum_nov;
				$tc_res_des += $cou_des;
				$ts_res_des += $sum_des;
				
			}
		}

		// Mischarge
		$where_mischarge = array('a.cs_group_parent_id' => '4');
		$get_mischarge   = $this->Cs_groups_model->get_data($where_mischarge)->result();

		if($get_mischarge)
		{	
			$no = 1;
			foreach($get_mischarge as $get_row)
			{	
				// $select    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_cou = 'COALESCE(SUM(a.cs_detail_quantity), 0) as total';
				$select_sum = 'COALESCE(SUM(a.cs_detail_total), 0) as total';
				$where_jan  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '1', 'YEAR(cs_header_date)' => $year_filter);
				$where_feb  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '2', 'YEAR(cs_header_date)' => $year_filter);
				$where_mar  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '3', 'YEAR(cs_header_date)' => $year_filter);
				$where_apr  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '4', 'YEAR(cs_header_date)' => $year_filter);
				$where_mei  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '5', 'YEAR(cs_header_date)' => $year_filter);
				$where_jun  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '6', 'YEAR(cs_header_date)' => $year_filter);
				$where_jul  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '7', 'YEAR(cs_header_date)' => $year_filter);
				$where_agu  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '8', 'YEAR(cs_header_date)' => $year_filter);
				$where_sep  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '9', 'YEAR(cs_header_date)' => $year_filter);
				$where_okt  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '10', 'YEAR(cs_header_date)' => $year_filter);
				$where_nov  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '11', 'YEAR(cs_header_date)' => $year_filter);
				$where_des  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '12', 'YEAR(cs_header_date)' => $year_filter);
					
				$cou_jan   = $this->Reports_model->cs_detail($select_cou, $where_jan)->row()->total; 
				$sum_jan   = $this->Reports_model->cs_detail($select_sum, $where_jan)->row()->total;
				$cou_feb   = $this->Reports_model->cs_detail($select_cou, $where_feb)->row()->total; 
				$sum_feb   = $this->Reports_model->cs_detail($select_sum, $where_feb)->row()->total;
				$cou_mar   = $this->Reports_model->cs_detail($select_cou, $where_mar)->row()->total; 
				$sum_mar   = $this->Reports_model->cs_detail($select_sum, $where_mar)->row()->total;
				$cou_apr   = $this->Reports_model->cs_detail($select_cou, $where_apr)->row()->total; 
				$sum_apr   = $this->Reports_model->cs_detail($select_sum, $where_apr)->row()->total;
				$cou_mei   = $this->Reports_model->cs_detail($select_cou, $where_mei)->row()->total; 
				$sum_mei   = $this->Reports_model->cs_detail($select_sum, $where_mei)->row()->total;
				$cou_jun   = $this->Reports_model->cs_detail($select_cou, $where_jun)->row()->total; 
				$sum_jun   = $this->Reports_model->cs_detail($select_sum, $where_jun)->row()->total;
				$cou_jul   = $this->Reports_model->cs_detail($select_cou, $where_jul)->row()->total; 
				$sum_jul   = $this->Reports_model->cs_detail($select_sum, $where_jul)->row()->total;
				$cou_agu   = $this->Reports_model->cs_detail($select_cou, $where_agu)->row()->total; 
				$sum_agu   = $this->Reports_model->cs_detail($select_sum, $where_agu)->row()->total;
				$cou_sep   = $this->Reports_model->cs_detail($select_cou, $where_sep)->row()->total; 
				$sum_sep   = $this->Reports_model->cs_detail($select_sum, $where_sep)->row()->total;
				$cou_okt   = $this->Reports_model->cs_detail($select_cou, $where_okt)->row()->total; 
				$sum_okt   = $this->Reports_model->cs_detail($select_sum, $where_okt)->row()->total;
				$cou_nov   = $this->Reports_model->cs_detail($select_cou, $where_nov)->row()->total; 
				$sum_nov   = $this->Reports_model->cs_detail($select_sum, $where_nov)->row()->total;
				$cou_des   = $this->Reports_model->cs_detail($select_cou, $where_des)->row()->total; 
				$sum_des   = $this->Reports_model->cs_detail($select_sum, $where_des)->row()->total; 
				 
				$data_mischarge[] = array(
					'no'         => $no,
					'group_name' => $get_row->cs_group_name,
					'cou_jan'    => ($cou_jan == 0 ? '' : $cou_jan),
					'sum_jan'    => ($sum_jan == 0 ? '' : number_format($sum_jan)),
					'cou_feb'    => ($cou_feb == 0 ? '' : number_format($cou_feb)),
					'sum_feb'    => ($sum_feb == 0 ? '' : number_format($sum_feb)),
					'cou_mar'    => ($cou_mar == 0 ? '' : number_format($cou_mar)),
					'sum_mar'    => ($sum_mar == 0 ? '' : number_format($sum_mar)),
					'cou_apr'    => ($cou_apr == 0 ? '' : number_format($cou_apr)),
					'sum_apr'    => ($sum_apr == 0 ? '' : number_format($sum_apr)),
					'cou_mei'    => ($cou_mei == 0 ? '' : number_format($cou_mei)),
					'sum_mei'    => ($sum_mei == 0 ? '' : number_format($sum_mei)),
					'cou_jun'    => ($cou_jun == 0 ? '' : number_format($cou_jun)),
					'sum_jun'    => ($sum_jun == 0 ? '' : number_format($sum_jun)),
					'cou_jul'    => ($cou_jul == 0 ? '' : number_format($cou_jul)),
					'sum_jul'    => ($sum_jul == 0 ? '' : number_format($sum_jul)),
					'cou_agu'    => ($cou_agu == 0 ? '' : number_format($cou_agu)),
					'sum_agu'    => ($sum_agu == 0 ? '' : number_format($sum_agu)),
					'cou_sep'    => ($cou_sep == 0 ? '' : number_format($cou_sep)),
					'sum_sep'    => ($sum_sep == 0 ? '' : number_format($sum_sep)),
					'cou_okt'    => ($cou_okt == 0 ? '' : number_format($cou_okt)),
					'sum_okt'    => ($sum_okt == 0 ? '' : number_format($sum_okt)),
					'cou_nov'    => ($cou_nov == 0 ? '' : number_format($cou_nov)),
					'sum_nov'    => ($sum_nov == 0 ? '' : number_format($sum_nov)),
					'cou_des'    => ($cou_des == 0 ? '' : number_format($cou_des)),
					'sum_des'    => ($sum_des == 0 ? '' : number_format($sum_des)),
				);
				$no++;
				
				$tc_mis_jan += $cou_jan;
				$ts_mis_jan += $sum_jan;
				$tc_mis_feb += $cou_feb;
				$ts_mis_feb += $sum_feb;
				$tc_mis_mar += $cou_mar;
				$ts_mis_mar += $sum_mar;
				$tc_mis_apr += $cou_apr;
				$ts_mis_apr += $sum_apr;
				$tc_mis_mei += $cou_mei;
				$ts_mis_mei += $sum_mei;
				$tc_mis_jun += $cou_jun;
				$ts_mis_jun += $sum_jun;
				$tc_mis_jul += $cou_jul;
				$ts_mis_jul += $sum_jul;
				$tc_mis_agu += $cou_agu;
				$ts_mis_agu += $sum_agu;
				$tc_mis_sep += $cou_sep;
				$ts_mis_sep += $sum_sep;
				$tc_mis_okt += $cou_okt;
				$ts_mis_okt += $sum_okt;
				$tc_mis_nov += $cou_nov;
				$ts_mis_nov += $sum_nov;
				$tc_mis_des += $cou_des;
				$ts_mis_des += $sum_des;
				
			}
		}

		// Swimming Pool
		$where_swimming = array('a.cs_group_parent_id' => '5');
		$get_swimming   = $this->Cs_groups_model->get_data($where_swimming)->result();

		if($get_swimming)
		{	
			$no = 1;
			foreach($get_swimming as $get_row)
			{	
				// $select    = 'a.*, b.*, c.*, SUM(a.paid) as total';
				$select_cou = 'COALESCE(SUM(a.cs_detail_quantity), 0) as total';
				$select_sum = 'COALESCE(SUM(a.cs_detail_total), 0) as total';
				$where_jan  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '1', 'YEAR(cs_header_date)' => $year_filter);
				$where_feb  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '2', 'YEAR(cs_header_date)' => $year_filter);
				$where_mar  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '3', 'YEAR(cs_header_date)' => $year_filter);
				$where_apr  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '4', 'YEAR(cs_header_date)' => $year_filter);
				$where_mei  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '5', 'YEAR(cs_header_date)' => $year_filter);
				$where_jun  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '6', 'YEAR(cs_header_date)' => $year_filter);
				$where_jul  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '7', 'YEAR(cs_header_date)' => $year_filter);
				$where_agu  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '8', 'YEAR(cs_header_date)' => $year_filter);
				$where_sep  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '9', 'YEAR(cs_header_date)' => $year_filter);
				$where_okt  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '10', 'YEAR(cs_header_date)' => $year_filter);
				$where_nov  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '11', 'YEAR(cs_header_date)' => $year_filter);
				$where_des  = array('c.cs_group_id' => $get_row->cs_group_id, 'MONTH(cs_header_date)' => '12', 'YEAR(cs_header_date)' => $year_filter);
					
				$cou_jan   = $this->Reports_model->cs_detail($select_cou, $where_jan)->row()->total; 
				$sum_jan   = $this->Reports_model->cs_detail($select_sum, $where_jan)->row()->total;
				$cou_feb   = $this->Reports_model->cs_detail($select_cou, $where_feb)->row()->total; 
				$sum_feb   = $this->Reports_model->cs_detail($select_sum, $where_feb)->row()->total;
				$cou_mar   = $this->Reports_model->cs_detail($select_cou, $where_mar)->row()->total; 
				$sum_mar   = $this->Reports_model->cs_detail($select_sum, $where_mar)->row()->total;
				$cou_apr   = $this->Reports_model->cs_detail($select_cou, $where_apr)->row()->total; 
				$sum_apr   = $this->Reports_model->cs_detail($select_sum, $where_apr)->row()->total;
				$cou_mei   = $this->Reports_model->cs_detail($select_cou, $where_mei)->row()->total; 
				$sum_mei   = $this->Reports_model->cs_detail($select_sum, $where_mei)->row()->total;
				$cou_jun   = $this->Reports_model->cs_detail($select_cou, $where_jun)->row()->total; 
				$sum_jun   = $this->Reports_model->cs_detail($select_sum, $where_jun)->row()->total;
				$cou_jul   = $this->Reports_model->cs_detail($select_cou, $where_jul)->row()->total; 
				$sum_jul   = $this->Reports_model->cs_detail($select_sum, $where_jul)->row()->total;
				$cou_agu   = $this->Reports_model->cs_detail($select_cou, $where_agu)->row()->total; 
				$sum_agu   = $this->Reports_model->cs_detail($select_sum, $where_agu)->row()->total;
				$cou_sep   = $this->Reports_model->cs_detail($select_cou, $where_sep)->row()->total; 
				$sum_sep   = $this->Reports_model->cs_detail($select_sum, $where_sep)->row()->total;
				$cou_okt   = $this->Reports_model->cs_detail($select_cou, $where_okt)->row()->total; 
				$sum_okt   = $this->Reports_model->cs_detail($select_sum, $where_okt)->row()->total;
				$cou_nov   = $this->Reports_model->cs_detail($select_cou, $where_nov)->row()->total; 
				$sum_nov   = $this->Reports_model->cs_detail($select_sum, $where_nov)->row()->total;
				$cou_des   = $this->Reports_model->cs_detail($select_cou, $where_des)->row()->total; 
				$sum_des   = $this->Reports_model->cs_detail($select_sum, $where_des)->row()->total; 
				 
				$data_swimming[] = array(
					'no'         => $no,
					'group_name' => $get_row->cs_group_name,
					'cou_jan'    => ($cou_jan == 0 ? '' : $cou_jan),
					'sum_jan'    => ($sum_jan == 0 ? '' : number_format($sum_jan)),
					'cou_feb'    => ($cou_feb == 0 ? '' : number_format($cou_feb)),
					'sum_feb'    => ($sum_feb == 0 ? '' : number_format($sum_feb)),
					'cou_mar'    => ($cou_mar == 0 ? '' : number_format($cou_mar)),
					'sum_mar'    => ($sum_mar == 0 ? '' : number_format($sum_mar)),
					'cou_apr'    => ($cou_apr == 0 ? '' : number_format($cou_apr)),
					'sum_apr'    => ($sum_apr == 0 ? '' : number_format($sum_apr)),
					'cou_mei'    => ($cou_mei == 0 ? '' : number_format($cou_mei)),
					'sum_mei'    => ($sum_mei == 0 ? '' : number_format($sum_mei)),
					'cou_jun'    => ($cou_jun == 0 ? '' : number_format($cou_jun)),
					'sum_jun'    => ($sum_jun == 0 ? '' : number_format($sum_jun)),
					'cou_jul'    => ($cou_jul == 0 ? '' : number_format($cou_jul)),
					'sum_jul'    => ($sum_jul == 0 ? '' : number_format($sum_jul)),
					'cou_agu'    => ($cou_agu == 0 ? '' : number_format($cou_agu)),
					'sum_agu'    => ($sum_agu == 0 ? '' : number_format($sum_agu)),
					'cou_sep'    => ($cou_sep == 0 ? '' : number_format($cou_sep)),
					'sum_sep'    => ($sum_sep == 0 ? '' : number_format($sum_sep)),
					'cou_okt'    => ($cou_okt == 0 ? '' : number_format($cou_okt)),
					'sum_okt'    => ($sum_okt == 0 ? '' : number_format($sum_okt)),
					'cou_nov'    => ($cou_nov == 0 ? '' : number_format($cou_nov)),
					'sum_nov'    => ($sum_nov == 0 ? '' : number_format($sum_nov)),
					'cou_des'    => ($cou_des == 0 ? '' : number_format($cou_des)),
					'sum_des'    => ($sum_des == 0 ? '' : number_format($sum_des)),
				);
				$no++;
				
				$tc_swi_jan += $cou_jan;
				$ts_swi_jan += $sum_jan;
				$tc_swi_feb += $cou_feb;
				$ts_swi_feb += $sum_feb;
				$tc_swi_mar += $cou_mar;
				$ts_swi_mar += $sum_mar;
				$tc_swi_apr += $cou_apr;
				$ts_swi_apr += $sum_apr;
				$tc_swi_mei += $cou_mei;
				$ts_swi_mei += $sum_mei;
				$tc_swi_jun += $cou_jun;
				$ts_swi_jun += $sum_jun;
				$tc_swi_jul += $cou_jul;
				$ts_swi_jul += $sum_jul;
				$tc_swi_agu += $cou_agu;
				$ts_swi_agu += $sum_agu;
				$tc_swi_sep += $cou_sep;
				$ts_swi_sep += $sum_sep;
				$tc_swi_okt += $cou_okt;
				$ts_swi_okt += $sum_okt;
				$tc_swi_nov += $cou_nov;
				$ts_swi_nov += $sum_nov;
				$tc_swi_des += $cou_des;
				$ts_swi_des += $sum_des;
				
			}
		}

		$ttl_jan = ($ts_jan + $ts_rsd_jan + $ts_mee_jan + $ts_poo_jan + $ts_res_jan + $ts_mis_jan + $ts_swi_jan);
		$ttl_feb = ($ts_feb + $ts_rsd_feb + $ts_mee_feb + $ts_poo_feb + $ts_res_feb + $ts_mis_feb + $ts_swi_feb);
		$ttl_mar = ($ts_mar + $ts_rsd_mar + $ts_mee_mar + $ts_poo_mar + $ts_res_mar + $ts_mis_mar + $ts_swi_mar);
		$ttl_apr = ($ts_apr + $ts_rsd_apr + $ts_mee_apr + $ts_poo_apr + $ts_res_apr + $ts_mis_apr + $ts_swi_apr);
		$ttl_mei = ($ts_mei + $ts_rsd_mei + $ts_mee_mei + $ts_poo_mei + $ts_res_mei + $ts_mis_mei + $ts_swi_mei);
		$ttl_jun = ($ts_jun + $ts_rsd_jun + $ts_mee_jun + $ts_poo_jun + $ts_res_jun + $ts_mis_jun + $ts_swi_jun);
		$ttl_jul = ($ts_jul + $ts_rsd_jul + $ts_mee_jul + $ts_poo_jul + $ts_res_jul + $ts_mis_jul + $ts_swi_jul);
		$ttl_agu = ($ts_agu + $ts_rsd_agu + $ts_mee_agu + $ts_poo_agu + $ts_res_agu + $ts_mis_agu + $ts_swi_agu);
		$ttl_sep = ($ts_sep + $ts_rsd_sep + $ts_mee_sep + $ts_poo_sep + $ts_res_sep + $ts_mis_sep + $ts_swi_sep);
		$ttl_okt = ($ts_okt + $ts_rsd_okt + $ts_mee_okt + $ts_poo_okt + $ts_res_okt + $ts_mis_okt + $ts_swi_okt);
		$ttl_nov = ($ts_nov + $ts_rsd_nov + $ts_mee_nov + $ts_poo_nov + $ts_res_nov + $ts_mis_nov + $ts_swi_nov);
		$ttl_des = ($ts_des + $ts_rsd_des + $ts_mee_des + $ts_poo_des + $ts_res_des + $ts_mis_des + $ts_swi_des);

		$response = [
			// 'date_1'                 => change_format_date($date_1, 'd/m/Y'),
			// 'date_2'                 => change_format_date($date_2, 'd/m/Y'),
			'data_room'       => $data_room,
			'data_meeting'    => $data_meeting,
			'data_poolside'   => $data_poolside,
			'data_resto'      => $data_resto,
			'data_mischarge'  => $data_mischarge,
			'data_swimming'   => $data_swimming,

			'tc_jan' => ($tc_jan == 0 ? '' : number_format($tc_jan)),
			'ts_jan' => ($ts_jan == 0 ? '' : number_format($ts_jan)),
			'tc_feb' => ($tc_feb == 0 ? '' : number_format($tc_feb)),
			'ts_feb' => ($ts_feb == 0 ? '' : number_format($ts_feb)),
			'tc_mar' => ($tc_mar == 0 ? '' : number_format($tc_mar)),
			'ts_mar' => ($ts_mar == 0 ? '' : number_format($ts_mar)),
			'tc_apr' => ($tc_apr == 0 ? '' : number_format($tc_apr)),
			'ts_apr' => ($ts_apr == 0 ? '' : number_format($ts_apr)),
			'tc_mei' => ($tc_mei == 0 ? '' : number_format($tc_mei)),
			'ts_mei' => ($ts_mei == 0 ? '' : number_format($ts_mei)),
			'tc_jun' => ($tc_jun == 0 ? '' : number_format($tc_jun)),
			'ts_jun' => ($ts_jun == 0 ? '' : number_format($ts_jun)),
			'tc_jul' => ($tc_jul == 0 ? '' : number_format($tc_jul)),
			'ts_jul' => ($ts_jul == 0 ? '' : number_format($ts_jul)),
			'tc_agu' => ($tc_agu == 0 ? '' : number_format($tc_agu)),
			'ts_agu' => ($ts_agu == 0 ? '' : number_format($ts_agu)),
			'tc_sep' => ($tc_sep == 0 ? '' : number_format($tc_sep)),
			'ts_sep' => ($ts_sep == 0 ? '' : number_format($ts_sep)),
			'tc_okt' => ($tc_okt == 0 ? '' : number_format($tc_okt)),
			'ts_okt' => ($ts_okt == 0 ? '' : number_format($ts_okt)),
			'tc_nov' => ($tc_nov == 0 ? '' : number_format($tc_nov)),
			'ts_nov' => ($ts_nov == 0 ? '' : number_format($ts_nov)),
			'tc_des' => ($tc_des == 0 ? '' : number_format($tc_des)),
			'ts_des' => ($ts_des == 0 ? '' : number_format($ts_des)),

			'px_jan' => ($tc_jan == 0 ? '' : number_format($px_jan)),
			'px_feb' => ($tc_feb == 0 ? '' : number_format($px_feb)),
			'px_mar' => ($tc_mar == 0 ? '' : number_format($px_mar)),
			'px_apr' => ($tc_apr == 0 ? '' : number_format($px_apr)),
			'px_mei' => ($tc_mei == 0 ? '' : number_format($px_mei)),
			'px_jun' => ($tc_jun == 0 ? '' : number_format($px_jun)),
			'px_jul' => ($tc_jul == 0 ? '' : number_format($px_jul)),
			'px_agu' => ($tc_agu == 0 ? '' : number_format($px_agu)),
			'px_sep' => ($tc_sep == 0 ? '' : number_format($px_sep)),
			'px_okt' => ($tc_okt == 0 ? '' : number_format($px_okt)),
			'px_nov' => ($tc_nov == 0 ? '' : number_format($px_nov)),
			'px_des' => ($tc_des == 0 ? '' : number_format($px_des)),

			'tc_rsd_jan' => ($tc_rsd_jan == 0 ? '' : number_format($tc_rsd_jan)),
			'ts_rsd_jan' => ($ts_rsd_jan == 0 ? '' : number_format($ts_rsd_jan)),
			'tc_rsd_feb' => ($tc_rsd_feb == 0 ? '' : number_format($tc_rsd_feb)),
			'ts_rsd_feb' => ($ts_rsd_feb == 0 ? '' : number_format($ts_rsd_feb)),
			'tc_rsd_mar' => ($tc_rsd_mar == 0 ? '' : number_format($tc_rsd_mar)),
			'ts_rsd_mar' => ($ts_rsd_mar == 0 ? '' : number_format($ts_rsd_mar)),
			'tc_rsd_apr' => ($tc_rsd_apr == 0 ? '' : number_format($tc_rsd_apr)),
			'ts_rsd_apr' => ($ts_rsd_apr == 0 ? '' : number_format($ts_rsd_apr)),
			'tc_rsd_mei' => ($tc_rsd_mei == 0 ? '' : number_format($tc_rsd_mei)),
			'ts_rsd_mei' => ($ts_rsd_mei == 0 ? '' : number_format($ts_rsd_mei)),
			'tc_rsd_jun' => ($tc_rsd_jun == 0 ? '' : number_format($tc_rsd_jun)),
			'ts_rsd_jun' => ($ts_rsd_jun == 0 ? '' : number_format($ts_rsd_jun)),
			'tc_rsd_jul' => ($tc_rsd_jul == 0 ? '' : number_format($tc_rsd_jul)),
			'ts_rsd_jul' => ($ts_rsd_jul == 0 ? '' : number_format($ts_rsd_jul)),
			'tc_rsd_agu' => ($tc_rsd_agu == 0 ? '' : number_format($tc_rsd_agu)),
			'ts_rsd_agu' => ($ts_rsd_agu == 0 ? '' : number_format($ts_rsd_agu)),
			'tc_rsd_sep' => ($tc_rsd_sep == 0 ? '' : number_format($tc_rsd_sep)),
			'ts_rsd_sep' => ($ts_rsd_sep == 0 ? '' : number_format($ts_rsd_sep)),
			'tc_rsd_okt' => ($tc_rsd_okt == 0 ? '' : number_format($tc_rsd_okt)),
			'ts_rsd_okt' => ($ts_rsd_okt == 0 ? '' : number_format($ts_rsd_okt)),
			'tc_rsd_nov' => ($tc_rsd_nov == 0 ? '' : number_format($tc_rsd_nov)),
			'ts_rsd_nov' => ($ts_rsd_nov == 0 ? '' : number_format($ts_rsd_nov)),
			'tc_rsd_des' => ($tc_rsd_des == 0 ? '' : number_format($tc_rsd_des)),
			'ts_rsd_des' => ($ts_rsd_des == 0 ? '' : number_format($ts_rsd_des)),

			'tc_mee_jan' => ($tc_mee_jan == 0 ? '' : number_format($tc_mee_jan)),
			'ts_mee_jan' => ($ts_mee_jan == 0 ? '' : number_format($ts_mee_jan)),
			'tc_mee_feb' => ($tc_mee_feb == 0 ? '' : number_format($tc_mee_feb)),
			'ts_mee_feb' => ($ts_mee_feb == 0 ? '' : number_format($ts_mee_feb)),
			'tc_mee_mar' => ($tc_mee_mar == 0 ? '' : number_format($tc_mee_mar)),
			'ts_mee_mar' => ($ts_mee_mar == 0 ? '' : number_format($ts_mee_mar)),
			'tc_mee_apr' => ($tc_mee_apr == 0 ? '' : number_format($tc_mee_apr)),
			'ts_mee_apr' => ($ts_mee_apr == 0 ? '' : number_format($ts_mee_apr)),
			'tc_mee_mei' => ($tc_mee_mei == 0 ? '' : number_format($tc_mee_mei)),
			'ts_mee_mei' => ($ts_mee_mei == 0 ? '' : number_format($ts_mee_mei)),
			'tc_mee_jun' => ($tc_mee_jun == 0 ? '' : number_format($tc_mee_jun)),
			'ts_mee_jun' => ($ts_mee_jun == 0 ? '' : number_format($ts_mee_jun)),
			'tc_mee_jul' => ($tc_mee_jul == 0 ? '' : number_format($tc_mee_jul)),
			'ts_mee_jul' => ($ts_mee_jul == 0 ? '' : number_format($ts_mee_jul)),
			'tc_mee_agu' => ($tc_mee_agu == 0 ? '' : number_format($tc_mee_agu)),
			'ts_mee_agu' => ($ts_mee_agu == 0 ? '' : number_format($ts_mee_agu)),
			'tc_mee_sep' => ($tc_mee_sep == 0 ? '' : number_format($tc_mee_sep)),
			'ts_mee_sep' => ($ts_mee_sep == 0 ? '' : number_format($ts_mee_sep)),
			'tc_mee_okt' => ($tc_mee_okt == 0 ? '' : number_format($tc_mee_okt)),
			'ts_mee_okt' => ($ts_mee_okt == 0 ? '' : number_format($ts_mee_okt)),
			'tc_mee_nov' => ($tc_mee_nov == 0 ? '' : number_format($tc_mee_nov)),
			'ts_mee_nov' => ($ts_mee_nov == 0 ? '' : number_format($ts_mee_nov)),
			'tc_mee_des' => ($tc_mee_des == 0 ? '' : number_format($tc_mee_des)),
			'ts_mee_des' => ($ts_mee_des == 0 ? '' : number_format($ts_mee_des)),

			'tc_poo_jan' => ($tc_poo_jan == 0 ? '' : number_format($tc_poo_jan)),
			'ts_poo_jan' => ($ts_poo_jan == 0 ? '' : number_format($ts_poo_jan)),
			'tc_poo_feb' => ($tc_poo_feb == 0 ? '' : number_format($tc_poo_feb)),
			'ts_poo_feb' => ($ts_poo_feb == 0 ? '' : number_format($ts_poo_feb)),
			'tc_poo_mar' => ($tc_poo_mar == 0 ? '' : number_format($tc_poo_mar)),
			'ts_poo_mar' => ($ts_poo_mar == 0 ? '' : number_format($ts_poo_mar)),
			'tc_poo_apr' => ($tc_poo_apr == 0 ? '' : number_format($tc_poo_apr)),
			'ts_poo_apr' => ($ts_poo_apr == 0 ? '' : number_format($ts_poo_apr)),
			'tc_poo_mei' => ($tc_poo_mei == 0 ? '' : number_format($tc_poo_mei)),
			'ts_poo_mei' => ($ts_poo_mei == 0 ? '' : number_format($ts_poo_mei)),
			'tc_poo_jun' => ($tc_poo_jun == 0 ? '' : number_format($tc_poo_jun)),
			'ts_poo_jun' => ($ts_poo_jun == 0 ? '' : number_format($ts_poo_jun)),
			'tc_poo_jul' => ($tc_poo_jul == 0 ? '' : number_format($tc_poo_jul)),
			'ts_poo_jul' => ($ts_poo_jul == 0 ? '' : number_format($ts_poo_jul)),
			'tc_poo_agu' => ($tc_poo_agu == 0 ? '' : number_format($tc_poo_agu)),
			'ts_poo_agu' => ($ts_poo_agu == 0 ? '' : number_format($ts_poo_agu)),
			'tc_poo_sep' => ($tc_poo_sep == 0 ? '' : number_format($tc_poo_sep)),
			'ts_poo_sep' => ($ts_poo_sep == 0 ? '' : number_format($ts_poo_sep)),
			'tc_poo_okt' => ($tc_poo_okt == 0 ? '' : number_format($tc_poo_okt)),
			'ts_poo_okt' => ($ts_poo_okt == 0 ? '' : number_format($ts_poo_okt)),
			'tc_poo_nov' => ($tc_poo_nov == 0 ? '' : number_format($tc_poo_nov)),
			'ts_poo_nov' => ($ts_poo_nov == 0 ? '' : number_format($ts_poo_nov)),
			'tc_poo_des' => ($tc_poo_des == 0 ? '' : number_format($tc_poo_des)),
			'ts_poo_des' => ($ts_poo_des == 0 ? '' : number_format($ts_poo_des)),

			'tc_res_jan' => ($tc_res_jan == 0 ? '' : number_format($tc_res_jan)),
			'ts_res_jan' => ($ts_res_jan == 0 ? '' : number_format($ts_res_jan)),
			'tc_res_feb' => ($tc_res_feb == 0 ? '' : number_format($tc_res_feb)),
			'ts_res_feb' => ($ts_res_feb == 0 ? '' : number_format($ts_res_feb)),
			'tc_res_mar' => ($tc_res_mar == 0 ? '' : number_format($tc_res_mar)),
			'ts_res_mar' => ($ts_res_mar == 0 ? '' : number_format($ts_res_mar)),
			'tc_res_apr' => ($tc_res_apr == 0 ? '' : number_format($tc_res_apr)),
			'ts_res_apr' => ($ts_res_apr == 0 ? '' : number_format($ts_res_apr)),
			'tc_res_mei' => ($tc_res_mei == 0 ? '' : number_format($tc_res_mei)),
			'ts_res_mei' => ($ts_res_mei == 0 ? '' : number_format($ts_res_mei)),
			'tc_res_jun' => ($tc_res_jun == 0 ? '' : number_format($tc_res_jun)),
			'ts_res_jun' => ($ts_res_jun == 0 ? '' : number_format($ts_res_jun)),
			'tc_res_jul' => ($tc_res_jul == 0 ? '' : number_format($tc_res_jul)),
			'ts_res_jul' => ($ts_res_jul == 0 ? '' : number_format($ts_res_jul)),
			'tc_res_agu' => ($tc_res_agu == 0 ? '' : number_format($tc_res_agu)),
			'ts_res_agu' => ($ts_res_agu == 0 ? '' : number_format($ts_res_agu)),
			'tc_res_sep' => ($tc_res_sep == 0 ? '' : number_format($tc_res_sep)),
			'ts_res_sep' => ($ts_res_sep == 0 ? '' : number_format($ts_res_sep)),
			'tc_res_okt' => ($tc_res_okt == 0 ? '' : number_format($tc_res_okt)),
			'ts_res_okt' => ($ts_res_okt == 0 ? '' : number_format($ts_res_okt)),
			'tc_res_nov' => ($tc_res_nov == 0 ? '' : number_format($tc_res_nov)),
			'ts_res_nov' => ($ts_res_nov == 0 ? '' : number_format($ts_res_nov)),
			'tc_res_des' => ($tc_res_des == 0 ? '' : number_format($tc_res_des)),
			'ts_res_des' => ($ts_res_des == 0 ? '' : number_format($ts_res_des)),

			'tc_mis_jan' => ($tc_mis_jan == 0 ? '' : number_format($tc_mis_jan)),
			'ts_mis_jan' => ($ts_mis_jan == 0 ? '' : number_format($ts_mis_jan)),
			'tc_mis_feb' => ($tc_mis_feb == 0 ? '' : number_format($tc_mis_feb)),
			'ts_mis_feb' => ($ts_mis_feb == 0 ? '' : number_format($ts_mis_feb)),
			'tc_mis_mar' => ($tc_mis_mar == 0 ? '' : number_format($tc_mis_mar)),
			'ts_mis_mar' => ($ts_mis_mar == 0 ? '' : number_format($ts_mis_mar)),
			'tc_mis_apr' => ($tc_mis_apr == 0 ? '' : number_format($tc_mis_apr)),
			'ts_mis_apr' => ($ts_mis_apr == 0 ? '' : number_format($ts_mis_apr)),
			'tc_mis_mei' => ($tc_mis_mei == 0 ? '' : number_format($tc_mis_mei)),
			'ts_mis_mei' => ($ts_mis_mei == 0 ? '' : number_format($ts_mis_mei)),
			'tc_mis_jun' => ($tc_mis_jun == 0 ? '' : number_format($tc_mis_jun)),
			'ts_mis_jun' => ($ts_mis_jun == 0 ? '' : number_format($ts_mis_jun)),
			'tc_mis_jul' => ($tc_mis_jul == 0 ? '' : number_format($tc_mis_jul)),
			'ts_mis_jul' => ($ts_mis_jul == 0 ? '' : number_format($ts_mis_jul)),
			'tc_mis_agu' => ($tc_mis_agu == 0 ? '' : number_format($tc_mis_agu)),
			'ts_mis_agu' => ($ts_mis_agu == 0 ? '' : number_format($ts_mis_agu)),
			'tc_mis_sep' => ($tc_mis_sep == 0 ? '' : number_format($tc_mis_sep)),
			'ts_mis_sep' => ($ts_mis_sep == 0 ? '' : number_format($ts_mis_sep)),
			'tc_mis_okt' => ($tc_mis_okt == 0 ? '' : number_format($tc_mis_okt)),
			'ts_mis_okt' => ($ts_mis_okt == 0 ? '' : number_format($ts_mis_okt)),
			'tc_mis_nov' => ($tc_mis_nov == 0 ? '' : number_format($tc_mis_nov)),
			'ts_mis_nov' => ($ts_mis_nov == 0 ? '' : number_format($ts_mis_nov)),
			'tc_mis_des' => ($tc_mis_des == 0 ? '' : number_format($tc_mis_des)),
			'ts_mis_des' => ($ts_mis_des == 0 ? '' : number_format($ts_mis_des)),

			'tc_swi_jan' => ($tc_swi_jan == 0 ? '' : number_format($tc_swi_jan)),
			'ts_swi_jan' => ($ts_swi_jan == 0 ? '' : number_format($ts_swi_jan)),
			'tc_swi_feb' => ($tc_swi_feb == 0 ? '' : number_format($tc_swi_feb)),
			'ts_swi_feb' => ($ts_swi_feb == 0 ? '' : number_format($ts_swi_feb)),
			'tc_swi_mar' => ($tc_swi_mar == 0 ? '' : number_format($tc_swi_mar)),
			'ts_swi_mar' => ($ts_swi_mar == 0 ? '' : number_format($ts_swi_mar)),
			'tc_swi_apr' => ($tc_swi_apr == 0 ? '' : number_format($tc_swi_apr)),
			'ts_swi_apr' => ($ts_swi_apr == 0 ? '' : number_format($ts_swi_apr)),
			'tc_swi_mei' => ($tc_swi_mei == 0 ? '' : number_format($tc_swi_mei)),
			'ts_swi_mei' => ($ts_swi_mei == 0 ? '' : number_format($ts_swi_mei)),
			'tc_swi_jun' => ($tc_swi_jun == 0 ? '' : number_format($tc_swi_jun)),
			'ts_swi_jun' => ($ts_swi_jun == 0 ? '' : number_format($ts_swi_jun)),
			'tc_swi_jul' => ($tc_swi_jul == 0 ? '' : number_format($tc_swi_jul)),
			'ts_swi_jul' => ($ts_swi_jul == 0 ? '' : number_format($ts_swi_jul)),
			'tc_swi_agu' => ($tc_swi_agu == 0 ? '' : number_format($tc_swi_agu)),
			'ts_swi_agu' => ($ts_swi_agu == 0 ? '' : number_format($ts_swi_agu)),
			'tc_swi_sep' => ($tc_swi_sep == 0 ? '' : number_format($tc_swi_sep)),
			'ts_swi_sep' => ($ts_swi_sep == 0 ? '' : number_format($ts_swi_sep)),
			'tc_swi_okt' => ($tc_swi_okt == 0 ? '' : number_format($tc_swi_okt)),
			'ts_swi_okt' => ($ts_swi_okt == 0 ? '' : number_format($ts_swi_okt)),
			'tc_swi_nov' => ($tc_swi_nov == 0 ? '' : number_format($tc_swi_nov)),
			'ts_swi_nov' => ($ts_swi_nov == 0 ? '' : number_format($ts_swi_nov)),
			'tc_swi_des' => ($tc_swi_des == 0 ? '' : number_format($tc_swi_des)),
			'ts_swi_des' => ($ts_swi_des == 0 ? '' : number_format($ts_swi_des)),

			'ttl_jan' => ($ttl_jan == 0 ? '' : number_format($ttl_jan)),
			'ttl_feb' => ($ttl_feb == 0 ? '' : number_format($ttl_feb)),
			'ttl_mar' => ($ttl_mar == 0 ? '' : number_format($ttl_mar)),
			'ttl_apr' => ($ttl_apr == 0 ? '' : number_format($ttl_apr)),
			'ttl_mei' => ($ttl_mei == 0 ? '' : number_format($ttl_mei)),
			'ttl_jun' => ($ttl_jun == 0 ? '' : number_format($ttl_jun)),
			'ttl_jul' => ($ttl_jul == 0 ? '' : number_format($ttl_jul)),
			'ttl_agu' => ($ttl_agu == 0 ? '' : number_format($ttl_agu)),
			'ttl_sep' => ($ttl_sep == 0 ? '' : number_format($ttl_sep)),
			'ttl_okt' => ($ttl_okt == 0 ? '' : number_format($ttl_okt)),
			'ttl_nov' => ($ttl_nov == 0 ? '' : number_format($ttl_nov)),
			'ttl_des' => ($ttl_des == 0 ? '' : number_format($ttl_des)),
		];

		output_json($response);
	}

}

?>
