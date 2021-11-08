<?php
/**
 * Report Day Recap Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_dayrecaps extends BaseController
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
        $data['content_title'] = 'Laporan - Bulanan (Rekap)';

		$data['shift_data']    = $this->Checkin_model->get_data_shift()->result();
		
		$this->twiggy_display('adm/r_dayrecaps/index', $data);
	}

	public function get_data_detail()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');

		$data = [];
		
		$where    = array('date >=' => $date_1, 'date <=' => $date_2);
		$get_date = $this->Reports_model->get_dayrecap('id, date, coalesce(sum(kartu),0) as kartu, coalesce(sum(tunai),0) as tunai, coalesce(sum(trans),0) as trans, coalesce(sum(total),0) as total', $where, 'date')->result();

		$total_kartu = 0;
		$total_tunai = 0;
		$total_trans = 0;
		$total_all = 0;

		if($get_date)
		{	
			$no = 1;
			foreach($get_date as $get_row)
			{	

				$data[] = array(
					'no'    => $no,
					'id'    => $get_row->id,
					'date'  => change_format_date($get_row->date, 'd/m/Y'),
					'kartu' => number_format($get_row->kartu),
					'tunai' => number_format($get_row->tunai),
					'trans' => number_format($get_row->trans),
					'total' => number_format($get_row->total)
				);
				$no++;

				$total_kartu += $get_row->kartu;
				$total_tunai += $get_row->tunai;
				$total_trans += $get_row->trans;
				$total_all   += $get_row->total;
	
			}
		}
		
		

		$response = [
			'date_1'      => change_format_date($date_1, 'd/m/Y'),
			'date_2'      => change_format_date($date_2, 'd/m/Y'),
			'data'        => $data,
			'total_kartu' => number_format($total_kartu),
			'total_tunai' => number_format($total_tunai),
			'total_trans' => number_format($total_trans),
			'total_all'   => number_format($total_all),
		];

		output_json($response);
	}

}

?>
