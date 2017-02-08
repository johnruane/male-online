<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
?>
<div class="mo-results-list">
    <h2>Today</h2>
    <?php $dailyResults = getDailyCount(); ?>
    <?php if ($dailyResults) { ?>
        <ul class="mo-daily-list">
            <?php foreach ($dailyResults as $row): ?>
                <li><span class="word-key"><?php echo $row['word'] ?></span>
                <span class="word-value"><?php echo $row['total'] ?></span></li>
            <?php endforeach ?>
        </ul>
    <?php } ?>
</div>
