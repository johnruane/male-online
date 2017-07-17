<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
?>
<div class="admin-results clearfix">
	<span>Table populated for:
		<?php foreach ($_POST['options'] as $year) { ?>
			<?php echo $year ?>
		<?php } ?>
	</span>
	<?php foreach ($_POST['options'] as $year) {
		// Get a list of daily article headlines from daily url
		$dailyLinks = getDailyArchiveLinks($mo_archive_url.$year.'.html');
		$articleList = getListOfArticleLinks($dailyLinks);
		var_dump($articleList);
		// populateArchiveWithArticles($articleList);
	 } ?>
