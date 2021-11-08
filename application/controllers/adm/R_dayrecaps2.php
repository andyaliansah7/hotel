<?php
/**
 * Report Day Recap Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class R_dayrecaps2 extends BaseController
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
        $data['content_title'] = 'Laporan - Pembayaran (Rekap)';

		$data['shift_data']    = $this->Checkin_model->get_data_shift()->result();
		
		$this->twiggy_display('adm/r_dayrecaps2/index', $data);
	}

	public function get_data_detail()
	{	
		date_default_timezone_set('Asia/Jakarta');
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');

		$data = [];
		
		$where    = array('date >=' => $date_1, 'date <=' => $date_2);
		$get_date = $this->Reports_model->get_dayrecap2('*', $where, 'date')->result();

		$ttl_bca_682       = 0;
		$ttl_bca_682p      = 0;
		$ttl_bca_edc       = 0;
		$ttl_bca_edca      = 0;
		$ttl_complimentary = 0;
		$ttl_house_use     = 0;
		$ttl_mandiri_edc   = 0;
		$ttl_mandiri_tf    = 0;
		$ttl_pending       = 0;
		$ttl_voucher       = 0;
		$ttl_all = 0;

		if($get_date)
		{	
			$no = 1;
			foreach($get_date as $get_row)
			{	

				$select = 'coalesce(sum(total),0) as ttl';

				$where1  = array('date' => $get_row->date, 'method_id' => '12');
				$where2  = array('date' => $get_row->date, 'method_id' => '13');
				$where3  = array('date' => $get_row->date, 'method_id' => '23');
				$where4  = array('date' => $get_row->date, 'method_id' => '16');
				$where5  = array('date' => $get_row->date, 'method_id' => '2');
				$where6  = array('date' => $get_row->date, 'method_id' => '3');
				$where7  = array('date' => $get_row->date, 'method_id' => '21');
				$where8  = array('date' => $get_row->date, 'method_id' => '22');
				$where9  = array('date' => $get_row->date, 'method_id' => '1');
				$where10  = array('date' => $get_row->date, 'method_id' => '5');

				$ttl1  = $this->Reports_model->get_dayrecap2($select, $where1, 'date')->row();
				$ttl2  = $this->Reports_model->get_dayrecap2($select, $where2, 'date')->row();
				$ttl3  = $this->Reports_model->get_dayrecap2($select, $where3, 'date')->row();
				$ttl4  = $this->Reports_model->get_dayrecap2($select, $where4, 'date')->row();
				$ttl5  = $this->Reports_model->get_dayrecap2($select, $where5, 'date')->row();
				$ttl6  = $this->Reports_model->get_dayrecap2($select, $where6, 'date')->row();
				$ttl7  = $this->Reports_model->get_dayrecap2($select, $where7, 'date')->row();
				$ttl8  = $this->Reports_model->get_dayrecap2($select, $where8, 'date')->row();
				$ttl9  = $this->Reports_model->get_dayrecap2($select, $where9, 'date')->row();
				$ttl10 = $this->Reports_model->get_dayrecap2($select, $where10, 'date')->row();

				// Fee -----------------------------------------------
				$selectfee = 'coalesce(sum(a.paid * (d.guest_group_fee/100)),0) as ttl';

				$wherefee1  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '12');
				$wherefee2  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '13');
				$wherefee3  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '23');
				$wherefee4  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '16');
				$wherefee5  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '2');
				$wherefee6  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '3');
				$wherefee7  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '21');
				$wherefee8  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '22');
				$wherefee9  = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '1');
				$wherefee10 = array('b.payment_date' => $get_row->date, 'b.payment_method_1' => '5');

				$ttlfee1  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee1)->row();
				$ttlfee2  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee2)->row();
				$ttlfee3  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee3)->row();
				$ttlfee4  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee4)->row();
				$ttlfee5  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee5)->row();
				$ttlfee6  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee6)->row();
				$ttlfee7  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee7)->row();
				$ttlfee8  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee8)->row();
				$ttlfee9  = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee9)->row();
				$ttlfee10 = $this->Reports_model->get_data_payment_detail2($selectfee, $wherefee10)->row();

				// Min Deposit
				$selectmdx = 'coalesce(sum(if(deposit_amount_1 > 0 , deposit_amount_1 , deposit_amount_1 * -1)),0) as ttl';
				$selectmd = 'coalesce(sum(deposit_amount_1),0) as ttl';


				$wheremd1  = array('payment_date' => $get_row->date, 'payment_method_1' => '12');
				$wheremd2  = array('payment_date' => $get_row->date, 'payment_method_1' => '13');
				$wheremd3  = array('payment_date' => $get_row->date, 'payment_method_1' => '23');
				$wheremd4  = array('payment_date' => $get_row->date, 'payment_method_1' => '16');
				$wheremd5  = array('payment_date' => $get_row->date, 'payment_method_1' => '2');
				$wheremd6  = array('payment_date' => $get_row->date, 'payment_method_1' => '3');
				$wheremd7  = array('payment_date' => $get_row->date, 'payment_method_1' => '21');
				$wheremd8  = array('payment_date' => $get_row->date, 'payment_method_1' => '22');
				$wheremd9  = array('payment_date' => $get_row->date, 'payment_method_1' => '1');
				$wheremd10  = array('payment_date' => $get_row->date, 'payment_method_1' => '5');

				$ttlmd1  = $this->Reports_model->get_deposit2($selectmd, $wheremd1, 'payment_method_1')->row();
				$ttlmd2  = $this->Reports_model->get_deposit2($selectmd, $wheremd2, 'payment_method_1')->row();
				$ttlmd3  = $this->Reports_model->get_deposit2($selectmd, $wheremd3, 'payment_method_1')->row();
				$ttlmd4  = $this->Reports_model->get_deposit2($selectmd, $wheremd4, 'payment_method_1')->row();
				$ttlmd5  = $this->Reports_model->get_deposit2($selectmd, $wheremd5, 'payment_method_1')->row();
				$ttlmd6  = $this->Reports_model->get_deposit2($selectmd, $wheremd6, 'payment_method_1')->row();
				$ttlmd7  = $this->Reports_model->get_deposit2($selectmd, $wheremd7, 'payment_method_1')->row();
				$ttlmd8  = $this->Reports_model->get_deposit2($selectmd, $wheremd8, 'payment_method_1')->row();
				$ttlmd9  = $this->Reports_model->get_deposit2($selectmd, $wheremd9, 'payment_method_1')->row();
				$ttlmd10 = $this->Reports_model->get_deposit2($selectmd, $wheremd10, 'payment_method_1')->row();

				$ttlmd1x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd1, 'payment_date')->row();
				$ttlmd2x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd2, 'payment_date')->row();
				$ttlmd3x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd3, 'payment_date')->row();
				$ttlmd4x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd4, 'payment_date')->row();
				$ttlmd5x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd5, 'payment_date')->row();
				$ttlmd6x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd6, 'payment_date')->row();
				$ttlmd7x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd7, 'payment_date')->row();
				$ttlmd8x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd8, 'payment_date')->row();
				$ttlmd9x  = $this->Reports_model->get_deposit2($selectmdx, $wheremd9, 'payment_date')->row();
				$ttlmd10x = $this->Reports_model->get_deposit2($selectmdx, $wheremd10, 'payment_date')->row();

				// Plus Deposit
				$wherepd1  = array('deposit_date' => $get_row->date, 'payment_method_1' => '12');
				$wherepd2  = array('deposit_date' => $get_row->date, 'payment_method_1' => '13');
				$wherepd3  = array('deposit_date' => $get_row->date, 'payment_method_1' => '23');
				$wherepd4  = array('deposit_date' => $get_row->date, 'payment_method_1' => '16');
				$wherepd5  = array('deposit_date' => $get_row->date, 'payment_method_1' => '2');
				$wherepd6  = array('deposit_date' => $get_row->date, 'payment_method_1' => '3');
				$wherepd7  = array('deposit_date' => $get_row->date, 'payment_method_1' => '21');
				$wherepd8  = array('deposit_date' => $get_row->date, 'payment_method_1' => '22');
				$wherepd9  = array('deposit_date' => $get_row->date, 'payment_method_1' => '1');
				$wherepd10  = array('deposit_date' => $get_row->date, 'payment_method_1' => '5');

				$ttlpd1  = $this->Reports_model->get_deposit2($selectmd, $wherepd1, 'deposit_date')->row();
				$ttlpd2  = $this->Reports_model->get_deposit2($selectmd, $wherepd2, 'deposit_date')->row();
				$ttlpd3  = $this->Reports_model->get_deposit2($selectmd, $wherepd3, 'deposit_date')->row();
				$ttlpd4  = $this->Reports_model->get_deposit2($selectmd, $wherepd4, 'deposit_date')->row();
				$ttlpd5  = $this->Reports_model->get_deposit2($selectmd, $wherepd5, 'deposit_date')->row();
				$ttlpd6  = $this->Reports_model->get_deposit2($selectmd, $wherepd6, 'deposit_date')->row();
				$ttlpd7  = $this->Reports_model->get_deposit2($selectmd, $wherepd7, 'deposit_date')->row();
				$ttlpd8  = $this->Reports_model->get_deposit2($selectmd, $wherepd8, 'deposit_date')->row();
				$ttlpd9  = $this->Reports_model->get_deposit2($selectmd, $wherepd9, 'deposit_date')->row();
				$ttlpd10 = $this->Reports_model->get_deposit2($selectmd, $wherepd10, 'deposit_date')->row();

				$py1  = (!empty($ttl1) ? $ttl1->ttl : 0);
				$py2  = (!empty($ttl2) ? $ttl2->ttl : 0);
				$py3  = (!empty($ttl3) ? $ttl3->ttl : 0);
				$py4  = (!empty($ttl4) ? $ttl4->ttl : 0);
				$py5  = (!empty($ttl5) ? $ttl5->ttl : 0);
				$py6  = (!empty($ttl6) ? $ttl6->ttl : 0);
				$py7  = (!empty($ttl7) ? $ttl7->ttl : 0);
				$py8  = (!empty($ttl8) ? $ttl8->ttl : 0);
				$py9  = (!empty($ttl9) ? $ttl9->ttl : 0);
				$py10 = (!empty($ttl10) ? $ttl10->ttl : 0);

				$fee1  = (!empty($ttlfee1) ? $ttlfee1->ttl : 0);
				$fee2  = (!empty($ttlfee2) ? $ttlfee2->ttl : 0);
				$fee3  = (!empty($ttlfee3) ? $ttlfee3->ttl : 0);
				$fee4  = (!empty($ttlfee4) ? $ttlfee4->ttl : 0);
				$fee5  = (!empty($ttlfee5) ? $ttlfee5->ttl : 0);
				$fee6  = (!empty($ttlfee6) ? $ttlfee6->ttl : 0);
				$fee7  = (!empty($ttlfee7) ? $ttlfee7->ttl : 0);
				$fee8  = (!empty($ttlfee8) ? $ttlfee8->ttl : 0);
				$fee9  = (!empty($ttlfee9) ? $ttlfee9->ttl : 0);
				$fee10 = (!empty($ttlfee10) ? $ttlfee10->ttl : 0);

				$md1  = (!empty($ttlmd1) ? ($ttlmd1->ttl) : 0);
				$md2  = (!empty($ttlmd2) ? ($ttlmd2->ttl) : 0);
				$md3  = (!empty($ttlmd3) ? ($ttlmd3->ttl) : 0);
				$md4  = (!empty($ttlmd4) ? ($ttlmd4->ttl) : 0);
				$md5  = (!empty($ttlmd5) ? ($ttlmd5->ttl) : 0);
				$md6  = (!empty($ttlmd6) ? ($ttlmd6->ttl) : 0);
				$md7  = (!empty($ttlmd7) ? ($ttlmd7->ttl) : 0);
				$md8  = (!empty($ttlmd8) ? ($ttlmd8->ttl) : 0);
				$md9  = (!empty($ttlmd9) ? ($ttlmd9->ttl) : 0);
				$md10 = (!empty($ttlmd10) ? ($ttlmd10->ttl) : 0);

				$md1x  = (!empty($ttlmd1x) ? ($ttlmd1x->ttl) : 0);
				$md2x  = (!empty($ttlmd2x) ? ($ttlmd2x->ttl) : 0);
				$md3x  = (!empty($ttlmd3x) ? ($ttlmd3x->ttl) : 0);
				$md4x  = (!empty($ttlmd4x) ? ($ttlmd4x->ttl) : 0);
				$md5x  = (!empty($ttlmd5x) ? ($ttlmd5x->ttl) : 0);
				$md6x  = (!empty($ttlmd6x) ? ($ttlmd6x->ttl) : 0);
				$md7x  = (!empty($ttlmd7x) ? ($ttlmd7x->ttl) : 0);
				$md8x  = (!empty($ttlmd8x) ? ($ttlmd8x->ttl) : 0);
				$md9x  = (!empty($ttlmd9x) ? ($ttlmd9x->ttl) : 0);
				$md10x = (!empty($ttlmd10x) ? ($ttlmd10x->ttl) : 0);

				$pd1  = (!empty($ttlpd1) ? ($ttlpd1->ttl) : 0);
				$pd2  = (!empty($ttlpd2) ? ($ttlpd2->ttl) : 0);
				$pd3  = (!empty($ttlpd3) ? ($ttlpd3->ttl) : 0);
				$pd4  = (!empty($ttlpd4) ? ($ttlpd4->ttl) : 0);
				$pd5  = (!empty($ttlpd5) ? ($ttlpd5->ttl) : 0);
				$pd6  = (!empty($ttlpd6) ? ($ttlpd6->ttl) : 0);
				$pd7  = (!empty($ttlpd7) ? ($ttlpd7->ttl) : 0);
				$pd8  = (!empty($ttlpd8) ? ($ttlpd8->ttl) : 0);
				$pd9  = (!empty($ttlpd9) ? ($ttlpd9->ttl) : 0);
				$pd10 = (!empty($ttlpd10) ? ($ttlpd10->ttl) : 0);

				// $bca_682       = ($py1 + ($md1 >= 0 ? abs($md1) : 0) - $fee1 - abs($md1) - $pd1);
				// $bca_682p      = ($py2 + ($md2 >= 0 ? abs($md2) : 0) - $fee2 - abs($md2) - $pd2);
				// $bca_edc       = ($py3 + ($md3 >= 0 ? abs($md3) : 0) - $fee3 - abs($md3) - $pd3);
				// $bca_edca      = ($py4 + ($md4 >= 0 ? abs($md4) : 0) - $fee4 - abs($md4) - $pd4);
				// $complimentary = ($py5 + ($md5 >= 0 ? abs($md5) : 0) - $fee5 - abs($md5) - $pd5);
				// $house_use     = ($py6 + ($md6 >= 0 ? abs($md6) : 0) - $fee6 - abs($md6) - $pd6);
				// $mandiri_edc   = ($py7 + ($md7 >= 0 ? abs($md7) : 0) - $fee7 - abs($md7) - $pd7);
				// $mandiri_tf    = ($py8 + ($md8 >= 0 ? abs($md8) : 0) - $fee8 - abs($md8) - $pd8);
				// $pending       = ($py9 + ($md9 >= 0 ? abs($md9) : 0) - $fee9 - abs($md9) - $pd9);
				$voucher       = ($py10);

				// $bca_682       = ($py1 + ($md1 >= 0 ? abs($md1) : 0) - $fee1 - abs($md1) + abs($pd1));
				// $bca_682p      = ($py2 + ($md2 >= 0 ? abs($md2) : 0) - $fee2 - abs($md2) + abs($pd2));
				// $bca_edc       = ($py3 + ($md3 >= 0 ? abs($md3) : 0) - $fee3 - abs($md3) + abs($pd3));
				// $bca_edca      = ($py4 + ($md4 >= 0 ? abs($md4) : 0) - $fee4 - abs($md4) + abs($pd4));
				// $complimentary = ($py5 + ($md5 >= 0 ? abs($md5) : 0) - $fee5 - abs($md5) + abs($pd5));
				// $house_use     = ($py6 + ($md6 >= 0 ? abs($md6) : 0) - $fee6 - abs($md6) + abs($pd6));
				// $mandiri_edc   = ($py7 + ($md7 >= 0 ? abs($md7) : 0) - $fee7 - abs($md7) + abs($pd7));
				// $mandiri_tf    = ($py8 + ($md8 >= 0 ? abs($md8) : 0) - $fee8 - abs($md8) + abs($pd8));
				// $pending       = ($py9 + ($md9 >= 0 ? abs($md9) : 0) - $fee9 - abs($md9) + abs($pd9));

				$bca_682       = ($py1 + ($md1 >= 0 ? abs($md1) : 0) - $fee1 - abs($md1) + abs($pd1));
				$bca_682p      = ($py2 + ($md2 >= 0 ? abs($md2) : 0) - $fee2 - abs($md2) + abs($pd2));
				$bca_edc       = ($py3 + ($md3 >= 0 ? abs($md3) : 0) - $fee3 - abs($md3) + abs($pd3));
				$bca_edca      = ($py4 + ($md4 >= 0 ? abs($md4) : 0) - $fee4 - abs($md4) + abs($pd4));
				$complimentary = ($py5 + ($md5 >= 0 ? abs($md5) : 0) - $fee5 - abs($md5) + abs($pd5));
				$house_use     = ($py6 + ($md6 >= 0 ? abs($md6) : 0) - $fee6 - abs($md6) + abs($pd6));
				$mandiri_edc   = ($py7 + ($md7 >= 0 ? abs($md7) : 0) - $fee7 - abs($md7) + abs($pd7));
				$mandiri_tf    = ($py8 + ($md8 >= 0 ? abs($md8) : 0) - $fee8 - abs($md8) + abs($pd8));
				$pending       = ($py9 + ($md9 >= 0 ? abs($md9) : 0) - $fee9 - abs($md9) + abs($pd9));

				// $bca_682       = ($py1 + ($md1 >= 0 ? abs(($md1 + $md1x)/2) : 0) - $fee1 - abs($md1x) + abs($pd1));
				// $bca_682p      = ($py2 + ($md2 >= 0 ? abs(($md2 + $md2x)/2) : 0) - $fee2 - abs($md2x) + abs($pd1));
				// $bca_edc       = ($py3 + ($md3 >= 0 ? abs(($md3 + $md3x)/2) : 0) - $fee3 - abs($md3x) + abs($pd1));
				// $bca_edca      = ($py4 + ($md4 >= 0 ? abs(($md4 + $md4x)/2) : 0) - $fee4 - abs($md4x) + abs($pd1));
				// $complimentary = ($py5 + ($md5 >= 0 ? abs(($md5 + $md5x)/2) : 0) - $fee5 - abs($md5x) + abs($pd1));
				// $house_use     = ($py6 + ($md6 >= 0 ? abs(($md6 + $md6x)/2) : 0) - $fee6 - abs($md6x) + abs($pd1));
				// $mandiri_edc   = ($py7 + ($md7 >= 0 ? abs(($md7 + $md7x)/2) : 0) - $fee7 - abs($md7x) + abs($pd1));
				// $mandiri_tf    = ($py8 + ($md8 >= 0 ? abs(($md8 + $md8x)/2) : 0) - $fee8 - abs($md8x) + abs($pd1));
				// $pending       = ($py9 + ($md9 >= 0 ? abs(($md9 + $md9x)/2) : 0) - $fee9 - abs($md9x) + abs($pd1));

				// $ttl_list = $bca_682 + $bca_682p + $bca_edc + $bca_edca + $complimentary + $house_use + $mandiri_edc + $mandiri_tf + $pending + $voucher;
				$ttl_list = $bca_682 + $bca_682p + $bca_edc + $bca_edca + $house_use + $mandiri_edc + $mandiri_tf + $pending + $voucher;

				$data[] = array(
					'no'            => $no,
					'id'            => $get_row->id,
					'date'          => change_format_date($get_row->date, 'd/m/Y'),
					'bca_682'       => number_format($bca_682),
					'bca_682p'      => number_format($bca_682p),
					'bca_edc'       => number_format($bca_edc),
					'bca_edca'      => number_format($bca_edca),
					'complimentary' => number_format($complimentary),
					'house_use'     => number_format($house_use),
					'mandiri_edc'   => number_format($mandiri_edc),
					'mandiri_tf'    => number_format($mandiri_tf),
					'pending'       => number_format($pending),
					'voucher'       => number_format($voucher),
					'total'         => number_format($ttl_list)
				);
				$no++;

				$ttl_bca_682       += $bca_682;
				$ttl_bca_682p      += $bca_682p;
				$ttl_bca_edc       += $bca_edc;
				$ttl_bca_edca      += $bca_edca;
				$ttl_complimentary += $complimentary;
				$ttl_house_use     += $house_use;
				$ttl_mandiri_edc   += $mandiri_edc;
				$ttl_mandiri_tf    += $mandiri_tf;
				$ttl_pending       += $pending;
				$ttl_voucher       += $voucher;
				$ttl_all += $ttl_list;
	
			}
		}
		
		

		$response = [
			'date_1'            => change_format_date($date_1, 'd/m/Y'),
			'date_2'            => change_format_date($date_2, 'd/m/Y'),
			'data'              => $data,
			'ttl_bca_682'       => number_format($ttl_bca_682),
			'ttl_bca_682p'      => number_format($ttl_bca_682p),
			'ttl_bca_edc'       => number_format($ttl_bca_edc),
			'ttl_bca_edca'      => number_format($ttl_bca_edca),
			'ttl_complimentary' => number_format($ttl_complimentary),
			'ttl_house_use'     => number_format($ttl_house_use),
			'ttl_mandiri_edc'   => number_format($ttl_mandiri_edc),
			'ttl_mandiri_tf'    => number_format($ttl_mandiri_tf),
			'ttl_pending'       => number_format($ttl_pending),
			'ttl_voucher'       => number_format($ttl_voucher),
			'ttl_all'           => number_format($ttl_all)
		];

		output_json($response);
	}

}

?>
