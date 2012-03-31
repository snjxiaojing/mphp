<?php if(!defined('MPHP')) die(FORBIDDEN);

class Tag extends M_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add($tag, $pid)
	{
		$tag = array_filter(explode(' ', $tag));
		$tag = array_unique($tag);
		$data = array();
		foreach ($tag as $k => $v) {
			$data[] = array('name'=>$v);
		}
		$this->db->ignore_multi_insert('tag', $data);
		$re = $this->db->select('id')->where_in('name', $tag)->get('tag');

		$data = array();
		foreach ($re->fetch_array as $k => $v) {
			$data[] = array(
				'tid'=>$v['id'],
				'pid'=>$pid
				);
		}
	
		$this->db->ignore_multi_insert('tag_post', $data);
		$this->db->where_in('name',$tag)->incre('tag', 'p_count');
	}

	public function post_tag_del($pid)
	{
		$re = $this->db->select('tid')->get_where('tag_post', array('pid'=>$pid));
		if($re->num_rows)
		{
			$this->db->where('pid', $pid)->delete('tag_post');
			$tag = array();
			foreach($re->fetch_array as $v)
			{
				$tag[] = $v['tid'];
			}
			$this->db->where_in('id', $tag)->incre('tag', 'p_count', -1);
		}
	}

	public function get_by_id($pid)
	{
		$re = $this->db
			->select('t.id,t.name,t.p_count')
			->join('tag t', 't.id=tp.tid')
			->get_where('tag_post tp', array('tp.pid'=>$pid));
		
		return $re->fetch_array;
	}

	public function get_bar($count)
	{
		$re = $this->db
			->limit(50)
			->order_by('p_count','desc')
			->get('tag');
		return $re->fetch_array;
	}
}