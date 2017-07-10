<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    //$word = $_POST['word'];
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
		                <?php foreach ($wordResults as $row): ?>
		                    <li><span class="word-key"><?php echo $row['year'] ?></span>
		                    <span class="word-value"><?php echo $row['count'] ?></span></li>
		                <?php endforeach ?>
		            </ul>
		            <canvas id="<?php echo $word ?>-chart-canvas"></canvas>
					<div class="trends-labels">
						<span>2000</span>
						<span>2017</span>
					</div>
				</div>
				<div class="graph-stat today-word-articles-text">
					<span class="graph-label" id="random-use">Random use in an article: </span>
					<?php $randomWord = randomArticleByWord($word); ?>
					<span class="graph-article">"<?php echo substr($randomWord[0]['article_text'], 1, -1); ?>"</span>
					<a class="graph-link" href="<?php echo $mo_home_domain ?><?php echo $randomWord[0]['article_link']; ?>" target="_blank">Go to full article	</a>
				</div>
			</div>
        </div>
<?php } ?>
