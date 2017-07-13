<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
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
