<div id='articles'>
	<div id='tblog'>
	<?php foreach($post as $v) {?>
		<div>
			<p><span><?=++$start?>.[我叫小井]发表于：<?=$v['ctime']?></span> | <span>分类：<a href="/tblog/<?=$v['cid']?>/1.html"><?=$v['cname']?></a></span></p>
			<p class='t_cnt'><?=$v['cnt']?></p>
		</div>
	<?php } ?>
	</div>
	<div id="pager"><?=$pager?></div>
</div>