<?php
/**
 * Output Helper
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 *         Andy Aliansah <andyaliansah97@gmail.com>
 */

/**
 * Output JSON
 */
function output_json($array)
{
	$ci =& get_instance();

	return $ci->output->set_output(json_encode($array));
}

/**
 * Asset URL
 */
function assets_url($path)
{
	return base_url($path).'?'.time_now();
}

/**
 * Convert NUmbering Decimal
 */
function to_decimal($val, $num = 2, $add_null_after = false, $show_zero = false)
{
	if($show_zero == false)
	{
		if($val == 0)
		{
			return '';
		}
	}

	$decimal = (float) round($val, $num, PHP_ROUND_HALF_ODD);
	if($add_null_after == true || $add_null_after == 'true')
	{
		return sprintf("%0.".$num."f",$decimal);
	}
	return $decimal;
}

/**
 * DUmping in Twig
 */
function dump($dumping)
{
	return var_dump($dumping);
}

/**
 * Add Zero
 */
function add_zero($numbering, $type = 2)
{
	return str_pad($numbering, $type, '0', STR_PAD_LEFT);
}

/**
 * Trim String
 */
function trims($str)
{
	return str_replace(',', '', $str);
}

/**
 * Replaced Text
 */
function replaced_text($subject = '', $search = '/', $replace = 'cmr')
{
	return str_replace($search, $replace, $subject);
}

/**
 * Image Loader
 */
function loader_app($width = '')
{
	$html = '<div class="loader">
				</div>';
	//$img = '<span><img class="app-loader" src="'.base_url('assets/images/loader.gif').'" style="width: '.$width.'"></span>';

	return $html;
}

/**
 * Check array key exist
 */
function check_array_key($array, $key)
{
	if(count($array) > 0)
	{
		if(array_key_exists($key, $array))
		{
			return $array[$key];
		}
	}

	return '';
}

/**
 * Untuk konversi button crud
 */
function convert_button($button_action, $crud_id)
{
	switch ($button_action) {
		case 'save':
			$id = $crud_id;
			break;

		case 'save_new':
			$id = 'new';
			break;

		case 'save_close':
			$id = 'close';
			break;

		default:
			$id = 'new';
			break;
	}

	return $id;
}

function count_twig($array)
{
	return count($array);
}

/**
 * Currency Format
 */
function to_currency($number)
{
	$replace = number_format($number,0,',','.');
	return $replace;
}

function array_remove_duplicate_key($data, $key) {

	$_data = array();

	foreach ($data as $v) {
		if (isset($_data[$v[$key]])) {
			// found duplicate
			continue;
		}
		// remember unique item
		$_data[$v[$key]] = $v;
	}

	// if you need a zero-based array, otheriwse work with $_data
	$data = array_values($_data);
	return $data;
}
