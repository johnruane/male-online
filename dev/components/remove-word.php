<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
?>
<div class="admin-results clearfix">

	<span><?php echo $_POST['option'] ?> has been removed</span>
	<?php removeWordFromArchive($_POST['option'])?>
	<?php removeWordFromYearly($_POST['option'])?>
</div>
