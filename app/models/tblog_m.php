<?php if(!defined('MPHP')) die(FORBIDDEN);

class Tblog_m extends M_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add($data)
	{
		$data['ctime'] = time();
		$re = $this->db->insert('tblog', $data);
		$this->db->where('id',$data['cid'])->incre('cat', 'p_count');
		return $re->affected_rows;
	}

	public function del()
	{

	}

	public function admin_list($page, &$pager)
	{
		$pagesize = 30;
		$start = ($page - 1) * $pagesize;

		$total = $this->db->count('tblog');
		$url = "/admin/tblog_list/%page";

		$re = $this->db
				->select('t.id, t.cnt,t.cid,t.ctime,c.name cname')
				->join('cat c','c.id = t.cid')
				->order_by('t.id', 'desc')
				->limit($start, $pagesize)
				->get('tblog t');
		$post = $re->fetch_array;
		foreach($post as &$v)
		{
			$v['ctime'] = date('Y-m-d H:i', $v['ctime']);
		}

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($pagesize, $total, $url);

		return $post;
	}

	public function get($page, $cid, &$pager)
	{
		$pagesize = 20;
		$start = ($page - 1) * $pagesize;
		if(0 != $cid)
		{
			$total = $this->db->count('tblog', array('cid'=>$cid));
			$url = "/tblog/{$cid}/%page.html";
			$this->db->where('cid', $cid);
		}
		else
		{
			$total = $this->db->count('tblog');
			$url = "/tblog/%page.html";
		}
		$re = $this->db
				->select('t.cnt,t.cid,t.ctime,c.name cname')
				->join('cat c','c.id = t.cid')
				->order_by('t.id', 'desc')
				->limit($start, $pagesize)
				->get('tblog t');
		$post = $re->fetch_array;
		foreach($post as &$v)
		{
			$v['ctime'] = date('Y-m-d H:i', $v['ctime']);
		}

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($pagesize, $total, $url);

		return $post;
	}

}

/*EOF*/