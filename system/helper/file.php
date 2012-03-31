<?php if(!defined('MPHP')) die(FORBIDDEN);

function file_truncate_dir($dir)
{
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