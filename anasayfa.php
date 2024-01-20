<?php include('db_connect.php') ?>
<?php
$twhere = "";
if ($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
<div class="col-12">
	<div class="card">
		<div class="card-body">
			Hoşgeldin
			<?php echo $_SESSION['login_name'] ?>!
		</div>
	</div>
</div>
<hr>
<?php

$where = "";
if ($_SESSION['login_type'] == 2) {
  $where = " where manager_id = '{$_SESSION['login_id']}' ";
} elseif ($_SESSION['login_type'] == 3) {
  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
$where2 = "";
if ($_SESSION['login_type'] == 2) {
  $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
} elseif ($_SESSION['login_type'] == 3) {
  $where2 = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
?>

<div class="row">
	<div class="col-md-8">
		<div class="card card-outline card-success">
			<div class="card-header">
				<b>Proje İlerlemesi</b>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table m-0 table-hover">
						<colgroup>
							<col width="5%">
							<col width="30%">
							<col width="35%">
							<col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
							<th>#</th>
							<th>Proje</th>
							<th>İlerleme</th>
							<th>Durum</th>
							<th></th>
						</thead>
						<tbody>
							<?php
              $i = 1;
              $where = "";
              if($_SESSION['login_type'] == 1){ //! Admin request
                $where = '';
              }
              elseif ($_SESSION['login_type'] == 2) { //! manager Request
                $where = " where manager_id = '{$_SESSION['login_id']}' ";
              } elseif ($_SESSION['login_type'] == 3) { //! Regular User request
                $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
              }
              $qry = $conn->query("SELECT * FROM project_list $where order by name asc");
              //! bu kisim ana sayfaya degil index.php ye tasinmali
              while ($prjrow = $qry->fetch_assoc()):
                $prog = 0;
                $tprog = $conn->query("SELECT * FROM task_list where project_id = {$prjrow['id']}")->num_rows;
                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$prjrow['id']} and status = 3")->num_rows;
                $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                $prog = $prog > 0 ? number_format($prog, 2) : $prog;
                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$prjrow['id']}")->num_rows;
                ?>
							<tr>
								<td>
									<?php echo $i++ ?>
								</td>
								<td>
									<a>
										<?php echo ucwords($prjrow['name']) ?>
									</a>
									<br>
									<small>
										Bitiş:
										<?php 
                        if(is_string($prjrow['end_date']))
                        {
                          echo $prjrow['end_date'];
                        }else 
                        {
                          echo $prjrow['end_date']->format('Y-m-d');
                        }
                      ?>
									</small>
								</td>
								<td class="project_progress">
									<div class="progress progress-sm">
										<div class="progress-bar bg-green" role="progressbar" aria-valuenow="57"
											aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
										</div>
									</div>
									<small>
										<?php echo $prog ?>% Tamamlandı
									</small>
								</td>
								<td class="project-state">
									<?php
                    if ($stat[$prjrow['status']] == 'Beklemede') {
                      echo "<span class='badge badge-secondary'>{$stat[$prjrow['status']]}</span>";
                    } elseif ($stat[$prjrow['status']] == 'Başlatıldı') {
                      echo "<span class='badge badge-primary'>{$stat[$prjrow['status']]}</span>";
                    } elseif ($stat[$prjrow['status']] == 'Tamamlanacak') {
                      echo "<span class='badge badge-info'>{$stat[$prjrow['status']]}</span>";
                    } elseif ($stat[$prjrow['status']] == 'Devam-Ediyor') {
                      echo "<span class='badge badge-warning'>{$stat[$prjrow['status']]}</span>";
                    } elseif ($stat[$prjrow['status']] == 'Zaman Aşımı') {
                      echo "<span class='badge badge-danger'>{$stat[$prjrow['status']]}</span>";
                    } elseif ($stat[$prjrow['status']] == 'Tamamlandı') {
                      echo "<span class='badge badge-success'>{$stat[$prjrow['status']]}</span>";
                    }
                    ?>
								</td>
								<td>
									<a class="btn btn-primary btn-sm"
										href="./index.php?page=view_project&id=<?php echo $prjrow['id'] ?>">
										<i class="fas fa-folder">
										</i>
										Görüntüle
									</a>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="row">
			<div class="col-12 col-sm-6 col-md-12">
				<div class="small-box bg-light shadow-sm border">
					<div class="inner">
						<h3>
							<?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?>
						</h3>

						<p>Toplam Proje</p>
					</div>
					<div class="icon">
						<i class="fa fa-layer-group"></i>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-12">
				<div class="small-box bg-light shadow-sm border">
					<div class="inner">
						<h3>
							<?php echo $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where2")->num_rows; ?>
						</h3>
						<p>Toplam Görev</p>
					</div>
					<div class="icon">
						<i class="fa fa-tasks"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>