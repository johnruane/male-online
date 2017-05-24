<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    //$word = $_POST['word'];
?>
<?php $wordResults = getWordCount($word); ?>
<?php if ($wordResults != null && count($wordResults) > 1) { ?>
        <div id="<?php echo $word ?>-chart" data-bind="word-chart" class="tile clearfix">
            <h2 class="results-heading"><?php echo $word ?></h2>
            <ul class="hidden-word-results">
                <?php foreach ($wordResults as $row): ?>
                    <li><span class="word-key"><?php echo $row['year'] ?></span>
                    <span class="word-value"><?php echo $row['count'] ?></span></li>
                <?php endforeach ?>
            </ul>
            <canvas id="<?php echo $word ?>-chart-canvas" width="100"></canvas>
            <div class="trends-labels">
                <span>2000</span>
                <span>2017</span>
            </div>
            <div class="graph-stat">
                <span class="graph-label" id="random-use">Random use in an article: </span>
                <?php $randomWord = randomArticleByWord($word); ?>
                <span><a class="graph-link" href="<?php echo $mo_home_domain ?><?php echo $randomWord[0]['article_link']; ?>" target="_blank">"<span><?php echo substr($randomWord[0]['article_text'], 1, -1); ?></span>"</a>
            </div>
        </div>
<?php } ?>
