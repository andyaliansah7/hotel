<?php
/**
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 */

class Loginweb extends CI_Controller
{
	/**
	 * Constructor Codeigniter
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Users_model');
	}

	/**
	 * Halaman Index
	 */
	public function index()
	{
		//redirect('adm/dashboard');

		$this->load->view('login/index');
	}

	/**
	 * Proses Login
	 */
	public function loginprocess()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$login = $this->Users_model->get_data_advance('', $username, $password)->row();

		// echo json_encode($login);
		// die();
		if ($login)
		{
			$session = array(
				'user_id' => $login->id,
				'role'    => $login->role
			);

			$this->session->set_userdata($session);

			// print_r($session);
			redirect('adm/dashboard');
		}
		else
		{
			$message = true;

			$this->session->set_flashdata('error_login', $message);
			$this->index();
		}
	}
}
?>
