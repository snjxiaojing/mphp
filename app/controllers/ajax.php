<?php if(!defined('MPHP')) die(FORBIDDEN);

class Ajax extends M
{
	public function __construct()
	{
		parent::__construct();
		$this->load->db();
	}

	public function add_cmt()
	{
		if($pid = $this->get_post_id_by_referer())
		{

			$data = array();
			$ip = get_var($_SERVER, 'REMOTE_ADDR', false);
			if($ip)
			{
				$data['author_ip'] = ip2long($ip);
			}
			else
			{
				$data['author_ip'] = 0;
			}
			$data['user_agent'] = get_var($_SERVER, 'HTTP_USER_AGENT', '');
			$post = $this->input->post();

			set_cookie('user', $post['user']);
			set_cookie('email', $post['email']);
			set_cookie('url', $post['url']);

			$data['post_id'] = $pid;
			$data['author_name'] = htmlspecialchars($post['user'], ENT_QUOTES);
			$data['author_email'] = $post['email'];
			$data['author_url'] = $post['url'];
			$data['content'] = $post['cmt'];
			$data['pid'] = $post['pid'];
			$data['ctime'] = time();
			$this->load->model('comment', 'cmt');
			$this->cmt->add($data);
		}
		die;
	}

	public function pv()
	{
		if($pid = $this->get_post_id_by_referer())
		{
			$this->load->db();
			$this->load->model('post', 'p');
			$pv = $this->p->pv($pid);
			echo $pv;
		}
		die;
	}

	private function get_post_id_by_referer()
	{
		if(array_key_exists('HTTP_REFERER', $_SERVER))
		{
			// [HTTP_REFERER] => http://127.0.0.1/post/47.html

			$referer = $_SERVER['HTTP_REFERER'];
			$url = parse_url($referer);
			$bool = preg_match('/^\/post\/(\d+)\.html/', $url['path'], $matches);
			if($bool)
			{
				return $matches[1];
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

}

/*EOF*/