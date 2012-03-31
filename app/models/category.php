<?php if(!defined('MPHP')) die(FORBIDDEN);

class Category extends M_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get($type = 0)
	{
		if(-1 == $type)
		{
			$re = $this->db->order_by('type')->get('cat');
		}
		else
		{
			$re = $this->db->select('id,name,p_count')->get_where('cat',array('type'=>$type));
		}
		return $re->fetch_array;
	}

	public function add($data)
	{
		$re = $this->db->insert('cat', $data);
		return $re->insert_id;
	}

	public function update()
	{

	}

	public function get_bar()
	{
		return $this->get();
	}

	public function get_post()
	{

	}

	public function incre($cid)
	{
		$this->db->where('id', $cid)->incre('cat', 'p_count');
	}
}