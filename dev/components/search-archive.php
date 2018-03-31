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
		$articlesWithBadWords = array();

		// Get a list of daily article headlines from daily url
		$dailyLinks = getLinksFromURLAndXpath('http://www.dailymail.co.uk/home/sitemaparchive/year_'.$year.'.html', $archive_by_year);
		$articlesWithBadWords = searchArticlesForBadWords($dailyLinks, $archive_by_day);
		populateArchiveWithArticles($articlesWithBadWords);
	 } ?>
</div>
