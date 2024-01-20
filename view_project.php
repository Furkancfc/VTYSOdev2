<?php
include 'db_connect.php';
$stat = array("Beklemede", "Başlatıldı", "Tamamlanacak", "Devam Ediyor", "Zaman Aşımı", "Tamamlandı");
$qry = $conn->query("SELECT * FROM project_list where id = " . $_GET['id'])->fetch_array();
foreach ($qry as $k => $v) {
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
$prog = $prog > 0 ? number_format($prog, 2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;
$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Proje Adı</b></dt>
								<dd>
									<?php echo ucwords($name) ?>
								</dd>
								<dt><b class="border-bottom border-primary">Açıklama</b></dt>
								<dd>
									<?php echo html_entity_decode($description) ?>
								</dd>
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Başlangıç Tarihi</b></dt>
								<dd>
									<?php echo date("F d, Y", strtotime($start_date)) ?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Bitiş Tarihi</b></dt>
								<dd>
									<?php echo date("F d, Y", strtotime($end_date)) ?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Proje Durumu</b></dt>
								<dd>
									<?php
									if ($stat[$status] == 'Beklemede') {
										echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Başlatıldı') {
										echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Tamamlanacak') {
										echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Devam Ediyor') {
										echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Zaman Aşımı') {
										echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Tamamlandı') {
										echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									}
									?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Proje Yöneticisi</b></dt>
								<dd>
									<?php if (isset($manager['id'])): ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3"
											src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b>
											<?php echo ucwords($manager['name']) ?>
										</b>
									</div>
									<?php else: ?>
									<small><i>Yönetici Veritabanından silindi veya eklenmedi.</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Takım Üyeleri:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php
						if (!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while ($prjrow = $members->fetch_assoc()):
								?>
						<li>
							<img src="assets/uploads/<?php echo $prjrow['avatar'] ?>" alt="User Image">
							<a class="users-list-name" href="javascript:void(0)">
								<?php echo ucwords($prjrow['name']) ?>
							</a>
							<!-- <span class="users-list-date">Today</span> -->
						</li>
						<?php
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Görev Listesi:</b></span>
					<?php if ($_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i
								class="fa fa-plus"></i> Yeni Görev</button>
					</div>
					<?php endif; ?>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-condensed m-0 table-hover">
							<colgroup>
								<col width="5%">
								<col width="25%">
								<col width="30%">
								<col width="15%">
								<col width="15%">
							</colgroup>
							<thead>
								<th>#</th>
								<th>Görev</th>
								<th>Açıklama</th>
								<th>Görev Durumu</th>
								<th>Eylem</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$tasks = $conn->query("SELECT * FROM task_list where project_id = {$id} order by task asc");
								while ($prjrow = $tasks->fetch_assoc()):
									$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
									unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
									$desc = strtr(html_entity_decode($prjrow['description']), $trans);
									$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
									?>
								<tr>
									<td class="text-center">
										<?php echo $i++ ?>
									</td>
									<td class=""><b>
											<?php echo ucwords($prjrow['task']) ?>
										</b></td>
									<td class="">
										<p class="truncate">
											<?php echo strip_tags($desc) ?>
										</p>
									</td>
									<td>
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
											<a class="dropdown-item view_task" href="javascript:void(0)"
												data-id="<?php echo $prjrow['id'] ?>"
												data-task="<?php echo $prjrow['task'] ?>">Görüntüle</a>
											<?php if ($_SESSION['login_type'] != 3): ?>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item edit_task" href="javascript:void(0)"
												data-id="<?php echo $prjrow['id'] ?>"
												data-task="<?php echo $prjrow['task'] ?>">Düzenle</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_task" href="javascript:void(0)"
												data-id="<?php echo $prjrow['id'] ?>">Sil</a>
											<?php endif; ?>
										</div>
									</td>
								</tr>
								<?php
								endwhile;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>Üye Aktivitesi</b>
					<?php if(isset($_SESSION['login_type']) && $_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button"
							id="new_productivity"><i class="fa fa-plus"></i> Yeni Aktivite</button>
					</div>
					<?php endif ?>
				</div>
				<table class='table'>
					<tr class='text-center'>
						<th class='text-center'>#</th>
						<th>Görev Adı</th>
						<th>Kullanıcı Numarası</th>
						<th>Kullanıcı Adı</th>
						<th>Aktivite Başlığı</th>
						<th>Aktivite Detayı</th>
					</tr>
					<?php
					$i = 1;
					$progress = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.task FROM user_productivity p inner join users u on u.id = p.user_id inner join task_list t on t.id = p.task_id where p.project_id = $id order by unix_timestamp(p.date_created) desc ");
					while ($prjrow = $progress->fetch_assoc()):
						?>
					<tr class='text-center'>
						<?php if ($_SESSION['login_id'] == $prjrow['user_id']): ?>
						<?php endif; ?>
						<td class='text-center'>
							<?php echo $i ?>
						<td class='taskname'>
							<span>
								<?php echo ucwords($prjrow['task']) ?>
							</span>

						<td>
							<?php echo ucwords($prjrow['user_id']) ?>
						</td>
						<td class='user-in-table clearfix'>
							<img src="assets/uploads/<?php echo $prjrow['avatar'] ?>" />
							<?php echo ucwords($prjrow['uname']) ?>
						</td>
						<td>
							<?php echo ucwords($prjrow['subject']) ?>
						</td>
						<td class='text-center'>
							<a class='btn btn-primary view-details' href="javascript:void(0)"
								data-task='<?php echo $prjrow['task_id'] ?>' data-id='<?php echo $prjrow['id']?>'>Detayları
								Göster</a>
						</td>
					</tr>
					<?php $i++ ?>
					<?php endwhile; ?>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
.user-in-table img {
	border-radius: 25%;
	height: 45px;
	width: 45px;
	object-fit: cover;
}

.user-in-table li {}

.users-list>li img {
	border-radius: 50%;
	height: 67px;
	width: 67px;
	object-fit: cover;
}

.users-list>li {
	width: 33.33% !important
}

.truncate {
	-webkit-line-clamp: 1 !important;
}
</style>
<script>
$('.view-details').click(function() {
	console.log(this);
	uni_modal("Detaylar", "view_activity.php?id=" + $(this).attr('data-id'), "mid-large");
})
$('#new_task').click(function() {
	uni_modal("<?php echo ucwords($name) ?> için yeni görev", "manage_task.php?pid=<?php echo $id ?>",
		"mid-large")
})
$('.edit_task').click(function() {
	console.log("manage_progress");
	uni_modal("Görev Düzenleme: " + $(this).attr('data-task'), "manage_task.php?pid=<?php echo $id ?>&id=" + $(
		this).attr('data-id'), "mid-large")
})
$('.view_task').click(function() {
	uni_modal("Görev Detayları", "view_task.php?id=" + $(this).attr('data-id'), "mid-large")
})
$('.delete_task').click(function() {
	_conf("Are you sure to delete this task?", "delete_task", [$(this).attr('data-id')])
})
$('#new_productivity').click(function() {
	uni_modal("<i class='fa fa-plus'></i> New Progress", "manage_progress.php?pid=<?php echo $id ?>", 'large')
})
$('.manage_progress').click(function() {
	uni_modal("<i class='fa fa-edit'></i> Edit Progress", "manage_progress.php?pid=<?php echo $id ?>&id=" + $(
		this).attr('data-id'), 'large')
})
$('.delete_progress').click(function() {
	_conf("Are you sure to delete this progress?", "delete_progress", [$(this).attr('data-id')])
})

function delete_progress($id) {
	start_load()
	$.ajax({
		url: 'ajax.php?action=delete_progress',
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

function delete_task($id) {
	start_load()
	$.ajax({
		url: 'ajax.php?action=delete_task',
		method: 'POST',
		data: {
			id: $id
		},
		success: function(resp) {
			if (resp == 1) {
				alert_toast("Data successfully deleted", 'success')
				setTimeout(function() {
					location.reload()
				}, 1500);
			}
		}
	})
}
</script>