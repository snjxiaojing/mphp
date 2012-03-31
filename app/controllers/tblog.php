<?php if(!defined('MPHP')) die(FORBIDDEN);

class Tblog extends M
{
	public function __construct()
	{
		parent::__construct();
		$this->load->db();
		$this->load->model('generator', 'g');
	}

	public function index($num1 = 1, $num2 = 0)
	{
		if(0 == $num2)
		{
			$page = $num1;
			$cid = 0;
		}
		else
		{
			$page = $num2;
			$cid = $num1;
		}
		$this->g->tblog($page, $cid);
	}
}

/*EOF*/