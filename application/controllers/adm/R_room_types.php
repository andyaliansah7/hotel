<?php
/**
 * Report Room Types Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_room_types extends BaseController
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
        $data['content_title'] = 'Laporan - Tipe Kamar';
		
		$this->twiggy_display('adm/r_room_types/index', $data);
	}

	public function get_data_detail()
	{	
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');
		$data   = [];
		
		$where_header = '';
		$order_header = 'room_type_name ASC';
		$get_data     = $this->Reports_model->get_room_type('', $where_header, $order_header)->result();

		$gt_stay = 0;
		$gt_room = 0;
		$gt_discount = 0;
		$gt_deposit = 0;
		$gt_consumption = 0;
		$gt_service = 0;
		$gt_total = 0;

		if($get_data)
		{	
			$no = 1;
			foreach($get_data as $get_row)
			{	
				
				$where_detail    = array('e.room_type_id' => $get_row->room_type_id, 'payment_date >=' => $date_1, 'payment_date <=' => $date_2);
				$where_detail_c  = array('d.room_type_id' => $get_row->room_type_id, 'payment_date >=' => $date_1, 'payment_date <=' => $date_2, 'cs_detail_item_type' => 'C');
				$where_detail_s  = array('d.room_type_id' => $get_row->room_type_id, 'payment_date >=' => $date_1, 'payment_date <=' => $date_2, 'cs_detail_item_type' => 'S');

				$total_stay        = count($this->Reports_model->get_data_payment('', $where_detail)->result());
				$total_room_price  = $this->Reports_model->get_data_payment('COALESCE(SUM(total_room_price),0) as total', $where_detail)->row()->total;
				$total_discount    = $this->Reports_model->get_data_payment('COALESCE(SUM(a.discount),0) as total', $where_detail)->row()->total;
				$total_deposit     = $this->Reports_model->get_data_payment('COALESCE(SUM(a.deposit),0) as total', $where_detail)->row()->total;
				$total_consumption = $this->Reports_model->get_consumption_service('COALESCE(SUM(cs_detail_total), 0) as total', $where_detail_c)->row()->total;
				$total_service     = $this->Reports_model->get_consumption_service('COALESCE(SUM(cs_detail_total), 0) as total', $where_detail_s)->row()->total;

				$total = ($total_room_price + $total_consumption + $total_service) - ($total_discount + $total_deposit);
				
				$data[] = array(
					'no'               => $no,
					'room_type_id'     => $get_row->room_type_id,
					'room_type'        => $get_row->room_type_name,
					'total_stay'       => number_format($total_stay),
					'total_room_price' => number_format($total_room_price),
					'discount'         => number_format($total_discount),
					'deposit'          => number_format($total_deposit),
					'consumption'      => number_format($total_consumption),
					'service'          => number_format($total_service),
					'total'            => number_format($total)
				);
                $no++;
                
				$gt_stay        += $total_stay;
				$gt_room        += $total_room_price;
				$gt_discount    += $total_discount;
				$gt_deposit     += $total_deposit;
				$gt_consumption += $total_consumption;
				$gt_service     += $total_service;
				$gt_total       += $total;
			}
		}

		$response = [
			'data'           => $data,
			'gt_stay'        => number_format($gt_stay),
			'gt_room'        => number_format($gt_room),
			'gt_discount'    => number_format($gt_discount),
			'gt_deposit'     => number_format($gt_deposit),
			'gt_consumption' => number_format($gt_consumption),
			'gt_service'     => number_format($gt_service),
			'gt_total'       => number_format($gt_total),
		];

		output_json($response);
	}

}

?>
