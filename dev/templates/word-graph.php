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
    <?php usort($wordResults, "cmp"); ?>
    <div class="graph-text">
        <div id="word-year-highest">
            <span>Highest:</span>
            <span><?php echo current($wordResults)['count'] ?> times in <?php echo current($wordResults)['year'] ?></span>
        </div>
        <div id="word-year-lowest">
            <span class="graph-label">Lowest:</span>
            <span><?php echo end($wordResults)['count'] ?> times in <?php echo end($wordResults)['year'] ?></span>
        </div>
        <?php $randomWord = randomArticleByWord($word); ?>
        <div class="graph-text-data">
            <span id="random-use">Random use in an article: </span>
            <span><?php echo $randomWord[0]['article_text']; ?></span>
        </div>
    </div>
</div>
