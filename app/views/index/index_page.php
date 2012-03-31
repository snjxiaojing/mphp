<div id='articles'>
	<?php foreach($post as $v) {?>
	<div class='article'>
		<div class='post_head'>
			<h1><a href='/post/<?=$v['id']?>.html'><?=$v['title']?></a></h1>
			<div>
				<span>[我叫小井]发表于：<?=$v['ctime']?></span> | <span>分类：<a href="/category/<?=$v['cat_id']?>.html"><?=$v['cat_name']?></a></span>
			</div>
		</div>
		<hr style='margin:10px 0' />
		<div class='post_cnt'>
			<div><a href='/post/<?=$v['id']?>.html'><img width='630px' src="/static/img/top_pic/<?=$v['top_pic']?>" alt="" /></a></div>
			<div class='excerpt'><?=$v['excerpt']?></div>
		</div>
		<div class='post_action'>
			<span>查看[<?=$v['view_count']?>]</span>
			<span><a href="/post/<?=$v['id']?>.html#cmt">评论[<?=$v['cmt_count']?>]</a></span>
			<span><a href="/post/<?=$v['id']?>.html">点击阅读 >>></a></span>
		</div>
	</div>
	<?php } ?>
	<div id="pager"><?=$pager?></div>
</div>