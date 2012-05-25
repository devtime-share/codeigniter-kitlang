<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Devtime
 *
 * Set language code from URI
 * 
 *	lang : refers to lang code (ex : en, es)
 *	language : refers to CI directory within application/language (ex : english, spanish)
 * 
 * @package		Kitlang
 * @author		<share@devtime.com> Devtime
 * @name		Kitlang.php
 * @link        http://www.devtime.com
 * @version		1.0.0
 */

class Kitlang
{
	/**
	 * The active lang code 
	 * @var string 
	 */
	private $lang = '';
	
	/**
	 * Default lang code
	 * Defined in config file
	 * @var string 
	 */
	private $lang_default = '';
	
	/**
	 * Array of all languages ('code' => 'language')
	 * Defined in config file
	 * @var array
	 */
	private $languages = array();
	
	/**
	 * Keys of $languages
	 * @var array 
	 */
	private $langs = array();	
	
	/**
	 * Session name where lang code $lang is stored
	 * Defined in config file
	 * @var string 
	 */
	private $session_name = '';
	
	// boolean
	/**
	 * Browser lang detection
	 * Defined in config file
	 * @var boolean 
	 */
	private $detect_browser = true;
	
	private $CI = null;
	
	public function __construct() 
	{
		$this->CI =& get_instance();
		
		$this->load_config();
		$this->set_lang();
		$this->set_CI_language();
	}
	
	public function get_lang()
	{
		return $this->lang;
	}
	
	public function get_language()
	{
		return $this->languages[$this->lang];
	}	
	
	// Build the current url with the lang code
	public function switch_lang($lang)
	{
		$uri = $this->CI->uri->uri_string();
		$group_langs = implode('|', $this->langs);

		$uri = preg_replace("/^(\/)?($group_langs)/", '',$uri);
		$uri = ltrim($uri, "/");

		$url_lang = $this->CI->config->site_url($lang . "/" . $uri);
		return $url_lang;
	}
	
	// Define the active language
	private function set_lang()
	{
		//print_r($this->CI->uri->rsegment_array());
		//echo $this->CI->uri->ruri_string();
		
		// Set from default config
		$this->lang = $this->lang_default;
				
		// Set from browser
		if ($this->detect_browser)
		{
			$this->lang = $this->detect_browser_lang();
		}

		// Set from session
		$this->set_session();
		
		// Set from URI
		$first_segment = $this->CI->uri->segment(1);
		if (in_array($first_segment, $this->langs))
		{
			$this->lang = $first_segment;
			$this->set_session($first_segment);
		}
	}	
	
	// Load config/kitlang.php
	private function load_config()
	{
		$this->CI->config->load('kitlang');
		
		$this->lang_default = $this->CI->config->item('kitlang_default');
		
		$languages = $this->CI->config->item('kitlang_languages');
		if ($languages)
		{
			$this->languages = $languages;
			$this->langs = array_keys($languages);
		}
		
		if (! array_key_exists($this->lang_default, $this->languages))
		{
			return show_error("Spark kitlang : Default lang code is not defined in languages. Check the config file.");
		}
		
		$this->session_name = $this->CI->config->item('kitlang_session_name');
		
		$this->detect_browser = $this->CI->config->item('kitlang_detect_browser');
	}
	
	private function set_session($lang = null)
	{
		$name = $this->session_name;
		if ($name)
		{
			$this->CI->load->library('session');
			if ($lang)
			{
				$this->CI->session->set_userdata($name, $lang);
				return;
			}
			if ($this->CI->session->userdata($name))
			{
				$this->lang = $this->CI->session->userdata($name);
			}
			$this->CI->session->set_userdata($name, $this->lang);
		}
	}
	
	private function detect_browser_lang()
	{
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$browser_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			foreach ($browser_langs as $str_lang)
			{
				$lang = substr($str_lang, 0, 2);
				if(in_array($lang, $this->langs))
				{
					return $lang;
				}
			}
		}
		return $this->lang_default;
	}
	
	// Set $config['language'] in config.php 
	private function set_CI_language()
	{
		$this->CI->config->set_item('language', $this->get_language());
	}	
}