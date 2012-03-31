<?php if(!defined('MPHP')) die(FORBIDDEN);

class Index extends M
{
	public function __construct()
	{
		parent::__construct();
		$this->load->db();
		$this->load->model('generator', 'g');
	}

	public function index()
	{
		$this->g->page(1);
	}

	public function page($page)
	{
		$this->g->page($page);
	}

	public function post($pid)
	{
		$this->g->post($pid);
	}

	public function category($cid, $page = 1)
	{
		$this->g->category($cid, $page);
	}

	public function tag($tid, $page = 1)
	{
		$this->g->tag($tid, $page);
	}
}

/*EOF*/