<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

if (! function_exists('get_settings')) {
  function get_settings($key = '') {
    $CI	=&	get_instance();
    $CI->load->database();

    $CI->db->where('key', $key);
    $result = $CI->db->get('settings')->row()->value;
    return $result;
  }
}

if (! function_exists('currency')) {
  function currency($price = "") {
    $CI =&  get_instance();
    $CI->load->database();

    // 1. Kiểm tra nếu giá rỗng hoặc bằng 0 thì hiển thị Free/Miễn phí
    if ($price == "" || $price == 0) {
        return get_phrase('free');
    }

    // 2. Lấy các cài đặt từ Database (Logic cũ của bạn)
    $CI->db->where('key', 'system_currency');
    $currency_code = $CI->db->get('settings')->row()->value;

    $CI->db->where('code', $currency_code);
    $symbol = $CI->db->get('currency')->row()->symbol;

    $CI->db->where('key', 'currency_position');
    $position = $CI->db->get('settings')->row()->value;

    // 3. FORMAT SỐ TIỀN (Phần nâng cấp)
    // 5000000 -> 5.000.000
    // number_format(số tiền, số thập phân, dấu thập phân, dấu hàng nghìn)
    $formatted_price = number_format($price, 0, ',', '.');

    // 4. Trả về kết quả tùy theo vị trí hiển thị (Trái/Phải)
    if ($position == 'right') {
        return $formatted_price.$symbol;
    }elseif ($position == 'right-space') {
        return $formatted_price.' '.$symbol;
    }elseif ($position == 'left') {
        return $symbol.$formatted_price;
    }elseif ($position == 'left-space') {
        return $symbol.' '. $formatted_price;
    }
  }
}
// ----------------------------------------------------------------------------

if (! function_exists('currency_code_and_symbol')) {
  function currency_code_and_symbol($type = "") {
    $CI =&  get_instance();
    $CI->load->database();
    $CI->db->where('key', 'system_currency');
    $currency_code = $CI->db->get('settings')->row()->value;

    $CI->db->where('code', $currency_code);
    $symbol = $CI->db->get('currency')->row()->symbol;
    if ($type == "") {
      return $symbol;
    }else {
      return $currency_code;
    }

  }
}

if (! function_exists('get_frontend_settings')) {
  function get_frontend_settings($key = '') {
    $CI	=&	get_instance();
    $CI->load->database();

    $CI->db->where('key', $key);
    $result = $CI->db->get('frontend_settings')->row()->value;
    return $result;
  }
}

if ( ! function_exists('slugify'))
{
  function slugify($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
            return 'n-a';
        return $text;
    }
}

if ( ! function_exists('get_video_extension'))
{
    // Checks if a video is youtube, vimeo or any other
    function get_video_extension($url) {
        if (strpos($url, '.mp4') > 0) {
            return 'mp4';
        } elseif (strpos($url, '.webm') > 0) {
            return 'webm';
        } else {
            return 'unknown';
        }
    }
}

if ( ! function_exists('ellipsis'))
{
    // Checks if a video is youtube, vimeo or any other
    function ellipsis($long_string, $max_character = 30) {
        $short_string = strlen($long_string) > $max_character ? substr($long_string, 0, $max_character)."..." : $long_string;
		return $short_string;
    }
}



