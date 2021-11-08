<?php
/**
 * Unauthorized Controller
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
use libraries\BaseController;

class Unauthorized extends BaseController 
{
	/**
	 * Construcktor CodeIgniter
	 */
	public function __construct()
	{
		parent::__construct();

		// load model
		$this->auth->check_auth();
	}

	/**
	 * Halaman Index
	 * 
	 * @return HTML
	 */
	public function index()
	{
		$data['content_title'] = "401 Unauthorized Page";
		$this->twiggy_display('error404', $data);
	}

}