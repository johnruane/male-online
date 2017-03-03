<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    $word = $_POST['word'];
?>
<div class="mo-results-list">
    <h2 class="results-heading"><?php echo $word ?></h2>
    <?php $wordResults = getWordCount($word); ?>
    <?php if ($wordResults) { ?>
        <ul class="mo-word-list">
            <?php foreach ($wordResults as $row): ?>
                <li><span class="word-key"><?php echo $row['year'] ?></span>
                <span class="word-value"><?php echo $row['count'] ?></span></li>
            <?php endforeach ?>
        </ul>
    <?php } ?>
    <div class="ct-chart ct-golden-section"></div>
    <?php $wordResults.asort(); ?>
    <div id="word-year-highest">Highest yearly usage: <?php echo current($wordResults)['year'] ?>: <?php echo current($wordResults)['count'] ?></span></div>
    <div id="word-year-lowest">Lowest yearly usage: <?php echo end($wordResults)['year'] ?>: <?php echo end($wordResults)['count'] ?></span></div>
    <h4 id="random-use">Random use in an article:</h4>
</div>
