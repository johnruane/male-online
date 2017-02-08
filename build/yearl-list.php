<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
?>

<div class="mo-results-list">
    <?php $yearlyResults = getYearlyTotals($year); ?>
    <?php if ($dailyResults) { ?>
        <ul class="mo-yearly-list">
            <?php foreach ($dailyResults as $row): ?>
                <li><span class="word-key"><?php echo $row['word'] ?></span>
                <span class="word-value"><?php echo $row['count'] ?></span></li>
            <?php endforeach ?>
        </ul>
    <?php } ?>
</div>
