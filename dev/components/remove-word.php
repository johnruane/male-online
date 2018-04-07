<?php
	require_once("../resources/mo.php");
	require_once("../resources/conf.php");
	require_once("../resources/db.php");
?>
<div class="admin-results clearfix">

	<span><?php echo $_POST['option'] ?> has been removed</span>
	<?php removeWordFromArchive($_POST['option'])?>
	<?php removeWordFromYearly($_POST['option'])?>
</div>
