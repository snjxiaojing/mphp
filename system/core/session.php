<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Session
{
	public function __construct()
	{
		session_start();
	}

	public function set($key, $val = '')
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v) {
				$_SESSION[$k] = $v;
			}
		}
		else
		{
			$_SESSION[$key] = $val;
		}
	}

	public function get($key)
	{
		if(array_key_exists($key, $_SESSION))
		{
			return str_clean($_SESSION[$key]);
		}
		else
		{
			return false;
		}
	}
}