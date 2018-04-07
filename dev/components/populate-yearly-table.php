<?php
	require_once("../resources/mo.php");
	require_once("../resources/conf.php");
	require_once("../resources/db.php");
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
			setYearlyTotalsForWordByYear($year, $word, $word_result);
		}
	} ?>
</div>
