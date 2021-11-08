<?php
/**
 * Konfigurasi Tambahan
 *
 *  @author Andy Aliansah <andyaliansah97@gmail.com>
 */
$app_name = 'Sistem Reservasi';
$year = '2020';

// jika tahun melebih tahun dibuatnya aplikasi 
// maka otomatis tahun menjadi rentang tahun 
if(date('Y') > $year)
{
	$year = $year .' - '.date('Y');
}

$config['app_name'] = $app_name;
$config['app_name_short'] = substr($app_name, 0, 2);
$config['app_version'] = '1.0';
$config['app_year'] = $year;

$config['app_pc'] = trim(strtolower(gethostname()));

// folder untuk upload
$config['dir_upload'] = FCPATH . 'assets/images/';
$config['upload_dir'] = FCPATH . 'assets/';

?>