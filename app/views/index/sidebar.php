<div class='sb-d'>
	<h3>日志分类</h3>
	<ul>
		<?php foreach($cat as $v) {?>
		<li><a href="/category/<?=$v['id']?>.html"><?=$v['name']?>(<?=$v['p_count']?>)</a></li>
		<?php } ?>
	</ul>
</div>
<div class='sb-d'>
	<h3>最新文章</h3>
	<ul>
		<?php foreach($post as $v) {?>
		<li><a href="/post/<?=$v['id']?>.html"><?=$v['title']?></a></li>
		<?php } ?>
	</ul>
</div>
<div class='sb-d'>
	<h3>最新评论</h3>
	<ul id='sb_cmt'>
		<?php foreach($cmt as $v) {?>
		<li><a href="/post/<?=$v['post_id']?>.html#cmt_anchor_<?=$v['id']?>" title='<?=$v['author_name']?> 发表于 <?=$v['ctime']?>'><?=$v['content']?></a></li>
		<?php } ?>
	</ul>
</div>
<div class='sb-d'>
	<h3>日志标签</h3>
	<div>
		<?php foreach($tag as $v) {?>
		<a class="tag_cloud" href="/tag/<?=$v['id']?>.html"><?=$v['name']?>(<?=$v['p_count']?>)</a>
		<?php } ?>
	</div>
</div>
<div class='sb-d'>
	<h3>友情链接</h3>
	<ul>
		<li></li>
		<li></li>
	</ul>
</div>