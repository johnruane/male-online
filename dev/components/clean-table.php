<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
?>
<div class="admin-results clearfix">
	<span>Table cleaned: <?php echo $_POST['option'] ?></span>
	<?php cleanTable($_POST['option']) ?>
</div>
