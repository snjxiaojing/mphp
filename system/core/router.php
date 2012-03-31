<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Router
{
	private $src_uri;
	private $pro_uri;
	private $config;

	public $app;
	public $method;
	public $params;

	public function __construct()
	{

	}

	public function set_routing()
	{
		$CFG =& load_class('config', 'core', 'M_');
		$URI =& load_class('uri', 'core', 'M_');
		$this->config = $CFG->get_config('router');
		$this->src_uri = $URI->uri;

		$bool = $this->pro_uri();

		if(!$bool)
		{
			$this->pro_uri = $URI->uri;
		}

		$this->pro_uri;
		$uris = explode('/', $this->pro_uri);

		$this->app = array_shift($uris);
		$this->method = array_shift($uris);
		$this->params = $uris;
		$this->app = ($this->app) ? $this->app : $this->config['default_app'];
		$this->method = ($this->method) ? $this->method : 'index';

		unset($this->src_uri);
		unset($this->pro_uri);
		unset($this->config);
	}

	private function pro_uri()
	{
		$config = $this->config;
		$uri = $this->src_uri;
		unset($config['default_app']);

		foreach ($config as $k => $v) {
			$k = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $k));
			$preg = '#^' . $k . '$#';
			if(preg_match($preg, $uri))
			{
				if(false !== strpos($v, '$') && false !== strpos($k, '('))
				{
					$v = preg_replace($preg, $v, $uri);
				}
				$this->pro_uri = $v;
				return true;
			}
		}

		return false;
	}

}