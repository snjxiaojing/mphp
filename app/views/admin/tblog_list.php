<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>CNT</th>
			<th>CAT</th>
			<th>TIME</th>
			<th>ACTION</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $v){ ?>
		<tr>
			<td><?=$v['id']?></td>
			<td><?=$v['cnt']?></td>
			<td><?=$v['cname']?></td>
			<td><?=$v['ctime']?></td>
			<td>
				<a href='javascript:tblog_del(<?=$v['id']?>)'>删除</a>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan='7'><?=$pager?></td>
		</tr>
	</tbody>
</table>