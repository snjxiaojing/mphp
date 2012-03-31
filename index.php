<?php

/*
	Linux Only
	Php 5.2 Only
	Chrome Only
*/

date_default_timezone_set('Asia/Shanghai');

$bench_start = microtime();

$sql_num = 0;

define('MPHP', true);
define('DEBUG', true);


define('ROOT_PATH',dirname(__FILE__) . '/');

if(defined('STDIN'))
{
	chdir(ROOT_PATH);
}

define('SYS_PATH', ROOT_PATH . 'system/');
define('APP_PATH', ROOT_PATH . 'app/');

header("Content-Type: text/html;charset=utf-8");

require(SYS_PATH . 'core/mphp.php');

$bench_end = microtime();
$bench_time = $bench_end - $bench_start;
$bench_out = "<div style='background:#ddd;position:fixed;bottom:0;right:0;font-size:12px;'>"
			. "程序执行时间：$bench_time s"
			. "<br />内存使用：".memory_get_usage()."字节"
			. "<br />sql语句执行：{$sql_num} 条"
			. "</div>";

// echo $bench_out;

/*EOF*/