<?php if(!defined('MPHP')) die(FORBIDDEN);

function &load_class($class, $dir = 'libraries', $prefix = '')
{
	$className = $prefix . ucfirst($class);
	$classFile = strtolower($class);
	static $loaded_class;

	if(isset($loaded_class[$className]))
	{
		return $loaded_class[$className];
	}

	foreach (array(SYS_PATH, APP_PATH) as $v) {
		if(file_exists($file = $v . $dir . '/' . $classFile . '.php'))
		{
			include($file);
			break;
		}
	}

	if(!class_exists($className))
	{
		_exception_handler(E_ERROR, "Class $className not found!!!");
	}

	$loaded_class[$className] = new $className();
	return $loaded_class[$className];
}



function _exception_handler($errno, $errstr, $errfile = '', $errline = 0)
{
	$errfile = substr($errfile, strlen(ROOT_PATH), strlen($errfile) - strlen(ROOT_PATH));
	$levels = array(
					E_ERROR				=>	'Error',
					E_WARNING			=>	'Warning',
					E_PARSE				=>	'Parsing Error',
					E_NOTICE			=>	'Notice',
					E_CORE_ERROR		=>	'Core Error',
					E_CORE_WARNING		=>	'Core Warning',
					E_COMPILE_ERROR		=>	'Compile Error',
					E_COMPILE_WARNING	=>	'Compile Warning',
					E_USER_ERROR		=>	'User Error',
					E_USER_WARNING		=>	'User Warning',
					E_USER_NOTICE		=>	'User Notice',
					E_STRICT			=>	'Runtime Notice',
					E_RECOVERABLE_ERROR	=>	'Recoverable Error',
					// E_DEPRECATED		=>	'Deprecated',
					// E_USER_DEPRECATED	=>	'User Deprecated',
					E_ALL   			=>	'All Error'
				);

	if(DEBUG)
	{	$error = '<table style="text-align:left;width:100%;font-family:Courier New;font-size:14px;border:1px dashed #f00;">'
			. '<tr><th colspan="2">A PHP Error was encountered</th></tr>'
			. '<tr><th style="text-align:right" width="120px;">Severity:</th><td>'.$levels[$errno].'</td></tr>'
			. '<tr><th style="text-align:right">Message:</th><td>'.$errstr.'</td></tr>';

		if($errfile)$error .= '<tr><th style="text-align:right">Filename:</th><td>'.$errfile.'</td></tr>';
		if($errline)$error .= '<tr><th style="text-align:right">Line Number:</th><td>'.$errline.'</td></tr>';

		$error .= '</table>';
		echo $error;
	}
	else
	{
		$error = $levels[$errno] . ' | ' . $errstr . ' | ' . $errfile . ' | ' . $errline;
		l($error);
	}
	die;
}


function show_error($errno, $errmsg)
{
	_exception_handler($errno, $errmsg);
}


function show_404()
{
	header('HTTP/1.1 404 Page not found');
	l(404);
	if(file_exists(ROOT_PATH . 'runtime/data/error/404.html'))
	{
		die(file_get_contents(ROOT_PATH . 'runtime/data/error/404.html'));
	}
	else
	{
		die('404 - Page not found!!!');
	}
}

function l($error)
{
	$file = ROOT_PATH . 'runtime/log/log.txt';
	if(!file_exists($file))
	{
		if(!file_exists(dirname($file)))
		{
			file_mkdir(dirname($file));
		}
		file_put_contents($file, '');
	}
	$f = fopen($file ,'a');
	$error = date('Y-m-d H:i:s'). ' | ' . get_var($_SERVER, 'REQUEST_URI', 'URI') . ' | ' . get_var($_SERVER, 'HTTP_USER_AGENT', 'UA') . ' | ' . get_var($_SERVER, 'REMOTE_ADDR', 'IP') . ' | ' . get_var($_SERVER, 'HTTP_REFERER', 'REF') . ' | ' . $error . "\n";
	fwrite($f, $error);
	fclose($f);
}


function p($p, $v = false)
{
	if($v)
	{
		var_dump($p);
	}
	else
	{
		print_r($p);
	}
}

function get_var($arr, $key , $default = false)
{
	if(array_key_exists($key, $arr))
	{
		return $arr[$key];
	}
	else
	{
		return $default;
	}
}

function set_cookie($key, $val, $expire = 0, $path = '/')
{
	if(0 == $expire)
	{
		$expire = 3600 * 24 * 365 + time();
	}
	setcookie($key, $val, $expire, $path);
}

function file_truncate_dir($dir)
{
	if(!file_exists($dir))
	{
		return false;
	}
	$dir = rtrim($dir, '/') . '/';
	$d = opendir($dir);
	while($file = readdir($d))
	{
		if('.' == $file || '..' == $file)
		{
			continue;
		}
		$file = $dir . $file;
		if('dir' == filetype($file))
		{
			file_truncate_dir($file);
			rmdir($file);
		}
		else
		{
			unlink($file);
		}
	}
	closedir($d);
}

function file_mkdir($dir)
{
	$pdir = dirname($dir);
	if(!file_exists($pdir) || 'dir' != filetype($pdir))
	{
		file_mkdir($pdir);
	}
	mkdir($dir);
}

/*EOF*/