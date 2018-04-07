<?php
    require_once("resources/mo.php");
    require_once("resources/conf.php");
    require_once("resources/db.php");
?>
<?php $yearlyResults = getYearlyTotals(); ?>
<div class="graph-container yearly-chart clearfix" id="chart-1997">
    <ul class="hidden-word-results chart-vaules-<?php echo $row['year'] ?>">
        <?php foreach ($yearlyResults as $row): ?>
            <li><span class="yearly-word-key"><?php echo $row['word'] ?></span>
            <span class="yearly-word-value"><?php echo $row['count'] ?></span></li>
        <?php endforeach ?>
    </ul>
    <div class="ct-chart"></div>
</div>
