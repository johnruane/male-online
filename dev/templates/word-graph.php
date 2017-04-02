<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    //$word = $_POST['word'];
?>
<?php $wordResults = getWordCount($word); ?>
<?php if ($wordResults != null && count($wordResults) > 1) { ?>
    <div class="graph-container chart clearfix" id="<?php echo $word ?>-chart">
        <h2 class="results-heading"><?php echo $word ?></h2>
        <ul class="hidden-word-results">
            <?php foreach ($wordResults as $row): ?>
                <li><span class="word-key"><?php echo $row['year'] ?></span>
                <span class="word-value"><?php echo $row['count'] ?></span></li>
            <?php endforeach ?>
        </ul>
        <div class="ct-chart ct-square <?php echo $word ?>-chart"></div>
        <div class="graph-stat">
            <span class="graph-label" id="random-use">Random use in an article: </span>
            <span><a class="graph-link" href="#" target="_blank">What worrying does to your health and how to beat it</a>
        </div>
    </div>
<?php } ?>
