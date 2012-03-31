<?php if(!defined('MPHP')) die(FORBIDDEN);

class Generator extends M_Model
{
	public function __construct()
	{
		$this->load->model('post', 'p');
		hd_add_js('base');
		hd_add_css('base');
		hd_add_js('front');
		hd_add_theme_css('global');
	}

	public function gen_all()
	{

	}

	public function page($page)
	{
		if(1 == $page)
		{
			hd_add_title('首页');
			hd_set_pwd(array('日志首页'=>''));
		}
		else
		{
			hd_add_title('日志列表');
			hd_set_pwd(array('日志首页'=>'/', "日志列表&nbsp;第{$page}页"=>''));
		}
		hd_add_kw('我叫小井 日志');
		hd_add_desc('我叫小井的个人网络日志，记录我自己的生活。');
		$post = $this->p->get_brief($page, $pager);
		$body = $this->load->view('index_page', array('post'=>$post, 'pager'=>$pager), false);
		$str = $this->load->view('page', array('body'=>$body, 'sidebar'=>$this->sidebar()));
		$this->write('page/' . $page, $str);
		if(1 == $page && !DEBUG)
		{
			$str = str_replace(array("\n", "\t", "\r"), '', $str);
			$str = preg_replace('/>\s+</', '><', $str);
			file_put_contents(ROOT_PATH . 'index.html', $str);
		}
		$this->error_page(404);
	}

	public function post($pid = 20)
	{
		hd_add_js('prettify');
		hd_add_css('prettify');
		$post = $this->p->get($pid);

		hd_add_title($post['post']['title']);
		hd_set_pwd(array('日志首页'=>'/', "日志内容：【{$post['post']['title']}】"=>''));
		hd_add_kw('我叫小井 日志');
		hd_add_desc($post['post']['excerpt']);
		$kw = '';
		foreach($post['tag'] as $v)
		{
			$kw .= $v['name'] . ' ';
		}
		hd_add_kw($kw);

		$body = $this->load->view('post', array('post'=>$post), false);
		$str = $this->load->view('page', array('body'=>$body, 'sidebar'=>$this->sidebar()));
		$this->write('post/' . $pid, $str, false);
		$this->write('post/' . $pid, $str, false);
	}

	public function category($cid, $page)
	{
		$post = $this->p->get_brief_by_cat($cid, $page, $pager);
		hd_add_title("分类[{$post[0]['cat_name']}]");
		hd_set_pwd(array('日志首页'=>'/', "日志分类：【{$post[0]['cat_name']}】&nbsp;第{$page}页"=>''));
		$body = $this->load->view('index_page', array('post'=>$post, 'pager'=>$pager), false);
		$str = $this->load->view('page', array('body'=>$body, 'sidebar'=>$this->sidebar()));
		$this->write('category/' . $cid, $str);
		$this->write('category/' . $cid . '/' . $page, $str);
	}

	public function tag($tid, $page)
	{
		$post = $this->p->get_brief_by_tag($tid, $page, $pager);

		$re = $this->db->select('name')->get_where('tag', array('id'=>$tid));
		hd_add_title("标签[{$re->fetch_array[0]['name']}]");
		hd_set_pwd(array('日志首页'=>'/', "【{$re->fetch_array[0]['name']}】相关日志&nbsp;第{$page}页"=>''));

		$body = $this->load->view('index_page', array('post'=>$post, 'pager'=>$pager), false);
		$str = $this->load->view('page', array('body'=>$body, 'sidebar'=>$this->sidebar()));
		$this->write('tag/' . $tid, $str);
		$this->write('tag/' . $tid . '/' . $page, $str);
	}

	private function sidebar()
	{
		$data = array();

		$this->load->model('category','cat');
		$data['cat'] = $this->cat->get_bar();

		$data['post'] = $this->p->get_bar();

		$this->load->model('comment', 'cmt');
		$data['cmt'] = $this->cmt->get_bar();

		$this->load->model('tag', 't');
		$data['tag'] = $this->t->get_bar(50);

		return $this->load->view('index/sidebar', $data, false);
	}

	public function sitemap()
	{
		$re = $this->db->select('id,mtime')->get('post');
		$post = $re->fetch_array;
		$xml = $this->load->view('other/sitemap', array('post'=>$post), false);
		file_put_contents(ROOT_PATH . 'sitemap.xml', $xml);
	}

	public function tblog($page, $cid)
	{
		$this->load->model('tblog_m');
		$post = $this->tblog_m->get($page, $cid, $pager);
		if(0 == count($post))
		{
			show_404();
		}
		if(0 != $cid)
		{
			hd_add_title("分类[{$post[0]['cname']}] | 微日志");
			hd_set_pwd(array('微日志首页'=>'/tblog',"微日志分类：【{$post[0]['cname']}】&nbsp;第{$page}页"=>''));
		}
		else
		{
			hd_add_title("微日志");
			hd_set_pwd(array("微日志列表&nbsp;第{$page}页"=>''));
		}
		hd_add_kw('我叫小井 微日志');
		hd_add_desc('我叫小井的个人网络日志，记录我自己的生活 - 碎碎念页面');

		$start = ($page - 1) * 20;
		$body = $this->load->view('tblog', array('start'=>$start, 'post'=>$post, 'pager'=>$pager), false);
		$str = $this->load->view('page', array('body'=>$body, 'sidebar'=>$this->t_sidebar()));
		if(0 == $cid)
		{
			$this->write('tblog/' . $page, $str);
			if(1 == $page)
			{
				$this->write('tblog/index', $str);
			}
		}
		else
		{
			$this->write('tblog/' . $cid . '/' . $page, $str);
		}
	}

	private function t_sidebar()
	{
		$this->load->model('category', 'cat');
		$cats = $this->cat->get(1);
		return $this->load->view('sidebar', array('cats'=>$cats), false);
	}

	public function truncate()
	{
		// foreach (array('category', 'page', 'post', 'tag', 'error') as $dir) {
		// 	$dir = ROOT_PATH . 'runtime/data/' . $dir . '/';
		// 	file_truncate_dir($dir);
		// }

		file_truncate_dir(ROOT_PATH . 'runtime/data/');

		if(file_exists(ROOT_PATH . 'index.html'))
		{
			unlink(ROOT_PATH . 'index.html');
		}
	}

	public function t_truncate()
	{
		$dir = ROOT_PATH . 'runtime/data/tblog/';
		file_truncate_dir($dir);
	}

	private function write($file, $str, $trim = true)
	{
		if(DEBUG)
		{
			return false;
		}
		if($trim)
		{
			$str = str_replace(array("\n", "\t"), '', $str);
		}
		$str = str_replace(array("\r"), '', $str);
		$str = preg_replace('/>\s+</', '><', $str);
		$dir = dirname(ROOT_PATH . 'runtime/data/' . $file);
		if(!file_exists($dir))
		{
			file_mkdir($dir);
		}
		file_put_contents(ROOT_PATH . 'runtime/data/' . $file . '.html', $str);
	}

	public function error_page($type)
	{
		$file = ROOT_PATH . 'runtime/data/error/' . $type . '.html';
		if(!file_exists($file))
		{
			$body = $this->load->view('error/' . $type, array(), false);
			$str = $this->load->view('page', array('body'=>$body, 'sidebar'=>$this->sidebar()), false);
			$this->write('error/' . $type, $str);
		}
	}
}

/*EOF*/