<?php
/**
 * App Helper
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 *         Andy Aliansah <andyaliansah97@gmail.com>
 */

/**
 * Menampilkan Config Codeigniter
 */
function show_config($key = '')
{
    $ci =& get_instance();

    $config = $ci->config->config;

    if(array_key_exists($key, $config))
    {
        return $config[$key];
    }

    return '-';
}

/**
 * Helper untuk upload via Codeigniter
 */
function upload_file($path=null, $name=null, $rename=null, $allowed_types = '')
{
	$ci =& get_instance();

    ini_set('memory_limit','960M');
    ini_set('post_max_size','2084M');
    ini_set('upload_max_filesize', '2084M');
    $config['upload_path'] = $path;

    $allow = '*';

    if($allowed_types != '')
    {
        $allow = $allowed_types;
    }
    $config['allowed_types'] = $allow;
    //$config['max_size'] = '6400000';

    if($rename!=null) {
        $config['file_name'] = $rename;
    }
    $ci->load->library('upload',$config);
    if(!$ci->upload->do_upload($name)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Helper untuk If Clause in Twig
 */
function same($word1, $word2)
{
    if($word1 == $word2)
    {
        return true;
    }

    return false;
}

function check_array($array)
{
    if(is_array($array))
    {
        return true;
    }

    return false;
}


/***************
 * Helper Dies *
 * *************/
function super_unique_die($array)
{
    $res = array();
    $result = array_map("unserialize", array_unique(array_map("serialize", $array)) );

    foreach ($result as $rr) {
        $res[] = array(
            'text' => $rr['text'],
            'value' => $rr['value'],
            'id' => $rr['id'],
        );
    }

    return $res;
}

function convert_dice($dice)
{
    $dice_txt = ($dice == null) ? '' : $dice;

    $txt = '';
    $expl = explode(",", $dice_txt);

    if(count($expl) > 0)
    {
        foreach($expl as $rexpl)
        {
            if($rexpl != '' || $rexpl != null)
            {
                $txt .= $rexpl.', ';
            }
        }
    }
    else
    {
        $txt = $dice_txt;
    }

    return rtrim($txt, ', ');
}


/**
 * Data level User
 */
function user_level_data($level_id = '')
{
    $level_data = [
        [
            'level_id'   => 0,
            'level_name' => 'Admin'
        ],
        [
            'level_id'   => 1,
            'level_name' => 'Super Admin'
        ],
    ];

    if($level_id != '')
    {
        if(array_key_exists($level_id, $level_data))
        {
            return $level_data[$level_id]['level_name'];
        }

        return '-';
    }

    return $level_data;

}


/**************************** PO *******************************/

/**
 * Get Total Qty by header id
 */
function get_po_qty_ammount_by_header($header_id)
{
    // instance data dll
    $ci =& get_instance();
    $ci->load->model('Po_model');
    $total_qty = 0;
    $total_ammount = 0;

    // get detail by header
    $get_detail = $ci->Po_model->get_detail_advance($header_id)->result();

    // jika data ada maka lakukan perhitungan
    if($get_detail)
    {
        // looping data
        foreach($get_detail as $detail)
        {
            $total_qty +=
                $detail->size_1 +
                $detail->size_2 +
                $detail->size_3 +
                $detail->size_4 +
                $detail->size_5 +
                $detail->size_6 +
                $detail->size_7 +
                $detail->size_8 +
                $detail->size_9 +
                $detail->size_10;

            $total_qty2 =
                $detail->size_1 +
                $detail->size_2 +
                $detail->size_3 +
                $detail->size_4 +
                $detail->size_5 +
                $detail->size_6 +
                $detail->size_7 +
                $detail->size_8 +
                $detail->size_9 +
                $detail->size_10;

            $total_ammount += $total_qty2 * $detail->po_detail_fob;
        }
    }

    return [
        'qty'     => $total_qty,
        'ammount' => $total_ammount
    ];
}

/**
 * Currency
 */
function get_currency()
{
    $qr = [
        [
            'curr_id'   => 'RP',
            'curr_name' => 'RP',
        ],
        [
            'curr_id'   => 'USD',
            'curr_name' => 'USD'
        ]
    ];

    return $qr;
}
