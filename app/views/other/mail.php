<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<style type="text/css">
		a {
			color:#83B724;
		}
	</style>
</head>
<body style='background:#ddd;font-family:"Microsoft YaHei";font-size:12px;'>
	<div>
		<h3><?=$p['name']?>，您好，<?=$c['name']?>在<a href='http://isharp.me' target='_blank'>[我叫小井的网络日志]</a>博文<a target='_blank' href='http://isharp.me/post/<?=$p['post_id']?>.html'>《<?=$p['title']?>》</a>上回复了您的评论：</h3>
		<blockquote style='border:1px dashed #888;padding:10px;'>
			<p><?=$p['name']?>在<?=$p['time']?>时说：</p>
			<p><?=$p['content']?></p>
			<p style='text-indent:2em;'><?=$c['name']?>在<?=$c['time']?>时回复：</p>
			<p style='text-indent:2em;'><?=$c['content']?></p>
			<p><a target='_blank' href='http://isharp.me/post/<?=$p['post_id']?>.html#cmt_anchor_<?=$c['id']?>'>[点此去博客中查看该评论]</a></p>
		</blockquote>
		<div>
			<p style='color:gray;'><i>此邮件一式两份发送到 <?=$p['name']?> 的邮箱(<?=$p['email']?>)和博主邮箱(snjxiaojing@163.com)。</i></p>
		</div>
	</div>
</body>
</html>