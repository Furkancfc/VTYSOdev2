<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<?php if(!isset($_SESSION['login_type']) || $_SESSION['login_type'] == 3 ): ?>
<?php echo "Yetkisiz Erisim" ?>
<?php else: ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-project">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">İsim</label>
							<input type="text" class="form-control form-control-sm" name="name"
								value="<?php echo isset($name) ? $name : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="">Durum</label>
							<select name="status" id="status" class="custom-select custom-select-sm">
								<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>
									Tamamlanacak</option>
								<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Devam
									Ediyor</option>
								<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>
									Tamamlandı</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Başlangıç Tarihi</label>
							<input type="date" class="form-control form-control-sm" autocomplete="off" name="start_date"
								value="<?php echo isset($start_date) ? date("Y-m-d",strtotime($start_date)) : date("Y-m-d",strtotime(date_default_timezone_get())) ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Bitiş Tarihi</label>
							<input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date"
								value="<?php echo isset($end_date) ? date("Y-m-d",strtotime($end_date)) : date("Y-m-d",strtotime(date_default_timezone_get())) ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<?php if($_SESSION['login_type'] == 1 ): ?>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Proje Yöneticisi</label>
							<select class="form-control form-control-sm select2" name="manager_id">
								<option></option>
								<?php 
              	$managers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 2 order by concat(firstname,' ',lastname) asc ");
              	while($prjrow= $managers->fetch_assoc()):
              	?>
								<option value="<?php echo $prjrow['id'] ?>"
									<?php echo isset($manager_id) && $manager_id == $prjrow['id'] ? "selected" : '' ?>>
									<?php echo ucwords($prjrow['name']) ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
					<?php else: ?>
					<input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
					<?php endif; ?>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Proje Üyeleri</label>
							<select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
								<option></option>
								<?php 
              	$employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3 order by concat(firstname,' ',lastname) asc ");
              	while($prjrow= $employees->fetch_assoc()):
              	?>
								<option value="<?php echo $prjrow['id'] ?>"
									<?php echo isset($user_ids) && in_array($prjrow['id'],explode(',',$user_ids)) ? "selected" : '' ?>>
									<?php echo ucwords($prjrow['name']) ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label for="" class="control-label">Açıklama</label>
							<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
						<?php echo isset($description) ? $description : '' ?>
					</textarea>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer border-top border-info">
			<div class="d-flex w-100 justify-content-center align-items-center">
				<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project">Kaydet</button>
				<button class="btn btn-flat bg-gradient-secondary mx-2" type="button"
					onclick="location.href='index.php?page=proje_listesi'">Reddet</button>
			</div>
		</div>
	</div>
</div>
<script>
$('#manage-project').submit(function(e) {
	e.preventDefault()
	start_load()
	$.ajax({
		url: 'ajax.php?action=save_project',
		data: new FormData($(this)[0]),
		cache: false,
		contentType: false,
		processData: false,
		method: 'POST',
		type: 'POST',
		success: function(resp) {
			if (resp == 1) {
				alert_toast('Data successfully saved', "success");
				setTimeout(function() {
					location.href =
						'index.php?page=proje_listesi'
				}, 1500)
			}
			else{
				alert_toast('Failure',"error");
				
			}
		}

	})
})
</script>
<?php endif; ?>