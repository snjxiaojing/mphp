<?php if(!defined('MPHP')) die(FORBIDDEN);

include(SYS_PATH . 'db/db_main.php');
include(SYS_PATH . 'db/db_condition.php');
include(SYS_PATH . 'db/db_action.php');
include(SYS_PATH . 'db/db_result.php');

class M_Db extends DB_Result
{	
	private $config;
	protected $o = null; // mysqli
	protected $tp; // table prefix

	public function __construct($config = array())
	{
		parent::__construct();
		$this->o = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database'], $config['hostport']);
		$this->config = $config;
		$this->tp = $config['dbprefix'];
		$this->clear();
	}

	public function clear()
	{
		$p = &$this->p;
		$p = array(
			'select' 	=> array('*'),
			'where'		=> array(0=>''),
			'join'		=> array(),
			'group'		=> array(),
			'having'	=> array(0=>''),
			'order'		=> array(),
			'limit'		=> '',
			'tabname'	=> ''
			);
	}

	public function close()
	{
		$this->o->close();
	}
}

/*EOF*/