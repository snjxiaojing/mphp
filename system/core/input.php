<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Input
{
	private $get;
	private $post;
	private $cookie;

	public function __construct()
	{
		$this->get = $this->_pro_arr($_GET);
		$this->post = $this->_pro_arr($_POST);
		$this->cookie = $this->_pro_arr($_COOKIE);
		unset($_GET);
		unset($_POST);
		unset($_COOKIE);
	}

	private function _pro_arr($arr)
	{
		$new_arr = array();
		foreach ($arr as $k => $v) {
			$new_arr[$k] = str_clean($v);
		}
		return $new_arr;
	}

	private function _get($key, $type, $url_encode = false)
	{
		if(null === $key)
		{
			return $this->$type;
		}
		else
		{
			if(array_key_exists($key, $this->$type))
			{
				$arr = $this->$type;
				return str_clean($arr[$key], $url_encode);
			}
			else
			{
				return false;
			}
		}
		
	}

	public function get($key = null)
	{
		return $this->_get($key, 'get', true);
	}

	public function post($key = null)
	{
		return $this->_get($key, 'post');
	}

	public function cookie($key = null)
	{
		return $this->_get($key, 'cookie');
	}
}

/*EOF*/