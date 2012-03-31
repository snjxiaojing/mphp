<?php if(!defined('MPHP')) die(FORBIDDEN);

function hd_add_title($title)
{
	hd_add_header('add', 'title', $title);
}

function hd_add_desc($desc)
{
	hd_add_header('add', 'description', $desc);
}

function hd_add_kw($kw)
{
	hd_add_header('add', 'keywords', $kw);
}

function hd_add_js($js)
{
	hd_add_header('add', 'js', $js);
}

function hd_add_css($css)
{
	hd_add_header('add', 'css', $css);
}

function hd_add_header($method, $type = null, $value = null)
{
	static $hd_header;
	if('add' == $method)
	{
		if(in_array($type, array('js', 'css')))
		{
			$hd_header[$type][] = $value;
		}
		else
		{
			$hd_header[$type] = $value;
		}
	}
	elseif('get' == $method)
	{
		if(!$hd_header)
		{
			return '';
		}
		$header = '';

		// title
		$CFG = &load_class('config', 'core', 'M_');
		$global = $CFG->get_config('global');
		$ext_title = get_var($global, 'meta_title', '');
		$header .= '<title>' . get_var($hd_header, 'title', '') . ' | ' . $ext_title . '</title>';

		// description
		$header .= '<meta name="description" content="' . get_var($hd_header, 'description', '') . '" />';

		// keywords
		$header .= '<meta name="keywords" content="' . get_var($hd_header, 'keywords', '') . '" />';

		// css
		foreach(get_var($hd_header, 'css', array()) as $css)
		{
			$header .= '<link rel="stylesheet" type="text/css" href="/static/css/' . $css . '.css" />';
		}

		// js
		foreach(get_var($hd_header, 'js', array()) as $js)
		{
			$header .= '<script type="text/javascript" src="/static/js/' . $js . '.js"></script>';
		}

		// ext_header
		foreach(get_var($hd_header, 'ext_header', array()) as $v)
		{
			$header .= $v;
		}

		return $header;
	}
}

function hd_add_theme_js($js)
{
	$CFG = &load_class('config', 'core', 'M_');
	$config = $CFG->get_config('global');
	if(array_key_exists('theme', $config))
	{
		$theme = $config['theme'];
	}
	else
	{
		$theme = 'default';
	}
	hd_add_js($theme . '/' . $js);
}

function hd_add_theme_css($css)
{
	$CFG = &load_class('config', 'core', 'M_');
	$config = $CFG->get_config('global');
	if(array_key_exists('theme', $config))
	{
		$theme = $config['theme'];
	}
	else
	{
		$theme = 'default';
	}
	hd_add_css('th_' . $theme . '_' . $css);
}

function hd_clear()
{

}

function hd_set_pwd($pwd_arr = array())
{
	static $pwd;
	if(count($pwd_arr) > 0)
	{
		foreach($pwd_arr as $k=>$v)
		{
			if('' != $v)
			{
				$pwd .= "&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<a href='{$v}'>{$k}</a>";
			}
			else
			{
				$pwd .= "&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<a>{$k}</a>";
			}
		}
	}
	return '当前位置：' . ltrim($pwd, '&gt;&nbsp;');
}