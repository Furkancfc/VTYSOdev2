<?php if(isset($_SESSION['login_type']) && $_SESSION['login_type'] != 1):
	echo "Yetkisiz Erisim";
?>
<?php else: ?>

<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_user"><i
						class="fa fa-plus"></i> Yeni Kullanıcı Ekle</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>İsim</th>
						<th>Email</th>
						<th>Rol</th>
						<th>Eylem</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$type = array('',"Admin","Project Manager","Employee");
					$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users order by concat(firstname,' ',lastname) asc");
					while($prjrow= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($prjrow['name']) ?></b></td>
						<td><b><?php echo $prjrow['email'] ?></b></td>
						<td><b><?php echo $type[$prjrow['type']] ?></b></td>
						<td class="text-center">
							<button type="button"
								class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
								data-toggle="dropdown" aria-expanded="true">
								Eylem
							</button>
							<div class="dropdown-menu" style="">
								<a class="dropdown-item view_user" href="javascript:void(0)"
									data-id="<?php echo $prjrow['id'] ?>">Görüntüle</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item"
									href="./index.php?page=edit_user&id=<?php echo $prjrow['id'] ?>">Düzenle</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_user" href="javascript:void(0)"
									data-id="<?php echo $prjrow['id'] ?>">Sil</a>
							</div>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$('#list').dataTable()
	$('.view_user').click(function() {
		uni_modal("<i class='fa fa-id-card'></i> User Details", "view_user.php?id=" + $(this).attr(
			'data-id'))
	})
	$('.delete_user').click(function() {
		_conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')])
	})
})

function delete_user($id) {
	start_load()
	$.ajax({
		url: 'ajax.php?action=delete_user',
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
<?php endif; ?>