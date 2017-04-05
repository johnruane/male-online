<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    // $year = $_POST['year'];
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
