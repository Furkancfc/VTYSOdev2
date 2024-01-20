<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<?php if($_SESSION['login_type'] != 3): ?>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary"
					href="./index.php?page=new_project"><i class="fa fa-plus"></i> Yeni Proje Ekle</a>
			</div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Proje</th>
						<th>Proje Başlangıç Tarihi</th>
						<th>Proje Bitiş Tarihi</th>
						<th>Projenin Durumu</th>
						<th>Gün Erteleme Sayısı</th>
						<th>Eylem</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if($_SESSION['login_type'] == 3){	//! regular user request 
						$where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
						$qry = $conn->query("select * from project_list p  $where");
					}
					if($_SESSION['login_type'] == 2){	//! manager request 
						$where = " where manager_id = '{$_SESSION['login_id']}' ";
						$qry = $conn->query("SELECT * FROM project_list $where order by name asc");
					}if($_SESSION['login_type'] == 1){	//! admin request
						// $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
						$qry = $conn->query("SELECT * FROM project_list order by name asc");
					}
					while($prjrow= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($prjrow['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td>
							<p><b><?php echo ucwords($prjrow['name']) ?></b></p>
							<p class="truncate"><?php echo strip_tags($desc) ?></p>
						</td>
						<td><b><?php echo date("M d, Y",strtotime($prjrow['start_date'])) ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($prjrow['end_date'])) ?></b></td>
						<td class="text-center">
							<?php
							  if($stat[$prjrow['status']] =='Beklemede'){
							  	echo "<span class='badge badge-secondary'>{$stat[$prjrow['status']]}</span>";
							  }elseif($stat[$prjrow['status']] =='Başlatıldı'){
							  	echo "<span class='badge badge-primary'>{$stat[$prjrow['status']]}</span>";
							  }elseif($stat[$prjrow['status']] =='Tamamlanacak'){
							  	echo "<span class='badge badge-info'>{$stat[$prjrow['status']]}</span>";
							  }elseif($stat[$prjrow['status']] =='Devam-Ediyor'){
							  	echo "<span class='badge badge-warning'>{$stat[$prjrow['status']]}</span>";
							  }elseif($stat[$prjrow['status']] =='Zaman Aşımı'){
							  	echo "<span class='badge badge-danger'>{$stat[$prjrow['status']]}</span>";
							  }elseif($stat[$prjrow['status']] =='Tamamlandı'){
							  	echo "<span class='badge badge-success'>{$stat[$prjrow['status']]}</span>";
							  }
							?>
						</td>
						<td class='text-center'>
							<?php echo $prjrow['delay_time_days']; ?>
						</td>
						<td class="text-center">
							<button type="button"
								class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
								data-toggle="dropdown" aria-expanded="true">
								Eylem
							</button>
							<div class="dropdown-menu" style="">
								<a class="dropdown-item view_project"
									href="./index.php?page=view_project&id=<?php echo $prjrow['id'] ?>"
									data-id="<?php echo $prjrow['id'] ?>">Görüntüle</a>
								<div class="dropdown-divider"></div>
								<?php if($_SESSION['login_type'] != 3): ?>
								<a class="dropdown-item"
									href="./index.php?page=edit_project&id=<?php echo $prjrow['id'] ?>">Düzenle</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_project" href="javascript:void(0)"
									data-id="<?php echo $prjrow['id'] ?>">Sil</a>
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
table thead tr th{
	text-align: center;
}
table tbody tr td{
	text-align: center;
}
</style>
<script>
$(document).ready(function() {
	$('#list').dataTable()

	$('.delete_project').click(function() {
		_conf("Are you sure to delete this project?", "delete_project", [$(this).attr('data-id')])
	})
})

function delete_project($id) {
	start_load()
	$.ajax({
		url: 'ajax.php?action=delete_project',
		method: 'POST',
		data: {
			id: $id
		},
		success: function(resp) {
			if (resp == 1) {
				alert_toast("Data successfully deleted", 'success')
				setTimeout(function() {
					location.reload()
				}, 1500)

			}
		}
	})
}
</script>