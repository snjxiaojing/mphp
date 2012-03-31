<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Uri
{
	public $uri;
	public $uris;
	private $uri_num;


	public function __construct()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$re = parse_url($uri);
		$this->uri = $path = trim($re['path'], '/');
		$uris = explode('/', $path);
		$tmp_seg = array_pop($uris);
		if('' != $tmp_seg)
		{
			array_push($uris, $tmp_seg);
		}
		$this->uris = $uris;
		$this->uri_num = count($uris);
	}

	public function segment($i)
	{
		if($i <= 0 || $i > $this->uri_num)
		{
			return false;
		}
		else
		{
			return $this->uris[$i - 1];
		}
	}
}