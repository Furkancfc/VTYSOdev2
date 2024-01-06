<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
	$qry2 = $conn->query("SELECT concat(firstname,' ',lastname) as name FROM users where id = ".$task_member)->fetch_array();
	// $row= $qry2->fetch_assoc();
}
?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Görev</b></dt>
		<dd><?php echo ucwords($task) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Durum</b></dt>
		<dd>
			<?php 
        	if($status == 1){
		  		echo "<span class='badge badge-secondary'>Pending</span>";
        	}elseif($status == 2){
		  		echo "<span class='badge badge-primary'>On-Progress</span>";
        	}elseif($status == 3){
		  		echo "<span class='badge badge-success'>Done</span>";
        	}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Açıklama</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Görev Sorumlusu</b></dt>
		<dd><?php echo html_entity_decode($qry2["name"]) ?></dd>
	</dl>
</div>