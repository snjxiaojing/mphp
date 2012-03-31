<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Loader
{
	private $loaded;
	public function __construct()
	{
		$this->loaded['model'] = array();
		$this->loaded['lib'] = array();
		$this->loaded['helper'] = array();
	}

	public function model($class, $var = '', $force_load = false)
	{
		if('' == $var)
		{
			$var = $class;
		}
		$class = ucfirst($class);


		if(!class_exists('M_Model'))
		{
			include(SYS_PATH . 'core/model.php');
		}

		if(!class_exists($class))
		{
			include(APP_PATH . 'models/' . strtolower($class) . '.php');
		}

		$M = get_instance();
		if(isset($M->$var) && !$force_load)
		{
			if($class === get_class($M->$var))
			{
				return $M->$var;
			}
			else
			{
				show_error(E_NOTICE, "The property Controller::$var already exists, if you want to force load, please call like this : \$this->load->model('class', 'var', true)");
			}
		}

		if(isset($this->loaded['model'][$class]))
		{
			$M->$var = $this->loaded['model'][$class];
			return false;
		}

		$M->$var = new $class();
		$this->loaded['model'][$class] = $M->$var;
	}

	public function lib($class, $var = '', $force_load = false)
	{
		if('' == $var)
		{
			$var = $class;
		}
		$class = ucfirst($class);

		$M = get_instance();
		if(isset($M->$var) && !$force_load)
		{
			show_error(E_NOTICE, "The property Controller::$var already exists, if you want to force load, please call klike this : \$this->load->model('class‘, ’var', true)");
		}

		if(isset($this->loaded['lib'][$class]))
		{
			$M->$var = $this->loaded['lib'][$class];
			return false;
		}

		foreach (array(APP_PATH, SYS_PATH) as $v) {
			$file = $v . 'lib/' . strtolower($class) . '.php';
			if (file_exists($file)) {
				include $file;
			}
		}

		$M->$var = new $class();
		$this->loaded['lib'][$class] = $M->$var;
	}

	public function view($filename, $data = array(), $echo = true)
	{
		$M_RTR = load_class('router', 'core', 'M_');
		$found = false;
		foreach(array(APP_PATH . 'views/' . $M_RTR->app, APP_PATH . 'views') as $v)
		{
			$file = $v . '/' . $filename . '.php';
			if(file_exists($file))
			{
				$found = true;
				break;
			}
		}
		if(!$found)
		{
			show_error(E_NOTICE, "Template file $filename.php not found");
		}

		extract($data);
		ob_start();
		include($file);
		$str = ob_get_contents();
		ob_end_clean();

		if($echo)
		{
			$M_OP = load_class('output', 'core', 'M_');
			$M_OP->append($str);
		}
		return $str;
	}

	public function db($force_load = false, $var = 'db', $group = '')
	{

		$M = get_instance();
		if(isset($M->$var))
		{
			if($force_load)
			{
				$M->db->close();
				$M->db = null;
			}
			else
			{
				show_error(E_NOTICE, "The property Controller::$var already exists, if you want to force load, please call klike this : \$this->load->model('class‘, ’var', true)");
			}

		}

		$M_CFG = load_class('config', 'core', 'M_');
		$config = $M_CFG->get_config('database');

		if('' == $group)
		{
			$group = $config['active'];
		}
		$config = $config[$group];
		include SYS_PATH . 'db/db.php';
		$M->$var = new M_Db($config);
	}

	public function helper($name)
	{
		if(in_array($name, $this->loaded['helper']))
		{
			return false;
		}
		$found = false;
		foreach(array(APP_PATH, SYS_PATH) as $v)
		{
			$file = $v . 'helper/' . $name . '.php';
			if(file_exists($file))
			{
				include($file);
				$found= true;
				$this->loaded['helper'][] = $name;
				break;
			}
		}

		if(!$found)
		{
			show_error(E_NOTICE, "Helper $name not found");
		}
		return true;
	}
}

/*EOF*/