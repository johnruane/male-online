<?php
    require_once("resources/mo.php");
    require_once("resources/conf.php");
    require_once("resources/db.php");
    $date = new DateTime();
    $date->sub(new DateInterval('P1D'));
    $year = $date->format('Y');
?>
<?php $wordResults = getWordCount($word); ?>
<?php if ($wordResults != null && count($wordResults) > 1) { ?>
        <div id="<?php echo $word ?>-chart" data-bind="word-chart" data-highlighter="<?php echo $word ?>" class="word-chart clearfix">
			<div class="item-card-heading">
                <h2 class="word-key"><?php echo $word ?></h2>
            </div>
			<div class="trends-wrapper">
				<div class="trends-graph-wrapper">
		            <ul class="hidden-word-results">
                        <?php $trendString = ""; ?>
		                <?php foreach ($wordResults as $row):
                            if ($row['year'] != $year) {
                                $trendRow = '"' . $row['year'] . '"' . ':' . $row['count'];
                                echo $trendRow;
                                $trendString = $trendString . $trendRow . ',';
                            }
		                endforeach ?>
                        <?php $trendString = substr($trendString, 0, -1); ?>
		            </ul>
		            <div class="ct-chart ct-major-sixth <?php echo $word ?>-chart" data-trend-graph='<?php echo $trendString ?>'></div>
					<div class="trends-labels">
						<span>2000</span>
						<span><?php echo $year-1 ?></span>
					</div>
				</div>
                <!-- <?php $yearCount = getWordCountByYear($word, $year); ?>
                <span class="">Used <strong><?php echo $yearCount[0]['count'] ?></strong> times in <?php echo $year ?></span> -->
				<div class="graph-stat today-word-articles-text">
					<span class="graph-label" id="random-use">Random use in an article: </span>
					<?php $randomWord = randomArticleByWord($word); ?>
					<span class="graph-article">"<?php echo substr($randomWord[0]['article_text'], 1, -1); ?>"</span>
					<a class="graph-link" href="<?php echo $mo_domain ?><?php echo $randomWord[0]['article_link']; ?>" target="_blank">Go to full article	</a>
				</div>
			</div>
        </div>
<?php } ?>
