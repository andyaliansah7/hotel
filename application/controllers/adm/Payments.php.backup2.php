<?php
/**
 * Payments Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Payments extends BaseController
{
	/**
	 * Constructor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_auth();

		// load model
		$this->load->model('Payments_model');
		$this->load->model('Guests_model');
		$this->load->model('Payment_methods_model');
	}

	/**
	 * Halaman Index
	 *
	 * @return HTML
	 */

	public function index()
 	{	
		$data['content_title'] = 'Pembayaran';
		
		if(check_roles('1')){
			$this->twiggy_display('adm/payments/index', $data);
		}else{
			redirect("Error");
		}
	}
	 
	public function get_data_header()
	{	
		$data = [];
		$get_data = $this->Payments_model->payment_header()->result();

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
					'number' => $get_row->payment_number,
					'date'   => indonesian_date($get_row->payment_date),
					'guest'  => $get_row->guest_name,
					'total'  => number_format($get_row->total_amount),
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
		$title = "Tambah Purchase Order";
		$header_data = array(null);

		$prefix           = "INV";
		$datenow          = date("mY");
		$number_generator = $this->Payments_model->payment_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		$date_text   = change_format_date(date_now(), 'd/m/Y');

	    if($id != 'new')
	    {
			$title       = "Edit Purchase Order";
			$where       = array('header_id' => $id);
			$header_data = $this->Payments_model->payment_header($where)->row_array();
			$autonumber  = $header_data['payment_number'];
			$date_text   = change_format_date($header_data['payment_date'], 'd/m/Y');
		}

		$data['id']            = $id;
		$data['content_title'] = $title;
		$data['header_data']   = $header_data;
		$data['guest_data']    = $this->Guests_model->get_data()->result();
		$data['paymethod_data']   = $this->Payment_methods_model->get_data()->result();

		
		$data['autonumber'] = $autonumber;
		$data['date_text']  = $date_text;

		if(check_roles('1')){
			$this->twiggy_display('adm/payments/edit', $data);
		}else{
			redirect("Error");
		}
	}

	public function get_data_detail()
	{	
		$id = $this->input->post("id");
		$where       = array('header_id' => $id);
		// $order       = array('material_name' => 'asc');

		$data = [];
		$get_data = $this->Payments_model->payment_detail($where)->result();

		// ketika data tersedia
		// maka generate data json untuk Datatable
		if($get_data)
		{
			$no = 1;
			foreach($get_data as $get_row)
			{

				$data[] = array(
					'no'        => $no,
					'detail_id' => $get_row->detail_id,
					'header_id' => $get_row->header_id,
					'id'        => $get_row->transaction_id,
					'code'      => $get_row->transaction_code,
					'number'    => $get_row->transaction_number,
					'type'      => $get_row->transaction_type,
					'price'     => number_format($get_row->price),
					'discount'  => number_format($get_row->discount),
					'deposit'   => number_format($get_row->deposit),
					'total'     => number_format($get_row->total),
				);
				$no++;
			}
		}

		output_json($data);
	}

	public function get_embed()
	{
		$data = [];
		$data['content_title'] = 'Data Transaksi';
		$this->twiggy_display('adm/payments/embed', $data);
	}

	public function get_data_embed()
	{
		$data = [];
		$response = [];
		// $search   = $this->input->post('search');

		$get_data  = $this->Payments_model->get_all_transaction()->result();

		$no = 0;
		foreach($get_data as $get_row)
		{	
			$data[] = array(
				'no'       => $no,
				'id'       => $get_row->id,
				'code'     => $get_row->code,
				'number'   => $get_row->number,
				'type'     => $get_row->type,
				'price'    => number_format($get_row->price),
				'discount' => number_format($get_row->discount),
				'deposit'  => number_format($get_row->deposit),
				'total'    => number_format(($get_row->price) - ($get_row->discount) - ($get_row->deposit)),
				'paid'     => number_format($get_row->paid),
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
		$id                = $this->input->post('id');
		$date              = $this->input->post("date");
		$guest             = $this->input->post("guest");
		$total_price       = $this->input->post("total_price");
		$total_consumption = 0;
		$total_service     = 0;
		$total_discount    = $this->input->post("total_discount");
		$total_deposit     = $this->input->post("total_deposit");
		$total_tax         = 0;
		$total_amount      = $this->input->post("total_amount");
		$total_paid_1      = $this->input->post("total_paid_1");
		$total_paid_2      = $this->input->post("total_paid_2");
		$total_paid_3      = $this->input->post("total_paid_3");
		$payment_method_1  = $this->input->post("payment_method_1");
		$payment_method_2  = $this->input->post("payment_method_2");
		$payment_method_3  = $this->input->post("payment_method_3");
		
		$vuedata = $this->input->post('vuedata');
		$header_id   = $id;

		$prefix           = "INV";
		$datenow          = date("mY");
		$number_generator = $this->Payments_model->payment_autonumber();
		$autonumber       = $prefix.$datenow.$number_generator;

		date_default_timezone_set('Asia/Jakarta');
		$timestamp = date('Y-m-d H:i:s');
		
		$header_data = [
			'guest_id'          => $guest,
			'payment_number'    => $autonumber,
			'payment_date'      => change_format_date($date),
			'total_price'       => trims($total_price),
			'total_consumption' => trims($total_consumption),
			'total_service'     => trims($total_service),
			'total_discount'    => trims($total_discount),
			'total_deposit'     => trims($total_deposit),
			'total_tax'         => trims($total_tax),
			'total_amount'      => trims($total_amount),
			'total_paid_1'      => trims($total_paid_1),
			'total_paid_2'      => trims($total_paid_2),
			'total_paid_3'      => trims($total_paid_3),
			'payment_method_1'  => $payment_method_1,
			'payment_method_2'  => $payment_method_2,
			'payment_method_3'  => $payment_method_3,
			'timestamp'         => $timestamp,
		];

		$total_paid = (trims($total_paid_1) + trims($total_paid_2) + trims($total_paid_3));
		$detail_data = [];
		
		// if save $id = new else update data
		if($id == "new")
		{
			// save header
			$save_header = $this->Payments_model->save_header($header_data);
			if($save_header)
			{
				$header_id = $this->db->insert_id();
				foreach($vuedata as $row)
				{
					$detail_data[] = [
						'header_id'          => $header_id,
						'transaction_id'     => $row['id'],
						'transaction_code'   => $row['code'],
						'transaction_number' => $row['number'],
						'transaction_type'   => $row['type'],
						'price'              => trims($row['price']),
						'discount'           => trims($row['discount']),
						'deposit'            => trims($row['deposit']),
						'total'              => trims($row['total']),
						'paid'              => trims($row['paid']),
					];

					if($row['type'] == 'T'){
						$table = 't_transaction';


						$data  = array('status_paid' => 1);
						$where = array('id'  => $row['id']);
						$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
					}

					if($row['type'] == 'C'){
						$table = 't_cs_header';

						$total_paid -= trims($row['total']);

						if($total_paid > 0){
							$data  = array(
								'cs_header_paid'    => $row['paid'] + $total_paid,
							);
						}else{
							$data  = array(
								'cs_header_paid'    => 9,
							);
						}
						

						$where = array('cs_header_id' => $row['id']);
						$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
					}

					
				}

				echo $total_paid;
					die();
				
				// $save_detail = $this->Payments_model->save_detail($detail_data, true);
				
				// if($save_detail)
				// {
				// 	$msg    = "Berhasil menyimpan data";
				// 	$status = "success";
				// }
				// else
				// {
				// 	$msg    = "Gagal menyimpan data";
				// 	$status = "error";	
				// }
			}
			else
			{
				$msg    = "Gagal menyimpan data";
				$status = "error";	
			}
			
		}else{

			$where_detail = array('header_id' => $header_id);
			$get_detail   = $this->Payments_model->payment_detail($where_detail)->result();
			foreach($get_detail as $row_detail){

				if($row_detail->transaction_type == 'T'){
					$table = 't_transaction';
					$data  = array('status_paid' => 0);
					$where = array('id' => $row_detail->transaction_id);
					$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
				}

				if($row_detail->transaction_type == 'C'){
					$table = 't_cs_header';
					$data  = array('status_paid' => 0);
					$where = array('cs_header_id' => $row_detail->transaction_id);
					$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
				}

			}
				
			$delete_detail = $this->Payments_model->delete_detail($id);

			if ($delete_detail) {
				$save_header = $this->Payments_model->update_header($id, $header_data);

				if($save_header)
				{
					// $header_id = $this->db->insert_id();
					foreach($vuedata as $row)
					{
						$detail_data[] = [
							'header_id'          => $header_id,
							'transaction_id'     => $row['id'],
							'transaction_code'   => $row['code'],
							'transaction_number' => $row['number'],
							'transaction_type'   => $row['type'],
							'price'              => trims($row['price']),
							'discount'           => trims($row['discount']),
							'deposit'            => trims($row['deposit']),
							'total'              => trims($row['total']),
						];

						if($row['type'] == 'T'){
							$table = 't_transaction';
							$data  = array('status_paid' => 1);
							$where = array('id'  => $row['id']);
							$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
						}
	
						if($row['type'] == 'C'){
							$table = 't_cs_header';
							$data  = array('status_paid' => 1);
							$where = array('cs_header_id' => $row['id']);
							$update_status = $this->Payments_model->update_status_paid($table, $data, $where);
						}
					}

					$save_detail = $this->Payments_model->save_detail($detail_data, true);
					
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
			$delete_header = $this->Payments_model->delete_header($row);
			
			if($delete_header){
				$delete_type = $this->Payments_model->delete_detail($row);
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
		$where_header = array('purchase_order_header_id' => $id);
		$where_detail = array('purchase_order_detail_header_id' => $id);

		$header = $this->Payments_model->payment_header($where_header)->row_array();
		$detail = $this->Payments_model->payment_header($where_detail)->result();

		$data['content_title'] = 'Print Purchase Order';
		$data['header'] = $header;
		$data['detail'] = $detail;

		$this->twiggy_display('adm/payments/print_out', $data);
	}

}

?>
