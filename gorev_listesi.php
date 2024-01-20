<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Proje</th>
						<th>Görev</th>
						<th>Başlangıç Tarihi</th>
						<th>Bitiş Tarihi</th>
						<th>Proje Durumu</th>
						<th>Görev Durumu</th>
						<th>Eylem</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if($_SESSION['login_type'] == 3){
						$where = " where t.task_member='{$_SESSION['login_id']}' ";
					}
					elseif ($_SESSION['login_type'] == 2) {
						$where = " where p.manager_id='{$_SESSION['login_id']}' ";
					} elseif ($_SESSION['login_type'] == 1) {
						$where = "";
					}
					$qry = $conn->query("SELECT t.*,p.manager_id,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
					while ($prjrow = $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($prjrow['description']), $trans);
						$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
						?>
					<tr>
						<td class="text-center">
							<?php echo $i++ ?>
						</td>
						<td>
							<p><b>
									<?php echo ucwords($prjrow['pname']) ?>
								</b></p>
						</td>
						<td>
							<p><b>
									<?php echo ucwords($prjrow['task']) ?>
								</b></p>
							<p class="truncate">
								<?php echo strip_tags($desc) ?>
							</p>
						</td>
						<td><b>
								<?php echo date("M d, Y", strtotime($prjrow['start_date'])) ?>
							</b></td> <!-- start date -->
						<td><b>
								<?php echo date("M d, Y", strtotime($prjrow['end_date'])) ?>
							</b></td> <!-- end date -->
						<td class="text-center">
							<!-- project status -->
							<?php
								if ($stat[$prjrow['pstatus']] == 'Beklemede') {
									echo "<span class='badge badge-secondary'>{$stat[$prjrow['pstatus']]}</span>";
								} elseif ($stat[$prjrow['pstatus']] == 'Başlatıldı') {
									echo "<span class='badge badge-primary'>{$stat[$prjrow['pstatus']]}</span>";
								} elseif ($stat[$prjrow['pstatus']] == 'Tamamlanacak') {
									echo "<span class='badge badge-info'>{$stat[$prjrow['pstatus']]}</span>";
								} elseif ($stat[$prjrow['pstatus']] == 'Devam Ediyor') {
									echo "<span class='badge badge-warning'>{$stat[$prjrow['pstatus']]}</span>";
								} elseif ($stat[$prjrow['pstatus']] == 'Zaman Aşımı') {
									echo "<span class='badge badge-danger'>{$stat[$prjrow['pstatus']]}</span>";
								} elseif ($stat[$prjrow['pstatus']] == 'Tamamlandı') {
									echo "<span class='badge badge-success'>{$stat[$prjrow['pstatus']]}</span>";
								}
								?>
						</td>
						<td>
							<!-- task status -->
							<?php
								if ($prjrow['status'] == 1) {
									echo "<span class='badge badge-secondary'>Tamamlanacak</span>";
								} elseif ($prjrow['status'] == 2) {
									echo "<span class='badge badge-primary'>Devam Ediyor</span>";
								} elseif ($prjrow['status'] == 3) {
									echo "<span class='badge badge-success'>Tamamlandı</span>";
								}
								?>
						</td>
						<td class="text-center">
							<button type="button"
								class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
								data-toggle="dropdown" aria-expanded="true">
								Eylem
							</button>
							<div class="dropdown-menu" style="">
								<a class="dropdown-item new_productivity" data-pid='<?php echo $prjrow['pid'] ?>'
									data-id='<?php echo $prjrow['id'] ?>' data-task='<?php echo ucwords($prjrow['task']) ?>'
									href="javascript:void(0)">Yeni Aktivite
								</a>
								<a class="dropdown-item view_task" data-id='<?php echo $prjrow['id']?>'
									data-task='<?php echo $prjrow['task']?>' href="javascript:void(0)"> Görüntüle </a>
								<?php if(isset($_SESSION['login_type']) && $_SESSION['login_type'] != 3):?> <a
									class='dropdown-item edit_task' data-pid='<?php echo $prjrow['pid']?>'
									data-id='<?php echo $prjrow['id']?>' data-task='<?php echo ucwords($prjrow['task'])?>'
									href="javascript:void(0)"> Düzenle
								</a>
								<a class='dropdown-item delete_task' data-id='<?php echo $prjrow['id']?>'
									data-task='<?php echo ucwords($prjrow['task'])?>' href="javascript:void(0)"> Sil </a>
								<?php endif; ?>
							</div>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
table p {
	margin: unset !important;
}

table td {
	vertical-align: middle !important
}
</style>
<script>
$(document).ready(function() {
	$('#list').dataTable();
	$('.new_productivity').click(function() {
		uni_modal("<i class='fa fa-plus'></i> " + $(this).attr('data-task') + " için yeni İlerleme",
			"manage_progress.php?pid=" + $(this).attr('data-pid') + "&tid=" + $(this).attr(
				'data-id'), 'large')
	});
	$('.edit_task').click(function() {
		uni_modal("Görev Düzenleme: " + $(this).attr('data-task'), "manage_task.php?pid=" + $(this)
			.attr('data-pid') + "&id=" + $(
				this).attr('data-id'), "mid-large")
	});
	$('.delete_task').click(function() {
		_conf("Are you sure to delete this task", "delete_task", [$(this).attr('data-id')])
	});
	$('.view_task').click(function() {
		uni_modal("Görev Detayları", "view_task.php?id=" + $(this).attr('data-id'), "mid-large")
	});
})


function delete_task($id) {
	start_load()
	$.ajax({
		url: 'ajax.php?action=delete_task',
		method: 'POST',
		data: {
			id: $id,
		},
		success: function(resp) {
			if (resp == 1) {
				alert_toast("Data successfully deleted", 'success')
				setTimeout(function() {
					location.reload();
				}, 1500);
			}
		},


	})

}
</script>