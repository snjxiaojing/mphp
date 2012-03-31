<?php if(!defined('MPHP')) die(FORBIDDEN);

abstract class M
{
	private static $instance;

	public function __construct()
	{
		self::$instance = &$this;
		$this->_init();
	}

	private function _init()
	{
		$this->load = load_class('loader', 'core', 'M_');

		$this->load->helper('string');

		$this->session = load_class('session', 'core', 'M_');
		$this->cache = load_class('cache', 'core', 'M_');
		$this->input = load_class('input', 'core', 'M_');
		$this->load->helper('header');
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}