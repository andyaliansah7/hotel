<?php
/**
 * Nav Helper untuk menambahkan highlighter pada menu yang aktif
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
function menu_open_master()
{
    $CI = &get_instance();
     
    $class  = $CI->router->fetch_class();
    $master = array("cs_groups", "guest_groups", "guests", "room_types", "rooms", "special_rates", "consumptions", "services", "payment_methods");
	
    return (in_array($class, $master)) ? 'menu-open' : '';
}

function menu_open_stock()
{
    $CI = &get_instance();
     
    $class  = $CI->router->fetch_class();
    $master = array("stock_in", "stock_out");
	
    return (in_array($class, $master)) ? 'menu-open' : '';
}

function active_link_master()
{
    $CI = &get_instance();
     
    $class  = $CI->router->fetch_class();
    $master = array("cs_groups", "guest_groups", "guests", "room_types", "rooms", "special_rates", "consumptions", "services", "payment_methods");
    
    return (in_array($class, $master)) ? 'active' : '';
}

function active_link_stock()
{
    $CI = &get_instance();
     
    $class  = $CI->router->fetch_class();
    $master = array("stock_in", "stock_out");
    
    return (in_array($class, $master)) ? 'active' : '';
}

function active_link($controller)
{
    $CI = &get_instance();
     
    $class = $CI->router->fetch_class();
	
    return ($class == $controller) ? 'active' : '';
}

?>
