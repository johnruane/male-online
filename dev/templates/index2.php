<?php
ini_set("error_reporting","-1");
ini_set("display_errors","On");
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
$admin=false;
$admin=true;
$query='';

$mo_homepage_url = "http://www.dailymail.co.uk/home/index.html";
$mo_archive_url = "http://www.dailymail.co.uk/home/sitemaparchive/year_";
$xpath_archive_article_query_string = "//ul[contains(concat(' ', normalize-space(@class), ' '), ' archive-articles ')]/li";
$xpath_article_query_string = "//div[@class='beta']//div[contains(concat(' ', normalize-space(@class), ' '), 'femail')]//li | //div[@class='beta']//div[contains(concat(' ', normalize-space(@class), ' '), 'tvshowbiz')]//li";

$article_list = array(); // List of all the articles got from yearly page
$matched_articles = array();

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'clean-all-tables':
            cleanAllTables();
            error_log('All tables cleaned', 0);
            break;
        case 'populate-current-count-from-years': // query each year and populate archive_count
            foreach ($years_to_search as $year) {
                $article_list = getDailyArchiveLinks($mo_archive_url.$year.'.html', '//ul[@class="split"]/li');
                getListOfArticleLinks($article_list, $xpath_archive_article_query_string, $year);
                setFoundArticlesToCurrentDB($matched_articles);
                error_log($year.' done.', 0);
            }
            error_log('Archive populated from years array', 0);
            break;
        case 'populate-today-count': // populate today_count
            getListOfArticleLinks([$mo_homepage_url], $xpath_article_query_string);
            setTodaysArticles($matched_articles);
            error_log('Today count populated', 0);
            break;
        case 'set-yearly-count':
            foreach($years_to_search as $year) {
                setYearlyTotalsByYear($year, getCurrentCountsForYear($year));
            }
            break;
    }
}
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
    <?php if ($admin) { ?>
        <div class="admin-panel">
            <p>Clean tables: <input type="button" value="clean-all-tables"></p>
            <p>Populate archive_count: <input type="button" value="populate-current-count-from-years"></p>
            <p>Set yearly count: <input type="button" value="set-yearly-count"></p>
            <p>Populate today_count: <input type="button" value="populate-today-count"></p>
        </div>
    <?php } ?>
    <nav class="title-wrapper">
        <!-- <h1>The <span>Male</span> Online</h1> -->
    </nav>
    <div class="container">
        <div class="row">
            <div class="col col-xs-8">
                <h4>Today</h4>
                <?php $dailyResults = getDailyCount(); ?>
                <?php if ($dailyResults) { ?>
                    <?php foreach ($dailyResults as $row): ?>
                        <p><span class="word-key"><?php echo $row['word'] ?></span>
                        <span class="word-value"><?php echo $row['total'] ?></span></p>
                    <?php endforeach ?>
                <?php } ?>
            </div>
            <div class="col col-xs-4">
                <h4>Last 7 Days</h4>
                <?php $weeklyResults = getWeeklyCount('1994-01-01', '1994-12-31'); ?>
                <?php if ($weeklyResults) { ?>
                    <?php foreach ($weeklyResults as $row): ?>
                        <p><span class="word-key"><?php echo $row['word'] ?></span>
                        <span class="word-value"><?php echo $row['total'] ?></span></p>
                    <?php endforeach ?>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <?php foreach ( $years_to_search as $year ): ?>
                <?php $yearlyResults = getYearlyTotals($year); ?>
                <?php if ($yearlyResults) { ?>
                    <div class="col col-xs-4">
                        <h4><?php echo $year ?></h4>
                        <?php foreach ($yearlyResults as $row): ?>
                            <p><span class="word-key"><?php echo $row['word'] ?></span>
                            <span class="word-value"><?php echo $row['count'] ?></span></p>
                        <?php endforeach ?>
                    </div>
                <?php } ?>
            <?php endforeach ?>
        </div>
    </div>
<script src="//localhost:35729/livereload.js"></script>
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
    $('.admin-panel input').on('click', function() {
        var clickBtnValue = $(this).val();
        var ajaxurl = 'index2.php',
        data =  {'action': clickBtnValue};
        $.post(ajaxurl, data, function (response) {
            $('body').html(response);
        });
    });
});
</script>
