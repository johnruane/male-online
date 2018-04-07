<?php
	require_once("../resources/mo.php");
	require_once("../resources/conf.php");
	require_once("../resources/db.php");
?>
<div class="admin-results clearfix">
	<span>New random articles generated</span>
	<?php
		cleanTable('random_articles');
		foreach (getBadWords() as $word) {
			populateRandomArticles($word);
		}
	?>
</div>
