<?php
/**
 * Stock In Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Stock_out extends BaseController
{
	/**
	 * Constructor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Stock_in_model');
		$this->load->model('Stock_out_model');
		$this->load->model('Items_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
		$data['content_title'] = 'Stok Keluar';
		$this->twiggy_display('adm/stock_out/index', $data);
	}
	 
	public function get_data_header()
	{	
		$order    = "number desc";
		$data     = [];
		$get_data = $this->Stock_out_model->stockout_header('', $order)->result();

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{	
				$data[] = array(
					'no'     => $no,
					'id'     => $get_row->header_id,
					'number' => $get_row->number,
					'date'   => indonesian_date($get_row->date),
					'desc'   => $get_row->description
				);
				$no++;
			}
		}

		$response = [
            'data'         => $data,
            'recordsTotal' => count($data)
        ];

        output_json($response);
	}

 	public function edit($id = 'new')
	{
		$title       = "Tambah Stok Keluar";
		$header_data = array(null);

		$prefix           = "STO";
		$datenow          = date("mY");
		$number_generator = $this->Stock_out_model->autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		$date_text = change_format_date(date_now(), 'd/m/Y');

	    if($id != 'new')
	    {
			$title       = "Edit Stok Keluar";
			$where       = array('header_id' => $id);
			$header_data = $this->Stock_out_model->stockout_header($where)->row_array();
			$autonumber  = $header_data['number'];
			$date_text   = change_format_date($header_data['date'], 'd/m/Y');
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['header_data']   = $header_data;
	
		$data['autonumber'] = $autonumber;
		$data['date_text']  = $date_text;

		$this->twiggy_display('adm/Stock_out/edit', $data);
	}

	public function get_data_detail()
	{	
		$id    = $this->input->post("id");
		$where = array('a.header_id' => $id);
		
		$data     = [];
		$get_data = $this->Stock_out_model->stockout_detail($where)->result();

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{

				$stock_in_all   = $this->Stock_in_model->stockin_detail_advance('COALESCE(SUM(quantity),0) as total', array('item_id' => $get_row->item_id))->row()->total;
				$stock_out_all  = $this->Stock_out_model->stockout_detail_advance('COALESCE(SUM(quantity),0) as total', array('item_id' => $get_row->item_id))->row()->total;
				$stock_out_this = $this->Stock_out_model->stockout_detail_advance('COALESCE(SUM(quantity),0) as total', array('header_id' => $id, 'item_id' => $get_row->item_id))->row()->total;

				$data[] = array(
					'no'        => $no,
					'detail_id' => $get_row->detail_id,
					'header_id' => $get_row->header_id,
					'id'        => $get_row->item_id,
					'name'      => $get_row->item_name,
					'room'      => number_format($get_row->room),
					'quantity'  => number_format($get_row->quantity),
					'stock'     => ($stock_in_all - $stock_out_all) + $stock_out_this
				);
				$no++;
			}
		}

		output_json($data);
	}

	public function get_embed()
	{
		$data = [];
		$data['content_title'] = 'Data';
		$this->twiggy_display('adm/stock_out/embed', $data);
	}

	public function get_data_embed()
	{
		$data = [];
		$response = [];
		$search   = $this->input->post('search');

		$get_data  = $this->Stock_out_model->item_master($search)->result();

		$no = 0;
		foreach($get_data as $get_row)
		{	

			$stock_in_all   = $this->Stock_in_model->stockin_detail_advance('COALESCE(SUM(quantity),0) as total', array('item_id' => $get_row->item_id))->row()->total;
			$stock_out_all  = $this->Stock_out_model->stockout_detail_advance('COALESCE(SUM(quantity),0) as total', array('item_id' => $get_row->item_id))->row()->total;

			$data[] = array(
				'no'       => $no,
				'id'       => $get_row->item_id,
				'name'     => $get_row->item_name,
				'stock'    => ($stock_in_all - $stock_out_all),
				'btncolor' => '',
				'btnicon'  => '',
			);

			$no++;
		}

		$response = [
            'data'         => $data,
            'recordsTotal' => count($data)
        ];

		output_json($response);
		
	}

	public function save()
	{	
		$id             = $this->input->post('id');
		$number         = $this->input->post("number");
		$date           = $this->input->post("date");
		$description    = $this->input->post("description");
		
		$vuedata = $this->input->post('vuedata');
		$header_id   = $id;
		
		$header_data = [
			'number'      => $number,
			'date'        => change_format_date($date),
			'description' => $description
		];

		$detail_data = [];

		// if save $id = new else update data
		if($id == "new")
		{
			// save header
			$save_header = $this->Stock_out_model->save_header($header_data);
			if($save_header)
			{
				$header_id = $this->db->insert_id();
				foreach($vuedata as $row)
				{
					$detail_data[] = [
						'header_id' => $header_id,
						'item_id'   => $row['id'],
						'room'      => trims($row['room']),
						'quantity'  => trims($row['quantity']),
					];
				}
				
				$save_detail = $this->Stock_out_model->save_detail($detail_data, true);
				
				if($save_detail)
				{
					$msg    = "Berhasil menyimpan data";
					$status = "success";
				}
				else
				{
					$msg    = "Gagal menyimpan data";
					$status = "error";	
				}
			}
			else
			{
				$msg    = "Gagal menyimpan data";
				$status = "error";	
			}
			
		}else{
				
			$delete_detail = $this->Stock_out_model->delete_detail($id);

			if ($delete_detail) {
				$save_header = $this->Stock_out_model->update_header($id, $header_data);

				if($save_header)
				{
					// $header_id = $this->db->insert_id();
					foreach($vuedata as $row)
					{
						$detail_data[] = [
							'header_id' => $header_id,
							'item_id'   => $row['id'],
							'room'      => trims($row['room']),
							'quantity'  => trims($row['quantity']),
						];
					}

					$save_detail = $this->Stock_out_model->save_detail($detail_data, true);
					
					if($save_detail)
					{
						$msg    = "Berhasil menyimpan data";
						$status = "success";
					}
					else
					{
						$msg    = "Gagal menyimpan data";
						$status = "error";	
					}
				}else{
					$msg    = "Gagal menyimpan data";
					$status = "error";
				}

			}else{
				$msg    = "Gagal menyimpan data";
				$status = "error";
			}
		}

		$response = [
			'message' => $msg,
			'status'  => $status,
			'id'      => $header_id
		];
		output_json($response);

	}

	public function delete()
	{
		$id = $this->input->post('id');
		
		foreach($id as $row)
		{	
			$delete_header = $this->Stock_out_model->delete_header($row);
			
			if($delete_header){
				$delete_type = $this->Stock_out_model->delete_detail($row);
			}
		}

		$response = array(
			'message' => 'Berhasil menghapus data',
			'status'  => 'success'
		);

		output_json($response);
	}

	public function print_out($id)
	{	
		$where_header = array('consumption_service_header_id' => $id);
		$where_detail = array('consumption_service_detail_header_id' => $id);

		$header = $this->Stock_out_model->purchaseorder_header($where_header)->row_array();
		$detail = $this->Stock_out_model->purchaseorder_detail($where_detail)->result();

		$data['content_title'] = 'Print Transaksi';
		$data['header'] = $header;
		$data['detail'] = $detail;

		$this->twiggy_display('adm/Stock_out/print_out', $data);
	}

}

?>
