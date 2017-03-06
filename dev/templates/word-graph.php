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
    <div id="word-year-highest">Highest: <?php echo current($wordResults)['count'] ?> times in <?php echo current($wordResults)['year'] ?></span></div>
    <div id="word-year-lowest">Lowest: <?php echo end($wordResults)['count'] ?> times in <?php echo end($wordResults)['year'] ?></span></div>
    <?php $randomWord = randomArticleByWord($word); ?>
    <h4 id="random-use">Random use in an article:</h4>
    <p><?php echo $randomWord[0]['article_text']; ?></p>
</div>
