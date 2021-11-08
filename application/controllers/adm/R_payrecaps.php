<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_payrecaps extends BaseController
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
        $data['content_title']  = 'Laporan - Pembayaran (Rekap)';
        $data['shift_data']     = $this->Checkin_model->get_data_shift()->result();
        $data['paymethod_data'] = $this->Payment_methods_model->get_data()->result();
		
		$this->twiggy_display('adm/r_payrecaps/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1    = $this->input->post('date_1');
		$date_2    = $this->input->post('date_2');
		$paymethod = $this->input->post('paymethod');
		$shift     = $this->input->post('shift');
		$data      = [];
		$where     = [];

		
		$where = array('payment_date >=' => $date_1, 'payment_date <=' => $date_2);
		$order = 'header_id ASC';
		$get_data = $this->Reports_model->payment_header($where, $paymethod, $shift, $order)->result();

		
		$total_amount = 0;
		
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
				$total   = $get_row->total_paid_1 + $get_row->total_paid_2 + $get_row->total_paid_3;
				$shift   = $this->Checkin_model->get_data_shift(array('shift_id' => $get_row->shift_id))->row();

				$data[] = array(
					'no'      => $no,
					'id'      => $get_row->header_id,
					'number'  => $get_row->payment_number,
					'date'    => change_format_date($get_row->payment_date, 'd/m/Y'),
					'guest'   => ($get_row->guest_id == 0 ? 'Bukan Tamu Menginap' : $get_row->guest_name),
					'pmethod' => $pmethod,
					'total'   => number_format($total),
					'shift'   => $shift->shift_name .'('.$shift->shift_time.')'
				);
				$no++;
                
				$total_amount += $total;
				
			}
		}


		$response = [
			'date_1'       => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2'       => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'data'         => $data,
			'total_amount' => number_format($total_amount)
		];

		output_json($response);
	}

}

?>
