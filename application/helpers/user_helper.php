<?php
/**
 * User Helper
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 *         Andy Aliansah <andyaliansah97@gmail.com>
 */

function logged_user($show)
{
	$ci =& get_instance();

	$ci->load->model('Users_model');

	$user_id = $ci->session->userdata('user_id');

	// cek ke server berdasarkan session user id
	$check_user = $ci->Users_model->get_data_advance($user_id)->row_array();

	// ketika user tersedia
	// maka check kembali array exists
	// jika tersedia maka return data user

	if($check_user)
	{
		if(array_key_exists($show, $check_user))
		{
			return $check_user[$show];
		}

		return '';
	}

	return '';
}

function check_roles($role_name)
{
	$ci =& get_instance();

	$ci->load->model('Users_model');

	$user_id 	= $ci->session->userdata('user_id');

	$check_user = $ci->Users_model->get_data_advance($user_id, "", "", $role_name)->row_array();

	return $check_user;
}


?>
