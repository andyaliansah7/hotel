<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_deprecaps extends BaseController
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
        $data['content_title']  = 'Laporan - Deposit';
        $data['shift_data']     = $this->Checkin_model->get_data_shift()->result();
        $data['paymethod_data'] = $this->Payment_methods_model->get_data()->result();
		
		$this->twiggy_display('adm/r_deprecaps/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1    = $this->input->post('date_1');
		$date_2    = $this->input->post('date_2');
		$paymethod = $this->input->post('paymethod');
		$status    = $this->input->post('status');
		$data      = [];
		$where     = [];

		if(isset($status) && $status == ""){
			$where = array('deposit_date >=' => $date_1, 'deposit_date <=' => $date_2);
		}

		if(isset($status) && $status == 1){
			$where = array('deposit_date >=' => $date_1, 'deposit_date <=' => $date_2, 'payment_id !=' => '0');
		}

		if(isset($status) && $status == 2){
			$where = array('deposit_date >=' => $date_1, 'deposit_date <=' => $date_2, 'payment_id' => '0');
		}

		// echo isset($status);
		// die();	
		
		$order = 'header_id ASC';
		$get_data = $this->Reports_model->get_deposit($where, $paymethod, $order)->result();

		
		$total_amount = 0;
		$total_kartu  = 0;
		$total_tunai  = 0;
		$total_trans  = 0;
		
		if($get_data)
		{	
			$no = 1;
			foreach($get_data as $get_row)
			{	
				
				$kartu = $get_row->deposit_kartu;
				$tunai = $get_row->deposit_tunai;
				$trans = $get_row->deposit_trans;

				$total_dep = ($kartu) + ($tunai) + ($trans);

				$data[] = array(
					'no'             => $no,
					'id'             => $get_row->deposit_id,
					'date'           => change_format_date($get_row->deposit_date, 'd/m/Y'),
					'guest'          => $get_row->guest_name,
					'total_kartu'    => number_format($kartu),
					'total_tunai'    => number_format($tunai),
					'total_trans'    => number_format($trans),
					'total'          => number_format($total_dep),
					'desc'           => $get_row->deposit_description,
				);
				$no++;
				
				$total_amount += $total_dep;
				$total_kartu  += $kartu;
				$total_tunai  += $tunai;
				$total_trans  += $trans;
				
			}
		}


		$response = [
			'date_1'       => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2'       => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'data'         => $data,
			'total_amount' => number_format($total_amount),
			'total_kartu'  => number_format($total_kartu),
			'total_tunai'  => number_format($total_tunai),
			'total_trans'  => number_format($total_trans)
		];

		output_json($response);
	}

}

?>
