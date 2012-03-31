<div id='page_header'></div>
<div class='article'>
	<div id="blog_header">
		<h1><?=$post['post']['title']?></h1>
		<span>[我叫小井]发表于：<?=$post['post']['ctime']?></span> | <span>分类：<a target='_blank' href="/category/<?=$post['post']['cat_id']?>.html"><?=$post['post']['cat_name']?></a>
		</span>
	</div>
	<hr style='margin:10px 0' />
	<div><img width='630px' src="/static/img/top_pic/<?=$post['post']['top_pic']?>" alt='' /></div>
	<div id="blog_content"><?=$post['post']['content']?></div>
	<div id='blog_end'>[全文完]<br /><a rel="license" target='_blank' href="http://creativecommons.org/licenses/by-sa/2.5/cn/"><img alt="知识共享署名-相同方式共享 2.5 中国大陆许可协议" title="知识共享署名-相同方式共享 2.5 中国大陆许可协议" src="http://i.creativecommons.org/l/by-sa/2.5/cn/88x31.png" /></a></div>
	<div id="blog_bottom">查看[<span id='pv'>加载中...</span>]<span><a href="#cmt">评论[<?=$post['post']['cmt_count']?>]</a></span><br />相关标签：<?php foreach($post['tag'] as $v){ ?>
			<span><a href="/tag/<?=$v['id']?>.html"><?=$v['name']?>[<?=$v['p_count']?>]</a></span>
		<?php } ?>
	</div>
</div>

<div id='neighbour_post'>
	<span class='fn-left'>
		<a href="/post/<?=$post['prev']['id']?>.html">&lt;&lt;&lt;&nbsp;&nbsp;&nbsp;<?=$post['prev']['title']?></a>
	</span>
	<span class='fn-right'>
		<a href="/post/<?=$post['next']['id']?>.html"><?=$post['next']['title']?>&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;</a>
	</span>
	<div class="fn-clear"></div>
	<span>暂无相关日志</span>
</div>
<div><strong>当前共有<?=$post['cmt']['cmt_tot']?>条评论：</strong></div>
<div id='cmt'>
	<ul id='cmt_ul'>
	<?php if($post['cmt']['cmt_tot'])
	{
		$i = 1;
		foreach($post['cmt']['cmt_list'] as $v)
		{
			$ml = (strlen($v['seat']) - 2) . '0';
			$ml = min(200, $ml);
			$ml = 0;// 取消回复设计， 对应改变获取评论列表时的order_by
			$wd = 607 - $ml;
			echo sprintf("<li id='cmt_list_%d' class='cmt_list' style='margin-left:%dpx;width:%dpx'>", $v['id'], $ml, $wd);
			?>
				<div id='cmt_anchor_<?=$v['id']?>'><?=$i++?>.</div>
				<div class='fn-right cmt_detail'>
					<div class="cmt_detail_inner">
						<p>
							<a id='cmt<?=$v['id']?>' href="http://<?=$v['author_url']?>" target='_blank' rel="nofollow"><?=$v['author_name']?></a>
							<span class='ctime'>[<?=$v['ctime']?>]</span>
							<span class='fn-right'><a href="#pre_cmt_div" name='cmt<?=$v['id']?>' onclick='reply(<?=$v['id']?>)'>回复</a></span>
						</p>
						<div id='cmt_cnt_<?=$v['id']?>'><?php if($v['pid']) { ?>
							<a class='show_pcmt' href='#cmt_anchor_<?=$v['pid']?>' onmouseover='show_pcmt(<?=$v['pid']?>,<?=$v['id']?>)' onmouseout='hide_pcmt()'>@&nbsp;<?=$v['p_user']?>：</a><?php } ?><?=$v['content']?></div>
					</div>
				</div>
				<div class='fn-left avatar'>
					<img src="<?=$v['avatar']?>" />
				</div>
			</li>
			<?php
		}
	} ?>
	</ul>

	<div class="fn-clear"></div>
	<div id="pre_cmt_div" class='fn-hide'>
		<a href="javascript:void(0)" onclick='cancel()' class="fn-right">取消回复</a>
		<span>回复评论：</span>
		<hr />
		<div id='pre_cmt_cnt'></div>
	</div>

	<div id='cmt_form'>
		<div class="input-div">
			<input id='username' type="text" />
			<label for="username">昵称[必填]</label>
		</div>
		<div class="input-div">
			<input id='email' type="text" />
			<label for="email">邮箱[必填，用于显示<a tabindex="-1" target='_blank' href='http://www.gravatar.com/'>Gravatar</a>头像]</label>
		</div>
		<div class="input-div">
			<input id='url' type="text" />
			<label for="url">站点[不需要'http://'，不支持中文域名]</label>
		</div>
		<div id='emt_btn'>
			<a tabindex="-1" href="javascript:void(0)" onclick='emt(this,0)'>普通表情</a>
			<a tabindex="-1" href="javascript:void(0)" onclick='emt(this,1)'>文艺表情</a>
			<a tabindex="-1" href="javascript:void(0)" onclick='emt(this,2)'>2B表情</a>
			<a tabindex="-1" href="javascript:void(0)" onclick='emt(this,3)'>随机表情</a>
			<br />
			<span>[注：每条回复暂限包含最多三个表情，多于三个会被自动过滤]</span>
		</div>
		<div>
			<div style='padding:0' id='cmt_textarea' contenteditable='true'></div>
		</div>
		<div>
			<p id='notice' class="fn-hide notice">提交中...</p>
			<a href='javascript:void(0)' id='submit'>提交评论</a>&nbsp;<span>[Ctrl+Enter]</span>
		</div>
	</div>
</div>
<div class="fn-hide emt" id='emt'></div>