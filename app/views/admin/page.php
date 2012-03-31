<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head><?=hd_add_header('get'); ?></head>
<body>
	<div><?=$body?></div>
	<div id='menu'>
		<ul>
			<li>isharp.me ↑</li>
			<li><a href="/admin/">CMT_LIST</a></li>
			<li><a href="/admin/post_list">POST_LIST</a></li>
			<li><a href="/admin/post_add">POST_ADD</a></li>
			<li><a href="/admin/tblog_list">TBLOG_LIST</a></li>
			<li><a href="javascript:window_show()">TBLOG_ADD</a></li>
			<li><a href="javascript:toQzoneLogin('<?=$global['qq_login_url']?>')">QQ_SYNC</a></li>
		</ul>
	</div>
	<div id='cover' class='fn-hide'></div>
	<div id="window" class='fn-hide'>
		<table>
			<tr><td style='background-color:#afe;cursor:move'>ADD_TBLOG<span onclick='window_hide()' id='window_close'>X</span></td></tr>
			<tr><td><textarea cols='100' rows='3' id='tblog_cnt'></textarea></td></tr>
			<tr><td>
				<select id='tblog_cat'>
					<?php foreach($global['tblog_cat'] as $v){ ?>
					<option value="<?=$v['id']?>"><?=$v['name']?></option>
					<? } ?>
				</select>
				<input type="text" />
				<input type="button" value='ADD' onclick='add_cat(1, this)' />
			</td></tr>
			<tr><td><input type="button" value='submit' onclick='tblog_add()' /></td></tr>
		</table>
	</div>
</body>
</html>