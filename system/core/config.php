<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Config
{
	private $config = array();

	public function __construct()
	{
		
	}

	public function get_config($item)
	{
		if(isset($this->config[$item]))
		{
			return $this->config[$item];
		}
		else
		{
			return $this->load_config($item);
		}
	}

	private function load_config($item)
	{
		include(APP_PATH . 'config/'. $item . '.php');
		$this->config[$item] = $$item;
		unset($$item);
		return $this->config[$item];
	}
}