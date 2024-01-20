<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM task_list t where t.id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v ) {
		$$k = $v;
	}
	if(isset($task_member))
	$qry2 = $conn->query("SELECT concat(firstname,' ',lastname) as name FROM users where id = " . $task_member);
	$prjrow= $qry2->fetch_assoc();
}
?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Görev</b></dt>
		<dd>
			<?php echo ucwords($task) ?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Görev</b></dt>
		<dd>
			<?php echo ucwords(number_format($task_member_day, 2, ',', ' ')) ?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Durum</b></dt>
		<dd>
			<?php
			if ($status == 1) {
				echo "<span class='badge badge-secondary'>Tamamlanacak</span>";
			} elseif ($status == 2) {
				echo "<span class='badge badge-primary'>Devam Ediyor</span>";
			} elseif ($status == 3) {
				echo "<span class='badge badge-success'>Tamamlandı</span>";
			}
			?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Açıklama</b></dt>
		<dd>
			<?php echo html_entity_decode($description) ?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Görev Sorumlusu</b></dt>
		<dd>
			<?php echo html_entity_decode($prjrow["name"]) ?>
		</dd>
	</dl>
	<dl>
		<dt><b class='border-bottom border-primary'>Aktivite Günlüğü</b></dt>
		<?php $query3 = $conn->query("select *,concat(t.id) as tid, concat(p.id) as pid from user_productivity p join task_list t on t.id = p.task_id where task_id = $id"); 
			$prjrow = $query3;
		?>
		<!-- create table about activity -->
		<table class='table tabe-hover table-condensed dataTable no-footer'>
			<thead>
				<th class='text-center sorting_asc ' aria-controls='list'> Subject </th>
				<th class='text-center sorting' aria-controls='list'> Comment </th>
				<th class='text-center sorting' aria-controls='list'> Start time </th>
				<th class='text-center sorting' aria-controls='list'> End Time </th>
				<th class='text-center sorting' aria-controls='list'> Creating Time </th>
			</thead>
			<tbody>
				<?php while($prjrow = $query3->fetch_assoc()): ?>
				<tr>
					<td>
						<?php echo $prjrow['subject'] ?>
					</td>
					<td>
						<?php echo $prjrow['comment'] ?>
					</td>
					<td>
						<?php echo $prjrow['start_time'] ?>
					</td>
					<td>
						<?php echo $prjrow['end_time'] ?>
					</td>
					<td>
						<?php echo $prjrow['date_created'] ?>
					</td>
				</tr>
			</tbody>
			<?php endwhile;?>
		</table>
	</dl>
</div>