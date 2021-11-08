<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_eaed extends BaseController
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
		$this->load->model('Checkin_model');
		$this->load->model('Users_model');
		$this->load->model('Rooms_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title'] = 'Laporan - EAED';
		
		$this->twiggy_display('adm/r_eaed/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_1');
		$data   = [];
		$data_book   = [];
		$data2  = [];
		$data3  = [];
		$data4  = [];
		$data5  = [];
		$data6  = [];
		
		// $where_header_1 = array('transaction_type' => 'C', 'shift_id' => '1', 'date_in <=' => $date_1, 'date_out >' => $date_2, 'a.room_type_id !=' => '1');
		// $where_header_2 = array('transaction_type' => 'C', 'shift_id' => '2', 'date_in <=' => $date_1, 'date_out >' => $date_2, 'a.room_type_id !=' => '1');
		// $where_header_3 = array('transaction_type' => 'C', 'shift_id' => '3', 'date_in <=' => $date_1, 'date_out >' => $date_2, 'a.room_type_id !=' => '1');

		$tommorrow = date('Y-m-d', strtotime($date_1 . ' +1 day'));

		$where_header_1 = 'transaction_type = "C" and date_in <= "'.$date_1.'" and date_out > "'.$date_2.'" and d.room_type_group != "Function Room" and (shift_id = "1" or (select DATEDIFF("'.$date_1.'", date_in)) > 0)';
		$where_header_2 = array('transaction_type' => 'C', 'shift_id' => '2', 'date_in' => $date_1, 'date_out >' => $date_2, 'd.room_type_group !=' => 'Function Room');
		$where_header_3 = array('transaction_type' => 'C', 'date_in' => $date_1, 'date_out >' => $date_2, 'd.room_type_group !=' => 'Function Room');

		$where_booking = array('transaction_type' => 'B','date_in' => $tommorrow, 'd.room_type_group !=' => 'Function Room');

		$order_header   = 'id ASC';
		$get_data_1     = $this->Checkin_model->get_data($where_header_1)->result();
		$get_data_2     = $this->Checkin_model->get_data($where_header_2)->result();
		$get_data_3     = $this->Checkin_model->get_data($where_header_1)->result();

		$get_data_booking = $this->Checkin_model->get_data($where_booking)->result();

		// echo json_encode($where_booking);
		// die();


		$total_pax_1 = 0;
		$total_pax_2 = 0;
		$total_pax_3 = 0;

		$total_pax_book = 0;


		$total_extra_bed_1 = 0;
		$total_extra_bed_2 = 0;
		$total_extra_bed_3 = 0;

		$total_extra_bed_book = 0;

		
		$total_room_rate_1 = 0;
		$total_room_rate_2 = 0;
		$total_room_rate_3 = 0;
		$total_discount_1  = 0;
		$total_discount_2  = 0;
		$total_discount_3  = 0;
		$total_room_rate_discount_1 = 0;
		$total_room_rate_discount_2 = 0;
		$total_room_rate_discount_3 = 0;

		$total_extra_bed_all          = 0;
		$total_pax_all                = 0;
		$total_room_rate_all          = 0;
		$total_discount_all           = 0;
		$total_room_rate_discount_all = 0;

		$room_sealeble = 0;
		$room_occupaid = 0;
		$room_sold = 0;
		$complimentary = 0;
		$house_use = 0;
		$percentage_occupancy = 0;
		$pax_summary = 0;
		$avg_room_rate = 0;
		$avg_rate_guest = 0;
		$room_rev_before_disc = 0;
		$room_allowance = 0;
		$total_room_revenue = 0;
		$total_non_room_revenue = 0;
		$total_all_revenue = 0;
		
		if($get_data_1)
		{	
			$no = 1;
			foreach($get_data_1 as $get_row)
			{	

				$select_extra       = 'a.*, b.*, c.*, SUM(a.cs_detail_quantity) as total_extra_bed';
				$where_extra        = array('cs_detail_item_id' => '2', 'cs_detail_item_type' => 'S', 'b.transaction_id' => $get_row->id);
				$get_extra_bed      = $this->Reports_model->cs_detail($select_extra, $where_extra)->row()->total_extra_bed;
				$extra_bed          = ($get_extra_bed == '' ? 0 : $get_extra_bed);

				// $room_rate          = $get_row->total_price;
				// $discount           = $get_row->total_price - $get_row->total;
				// $room_rate_discount = (($get_row->interval_stay * $get_row->total_price) - $discount);

				$date_now_check = change_format_date($date_1, 'D');

				$get_room_data  = $this->Rooms_model->get_data(array('a.room_id' => $get_row->room_id))->row();
				// OFF
				// $room_rate = 0;
				// if($get_room_data){
				// 	$room_rate      = $get_room_data->room_type_price_weekday;
				// 	if($date_now_check == 'Sat'){
				// 		$room_rate = $get_room_data->room_type_price_weekend;
				// 	}
				// }
				// OFF

				$room_rate      = $get_row->weekday_price;
				if($date_now_check == 'Sat'){
					$room_rate  = $get_row->weekend_price;
				}

				$discount           = ($get_row->total_price - $get_row->total);
				// $discount           = ($get_row->total_price - $get_row->total) / $get_row->interval_stay;
				$room_rate_discount = (($room_rate) - $discount);

				$get_paymethod = $this->Reports_model->get_data_payment_detail2('', array('a.transaction_id' => $get_row->id, 'a.transaction_type' => 'T'))->row();
				if($get_paymethod){
					if($get_paymethod->payment_method_1 == '2' || $get_paymethod->payment_method_2 == '2' || $get_paymethod->payment_method_3 == '2'){
						$room_rate = 0;
						$discount  = 0;
						$room_rate_discount = 0;
					}
				}

				$data[] = array(
					'no'                 => $no,
					'user'               => $this->Users_model->get_data_advance($get_row->user_id)->row()->fullname,
					'room_number'        => $get_row->room_number,
					'guest'              => $get_row->guest_name,
					'extra_bed_room'     => $extra_bed,
					'room_pax'           => $get_row->room_type_pax,
					'arrival_date'       => ($get_row->date_in  == '0000-00-00' ? '' : change_format_date($get_row->date_in, 'd/m/Y')),
					'departure_date'     => ($get_row->date_out == '0000-00-00' ? '' : change_format_date($get_row->date_out, 'd/m/Y')),
					'room_rate'          => number_format($room_rate),
					'discount'           => number_format($discount),
					'room_rate_discount' => number_format($room_rate_discount)
				);
                $no++;
                
				$total_extra_bed_1          += $extra_bed;
				$total_pax_1                += $get_row->room_type_pax;
				$total_room_rate_1          += $room_rate;
				$total_discount_1           += $discount;
				$total_room_rate_discount_1 += $room_rate_discount;
			}
		}

		if($get_data_2)
		{	
			$no = 1;
			foreach($get_data_2 as $get_row)
			{	

				$select_extra  = 'a.*, b.*, c.*, SUM(a.cs_detail_quantity) as total_extra_bed';
				$where_extra   = array('cs_detail_item_id' => '2', 'cs_detail_item_type' => 'S', 'b.transaction_id' => $get_row->id);
				$get_extra_bed = $this->Reports_model->cs_detail($select_extra, $where_extra)->row()->total_extra_bed;
				$extra_bed     = ($get_extra_bed == '' ? 0 : $get_extra_bed);

				$date_now_check = change_format_date($date_1, 'D');
				$get_room_data  = $this->Rooms_model->get_data(array('a.room_id' => $get_row->room_id))->row();
				$room_rate = 0;
				if($get_room_data){
					$room_rate      = $get_room_data->room_type_price_weekday;
					if($date_now_check == 'Sat'){
						$room_rate = $get_room_data->room_type_price_weekend;
					}
				}
				$discount           = ($get_row->total_price - $get_row->total);
				// $discount           = ($get_row->total_price - $get_row->total) / $get_row->interval_stay;
				$room_rate_discount = (($room_rate) - $discount);
				
				$get_paymethod = $this->Reports_model->get_data_payment_detail2('', array('a.transaction_id' => $get_row->id, 'a.transaction_type' => 'T'))->row();
				if($get_paymethod){
					if($get_paymethod->payment_method_1 == '2' || $get_paymethod->payment_method_2 == '2' || $get_paymethod->payment_method_3 == '2'){
						$room_rate = 0;
						$discount  = 0;
						$room_rate_discount = 0;
					}
				}

				$data2[] = array(
					'no'                 => $no,
					'user'               => $this->Users_model->get_data_advance($get_row->user_id)->row()->fullname,
					'room_number'        => $get_row->room_number,
					'guest'              => $get_row->guest_name,
					'extra_bed_room'     => $extra_bed,
					'room_pax'           => $get_row->room_type_pax,
					'arrival_date'       => ($get_row->date_in  == '0000-00-00' ? '' : change_format_date($get_row->date_in, 'd/m/Y')),
					'departure_date'     => ($get_row->date_out == '0000-00-00' ? '' : change_format_date($get_row->date_out, 'd/m/Y')),
					'room_rate'          => number_format($room_rate),
					'discount'           => number_format($discount),
					'room_rate_discount' => number_format($room_rate_discount)
				);
                $no++;
				
				$total_extra_bed_2          += $extra_bed;
				$total_pax_2                += $get_row->room_type_pax;
				$total_room_rate_2          += $room_rate;
				$total_discount_2           += $discount;
				$total_room_rate_discount_2 += $room_rate_discount;
			}
		}

		if($get_data_3)
		{	
			$no = 1;
			foreach($get_data_3 as $get_row)
			{	

				$select_extra  = 'a.*, b.*, c.*, SUM(a.cs_detail_quantity) as total_extra_bed';
				$where_extra   = array('cs_detail_item_id' => '2', 'cs_detail_item_type' => 'S', 'b.transaction_id' => $get_row->id);
				$get_extra_bed = $this->Reports_model->cs_detail($select_extra, $where_extra)->row()->total_extra_bed;
				$extra_bed     = ($get_extra_bed == '' ? 0 : $get_extra_bed);

				$date_now_check = change_format_date($date_1, 'D');
				$get_room_data  = $this->Rooms_model->get_data(array('a.room_id' => $get_row->room_id))->row();
				$room_rate = 0;
				if($get_room_data){
					$room_rate      = $get_room_data->room_type_price_weekday;
					if($date_now_check == 'Sat'){
						$room_rate = $get_room_data->room_type_price_weekend;
					}
				}
				$discount           = ($get_row->total_price - $get_row->total);
				// $discount           = ($get_row->total_price - $get_row->total) / $get_row->interval_stay;
				$room_rate_discount = (($room_rate) - $discount);

				$get_paymethod = $this->Reports_model->get_data_payment_detail2('', array('a.transaction_id' => $get_row->id, 'a.transaction_type' => 'T'))->row();
				if($get_paymethod){
					if($get_paymethod->payment_method_1 == '2' || $get_paymethod->payment_method_2 == '2' || $get_paymethod->payment_method_3 == '2'){
						$room_rate = 0;
						$discount  = 0;
						$room_rate_discount = 0;
					}
				}

				$data3[] = array(
					'no'                 => $no,
					'user'               => $this->Users_model->get_data_advance($get_row->user_id)->row()->fullname,
					'room_number'        => $get_row->room_number,
					'guest'              => $get_row->guest_name,
					'extra_bed_room'     => $extra_bed,
					'room_pax'           => $get_row->room_type_pax,
					'arrival_date'       => ($get_row->date_in  == '0000-00-00' ? '' : change_format_date($get_row->date_in, 'd/m/Y')),
					'departure_date'     => ($get_row->date_out == '0000-00-00' ? '' : change_format_date($get_row->date_out, 'd/m/Y')),
					'room_rate'          => number_format($room_rate),
					'discount'           => number_format($discount),
					'room_rate_discount' => number_format($room_rate_discount)
				);
                $no++;
                
				$total_extra_bed_3          += $extra_bed;
				$total_pax_3                += $get_row->room_type_pax;
				$total_room_rate_3          += $room_rate;
				$total_discount_3           += $discount;
				$total_room_rate_discount_3 += $room_rate_discount;
			}
		}

		if($get_data_booking)
		{	
			$no = 1;
			foreach($get_data_booking as $get_row)
			{	

				$select_extra       = 'a.*, b.*, c.*, SUM(a.cs_detail_quantity) as total_extra_bed';
				$where_extra        = array('cs_detail_item_id' => '2', 'cs_detail_item_type' => 'S', 'b.transaction_id' => $get_row->id);
				$get_extra_bed      = $this->Reports_model->cs_detail($select_extra, $where_extra)->row()->total_extra_bed;
				$extra_bed          = ($get_extra_bed == '' ? 0 : $get_extra_bed);

				// $room_rate          = $get_row->total_price;
				// $discount           = $get_row->total_price - $get_row->total;
				// $room_rate_discount = (($get_row->interval_stay * $get_row->total_price) - $discount);

				$date_now_check = change_format_date($date_1, 'D');

				$get_room_data  = $this->Rooms_model->get_data(array('a.room_id' => $get_row->room_id))->row();
				// OFF
				// $room_rate = 0;
				// if($get_room_data){
				// 	$room_rate      = $get_room_data->room_type_price_weekday;
				// 	if($date_now_check == 'Sat'){
				// 		$room_rate = $get_room_data->room_type_price_weekend;
				// 	}
				// }
				// OFF

				$room_rate      = $get_row->weekday_price;
				if($date_now_check == 'Sat'){
					$room_rate  = $get_row->weekend_price;
				}

				$discount           = ($get_row->total_price - $get_row->total);
				// $discount           = ($get_row->total_price - $get_row->total) / $get_row->interval_stay;
				$room_rate_discount = (($room_rate) - $discount);

				$get_paymethod = $this->Reports_model->get_data_payment_detail2('', array('a.transaction_id' => $get_row->id, 'a.transaction_type' => 'T'))->row();
				if($get_paymethod){
					if($get_paymethod->payment_method_1 == '2' || $get_paymethod->payment_method_2 == '2' || $get_paymethod->payment_method_3 == '2'){
						$room_rate = 0;
						$discount  = 0;
						$room_rate_discount = 0;
					}
				}

				$data_book[] = array(
					'no'                 => $no,
					'user'               => $this->Users_model->get_data_advance($get_row->user_id)->row()->fullname,
					'room_number'        => $get_row->room_number,
					'guest'              => $get_row->guest_name,
					'extra_bed_room'     => $extra_bed,
					'room_pax'           => $get_row->room_type_pax,
					'arrival_date'       => ($get_row->date_in  == '0000-00-00' ? '' : change_format_date($get_row->date_in, 'd/m/Y')),
					'departure_date'     => ($get_row->date_out == '0000-00-00' ? '' : change_format_date($get_row->date_out, 'd/m/Y')),
					'room_rate'          => number_format($room_rate),
					'discount'           => number_format($discount),
					'room_rate_discount' => number_format($room_rate_discount)
				);
                $no++;
                
				$total_extra_bed_book         += $extra_bed;
				$total_pax_book               += $get_row->room_type_pax;
				// $total_room_rate_1          += $room_rate;
				// $total_discount_1           += $discount;
				// $total_room_rate_discount_1 += $room_rate_discount;
			}
		}

		$order_guestgroup      = 'guest_group_name ASC';
		$get_guestgroup        = $this->Reports_model->get_guest_group('', '', $order_guestgroup)->result();
		$guest_group_total     = 0;
		$guest_group_residence = 0;

		if($get_guestgroup)
		{	
			$no = 1;
			foreach($get_guestgroup as $get_row)
			{	
				// $where_room           = array('c.guest_group_id' => $get_row->guest_group_id, 'd.room_type_id !=' => '1', 'date_in <=' => $date_1, 'date_out >' => $date_2);
				// $where_residence      = array('c.guest_group_id' => $get_row->guest_group_id, 'd.room_type_id' => '1', 'date_in <=' => $date_1, 'date_out >' => $date_2);
				$where_room           = array('transaction_type' => 'C', 'c.guest_group_id' => $get_row->guest_group_id, 'd.room_type_group !=' => 'Function Room', 'date_in <=' => $date_1, 'date_out >' => $date_2);
				$where_residence      = array('transaction_type' => 'C', 'c.guest_group_id' => $get_row->guest_group_id, 'd.room_type_group' => 'Function Room', 'date_in <=' => $date_1, 'date_out >' => $date_2);
				$total_stay_room      = count($this->Checkin_model->get_data($where_room)->result());
				$total_stay_residence = count($this->Checkin_model->get_data($where_residence)->result());
				$total_stay_all       = ($total_stay_room + $total_stay_residence);

				$data4[] = array(
					'no'                   => $no,
					'guest_group_id'       => $get_row->guest_group_id,
					'guest_group'          => $get_row->guest_group_name,
					'total_stay_room'      => number_format($total_stay_room),
					'total_stay_residence' => number_format($total_stay_residence),
					'total_stay_all'       => number_format($total_stay_all)
				);
                $no++;
                
				$guest_group_total     += $total_stay_all;
				$guest_group_residence += $total_stay_residence;
			}
		}

		$get_froom   = $this->Reports_model->get_room('', array('room_type_group' => 'Function Room'), '')->result();
		$froom_rate_total = 0;

		if($get_froom)
		{	
			$no = 1;
			foreach($get_froom as $get_row)
			{	

				// $select_froom    = 'a.*, b.*, c.*, d.*, SUM(a.cs_detail_quantity) as total_extra_bed';
				// $where_froom     = array('cs_detail_item_id' => '1', 'cs_detail_item_type' => 'S', 'd.room_type_id' => '1');
				// $get_froom = $this->Reports_model->cs_detail_2($select_froom, $where_froom)->row();

				// $where_check    = array('transaction_type' => 'C', 'date_in <=' => $date_1, 'date_out >' => $date_2, 'd.room_type_id' => '1', 'e.room_id' => $get_row->room_id);
				$where_check = 'transaction_type = "C" and d.room_type_group = "Function Room" and e.room_id = "'.$get_row->room_id.'" and (date_in <= "'.$date_1.'" and date_out > "'.$date_2.'" or date_in = "'.$date_1.'" and date_out = "'.$date_2.'")';
				$get_data_check = $this->Checkin_model->get_data($where_check)->row();

				// echo json_encode($get_data_check);
				// die();
				
				$on_behalf = '';
				$pax = 0;
				$rate = 0;
				if($get_data_check){
					$on_behalf = $this->Guests_model->get_data(array('guest_id' => $get_data_check->on_behalf))->row()->guest_name;
					$pax  = ($get_data_check->on_behalf == '' ? 0 : $get_row->room_type_pax);

					$date_now_check = change_format_date($date_1, 'D');
					$get_room_data  = $this->Rooms_model->get_data(array('a.room_id' => $get_row->room_id))->row();
					$room_rate = 0;
					if($get_room_data){
						$room_rate      = $get_room_data->room_type_price_weekday;
						if($date_now_check == 'Sat'){
							$room_rate = $get_room_data->room_type_price_weekend;
						}
					}

					$discount           = ($get_data_check->total_price - $get_data_check->total);
					// $discount           = ($get_row->total_price - $get_row->total) / $get_row->interval_stay;
					$room_rate_discount = (($room_rate) - $discount);
					
					$rate = ($get_data_check->on_behalf == '' ? 0 : $room_rate_discount);

					$get_paymethod = $this->Reports_model->get_data_payment_detail2('', array('a.transaction_id' => $get_data_check->id, 'a.transaction_type' => 'T'))->row();
					if($get_paymethod){
						if($get_paymethod->payment_method_1 == '2' || $get_paymethod->payment_method_2 == '2' || $get_paymethod->payment_method_3 == '2'){
							$rate = 0;
						}
					}
				}
				

				$data5[] = array(
					'no'             => $no,
					'room_number'    => $get_row->room_number,
					'group_event'    => $on_behalf,
					'pax'            => $pax,
					'total_extrabed' => number_format($rate),
				);
                $no++;

				$froom_rate_total += $rate;

			}
		}

		$select_trx = 'cs_detail_item_name, sum(cs_detail_total) as grand_total';
		$where_trx  = array('cs_header_date >=' => $date_1, 'cs_header_date <=' => $date_2);
		$get_trx    = $this->Reports_model->cs_detail_3($select_trx, $where_trx, '', 'cs_detail_item_id')->result();
		$trx_total  = 0;


		if($get_trx)
		{	
			$no = 1;
			foreach($get_trx as $get_row)
			{	

				$data6[] = array(
					'no'          => $no,
					'item_name'   => $get_row->cs_detail_item_name,
					'grand_total' => number_format($get_row->grand_total),
				);
                $no++;

				$trx_total += $get_row->grand_total;

			}
		}

		$total_extra_bed_all          = ($total_extra_bed_3);
		$total_pax_all                = ($total_pax_3);
		$total_room_rate_all          = ($total_room_rate_1 + $total_room_rate_2 + $total_room_rate_3);
		$total_discount_all           = ($total_discount_1 + $total_discount_2 + $total_discount_3);
		$total_room_rate_discount_all = ($total_room_rate_discount_1 + $total_room_rate_discount_2 + $total_room_rate_discount_3);

		// Summary
		$room_active            = count($this->Rooms_model->get_data(array('room_active' => '1', 'b.room_type_group !=' => 'Function Room'))->result());
		$room_nonactive         = count($this->Rooms_model->get_data(array('room_active' => '0', 'b.room_type_group !=' => 'Function Room'))->result());
		$room_sealeble          = $room_active;
		$room_occupaid          = $guest_group_total;
		$room_sold              = $guest_group_total;
		$complimentary          = count($this->Reports_model->payment_header(array('payment_date' => $date_1), '2')->result());
		$house_use              = count($this->Reports_model->payment_header(array('payment_date' => $date_1), '3')->result());
		$percentage_occupancy   = ($guest_group_total/$room_sealeble) * 100;
		$pax_summary            = $total_pax_all;
		$avg_room_rate          = ($room_sold == 0 ? 0 : $total_room_rate_discount_all/$room_sold);
		$avg_rate_guest         = ($pax_summary == 0 ? 0 : $total_room_rate_discount_all/$pax_summary);
		$room_rev_before_disc   = $total_room_rate_all;
		$room_allowance         = $total_discount_all;
		$total_room_revenue     = $total_room_rate_discount_all;
		$total_non_room_revenue = $froom_rate_total + $trx_total;

		$total_all_revenue = $total_room_revenue + $total_non_room_revenue;

		$response = [
			'date_1'                       => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2'                       => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'data'                         => $data,
			'data2'                        => $data2,
			'data3'                        => $data3,
			'data4'                        => $data4,
			'data5'                        => $data5,
			'data6'                        => $data6,
			'data_book'                        => $data_book,
			'total_extra_bed_1'            => number_format($total_extra_bed_1),
			'total_extra_bed_2'            => number_format($total_extra_bed_2),
			'total_extra_bed_3'            => number_format($total_extra_bed_3),
			'total_extra_bed_book'            => number_format($total_extra_bed_book),
			'total_pax_1'                  => number_format($total_pax_1),
			'total_pax_2'                  => number_format($total_pax_2),
			'total_pax_3'                  => number_format($total_pax_3),
			'total_pax_book'                  => number_format($total_pax_book),
			'total_room_rate_1'            => number_format($total_room_rate_1),
			'total_room_rate_2'            => number_format($total_room_rate_2),
			'total_room_rate_3'            => number_format($total_room_rate_3),
			'total_discount_1'             => number_format($total_discount_1),
			'total_discount_2'             => number_format($total_discount_2),
			'total_discount_3'             => number_format($total_discount_3),
			'total_room_rate_discount_1'   => number_format($total_room_rate_discount_1),
			'total_room_rate_discount_2'   => number_format($total_room_rate_discount_2),
			'total_room_rate_discount_3'   => number_format($total_room_rate_discount_3),
			'total_extra_bed_all'          => number_format($total_extra_bed_all),
			'total_pax_all'                => number_format($total_pax_all),
			'total_room_rate_all'          => number_format($total_room_rate_all),
			'total_discount_all'           => number_format($total_discount_all),
			'total_room_rate_discount_all' => number_format($total_room_rate_discount_all),
			'guest_group_total'            => number_format($guest_group_total),
			'guest_group_residence'        => number_format($guest_group_residence),
			'froom_rate_total'             => number_format($froom_rate_total),
			'trx_total'                    => number_format($trx_total),

			'room_sealeble'          => number_format($room_sealeble),
			'room_occupaid'          => number_format($room_occupaid),
			'room_sold'              => number_format($room_sold),
			'complimentary'          => number_format($complimentary),
			'house_use'              => number_format($house_use),
			'percentage_occupancy'   => number_format($percentage_occupancy),
			'pax_summary'            => number_format($pax_summary),
			'avg_room_rate'          => number_format($avg_room_rate),
			'avg_rate_guest'         => number_format($avg_rate_guest),
			'room_rev_before_disc'   => number_format($room_rev_before_disc),
			'room_allowance'         => number_format($room_allowance),
			'total_room_revenue'     => number_format($total_room_revenue),
			'total_non_room_revenue' => number_format($total_non_room_revenue),
			'total_all_revenue'      => number_format($total_all_revenue),
		];

		output_json($response);
	}

}

?>
