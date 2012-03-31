<table>
	<tr>
		<td style='width:40px'>标题：</td>
		<td><input type="text" id='title' value='' /></td>
	</tr>
	<tr>
		<td>摘要：</td>
		<td><textarea id='excerpt' rows='3'></textarea></td>
	</tr>
	<tr>
		<td>内容：</td>
		<td><textarea id="editor" style='width:100%' rows='15' class="xheditor {skin:'default',tools:'Blocktag,Strikethrough,Removeformat,Link,Unlink,Img,Source,Preview.Fullscreen'}"></textarea></td>
	</tr>
	<tr>
		<td>分类：</td>
		<td>
			<select id="cat">
			<?php foreach ($post['cat_list'] as $v) { ?>
			<option value="<?=$v['id']?>"><?=$v['name']?></option>
			<?php }?>
			</select>
			<input type='text' style='width:100px' />
			<input type='button' value='添加' onclick='add_cat(0, this)' />
		</td>
	</tr>
	<tr>
		<td>标签：</td>
		<td><input type='text' id='tag' value='' /></td>
	</tr>
	<tr>
		<td>密码：</td>
		<td><input type="text" id='passwd' value=''><input type="hidden" id='top_pic' value='banner.jpg'></td>
	</tr>
	<tr>
		<td colspan='2'><input type="button" onclick='add_submit()' value='发布' /></td>
	</tr>
</table>