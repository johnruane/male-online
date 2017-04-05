<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
    // $year = $_POST['year'];
?>
<?php $yearlyResults = getYearlyTotals($year); ?>
<?php if ($yearlyResults != null) { ?>
    <div class="graph-container yearly-chart clearfix" id="chart-<?php echo $year ?>">
        <h2 class="results-heading"><?php echo $year ?></h2>
        <ul class="hidden-word-results">
            <?php foreach ($yearlyResults as $row): ?>
                <li><span class="yearly-word-key"><?php echo $row['word'] ?></span>
                <span class="yearly-word-value"><?php echo $row['count'] ?></span></li>
            <?php endforeach ?>
        </ul>
        <div class="ct-chart chart-<?php echo $year ?>"></div>
    </div>
<?php } ?> 
