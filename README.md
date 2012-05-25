#CodeIgniter-Kitlang
Set the language code from URI - Extra helpers to switch language and build url.

##Detection language rules
1. Check the lang code in the first segment in URI. It's the master rule.
2. If no lang code is in URI, check for the code in a session if activated.
3. If no session is there, then check the browser language if available and activated.
4. As the last rule, set the lang code to the default one defined in config file.

##Configuration
Once installed, you need __to copy or move `MY_url_helper.php`__ in `application/helpers`

Check the `config/kitlang.php` to fit your needs.
	
	*  kitlang_default
	*  kitlang_languages
	*  kitlang_detect_browser
	*  kitlang_session_name

Add those lines in `application/config/routes.php` setting `$group_langs` properly.

	$group_langs = 'en|es|fr';
	$route["($group_langs)"] = $route['default_controller'];
	$route["($group_langs)/(:any)$"] = "$2";

##Usage
In your controller or your controller root

	$this->load->spark('kitlang/1.0.0');

Then you can use those if needed

	// Refers to the lang code in URL
	$lang = $this->kitlang->get_lang();

	// Refers to the language directory name in application/language
	$language = $this->kitlang->get_language();

##helpers
You can use those helpers in your application once kitlang has detected the current lang code.

	// Build the current URL with $lang in the first segment. 
	$url_lang = switch_lang($lang);

	// Override site_url() helper building the current URL automatically with the current lang code
	$url_lang = site_url(controller/action);

	// Fallback to default behaviour
	$url_without_lang = site_url(controller/action, true);




