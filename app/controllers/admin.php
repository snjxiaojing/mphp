<?php if(!defined('MPHP')) die(FORBIDDEN);

class Admin extends M
{
	public function __construct()
	{
		parent::__construct();
		if(!DEBUG && ('59.108.77.208' != $_SERVER['REMOTE_ADDR'] || 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.1 Safari/536.5' !== get_var($_SERVER, 'HTTP_USER_AGENT', '')))
		{
			header('WWW-Authenticate: Basic realm=""');
			header('HTTP/1.0 401 Unauthorized');
			echo 'forbidden';
			die;
		}

		$this->load->db();
		hd_add_css('base');
		hd_add_css('admin');
		hd_add_js('JQ');
		hd_add_js('admin');

		$this->global = array();

		$this->load->model('category','cat');
		$this->global['tblog_cat'] = $this->cat->get(1);

		$this->load->model('sync');
		$this->global['qq_login_url'] = $this->sync->qq_login_url();

	}

	public function test()
	{
		p($this->input->post());
	}

	public function auth()
	{
		// if (isset($_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic realm="My Realm"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Text to send if user hits Cancel button';
		// exit;
		// } else {
		// 	echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
		// 	echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
		// }

	}

	public function ajax($type, $id = 0)
	{
		$this->load->model('post', 'p');
		switch ($type) {
			case 'post_add':
				if($this->p->add($this->input->post()))
				{
					echo json_encode(array('errno'=>0, 'msg'=>''));
				}
				else
				{
					echo json_encode(array('errno'=>1, 'msg'=>'Error appears...'));
				}
				$this->load->model('generator', 'g');
				$this->g->sitemap();
				break;
			case 'post_edit':
				if($this->p->update($id, $this->input->post()))
				{
					echo json_encode(array('errno'=>0, 'msg'=>''));
				}
				else
				{
					echo json_encode(array('errno'=>1, 'msg'=>'Error appears...'));
				}
				break;
			case 'cat_add':
				echo $this->cat->add($this->input->post());
				break;
			case 'tblog_add':
				$this->load->model('tblog_m', 't');
				echo $n = $this->t->add($this->input->post());
				if(!DEBUG && $n)
				{
					$post = $this->input->post();
					$this->sync->qq_add_topic($post['cnt']);
				}
				break;
			default:
				# code...
				break;
		}
		$this->load->model('generator', 'g');
		$this->g->truncate();
		die;
	}

	public function index()
	{
		hd_add_title('ADMIN');
		$this->cmt_list();
		// $page = $this->load->view('main', array(), false);
		// $this->load->view('page', array('body'=>$page));
	}

	public function post_add()
	{
		hd_add_js('JQ');
		hd_add_js('xheditor');
		$post['cat_list'] = $this->cat->get();
		$body = $this->load->view('post_add', array('post'=>$post), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function post_list($page = 1)
	{
		$this->load->model('post', 'p');
		$list = $this->p->get_list($page, $pager);
		$body = $this->load->view('post_list', array('list'=>$list, 'pager'=>$pager), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function post_edit($pid)
	{
		hd_add_js('xheditor');
		$this->load->model('post');
		$post = $this->post->get($pid);
		$post['cat_list'] = $this->cat->get();
		$body = $this->load->view('post_edit', array('post'=>$post), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function tblog_add()
	{
		$cats = $this->cat->get(1);
		$body = $this->load->view('tblog_add', array('cats'=>$cats), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function tblog_list($page = 1)
	{
		$this->load->model('tblog_m', 't');
		$list = $this->t->admin_list($page, $pager);
		$body = $this->load->view('tblog_list', array('pager'=>$pager, 'list'=>$list), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function cmt_list($page = 1)
	{
		$this->load->model('comment', 'cmt');
		$list = $this->cmt->admin_list($page, $pager);
		$body = $this->load->view('cmt_list', array('pager'=>$pager, 'list'=>$list), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function cat_list()
	{
		$this->load->model('category', 'cat');
		$list = $this->cat->get(-1);
		foreach($list as &$v)
		{
			$v['type'] = $v['type'] ? 'TBLOG' : 'BLOG';
		}
		$body = $this->load->view('cat_list', array('list'=>$list), false);
		$this->load->view('page', array('body'=>$body, 'global'=>$this->global));
	}

	public function generate_static()
	{

	}

	public function up()
	{
		if(count($_FILES) > 0)
		{
			$this->load->lib('upload', 'u');
			if('post' == $this->input->post('type'))
			{
				$this->u->set(array('savedPath' => ROOT_PATH . 'static/img/post/'));
			}
			$file = $this->u->upload('toppic');
			if($file)
			{
				echo $file;
			}
			else
			{
				echo $this->u->get_error();
			}
		}
		$this->load->view('temp');
	}

	public function sync($type)
	{
		if('qq' == $type)
		{
			$login_url = $this->sync->qq_login_url();
		}


		$status = $this->sync->get_status();

		$body = $this->load->view('sync_status', array('status'=>$status, 'url'=>$login_url), false);
		$this->load->view('page', array('body'=>$body));
	}

	public function sync_callback()
	{
		$get = $this->input->get();
		if(array_key_exists('state', $get))
		{
			$type = 'qq';

			if($this->sync->pro_qq($get))
			{
				echo '<script>alert("Succ");window.close();</script>';
			}

		}
		else
		{
			$type = 'sina';
		}
	}
}