<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
	<?php echo hd_add_header('get'); ?>
	<script type="text/javascript">var _gaq = _gaq || [];_gaq.push(['_setAccount', 'UA-29841434-1']);_gaq.push(['_trackPageview']);(function(){var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();</script>
</head>
<body>
	<div id="top_nav_bg"></div>
	<div id="wrap">
		<div id="top_nav_word">欢迎光临</div>
		<div class='header fn-left'>
			<div class='fn-left' id='logo'>
				<div><a href='/'>我叫小井</a></div>
				<span>你不给我一个说法，我就给你一个说法</span>
			</div>
			<div class='menu fn-left'>
				<ul>
					<li><a href="/">日志</a></li>
					<li><a href="/post/1.html">关于</a></li>
					<li><a href="/tblog">微日志</a></li>
				</ul>
			</div>
		</div>
		<div class="fn-clear"></div>
		<div class='page_info'>
			<span ><?php echo hd_set_pwd(); ?></span>
			<span class='fn-right'>
				<span>
					<a href='javascript:void(0)' id='screem_w'>宽屏</a>
					<a href='javascript:void(0)' id='screem_n'>窄屏</a>
				</span>
			</span>
		</div>
		<div id="main" class='fn-left'>
			<div id="cnt">
				<div id="inner"><?=$body?></div>
			</div>
			<div id="aside"><?=$sidebar?></div>
		</div>
	</div>
	<div id='footer'>
		<p>CopyLeft © 2012 <a href="/">我叫小井</a> . All Rights Reserved.<br />由 <a target='_blank' href='http://91host.net/'>91host.net</a> 提供主机支撑。</p>
	</div>
</body>
</html>