<?php if(!defined('MPHP')) die(FORBIDDEN);

require(SYS_PATH . 'core/common.php');
require(APP_PATH . 'config/constants.php');

set_error_handler('_exception_handler', E_ALL);

ini_set('magic_quotes_runtime', 0);

$M_CACHE = load_class('cache', 'core', 'M_');
$bool = $M_CACHE->get_cache();

// $CFG = &load_class('config', 'core', 'M_');

if(DEBUG)
{
	$bool = false;
	error_reporting(E_ALL);
	ini_set("display_errors", TRUE);
}
else
{
	error_reporting(0);
	ini_set("display_errors", FALSE);
}

if(!$bool)
{
	$M_RTR = load_class('router', 'core', 'M_');
	$M_RTR->set_routing();

	include(SYS_PATH . 'core/controller.php');
	function &get_instance()
	{
		return M::get_instance();
	}

	$app = $M_RTR->app;
	if(!file_exists(APP_PATH . 'controllers/' . $app . '.php'))
	{
		show_404();
	}
	else
	{
		include(APP_PATH . 'controllers/' . $app . '.php');
	}
	$M = new $app;

	$method = $M_RTR->method;
	if(!in_array(strtolower($method), array_map('strtolower', get_class_methods($M))) || 0 === strpos($method, '_'))
	{
		show_404();
	}

	call_user_func_array(array($M, $method), $M_RTR->params);

	if(isset($M->db))
	{
		$M->db->close();
	}
}




/*EOF*/