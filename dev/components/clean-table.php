<?php
	require_once("../resources/mo.php");
	require_once("../resources/conf.php");
	require_once("../resources/db.php");
?>
<div class="admin-results clearfix">
	<span>Table cleaned: <?php echo $_POST['option'] ?></span>
	<?php cleanTable($_POST['option']) ?>
</div>
