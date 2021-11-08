<?php
/**
 * Consumption services Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Consumption_services extends BaseController
{
	/**
	 * Constructor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Guests_model');
		$this->load->model('Consumption_services_model');
		$this->load->model('Checkin_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
		$data['content_title'] = 'Transaksi';
		$this->twiggy_display('adm/consumption_services/index', $data);
	}
	 
	public function get_data_header()
	{	
		$order = "cs_header_number desc";
		$data = [];
		$get_data = $this->Consumption_services_model->cs_header('', $order)->result();

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{	
				$transaction = $get_row->room_number.' | '.$get_row->transaction_number;
				if($get_row->transaction_id == 0){
					$transaction = 'Bukan Tamu Menginap';
				}
				$data[] = array(
					'no'          => $no,
					'id'          => $get_row->cs_header_id,
					'number'      => $get_row->cs_header_number,
					'date'        => indonesian_date($get_row->cs_header_date),
					'room'        => $transaction,
					'on_behalf'   => $get_row->cs_header_on_behalf_name,
					'total'       => 'Rp. '.number_format($get_row->cs_header_total),
					'description' => $get_row->cs_header_description,
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
		$title = "Tambah Transaksi";
		$header_data = array(null);

		$prefix           = "TRA";
		$datenow          = date("mY");
		$number_generator = $this->Consumption_services_model->cs_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		$date_text   = change_format_date(date_now(), 'd/m/Y');

	    if($id != 'new')
	    {
			$title       = "Edit Transaksi";
			$where       = array('cs_header_id' => $id);
			$header_data = $this->Consumption_services_model->cs_header($where)->row_array();
			$autonumber  = $header_data['cs_header_number'];
			$date_text   = change_format_date($header_data['cs_header_date'], 'd/m/Y');
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['header_data']   = $header_data;
		$data['checkin_data']  = $this->Checkin_model->get_data(array('transaction_type' => 'C'))->result();

		$data['autonumber'] = $autonumber;
		$data['date_text']  = $date_text;

		$this->twiggy_display('adm/consumption_services/edit', $data);
	}

	public function get_data_detail()
	{	
		$id = $this->input->post("id");
		$where       = array('cs_detail_header_id' => $id);
		// $order       = array('item_name' => 'asc');

		// die();
		$data = [];
		$get_data = $this->Consumption_services_model->cs_detail($where)->result();

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{

				$data[] = array(
					'no'               => $no,
					'detail_id'        => $get_row->cs_detail_id,
					'detail_header_id' => $get_row->cs_detail_header_id,
					'id'               => $get_row->cs_detail_item_id,
					'code'             => $get_row->cs_code,
					'name'             => $get_row->cs_name,
					'type'             => $get_row->cs_type,
					'quantity'         => $get_row->cs_detail_quantity,
					'price'            => number_format($get_row->cs_detail_price),
					'total'            => number_format($get_row->cs_detail_total),
				);
				$no++;
			}
		}

		output_json($data);
	}

	public function get_checkin()
	{
		$id         = $this->input->post('id');
		$where      = array('id' => $id);
		$on_behalf_id = 0;
		$on_behalf_name = '';

		$get_data = $this->Checkin_model->get_data($where)->row();
		if($get_data){
			$on_behalf_id   = $get_data->on_behalf;
			$on_behalf_name = $this->Guests_model->get_data(array('guest_id' => $on_behalf_id))->row()->guest_name;
		}

		$response = [
			'id'   => $on_behalf_id,
			'name' => $on_behalf_name
		];
		echo json_encode($response);
	}

	public function get_embed()
	{
		$data = [];
		$data['content_title'] = 'Data';
		$this->twiggy_display('adm/consumption_services/embed', $data);
	}

	public function get_data_embed()
	{
		$data = [];
		$response = [];
		$search   = $this->input->post('search');

		$get_data  = $this->Consumption_services_model->cs_master($search)->result();

		$no = 0;
		foreach($get_data as $get_row)
		{	
			$data[] = array(
				'no'       => $no,
				'id'       => $get_row->cs_id,
				'code'     => $get_row->cs_code,
				'name'     => $get_row->cs_name,
				'type'     => $get_row->cs_type,
				'price'    => number_format($get_row->cs_price),
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
		$trx            = $this->input->post("trx");
		$number         = $this->input->post("number");
		$date           = $this->input->post("date");
		$on_behalf_id   = $this->input->post("on_behalf_id");
		$on_behalf_name = $this->input->post("on_behalf_name");
		$total          = $this->input->post("total");
		$description    = $this->input->post("description");
		
		$vuedata = $this->input->post('vuedata');
		$header_id   = $id;
		
		$header_data = [
			'transaction_id'           => $trx,
			'cs_header_number'         => $number,
			'cs_header_date'           => change_format_date($date),
			'cs_header_on_behalf_id'   => $on_behalf_id,
			'cs_header_on_behalf_name' => $on_behalf_name,
			'cs_header_total'          => trims($total),
			'cs_header_paid'           => 0,
			'status_paid'              => 0,
			'cs_header_description'    => $description
		];

		$detail_data = [];

		// if save $id = new else update data
		if($id == "new")
		{
			// save header
			$save_header = $this->Consumption_services_model->save_header($header_data);
			if($save_header)
			{
				$header_id = $this->db->insert_id();
				foreach($vuedata as $row)
				{
					$detail_data[] = [
						'cs_detail_header_id' => $header_id,
						'cs_detail_item_id'   => $row['id'],
						'cs_detail_item_type' => $row['type'],
						'cs_detail_item_name' => $row['name'],
						'cs_detail_quantity'  => $row['quantity'],
						'cs_detail_price'     => trims($row['price']),
						'cs_detail_total'     => trims($row['total'])
					];
				}
				
				$save_detail = $this->Consumption_services_model->save_detail($detail_data, true);
				
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
				
			$delete_detail = $this->Consumption_services_model->delete_detail($id);

			if ($delete_detail) {
				$save_header = $this->Consumption_services_model->update_header($id, $header_data);

				if($save_header)
				{
					// $header_id = $this->db->insert_id();
					foreach($vuedata as $row)
					{
						$detail_data[] = [
							'cs_detail_header_id' => $id,
							'cs_detail_item_id'   => $row['id'],
							'cs_detail_item_type' => $row['type'],
							'cs_detail_item_name' => $row['name'],
							'cs_detail_quantity'  => $row['quantity'],
							'cs_detail_price'     => trims($row['price']),
							'cs_detail_total'     => trims($row['total'])
						];
					}

					$save_detail = $this->Consumption_services_model->save_detail($detail_data, true);
					
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
			$delete_header = $this->Consumption_services_model->delete_header($row);
			
			if($delete_header){
				$delete_type = $this->Consumption_services_model->delete_detail($row);
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

		$header = $this->Consumption_services_model->purchaseorder_header($where_header)->row_array();
		$detail = $this->Consumption_services_model->purchaseorder_detail($where_detail)->result();

		$data['content_title'] = 'Print Transaksi';
		$data['header'] = $header;
		$data['detail'] = $detail;

		$this->twiggy_display('adm/consumption_services/print_out', $data);
	}

}

?>
