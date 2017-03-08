<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    $year = $_POST['year'];
?>

<div class="mo-results-list">
    <h2 class="results-heading">Yearly totals for <?php echo $year ?></h2>
    <?php $yearlyResults = getYearlyTotals($year); ?>
    <?php if ($yearlyResults) { ?>
        <ul class="mo-yearly-list">
            <?php foreach ($yearlyResults as $row): ?>
                <li class="mo-list-item"><span class="word-key"><?php echo $row['word'] ?></span>
                <span class="word-value"><?php echo $row['count'] ?></span></li>
            <?php endforeach ?>
        </ul>
    <?php } ?>
</div>
