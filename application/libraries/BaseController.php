<?php
/**
 * Library BaseController
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 */

namespace libraries;

class BaseController extends \CI_Controller {

	/**
	 * Modifikasi Meload View (Template Engine Twig)
	 * @return View
	 */
	public function twiggy_display($content = '', $data = array(), $dir_admin = 'adm')
	{
		// pengecekan bila konten kosong
		if($content == '')
		{
			$content = $dir_admin.'/dashboard/index';
		}

		// pengecekan utk array key content title
		if(!array_key_exists('content_title', $data))
		{
			$data['content_title'] = 'Dashboard';
		}

		$this->twiggy->set($data, NULL, TRUE);
		$this->twiggy->display($content);
	}

	/**
	 * Modifikasi Meload View
	 * @return View
	 */
	public function load_view($content = '', $data = array(), $dir_admin = 'adm')
	{
		// pengecekan bila konten kosong
		if($content == '')
		{
			$content = $dir_admin.'/content';
		}

		// pengecekan utk array key content title
		if(!array_key_exists('content_title', $data))
		{
			$data['content_title'] = 'Dashboard';
		}

		// load view 1 paket (header, content, footer)
		$this->load->view($dir_admin.'/header', $data);
		$this->load->view($content, $data);
		$this->load->view($dir_admin.'/footer', $data);
	}
}
?>