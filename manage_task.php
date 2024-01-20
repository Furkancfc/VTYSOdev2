<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM task_list where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Görev</label>
			<input type="text" class="form-control form-control-sm" name="task"
				value="<?php echo isset($task) ? $task : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Adam-Gün Değeri</label>
			<input type="text" class="form-control form-control-sm" name="task_member_day"
				value="<?php echo isset($task_member_day) ? number_format($task_member_day, 2, ',', ' ') : '' ?>"
				required>
		</div>
		<div class="form-group">
			<label for="">Açıklama</label>
			<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
				<?php echo isset($description) ? $description : '' ?>
			</textarea>
		</div>
		<div class="form-group">
			<label for="">Durum</label>
			<select name="status" id="status" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Tamamlanacak</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Devam Ediyor</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Tamamlandı</option>
			</select>
		</div>
		<div class="form-group">
			<label for="">Görev Üyesi</label>
			<select class="custom-select custom-select-sm" name="task_member">
				<?php
				$task_member = "";
				if (isset($id)) {
					$query = $conn->query("SELECT task_member as task_member FROM task_list where id= " . $id);
					$result = $query->fetch_assoc();
					$task_member = $result["task_member"];
				}

				$query2 = $conn->query("SELECT user_ids FROM project_list where id= " . $_GET['pid']);
				$result2 = $query2->fetch_assoc();
				$user_ids = $result2["user_ids"];

				$employees = $conn->query("SELECT *,concat(firstname,' ', lastname) as name FROM users where id in ($user_ids)");
				while ($prjrow = $employees->fetch_assoc()):
					?>
				<option value="<?php echo $prjrow['id'] ?>"
					<?php echo isset($user_ids) && $prjrow["id"] == $task_member ? "selected" : '' ?>>
					<?php echo ucwords($prjrow['name']) ?>
				</option>
				<?php endwhile; ?>
			</select>
		</div>
	</form>
</div>

<script>
$(document).ready(function() {


	$('.summernote').summernote({
		height: 200,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript',
				'clear'
			]],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ol', 'ul', 'paragraph', 'height']],
			['table', ['table']],
			['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
		]
	})
})

$('#manage-task').submit(function(e) {
	e.preventDefault()
	start_load()
	$.ajax({
		url: 'ajax.php?action=save_task',
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
					location.reload()
				}, 1500)
			}
		}
	})
})
</script>