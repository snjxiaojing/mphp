<?php if(!defined('MPHP')) die(FORBIDDEN);

class Comment extends M_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add($data)
	{
		$data['content'] = $this->filt_cmt($data['content']);
		$data['seat'] = $this->get_seat($data['post_id'], $data['pid']);
		$re = $this->db->insert('cmt', $data);

		$this->load->model('generator', 'g');
		$this->g->truncate();

		$cmt = array(
			'id'=> $re->insert_id,
			'n'	=> $data['author_name'],
			'u' => $data['author_url'],
			't' => date('Y.m.d H:i', $data['ctime']),
			'c' => $data['content'],
			'p' => $data['pid'],
			'a' => 'http://www.gravatar.com/avatar/'
					. md5(strtolower(trim($data['author_email']))),
			'pn'=> false
			);

		$id = $re->insert_id;

		if(0 != $cmt['p'])
		{
			$re = $this->db
				->select('author_name n')
				->get_where('cmt', array('id'=>$cmt['p']));
			$cmt['pn'] = $re->fetch_array[0]['n'];
		}
		$this->db->where('id', $data['post_id'])->incre('post', 'cmt_count');
		echo json_encode($cmt);

		if(0 != $data['pid'])
		{
			$this->mail_feedback($id, $data['pid']);
		}
	}

	public function filt_cmt($cmt)
	{
		$cmt = preg_replace('#<img(.*?)emotion/(\d{3}).gif">#i', '<img%src="/static/img/system/emotion/$2.gif">', $cmt, 3);
		$cmt = str_ireplace(array(' '), array('&nbsp;'), $cmt);
		$cmt = str_ireplace(array('</div><div>', '<div>', '</div>'), '<br>', $cmt);
		$cmt = str_ireplace(array('</p><p>', '<p>', '</p>'), '<br>', $cmt);
		while(str_find($cmt, '<br><br>'))
		{
			$cmt = str_ireplace('<br><br>', '<br>', $cmt);
		}
		while(str_find($cmt, '&nbsp;&nbsp;'))
		{
			$cmt = str_ireplace('&nbsp;&nbsp;', '&nbsp;', $cmt);
		}
		$cmt = str_ireplace('img%src="/static', 'img src="/static', $cmt);
		$cmt = strip_tags($cmt, '<br><img>');
		$cmt = str_trim($cmt, '<br>');
		return $cmt;
	}

	public function get_seat($post_id, $pid = 0)
	{
		if(0 == $pid)
		{
			$re = $this->db
					->select('seat')
					->order_by('seat', 'desc')
					->limit(1)
					->get_where('cmt', array('post_id'=>$post_id));
			$re = $re->fetch_array;
			if(0 == count($re))
			{
				return '00';
			}
			else
			{
				return $this->incre_seat($re[0]['seat']);
			}
		}
		else
		{
			$re = $this->db
					->select('seat')
					->get_where('cmt', array('post_id'=>$post_id, 'id'=>$pid));
			$re = $re->fetch_array;
			$seat = $re[0]['seat'];
			$re = $this->db
					->select('seat')
					->limit(1)
					->order_by('seat', 'desc')
					->like('seat', $seat, false)
					->get_where('cmt', array('post_id'=>$post_id));
			$re = $re->fetch_array;
			if(strlen($seat) == strlen($re[0]['seat']))
			{
				return $seat . '00';
			}
			else
			{
				return $this->incre_seat($re[0]['seat']);
			}
		}
	}

	private function incre_seat($seat)
	{
		$len = strlen($seat);
		$pre = '';
		if($len > 2)
		{
			$pre = substr($seat, 0, $len - 2);
			$seat = substr($seat, $len - 2, 2);
		}
		$a = substr($seat, 0, 1);
		$b = substr($seat, 1, 1);
		if('z' == $b)
		{
			$b = 0;
			$a = $this->incre_char($a);
		}
		else
		{
			$b = $this->incre_char($b);
		}
		return $pre . $a . $b;
	}

	private function incre_char($c)
	{
		$a = ord($c);
		if(57 == $a)
		{
			$a = 65;
		}
		elseif(90 == $a)
		{
			$a = 97;
		}
		elseif($a < 57 || $a < 90 || $a < 122)
		{
			$a++;
		}
		return chr($a);
	}

	public function del()
	{

	}

	public function get_bar()
	{
		$re = $this->db
			->select('id, post_id, author_name, content, ctime')
			->order_by('id', 'desc')
			->limit(10)
			->get('cmt');
		$cmts = $re->fetch_array;
		foreach($cmts as &$v)
		{
			$v['ctime'] = date('Y-m-d H:i', $v['ctime']);
			$v['content'] = strip_tags($v['content'], '<br>');
			$v['content'] = trim(str_replace(array('<br>', '&nbsp;'), ' ', $v['content']));
			$v['content'] = str_utf8_substr($v['content'], 40);
			$v['content'] = $v['content'] ? $v['content'] : '[纯表情回复]';
		}
		return $cmts;
	}

	public function get($pid)
	{
		$re = $this->db
			->select('id,author_name,author_email,author_url, ctime, content, pid, seat')
			->order_by('ctime', 'asc')
			->get_where('cmt', array('post_id'=>$pid));

		$return = array('cmt_tot'=>$re->num_rows);
		if($re->num_rows)
		{
			$list = $re->fetch_array;
			$tmp_list = array();

			foreach ($list as $k => &$v)
			{
				$tmp_list[$v['id']] = $v;
				$url = 'http://www.gravatar.com/avatar/'
					. md5(strtolower(trim($v['author_email'])));

				$v['avatar'] = $url;
				unset($v['author_email']);

				$v['ctime'] = date('Y.m.d H:i', $v['ctime']);

				if($v['pid'])
				{
					$v['p_user'] = $tmp_list[$v['pid']]['author_name'];
				}

				$v['author_url'] = ltrim($v['author_url'], 'http://');
			}
			$return['cmt_list'] = $list;
		}
		return $return;
	}

	function get_last_insert()
	{
		$re = $this->db
			->select('id,author_email,author_name n,author_url u,ctime t,content c,pid p')
			->limit(1)
			->order_by('ctime','desc')
			->get('cmt');
		$cmt = $re->fetch_array[0];
		$cmt['a'] = 'http://www.gravatar.com/avatar/'
					. md5(strtolower(trim($cmt['author_email'])));
		unset($cmt['author_email']);
		$cmt['t'] = date('Y.m.d H:i', $cmt['t']);
		$cmt['pn'] = false;
		if(0 != $cmt['p'])
		{
			$re = $this->db
				->select('author_name n')
				->get_where('cmt', array('id'=>$cmt['p']));
			$cmt['pn'] = $re->fetch_array[0]['n'];
		}
		return $cmt;
	}

	public function admin_list($page, &$pager)
	{
		$pagesize = 40;
		$start = ($page - 1) * $pagesize;
		$total = $this->db->count('cmt');
		$re = $this->db
				->select('c.id,c.post_id,p.title,c.content,c.ctime,c.pid,c.author_name,c.author_email,c.author_url')
				->limit($start, $pagesize)
				->join('post p', 'p.id=c.post_id')
				->order_by('c.ctime', 'desc')
				->get('cmt c');
		$list = $re->fetch_array;
		foreach ($list as &$v) {
			$v['ctime'] = date('Y-m-d H:i:s', $v['ctime']);
			$v['content'] = strip_tags($v['content']);
		}

		$this->load->lib('pager');
		$pager = $this->pager->gen_pager($pagesize, $total, "/admin/cmt_list/%page");

		return $list;
	}

	private function mail_feedback($cid, $pid)
	{
		$re = $this->db
				->select('c.id,c.author_name name, c.author_email email,c.ctime time,c.content,p.id post_id, p.title')
				->join('post p', 'p.id = c.post_id')
				->order_by('c.ctime')
				->where_in('c.id', array($cid, $pid))
				->get('cmt c');
		$re = $re->fetch_array;
		if($re[0]['email'] != $re[1]['email'] or TRUE)
		{
			$c = $re[1];
			$p = $re[0];
			$c['time'] = date('Y-m-d H:i', $c['time']);
			$p['time'] = date('Y-m-d H:i', $p['time']);
			$c['content'] = preg_replace('/<img(.*?)>/i', '[表情]', $c['content']);
			$p['content'] = preg_replace('/<img(.*?)>/i', '[表情]', $p['content']);

			$msg = $this->load->view('other/mail', array('p'=>$p, 'c'=>$c), false);

			$headers = 'From: blogadmin@isharp.com' . "\r\n"
			. 'Reply-To: snjxiaojing@163.com' . "\r\n"
			. 'Content-type: text/html; charset=UTF-8' . "\r\n"
			. 'MIME-Version: 1.0' . "\r\n"
			. 'X-Mailer: PHP/' . phpversion();

			$subject = "{$p['name']} 您好，您在博客[我叫小井]里的留言有新回复";
			$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
			mail($p['email'], $subject, $msg, $headers);
			mail('snjxiaojing@163.com', $subject, $msg, $headers);
		}
	}
}