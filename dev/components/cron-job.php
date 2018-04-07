<?php
	require_once("../resources/mo.php");
	require_once("../resources/conf.php");
	require_once("../resources/db.php");
?>
<div class="admin-results clearfix">
	<span>CRON job ran</span>
	<?php
		$date = new DateTime();
		$date->sub(new DateInterval('P1D'));
		$year = $date->format('Y');

		$yesterday = 'http://www.dailymail.co.uk/home/sitemaparchive/day_'.$date->format('Ymd').'.html';
		$articlesWithBadWords = array();

		$articlesWithBadWords = searchArticlesForBadWords([$yesterday], $archive_by_day);
		populateArchiveWithArticles($articlesWithBadWords);

		cleanTable('random_articles');
		cleanTableByYear('yearly_count', $year);
		foreach (getBadWords() as $word) {
			$word_result = getCurrentCountsForYearByWord($year, $word);
			setYearlyTotalsForWordByYear($year, $word, $word_result);
			populateRandomArticles($word);
		}
	?>
</div>
