<table>
	<thead>
		<th>TITLE</th>
		<th>TIME</th>
		<th>CNT</th>
		<th>AU_NAME</th>
		<th>AU_EMAIL</th>
		<th>AU_URL</th>
		<th>PID</th>
		<th>ACT</th>
	</thead>
	<tbody>
		<?php foreach($list as $v){ ?>
		<tr>
			<td><a target='_blank' href='/post/<?=$v['post_id']?>.html#cmt_anchor_<?=$v['id']?>'><?=$v['title']?></a></td>
			<td><?=$v['ctime']?></td>
			<td><?=$v['content']?></td>
			<td><?=$v['author_name']?></td>
			<td><?=$v['author_email']?></td>
			<td><?=$v['author_url']?></td>
			<td><?=$v['pid']?></td>
			<td><a href='javascript:cmt_del(<?=$v['id']?>)'>DEL</a></td>
		<tr>
		<?php } ?>
		<tr><td colspan='8'><?=$pager?></td></tr>
	</tbody>
</table>