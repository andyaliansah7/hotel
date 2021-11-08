<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_monthrecaps extends BaseController
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
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title']  = 'Laporan - Bulanan (Rekap)';
        // $data['shift_data']     = $this->Checkin_model->get_data_shift()->result();
        // $data['paymethod_data'] = $this->Payment_methods_model->get_data()->result();
		
		$this->twiggy_display('adm/r_monthrecaps/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1    = $this->input->post('date_1');
		$date_2    = $this->input->post('date_2');
		// $date_1    = '2021-03-01';
		// $date_2    = '2021-03-05';
		// $paymethod = $this->input->post('paymethod');
		$data      = [];
		$where     = [];

		
		$where = array('payment_date >=' => $date_1, 'payment_date <=' => $date_2);
		$order = 'payment_date ASC';
		$get_data = $this->Reports_model->get_data_payment('', $where, 'payment_date', $order)->result();
		
		$total_all_kartu    = 0;
		$total_all_tunai    = 0;
		$total_all_transfer = 0;
		$total_all_method   = 0;

		$line = 1;
		if($get_data)
		{	
			$no = 1;
			foreach($get_data as $k => $get_row)
			{	
				$where_kartu_1 = array('payment_date' => $get_row->payment_date, 'payment_method_type_1' => 'Kartu');
				$total_kartu_1 = $this->Reports_model->get_data_payment('SUM(a.total_paid_1) as total_paid_1', $where_kartu_1)->row()->total_paid_1;
				$where_kartu_2 = array('payment_date' => $get_row->payment_date, 'payment_method_type_2' => 'Kartu');
				$total_kartu_2 = $this->Reports_model->get_data_payment('SUM(a.total_paid_2) as total_paid_2', $where_kartu_2)->row()->total_paid_2;
				$where_kartu_3 = array('payment_date' => $get_row->payment_date, 'payment_method_type_3' => 'Kartu');
				$total_kartu_3 = $this->Reports_model->get_data_payment('SUM(a.total_paid_3) as total_paid_3', $where_kartu_3)->row()->total_paid_3;

				$where_tunai_1 = array('payment_date' => $get_row->payment_date, 'payment_method_type_1' => 'Tunai');
				$total_tunai_1 = $this->Reports_model->get_data_payment('SUM(a.total_paid_1) as total_paid_1', $where_tunai_1)->row()->total_paid_1;
				$where_tunai_2 = array('payment_date' => $get_row->payment_date, 'payment_method_type_2' => 'Tunai');
				$total_tunai_2 = $this->Reports_model->get_data_payment('SUM(a.total_paid_2) as total_paid_2', $where_tunai_2)->row()->total_paid_2;
				$where_tunai_3 = array('payment_date' => $get_row->payment_date, 'payment_method_type_3' => 'Tunai');
				$total_tunai_3 = $this->Reports_model->get_data_payment('SUM(a.total_paid_3) as total_paid_3', $where_tunai_3)->row()->total_paid_3;

				$where_transfer_1 = array('payment_date' => $get_row->payment_date, 'payment_method_type_1' => 'Transfer');
				$total_transfer_1 = $this->Reports_model->get_data_payment('SUM(a.total_paid_1) as total_paid_1', $where_transfer_1)->row()->total_paid_1;
				$where_transfer_2 = array('payment_date' => $get_row->payment_date, 'payment_method_type_2' => 'Transfer');
				$total_transfer_2 = $this->Reports_model->get_data_payment('SUM(a.total_paid_2) as total_paid_2', $where_transfer_2)->row()->total_paid_2;
				$where_transfer_3 = array('payment_date' => $get_row->payment_date, 'payment_method_type_3' => 'Transfer');
				$total_transfer_3 = $this->Reports_model->get_data_payment('SUM(a.total_paid_3) as total_paid_3', $where_transfer_3)->row()->total_paid_3;


				$total_kartu    = $total_kartu_1 + $total_kartu_2 + $total_kartu_3;
				$total_tunai    = $total_tunai_1 + $total_tunai_2 + $total_tunai_3;
				$total_transfer = $total_transfer_1 + $total_transfer_2 + $total_transfer_3;
				$total_all      = $total_kartu + $total_tunai + $total_transfer;

				$data[] = [
					'header_date'     => indonesian_date($get_row->payment_date),
					'header_kartu'    => number_format($total_kartu),
					'header_tunai'    => number_format($total_tunai),
					'header_transfer' => number_format($total_transfer),
					'header_total'    => number_format($total_all),
				];

				$where_detail = array('payment_date' => $get_row->payment_date);
				$detail = $this->Reports_model->get_data_payment('', $where_detail)->result();

				$a = 0;
				$rowspan = 1;

				foreach ($detail as $key => $value) {

					$where_1 = array('payment_method_id' => $value->payment_method_1);
					$getme_1  = $this->Payment_methods_model->get_data($where_1)->row();
					if($getme_1){
						$ptype_1  = $getme_1->payment_method_type;
					}

					$where_2 = array('payment_method_id' => $value->payment_method_2);
					$getme_2  = $this->Payment_methods_model->get_data($where_2)->row();
					if($getme_2){
						$ptype_2  = $getme_2->payment_method_type;
					}

					$where_3 = array('payment_method_id' => $value->payment_method_3);
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

					$kartu = 0;
					$tunai = 0;
					$transfer = 0;
					$t_all = 0;

					if($ptype_1 == 'Kartu'){
						$total_paid_kartu_1 = $value->total_paid_1;
					}
	
					if($ptype_2 == 'Kartu'){
						$total_paid_kartu_2 = $value->total_paid_2;
					}
	
					if($ptype_3 == 'Kartu'){
						$total_paid_kartu_3 = $value->total_paid_3;
					}
	
					if($ptype_1 == 'Tunai'){
						$total_paid_tunai_1 = $value->total_paid_1;
					}
	
					if($ptype_2 == 'Tunai'){
						$total_paid_tunai_2 = $value->total_paid_2;
					}
	
					if($ptype_3 == 'Tunai'){
						$total_paid_tunai_3 = $value->total_paid_3;
					}
	
					if($ptype_1 == 'Transfer'){
						$total_paid_trans_1 = $value->total_paid_1;
					}
	
					if($ptype_2 == 'Transfer'){
						$total_paid_trans_2 = $value->total_paid_2;
					}
	
					if($ptype_3 == 'Transfer'){
						$total_paid_trans_3 = $value->total_paid_3;
					}

					$kartu    = $total_paid_kartu_1 + $total_paid_kartu_2 + $total_paid_kartu_3;
					$tunai    = $total_paid_tunai_1 + $total_paid_tunai_2 + $total_paid_tunai_3;
					$transfer = $total_paid_trans_1 + $total_paid_trans_2 + $total_paid_trans_3;
					$t_all = $kartu + $tunai + $transfer;

					$data[$k]['detail'][$key] = array(	
						'detail_date'      => indonesian_date($value->payment_date),
						'detail_rowspan'   => $rowspan,
						'detail_line'      => $line,
						'detail_number'    => $value->payment_number,
						'detail_kartu'     => number_format($kartu),
						'detail_tunai'     => number_format($tunai),
						'detail_transfer'  => number_format($transfer),
						'detail_total_all' => number_format($t_all),

						// 'grandTotal' => to_currency($a += $value->maintenance_detail_total),
					);
					$rowspan++;
					$line++;

					$total_all_kartu    += $kartu;
					$total_all_tunai    += $tunai;
					$total_all_transfer += $transfer;
					$total_all_method   += $t_all;
					
				}
				$no++;
				
				
			}
			
		}


		$response = [
			'date_1'             => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2'             => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'detail_data'        => $data,
			'total_all_kartu'    => number_format($total_all_kartu),
			'total_all_tunai'    => number_format($total_all_tunai),
			'total_all_transfer' => number_format($total_all_transfer),
			'total_all_method'   => number_format($total_all_method)
		];

		output_json($response);
	}

}

?>
