<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
?>
<div class="admin-results clearfix">
	<span>CRON job ran</span>
	<?php
		$date = new DateTime(date());
		$date->sub(date_interval_create_from_date_string('1 day'));
		$yesterday = 'http://www.dailymail.co.uk/home/sitemaparchive/day_'.$date->format('Ymd').'.html';

		$articlesWithBadWords = array();

		$dailyLinks = getLinksFromURLAndXpath($yesterday, $news_archive_list);
		// $articlesWithBadWords = searchArticlesForBadWords($dailyLinks, $new_article_list);
		// populateArchiveWithArticles($articlesWithBadWords);
	?>
</div>
