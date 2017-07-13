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
		foreach (getBadWords() as $word) {
			$word_result = getCurrentCountsForYearByWord($year, $word);
			setYearlyTotalsByYear($year, $word, $word_result[0]['total']);
		}
	} ?>
</div>
