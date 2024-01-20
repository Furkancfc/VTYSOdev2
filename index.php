<!DOCTYPE html>
<html lang="en">
	<?php 
	include 'header.php';
	include 'db_connect.php';
	session_start(); 
	if(!isset($_SESSION['login_id']))
	header('location:login.php');
ob_start();
if(!isset($_SESSION['system'])){
	
	$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
    foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
    }
  }
  ob_end_flush();
  $stat = array("Beklemede","Başlatıldı","Tamamlanacak","Devam-Ediyor","Zaman Aşımı","Tamamlandı");
	$qry = $conn->query('SELECT * FROM project_list');
	while(($prjrow = $qry->fetch_assoc()) != null)
	{
		$task = ($tmp = $conn->query("SELECT * FROM task_list t where t.project_id={$prjrow['id']}")) != false ? $tmp->num_rows:0;
		$comtask = $conn->query("SELECT * FROM task_list where project_id = {$prjrow['id']} and status = 3")->num_rows;
		$currprogr = $task > 0 ? ($comtask / $task) * 100 : 0;
		$currprogr = $currprogr > 0 ? number_format($currprogr,2) : $currprogr;
		if(strtotime(date('Y-m-d')) < strtotime($prjrow['start_date'])){	//? baslama tarihi gelmedi ise
			if($prjrow['status'] != 5){
			$prjrow['status'] = 0;											//? beklemeye al
			}
		}
		else if(strtotime(date('Y-m-d')) < strtotime($prjrow['end_date']) && strtotime(date('Y-m-d')) >= strtotime($prjrow['start_date'])){	//? baslama tarihi geldi ama bitis tarihi gelmedi
			if($prjrow['status'] == 0 ){		//? beklemede ise
				$prjrow['status'] = 1;			//? baslatildi yap
			}
			else if($prjrow['status'] == 1){
				if($currprogr = 100){
					$prjrow['status'] = 2;
				}
				else if($currprogr > 0){
					$prjrow['status'] = 3;
				}
				else if($currprogr == 0){
					;
				}
			}
			else if($prjrow['status'] == 2){
				if($currprogr != 100){
					$prjrow['status'] = 3;
				}
			}
			else if($prjrow['status'] == 3){		//? devam ediyor ise
				if($currprogr == 100){			//? tamamlandi ise	
					$prjrow['status'] = 2;		//? durumu tamamlanacak yap ( manager tamamladi olarak duzenler )
				}
				else if($currprogr > 0){			//? ilerleme var ama tamamlanmadi ise
					$prjrow['status'] = 3;			//? devam ediyor durumu yapiliir
				}
				else if($currprogr == 0){								//? ilerleme yok ise bir sey yapma
					;
				}
			}
		}
		else if(strtotime(date('Y-m-d')) >= strtotime($prjrow['end_date'])){ //? projenin zamani asti
			if($prjrow['status'] != 5):
			if($currprogr == 100 ){					//? tamamlandi ise
				$prjrow['status'] = 5;	
			}
			else{									

				$date = new DateTime($prjrow['end_date']);
				$date = $date->add(new DateInterval("P6D"))->format('Y-m-d');
				$prjrow['delay_time_days'] = $prjrow['delay_time_days'] + 6;
				$prjrow['end_date'] = $date;
				$prjrow['status'] = 2;					//? tamamlanacak durumu yapilir
			}
		endif;
		}
		$conn->query("UPDATE project_list p SET p.status={$prjrow['status']}, p.end_date='{$prjrow['end_date']}', p.delay_time_days = {$prjrow['delay_time_days']} where id='{$prjrow['id']}'");
	}
?>

	<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
		<div class="wrapper">
			<?php include 'topbar.php' ?>
			<?php include 'sidebar.php' ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="toast-body text-white">
					</div>
				</div>
				<div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1 class="m-0"><?php echo $title ?></h1>
							</div><!-- /.col -->

						</div><!-- /.row -->
						<hr class="border-primary">
					</div><!-- /.container-fluid -->
				</div>
				<!-- /.content-header -->

				<!-- Main content -->
				<section class="content">
					<div class="container-fluid">
						<!-- layout kismi -->
						<?php 
            $page = isset($_GET['page']) ? $_GET['page'] : 'anasayfa';
            if(!file_exists($page.".php")){
                include '404.html';
            }else{
            include $page.'.php';

            }
          ?>
					</div>
					<!--/. container-fluid -->
				</section>
				<!-- /.content -->
				<div class="modal fade" id="confirm_modal" role='dialog'>
					<div class="modal-dialog modal-md" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Onayla</h5>
							</div>
							<div class="modal-body">
								<div id="delete_content"></div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" id='confirm' onclick="">Devam Et</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal"
									id='disconfirm'>Kapat</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="uni_modal" role='dialog'>
					<div class="modal-dialog modal-md" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"></h5>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" id='submit'
									onclick="$('#uni_modal form').submit()">Kaydet</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal"
									id='reddet'>Reddet</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="uni_modal_right" role='dialog'>
					<div class="modal-dialog modal-full-height  modal-md" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span class="fa fa-arrow-right"></span>
								</button>
							</div>
							<div class="modal-body">
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="viewer_modal" role='dialog'>
					<div class="modal-dialog modal-md" role="document">
						<div class="modal-content">
							<button type="button" class="btn-close" data-dismiss="modal"><span
									class="fa fa-times"></span></button>
							<img src="" alt="">
						</div>
					</div>
				</div>
			</div>
			<!-- /.content-wrapper -->

			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
				<!-- Control sidebar content goes here -->
			</aside>
			<!-- /.control-sidebar -->

			<!-- Main Footer -->
			<footer class="main-footer">
				<div class="float-right d-none d-sm-inline-block">
					<b><?php echo $_SESSION['system']['name'] ?></b>
				</div>
			</footer>
		</div>
		<!-- ./wrapper -->

		<!-- REQUIRED SCRIPTS -->
		<!-- jQuery -->
		<!-- Bootstrap -->
		<?php include 'footer.php' ?>
	</body>

</html>

<script>
$(document).keydown(function(e) {
	var keycode = e.keyCode;
	if (keycode == 27) {
		$('#disconfirm').trigger('click');
		$('#reddet').trigger('click');
	}
	if (keycode == 13) {
		$('#confirm').trigger('click');
		$('#submit').trigger('click');
	}
})
</script>