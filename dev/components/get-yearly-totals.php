<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
?>
<div class="admin-results clearfix">
	<span>Results for <?php echo $_POST['input'] ?></span>
	<ul class="reset-list inline">
		<?php foreach (getWordCount($_POST['input']) as $row) { ?>
			<li><?php echo $row['year'] ?>:<?php echo $row['count'] ?>&nbsp;|&nbsp;</li>
		<?php } ?>
	</ul>
</div>
