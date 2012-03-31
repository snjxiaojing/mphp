<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>TITLE</th>
			<th>CMT_COUNT</th>
			<th>VIEW_COUNT</th>
			<th>PASSWD</th>
			<th>CAT</th>
			<th>ACTION</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $v){ ?>
		<tr>
			<td><?=$v['id']?></td>
			<td>
				<a href='/post/<?=$v['id']?>.html' target='_blank'><?=$v['title']?></a>
			</td>
			<td><?=$v['cmt_count']?></td>
			<td><?=$v['view_count']?></td>
			<td><?=$v['passwd']?></td>
			<td><?=$v['cname']?></td>
			<td>
				<a href='/admin/post_edit/<?=$v['id']?>' target='_blank'>编辑</a>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan='7'><?=$pager?></td>
		</tr>
	</tbody>
</table>