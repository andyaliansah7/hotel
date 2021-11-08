<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_monthrecaps2 extends BaseController
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
		
		$this->twiggy_display('adm/r_monthrecaps2/index', $data);
	}

	public function get_data_detail()
	{	
		// $date_1      = $this->input->post('date_1');
		// $date_2      = $this->input->post('date_2');
		$date_1      = '2021-03-10';
		$date_2      = '2021-03-11';
		$date_3 = date('Y-m-d', strtotime($date_2. '+1 day'));

		$data        = [];
		
		

		$total_kartu = 0;
		$total_tunai = 0;
		$total_trans = 0;
		$total_allmethod = 0;

		$pemb_kartu = 0;
		$pemb_tunai = 0;
		$pemb_trans = 0;

		$depo_kartu = 0;
		$depo_tunai = 0;
		$depo_trans = 0;

		$depo_pemb_kartu = 0;
		$depo_pemb_tunai = 0;
		$depo_pemb_trans = 0;

		$daterange = new DatePeriod(new DateTime($date_1), new DateInterval('P1D'), new DateTime($date_3));
		
		$no = 1;
		foreach($daterange as $date){
			$p_select_1 = '*, COALESCE(SUM(total_room_1 + total_service_1 + total_consumption_1),0) as total';
			$p_select_2 = '*, COALESCE(SUM(total_room_2 + total_service_2 + total_consumption_2),0) as total';
			$p_select_3 = '*, COALESCE(SUM(total_room_3 + total_service_3 + total_consumption_3),0) as total';
			$p_where_kartu_1 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_1' => 'Kartu', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_kartu_2 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_2' => 'Kartu', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_kartu_3 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_3' => 'Kartu', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_tunai_1 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_1' => 'Tunai', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_tunai_2 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_2' => 'Tunai', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_tunai_3 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_3' => 'Tunai', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_trans_1 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_1' => 'Transfer', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_trans_2 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_2' => 'Transfer', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$p_where_trans_3 = array('payment_date' => $date->format("Y-m-d"), 'payment_method_type_3' => 'Transfer', 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);

			$d_select_kartu = '*, COALESCE(SUM(deposit_kartu),0) as total';
			$d_select_tunai = '*, COALESCE(SUM(deposit_tunai),0) as total';
			$d_select_trans = '*, COALESCE(SUM(deposit_trans),0) as total';
			$d_where_kartu  = array('deposit_date' => $date->format("Y-m-d"));
			$d_where_tunai  = array('deposit_date' => $date->format("Y-m-d"));
			$d_where_trans  = array('deposit_date' => $date->format("Y-m-d"));

			$dp_select_kartu = '*, COALESCE(SUM(total_deposit_kartu),0) as total';
			$dp_select_tunai = '*, COALESCE(SUM(total_deposit_tunai),0) as total';
			$dp_select_trans = '*, COALESCE(SUM(total_deposit_trans),0) as total';
			$dp_where_kartu  = array('payment_date' => $date->format("Y-m-d"), 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$dp_where_tunai  = array('payment_date' => $date->format("Y-m-d"), 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);
			$dp_where_trans  = array('payment_date' => $date->format("Y-m-d"), 'payment_method_1 !=' => 1, 'payment_method_1 !=' => 3);

			$order = 'payment_date';

			$kartu = 0;
			$tunai = 0;
			$trans = 0;
			$allmethod = 0;

			$p_kartu_1   = $this->Reports_model->get_data_payment_monthrecap($p_select_1, $p_where_kartu_1, '', $order)->row()->total;
			$p_kartu_2   = $this->Reports_model->get_data_payment_monthrecap($p_select_2, $p_where_kartu_2, '', $order)->row()->total;
			$p_kartu_3   = $this->Reports_model->get_data_payment_monthrecap($p_select_3, $p_where_kartu_3, '', $order)->row()->total;
			$p_tunai_1   = $this->Reports_model->get_data_payment_monthrecap($p_select_1, $p_where_tunai_1, '', $order)->row()->total;
			$p_tunai_2   = $this->Reports_model->get_data_payment_monthrecap($p_select_2, $p_where_tunai_2, '', $order)->row()->total;
			$p_tunai_3   = $this->Reports_model->get_data_payment_monthrecap($p_select_3, $p_where_tunai_3, '', $order)->row()->total;
			$p_trans_1   = $this->Reports_model->get_data_payment_monthrecap($p_select_1, $p_where_trans_1, '', $order)->row()->total;
			$p_trans_2   = $this->Reports_model->get_data_payment_monthrecap($p_select_2, $p_where_trans_2, '', $order)->row()->total;
			$p_trans_3   = $this->Reports_model->get_data_payment_monthrecap($p_select_3, $p_where_trans_3, '', $order)->row()->total;

			$d_kartu   = $this->Reports_model->get_data_deposit_main($d_select_kartu, $d_where_kartu)->row()->total;
			$d_tunai   = $this->Reports_model->get_data_deposit_main($d_select_tunai, $d_where_tunai)->row()->total;
			$d_trans   = $this->Reports_model->get_data_deposit_main($d_select_trans, $d_where_trans)->row()->total;

			$dp_kartu   = $this->Reports_model->get_data_payment_monthrecap($dp_select_kartu, $dp_where_kartu, '', $order)->row()->total;
			$dp_tunai   = $this->Reports_model->get_data_payment_monthrecap($dp_select_tunai, $dp_where_tunai, '', $order)->row()->total;
			$dp_trans   = $this->Reports_model->get_data_payment_monthrecap($dp_select_trans, $dp_where_trans, '', $order)->row()->total;

			$kartu = ($p_kartu_1 + $p_kartu_2 + $p_kartu_3);
			$tunai = ($p_tunai_1 + $p_tunai_2 + $p_tunai_3);
			$trans = ($p_trans_1 + $p_trans_2 + $p_trans_3);

			$data[] = array(
				'no'       => $no,
				'guest'    => 1,
				'numberk'  => 1,
				'number'   => $date->format("d/m/Y"),
				'kartu'    => number_format($kartu),
				'tunai'    => number_format($tunai),
				'transfer' => number_format($trans),
				'total'    => number_format($allmethod),
				'desc'     => 1,
			);

			$total_kartu     += $kartu;
			$total_tunai     += $tunai;
			$total_trans     += $trans;
			$total_allmethod += $allmethod;


			$pemb_kartu += $p_kartu_1 + $p_kartu_2 + $p_kartu_3;
			$pemb_tunai += $p_tunai_1 + $p_tunai_2 + $p_tunai_3;
			$pemb_trans += $p_trans_1 + $p_trans_2 + $p_trans_3;

			$depo_kartu += abs($d_kartu);
			$depo_tunai += abs($d_tunai);
			$depo_trans += abs($d_trans);

			$depo_pemb_kartu += abs($dp_kartu);
			$depo_pemb_tunai += abs($dp_tunai);
			$depo_pemb_trans += abs($dp_trans);

			$no++;
		}
		

		$response = [
			'date_1'                 => change_format_date($date_1, 'd/m/Y'),
			'date_2'                 => change_format_date($date_2, 'd/m/Y'),
			'data'                   => $data,
			'total_kartu'            => number_format($total_kartu),
			'total_tunai'            => number_format($total_tunai),
			'total_trans'            => number_format($total_trans),
			'total_all_method'       => number_format($total_allmethod),

			'pemb_kartu' => number_format($pemb_kartu),
			'pemb_tunai' => number_format($pemb_tunai),
			'pemb_trans' => number_format($pemb_trans),

			'depo_kartu' => number_format($depo_kartu),
			'depo_tunai' => number_format($depo_tunai),
			'depo_trans' => number_format($depo_trans),

			'depo_pemb_kartu' => number_format($depo_pemb_kartu),
			'depo_pemb_tunai' => number_format($depo_pemb_tunai),
			'depo_pemb_trans' => number_format($depo_pemb_trans),

		];

		output_json($response);
	}

}

?>
