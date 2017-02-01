<?php
    ini_set("error_reporting","-1");
    ini_set("display_errors","On");
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");

    $links = array();

    /*
        Query each year and populate current_count
    */
    // foreach ($years as $year) {
    //     $links = getLinks('http://www.dailymail.co.uk/home/sitemaparchive/year_'.$year.'.html', '//ul[@class="split"]/li');
    //     $found_articles_array = queryLinks($links,'//ul[contains(concat(" ", normalize-space(@class), " "), " archive-articles ")]/li');
    //     setFoundArticlesToCurrentDB($found_articles_array);
    // }

    /*
        Query today and populate today_count
    */
    // $found_articles_array = queryLinks(['http://www.dailymail.co.uk/home/index.html'],'//div[contains(concat(" ", normalize-space(@class), " "), " article ")]');
    // setTodaysArticles($found_articles_array);

    // $db = new Db();
    // $sql = $sql_create_today_count_table;
    // $db->query($sql);

    // foreach($years as $year) {
    //     setYearlyTotalsByYear($year, getCurrentCountsForYear($year));
    // }

    $todays_year = date('Y');
    // $found_articles_array = getYearlyTotals('1996');
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">

  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Eczar:800" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
    <nav class="title-wrapper">
        <!-- <h1>The <span>Male</span> Online</h1> -->
    </nav>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xs-8">
                <div class="results-panel">
                    <h4>Today</h4>
                    <?php $dailyResults = getDailyCount(); ?>
                    <?php foreach ($dailyResults as $row): ?>
                        <p><span class="word-key"><?php echo $row['word'] ?></span>
                        <span class="word-value"><?php echo $row['total'] ?></span></p>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="results-panel">
                    <h4>Last 7 Days</h4>
                    <?php $weeklyResults = getWeeklyCount(); ?>
                    <?php foreach ($weeklyResults as $row): ?>
                        <p><span class="word-key"><?php echo $row['word'] ?></span>
                        <span class="word-value"><?php echo $row['count'] ?></span></p>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ( $years as $year ): ?>
                <?php $yearlyResults = getYearlyTotals($year); ?>
                <div class="col-xs-4">
                    <div class="results-panel">
                        <h4><?php echo $year ?></h4>
                        <?php foreach ($yearlyResults as $row): ?>
                            <p><span class="word-key"><?php echo $row['word'] ?></span>
                            <span class="word-value"><?php echo $row['count'] ?></span></p>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</body>
</html>

<script>
$(function() {
    $('[data-collapse]').on('click', function() {
        var $moList = $(this).children('.keyword-wrapper')
        if ( $moList.is(':visible') ) {
            $($moList).slideUp(300);
        } else {
            $('.keyword-wrapper').slideUp(300);
            $($moList).slideDown(300);
        }
    });
});
</script>
