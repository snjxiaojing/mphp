<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Cache
{
	private $file;

	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		$M_CFG = load_class('config', 'core', 'M_');
		$this->config = $M_CFG->get_config('global');
		$cache_dir = $this->config['cache_dir'];
		$filename = md5($_SERVER['REQUEST_URI']);
		$file = rtrim($cache_dir, '/') . '/' . $filename;
		$this->file = $file;
	}

	public function set_cache($ttl)
	{
		$file = $this->file;
		$cache_dir = dirname($file);
		if(!file_exists($cache_dir))
		{
			file_mkdir($cache_dir);
		}
		$M_OP = load_class('output', 'core', 'M_');
		$buffer = $M_OP->get_buffer();
		$buffer = (time() + $ttl) . $buffer;
		file_put_contents($file, $buffer);
	}

	public function get_cache()
	{
		$cached = false;
		$file = $this->file;
		if(file_exists($file))
		{
			$current_time = time();
			$buffer = file_get_contents($file);
			$expire_time = substr($buffer, 0, 10);
			if($current_time > $expire_time)
			{
				$cached = false;
				unlink($file);
			}
			else
			{
				$cached = true;
				$buffer = substr($buffer, 10);
				$M_OP = load_class('output', 'core', 'M_');
				echo $M_OP->get_buffer();
				$M_OP->append($buffer);
			}
		}
		else
		{
			$cached = false;
		}

		return $cached;
	}


}