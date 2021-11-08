<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_stocks extends BaseController
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
		$this->load->model('Items_model');
		$this->load->model('Stock_in_model');
		$this->load->model('Stock_out_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title']  = 'Laporan - Stok';
        $data['item_data'] = $this->Items_model->get_data()->result();
		
		$this->twiggy_display('adm/r_stocks/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');
		$item   = $this->input->post('item');

		$data       = [];
		$where_item = [];
		// $where_out = [];

		// if(isset($status) && $status == 2){
		// 	$where = array('deposit_date >=' => $date_1, 'deposit_date <=' => $date_2, 'payment_id' => '0');
		// }

		if(isset($item) && $item != '')
		{
			$where_item['item_id'] = $item;
		}

		$get_data = $this->Items_model->get_data($where_item)->result();

		
		if($get_data)
		{	
			$no = 1;
			foreach($get_data as $get_row)
			{	

				if(isset($date_1) && $date_1 != '' && isset($date_2) && $date_2 != ''){
					$where_stock = array('item_id' => $get_row->item_id, 'date >=' => $date_1, 'date <=' => $date_2);
				}else{
					$where_stock = array('item_id' => $get_row->item_id);
				}
				
				$stock_in_all  = $this->Stock_in_model->stockin_detail_advance('COALESCE(SUM(quantity),0) as total', $where_stock)->row()->total;
				$stock_out_all = $this->Stock_out_model->stockout_detail_advance('COALESCE(SUM(quantity),0) as total', $where_stock)->row()->total;

				$data[] = array(
					'no'    => $no,
					'id'    => $get_row->item_id,
					'item'  => $get_row->item_name,
					'in'    => number_format($stock_in_all),
					'out'   => number_format($stock_out_all),
					'total' => number_format($stock_in_all - $stock_out_all),
				);
				$no++;

			}
		}


		$response = [
			'date_1' => ($date_1 == '' ? '' : change_format_date($date_1, 'd/m/Y')),
			'date_2' => ($date_2 == '' ? '' : change_format_date($date_2, 'd/m/Y')),
			'data'   => $data
		];

		output_json($response);
	}

}

?>
