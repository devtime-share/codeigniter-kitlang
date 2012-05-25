<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

// Build the current url with the lang code
if ( ! function_exists('switch_lang'))
{
	function switch_lang($lang)
	{
		$CI =& get_instance();
		$CI->load->library('kitlang');
		
		$url_lang = $CI->kitlang->switch_lang($lang);
		return $url_lang;
	}
}

