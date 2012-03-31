<?php if(!defined('MPHP')) die(FORBIDDEN);

class Post extends M_Model
{
	private $pagesize = 10;

	public function __construct()
	{
		parent::__construct();
	}

	public function get($pid)
	{
		$re = $this->db
			->select('p.id,p.title,p.excerpt,p.ctime,p.content,p.passwd,p.top_pic,p.cmt_count,p.cat_id,c.name as cat_name')
			->join('cat c','c.id = p.cat_id')
			->order_by('p.ctime', 'desc')
			->get_where('post p', array('p.id'=>$pid));

		if(0 == $re->num_rows)
		{
			show_404();
		}
		$data['post'] = $re->fetch_array[0];
		$data['post']['ctime'] = date('Y-m-d H:i', $data['post']['ctime']);
		$this->load->model('tag', 't');
		$data['tag'] = $this->t->get_by_id($pid);
		if($data['post']['cmt_count'])
		{
			$this->load->model('comment', 'cmt');
			$data['cmt'] = $this->cmt->get($pid);
		}
		else
		{
			$data['cmt']['cmt_tot'] = 0;
		}

		$re = $this->db
			->select('id,title')
			->where('id <', $pid)
			->order_by('id', 'desc')
			->limit(1)
			->get('post');
		if($re->num_rows)
		{
			$data['prev'] = $re->fetch_array[0];
		}
		else
		{
			$data['prev'] = array('id'=>$pid, 'title'=>'没有上一篇了');
		}

		$re = $this->db
			->select('id,title')
			->where('id >', $pid)
			->order_by('id', 'asc')
			->limit(1)
			->get('post');
		if($re->num_rows)
		{
			$data['next'] = $re->fetch_array[0];
		}
		else
		{
			$data['next'] = array('id'=>$pid, 'title'=>'没有下一篇了');
		}

		// p($data['cmt']);
		return $data;
	}

	public function get_bar()
	{
		$re = $this->db
			->select('id,title')
			->limit(10)
			->order_by('id','desc')
			->get('post');
		return $re->fetch_array;
	}

	public function get_brief($page, &$pager)
	{
		$start = ($page-1) * $this->pagesize;
		$total = $this->db->count('post');
		$re = $this->db
			->select('p.id,p.title,p.ctime,p.excerpt,p.passwd,p.top_pic,p.view_count,cmt_count,c.id as cat_id,c.name as cat_name')
			->join('cat c','c.id = p.cat_id')
			->order_by('p.ctime', 'desc')
			->limit($start, $this->pagesize)
			->get_where('post p', array('p.status'=>1));

		if(0 == $re->num_rows)
		{
			show_404();
		}

		$data = $re->fetch_array;
		foreach ($data as $k => $v) {
			$data[$k]['ctime'] = date('Y-m-d H:i', $v['ctime']);
		}

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($this->pagesize, $total, '/page/%page.html');

		return $data;
	}

	public function get_brief_by_cat($cid, $page, &$pager)
	{
		$start = ($page-1) * $this->pagesize;

		$re = $this->db->select('p_count')->get_where('cat', array('id'=>$cid));
		if(0 == $re->num_rows)
		{
			show_404();
		}
		$total = $re->fetch_array[0]['p_count'];

		$re = $this->db
			->select('p.id,p.title,p.ctime,p.excerpt,p.passwd,p.top_pic,p.view_count,cmt_count,c.id as cat_id,c.name as cat_name')
			->join('cat c','c.id = p.cat_id')
			->order_by('p.ctime', 'desc')
			->limit($start, $this->pagesize)
			->get_where('post p', array('p.status'=>1, 'cat_id'=>$cid));

		$data = $re->fetch_array;
		foreach ($data as $k => $v) {
			$data[$k]['ctime'] = date('Y-m-d H:i', $v['ctime']);
		}

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($this->pagesize, $total, "/category/{$cid}/%page.html");

		return $data;
	}

	public function get_brief_by_tag($tid, $page, &$pager)
	{
		$start = ($page-1) * $this->pagesize;

		$re = $this->db->select('p_count')->get_where('tag', array('id'=>$tid));
		if(0 == $re->num_rows)
		{
			show_404();
		}
		$total = $re->fetch_array[0]['p_count'];

		$re = $this->db
			->select('pid')
			->get_where('tag_post', array('tid'=>$tid));
		if(0 == $re->num_rows)
		{
			show_404();
		}
		$ids = array();
		foreach($re->fetch_array as $v)
		{
			array_push($ids, $v['pid']);
		}
		$re = $this->db
			->select('p.id,p.title,p.ctime,p.excerpt,p.passwd,p.top_pic,p.view_count,cmt_count,c.id as cat_id,c.name as cat_name')
			->where_in('p.id', $ids)
			->join('cat c','c.id = p.cat_id')
			->order_by('p.ctime', 'desc')
			->limit($start, $this->pagesize)
			->get_where('post p', array('status'=>1));

		$data = $re->fetch_array;
		foreach ($data as $k => $v) {
			$data[$k]['ctime'] = date('Y-m-d H:i', $v['ctime']);
		}

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($this->pagesize, $total, "/tag/{$tid}/%page.html");

		return $data;
	}

	public function add($post)
	{
		$post['content'] = $this->filt_cnt($post['content']);
		if('' == $post['excerpt'])
		{
			$post['excerpt'] = $this->get_excerpt($post['content']);
		}
		// p($post);die;
		$tag = $post['tag'];
		unset($post['tag']);
		$post['ctime'] = time();
		$post['mtime'] = time();
		$re = $this->db->insert('post', $post);
		if($re->affected_rows)
		{
			$this->load->model('tag', 't');
			$this->t->add($tag, $re->insert_id);
			$this->load->model('category', 'cat');
			$this->cat->incre($post['cat_id']);

			return true;
		}
		else
		{
			return false;
		}
	}

	private function filt_cnt($cnt)
	{
		$cnt = str_ireplace('<p><br></p>', '', $cnt);
		return $cnt;
	}

	private function get_excerpt($cnt)
	{
		$excerpt = substr($str, 3, strpos($str, '</p>'));
		return $excerpt;
	}

	public function pv($pid)
	{
		$this->db->where('id', $pid)->incre('post', 'view_count');
		$re = $this->db->select('view_count as n')->get_where('post', array('id'=>$pid));
		$pv = $re->fetch_array;
		return $pv[0]['n'];
	}

	public function get_list($page, &$pager)
	{
		$start = ($page - 1) * $this->pagesize;
		$total = $this->db->count('post');
		$re = $this->db
				->select('p.id,p.title,p.passwd,p.cmt_count,p.view_count,c.name cname')
				->order_by('ctime','desc')
				->join('cat c', 'c.id = p.cat_id')
				->limit($start, $this->pagesize)
				->get('post p');

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($this->pagesize, $total, "/admin/post_list/%page");

		return $re->fetch_array;
	}

	public function drop($id)
	{

	}

	public function update($id, $post)
	{
		$re = $this->db->select('cat_id')->get_where('post', array('id'=>$id));
		$cid = $re->fetch_array[0]['cat_id'];
		if($cid != $post['cat_id'])
		{
			$this->db->where('id', $cid)->incre('cat', 'p_count', -1);
			$this->db->where('id', $post['cat_id'])->incre('cat', 'p_count');
		}


		if('' == $post['excerpt'])
		{
			unset($post['excerpt']);
		}
		$post['content'] = $this->filt_cnt($post['content']);
		$tag = $post['tag'];
		unset($post['tag']);
		$post['mtime'] = time();

		$re = $this->db
				->where('id', $id)
				->update('post', $post);

		$this->load->model('tag', 't');
		$this->t->post_tag_del($id);
		$this->t->add($tag, $id);
		return true;
	}

	public function get_related($id)
	{

	}
}