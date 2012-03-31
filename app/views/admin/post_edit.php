<table>
	<tr>
		<td style='width:40px'>标题：</td>
		<td><input type="text" id='title' value='<?=$post['post']['title']?>' /></td>
	</tr>
	<tr>
		<td>摘要：</td>
		<td><textarea id='excerpt' rows='3'><?=$post['post']['excerpt']?></textarea></td>
	</tr>
	<tr>
		<td>内容：</td>
		<td><textarea id="editor" style='width:100%' rows='15' class="xheditor {skin:'default',tools:'Blocktag,Strikethrough,Removeformat,Link,Unlink,Img,Source,Preview.Fullscreen'}"><?=$post['post']['content']?></textarea></td>
	</tr>
	<tr>
		<td>分类：</td>
		<td>
			<select id="cat">
			<?php foreach ($post['cat_list'] as $v) { ?>
			<option <?php if($v['id'] == $post['post']['cat_id']){echo 'selected';}?> value="<?=$v['id']?>"><?=$v['name']?></option>
			<?php }?>
			</select>
		</td>
	</tr>
<?php $tag = '';
foreach ($post['tag'] as $v) {
	$tag .= $v['name'] . ' ';
}
?>
	<tr>
		<td>标签：</td>
		<td><input type='text' id='tag' value='<?=$tag?>' /></td>
	</tr>
	<tr>
		<td>密码：</td>
		<td><input type="text" id='passwd' value='<?=$post['post']['passwd']?>'></td>
	</tr>
	<tr>
		<td>图片</td>
		<td><input type="text" id='top_pic' value='<?=$post['post']['top_pic']?>'></td>
	</tr>
	<tr>
		<td colspan='2'><input type="button" onclick='edit_submit(<?=$post['post']['id']?>)' value='确认修改' /></td>
	</tr>
</table>