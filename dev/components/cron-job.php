<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
?>
<div class="admin-results clearfix">
	<span>CRON job ran</span>
	<?php
		$date = new DateTime();
		$date->sub(new DateInterval('P1D'));

		$yesterday = 'http://www.dailymail.co.uk/home/sitemaparchive/day_'.$date->format('Ymd').'.html';
		$articlesWithBadWords = array();

		$articlesWithBadWords = searchArticlesForBadWords([$yesterday], $archive_by_day);
		populateArchiveWithArticles($articlesWithBadWords);
	?>
</div>
