<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    $word = $_POST['word'];
?>
<div class="results-container">
    <h2 class="results-heading"><?php echo $word ?></h2>
    <?php $wordResults = getWordCount($word); ?>
    <?php if ($wordResults) { ?>
        <ul class="hidden-word-results">
            <?php foreach ($wordResults as $row): ?>
                <li><span class="word-key"><?php echo $row['year'] ?></span>
                <span class="word-value"><?php echo $row['count'] ?></span></li>
            <?php endforeach ?>
        </ul>
    <?php } ?>
    <div id="LineChart">
        <div class="ct-chart ct-square"></div>
    </div>
    <?php usort($wordResults, "cmp"); ?>
    <div>
        <div id="word-year-lowest" class="graph-stat">
            <span class="graph-label">Lowest:</span>
            <span><?php echo current($wordResults)['count'] ?> times in <?php echo current($wordResults)['year'] ?></span>
        </div>
        <?php reset($wordResults) ?>
        <div id="word-year-highest" class="graph-stat">
            <span class="graph-label">Highest:</span>
            <span><?php echo end($wordResults)['count'] ?> times in <?php echo end($wordResults)['year'] ?></span>
        </div>
        <?php $randomWord = randomArticleByWord($word); ?>
        <div id="word-year-lowest" class="graph-stat">
            <span class="graph-label" id="random-use">Random use in an article: </span>
            <span><a class="graph-link" href="<?php echo $mo_home_domain ?><?php echo $randomWord[0]['article_link']; ?>" target="_blank"><?php echo $randomWord[0]['article_text']; ?></a>
        </div>
        </span>
    </div>
</div>
