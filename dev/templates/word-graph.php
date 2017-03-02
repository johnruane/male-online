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
</div>
