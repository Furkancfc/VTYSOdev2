<?php include 'db_connect.php';
		if(isset($_GET['id'])){
			$qry = $conn->query("SELECT * FROM user_productivity p where p.id=".$_GET['id'])->fetch_array();
			foreach($qry as $k => $v){
				if($k != null && $v != null){
				$$k = $v;
				}
			}
		}
?>

<div class='container-fluid'>
	<b class="border-bottom border-primary">Başlık</b>
	<?php if($subject):?>
	<p>
		<?php echo $subject ?>
	</p>
	<?php else: ?>
	<p> No Subject! </p>
	<?php endif; ?>
	<b class="border-bottom border-primary">Açıklama</b>
	<?php if($comment):?>
	<p> <?php echo $comment ?></p>
	<?php else: ?>
	<p> No Comment! </p>
	<?php endif; ?>
</div>