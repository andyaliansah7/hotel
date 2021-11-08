<?php
/**
 * Report Consumption Services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_consumption_services extends BaseController
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
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
        $data['content_title'] = 'Laporan - Produk & Layanan';
		
		$this->twiggy_display('adm/r_consumption_services/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');
		$data   = [];
		$data2   = [];
		
		$where_header_1 = array('cs_type' => 'C');
		$where_header_2 = array('cs_type' => 'S');
		$order_header   = 'cs_name ASC';
		$get_data_1     = $this->Reports_model->get_consumption_service_m('', $where_header_1, $order_header)->result();
		$get_data_2     = $this->Reports_model->get_consumption_service_m('', $where_header_2, $order_header)->result();

		$gtc_quantity = 0;
		$gtc_total    = 0;
		$gts_quantity = 0;
		$gts_total    = 0;

		if($get_data_1)
		{	
			$no = 1;
			foreach($get_data_1 as $get_row)
			{	
				$where_detail = array('a.cs_detail_item_id' => $get_row->cs_id, 'payment_date >=' => $date_1, 'payment_date <=' => $date_2, 'cs_detail_item_type' => 'C');
				$quantity     = $this->Reports_model->get_consumption_service('COALESCE(SUM(cs_detail_quantity), 0) as total', $where_detail)->row()->total;
				$total        = $this->Reports_model->get_consumption_service('COALESCE(SUM(cs_detail_total), 0) as total', $where_detail)->row()->total;
				
				$data[] = array(
					'no'         => $no,
					'product_id' => $get_row->cs_id,
					'product'    => $get_row->cs_name,
					'quantity'   => number_format($quantity),
					'total'      => number_format($total)
				);
                $no++;
                
				$gtc_quantity += $quantity;
				$gtc_total    += $total;
			}
		}

		if($get_data_2)
		{	
			$no = 1;
			foreach($get_data_2 as $get_row)
			{	
				$where_detail = array('a.cs_detail_item_id' => $get_row->cs_id, 'payment_date >=' => $date_1, 'payment_date <=' => $date_2, 'cs_detail_item_type' => 'S');
				$quantity     = $this->Reports_model->get_consumption_service('COALESCE(SUM(cs_detail_quantity), 0) as total', $where_detail)->row()->total;
				$total        = $this->Reports_model->get_consumption_service('COALESCE(SUM(cs_detail_total), 0) as total', $where_detail)->row()->total;
				
				$data2[] = array(
					'no'         => $no,
					'service_id' => $get_row->cs_id,
					'service'    => $get_row->cs_name,
					'quantity'   => number_format($quantity),
					'total'      => number_format($total)
				);
                $no++;
                
				$gts_quantity += $quantity;
				$gts_total    += $total;
			}
		}

		$response = [
			'data'         => $data,
			'data2'         => $data2,
			'gtc_quantity' => number_format($gtc_quantity),
			'gtc_total'    => number_format($gtc_total),
			'gts_quantity' => number_format($gts_quantity),
			'gts_total'    => number_format($gts_total),
		];

		output_json($response);
	}

}

?>
