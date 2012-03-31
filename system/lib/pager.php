<?php if(!defined('MPHP')) die(FORBIDDEN);

class Pager
{
	private $pagesize;
	private $total;
	private $url;
	private $btn_num;
	private $show_first;
	private $show_pre;
	private $w_first;
	private $w_last;
	private $w_pre;
	private $w_next;
	private $c_nbtn;
	private $c_cbtn;

	public function __construct()
	{
		$CFG = load_class('config', 'core', 'M_');
		$pager_cfg = $CFG->get_config('pager');
		$this->set_cfg($pager_cfg);
	}

	public function gen_pager($pagesize, $total, $url)
	{
		list($left, $right) = explode('%page', $url);
		$ptn = str_replace(array('%page', '.'), array('(\d+)', '\.'), $url);
		$ptn = '#' . $ptn . '#';
		$cur_url = $_SERVER['REQUEST_URI'];
		$tot_page = ceil($total/$pagesize);
		if(1 == $tot_page)
		{
			return '';
		}
		$offset = floor($this->btn_num / 2);
		$cur_page = preg_match($ptn, $cur_url, $matches) ? $matches[1] : 1;
		if($cur_page > $tot_page)
		{
			$cur_page = $tot_page;
		}

		$pager = '';

		if($cur_page > 1 + $offset)
		{
			$pager .= $this->show_first ? "<span class='{$this->c_nbtn}'><a href='{$left}1{$right}'>{$this->w_first}</a></span>" : '';
			$pager .= $this->show_pre ? "<span class='{$this->c_nbtn}'><a href='{$left}" . ($cur_page - $offset - 1) . "{$right}'>{$this->w_pre}</a></span>" : '';
		}

		for ($i=$cur_page - $offset;$i <= $cur_page + $offset ;$i++) {
			if($i < 1 || $i > $tot_page)
			{
				continue;
			}
			$pager .= ($i == $cur_page) ?
					"<span class='{$this->c_cbtn}'><a>{$i}</a></span>"
					: "<span class='{$this->c_nbtn}'><a href='{$left}{$i}{$right}'>{$i}</a></span>";
		}

		if($cur_page < $tot_page - $offset)
		{
			$pager .= $this->show_pre ? "<span class='{$this->c_nbtn}'><a href='{$left}" . ($cur_page + $offset + 1) . "{$right}'>{$this->w_next}</a></span>" : '';
			$pager .= $this->show_first ? "<span class='{$this->c_nbtn}'><a href='{$left}{$tot_page}{$right}'>{$this->w_last}</a></span>" : '';
		}
		return $pager;
	}

	public function set_cfg($cfg = array())
	{
		foreach ($cfg as $k => $v) {
			if(in_array($k, array('btn_num', 'show_first', 'show_pre', 'w_first', 'w_last', 'w_pre', 'w_next', 'c_nbtn', 'c_cbtn')))
			{
				$this->$k = $v;
			}
		}
	}

}

/*EOF*/