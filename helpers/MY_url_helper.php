<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// DEVTIME Kitlang : Override of site_url() helper avoiding to add the lang code
// In case we want to fallback to default behaviour : $default = true
//
// WARNING : if we use some functions from core system that directly call CI_config->site_url() 
// we need to add the lang code (if desired) at the beginning of the uri parameter of those functions 

if ( ! function_exists('site_url'))
{
	function site_url($uri = '', $default = false)
	{
		$CI =& get_instance();
		
		if (! $default)
		{
			$CI->load->library('kitlang');
			$lang = $CI->kitlang->get_lang();
			
			if (is_array($uri))
			{
				array_unshift($uri, $lang);
			}
			else
			{
				$uri = $lang . "/" . $uri;
			}
			
		}
		return $CI->config->site_url($uri);
	}
}