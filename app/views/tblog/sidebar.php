<div class='sb-d'>
	<h3>微日志分类</h3>
	<ul>
		<?php foreach($cats as $v) {?>
		<li><a href="/tblog/<?=$v['id']?>/1.html"><?=$v['name']?>(<?=$v['p_count']?>)</a></li>
		<?php } ?>
	</ul>
</div>