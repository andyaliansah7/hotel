<?php
/**
 * Auth Helper
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 *         Andy Aliansah <andyaliansah97@gmail.com>
 */

if(!function_exists('is_logged'))
{
    function is_logged()
    {
        $ci =& get_instance();
        return $ci->session->userdata('user_id');
    }
}
?>