<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_transactions extends BaseController
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
        $data['content_title']  = 'Laporan - Transaksi';
        // $data['shift_data']     = $this->Checkin_model->get_data_shift()->result();
        // $data['paymethod_data'] = $this->Payment_methods_model->get_data()->result();
		
		$this->twiggy_display('adm/r_transactions/index', $data);
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
		
		$order = 'cs_name ASC';
		
		$get_data = $this->Consumption_services_model->cs_detail_advance2('*, sum(cs_detail_quantity) as qty, sum(cs_detail_total) as total', $where, $order)->result();

		$total_quantity = 0;
		$total_amount   = 0;
		
		if($get_data)
		{	
			$no = 1;
			foreach($get_data as $get_row)
			{	
			
				$data[] = array(
					'no'    => $no,
					'item'  => $get_row->cs_name,
					'qty'   => $get_row->qty,
					'total' => number_format($get_row->total),
				);
                $no++;
                
				$total_quantity += $get_row->qty;
				$total_amount   += $get_row->total;
			}
		}


		$response = [
			'date_1'         => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2'         => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'data'           => $data,
			'total_quantity' => number_format($total_quantity),
			'total_amount'   => number_format($total_amount),
		];

		output_json($response);
	}

}

?>
