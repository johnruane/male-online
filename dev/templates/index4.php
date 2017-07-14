<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
ini_set("error_reporting","-1");
ini_set("display_errors","On");

$sort_array = array();
$matched_articles = array();
$mo_homepage_url = "http://www.dailymail.co.uk/home/index.html";
$xpath_article_query_string = "//div[@class='beta']//div[contains(concat(' ', normalize-space(@class), ' '), 'femail')]//li | //div[@class='beta']//div[contains(concat(' ', normalize-space(@class), ' '), 'tvshowbiz')]//li";

?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>The Male Online</title>
    <meta name="description" content="The Male Online">
    <meta name="author" content="SitePoint">
    <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no;" />

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="js/jquery.randomColor.js"></script>
    <script src="js/bootstrap-tab.js"></script>

    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bigshot+One" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>

<body>
    <div class="site-wrapper">
        <header class="main-header">
            <a class="site-logo" href="/index4.php">
                <span class="flam-text">Male </span>
                <span class="thin-text">Online</span>
            </a>
            <!-- <nav class="nav-icon" data-bind="menu">
                <span></span>
                <span></span>
                <span></span>
            </nav> -->
        </header>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a id="today-tab" href="#today" aria-controls="today" role="tab" data-toggle="tab">Today</a></li>
			<li role="presentation"><a id="trends-tab" href="#trends" aria-controls="trends" role="tab" data-toggle="tab">Trends</a></li>
		</ul>
        <main class="container">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane tab-pane-today active" id="today">
                    <!-- <?php getListOfArticleLinks([$mo_homepage_url], $xpath_article_query_string); ?>
                    <?php cleanTable('today_count'); ?>
                    <?php setTodaysArticles($matched_articles); ?> -->
                    <h4 class="tab-heading">Today</h4>
					<p class="sub-heading">Current articles on today's homepage</p>
                    <!-- <?php include 'today.php' ?> -->
                </div>
                <div role="tabpanel" class="tab-pane" id="trends">
                    <h4 class="tab-heading">Trends</h4>
					<p class="sub-heading">Graph mapping usage over time</p>
                    <div class="trends-grid">
                        <?php foreach (getBadWords() as $word): ?>
                            <?php include 'trends.php' ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </main>
        <footer>
			<div class="container">
				<h4>Rarely Asked Questions</h4>
				<details>
					<summary>What is this website?</summary>
					<p>This website is an graphical online etymology of a set of words repeatedly used by The Daily Mail writing headlines about women.</p>
				</details>
				<details>
					<summary>What words are you looking for?</summary>
					<p>Here is a complete list of the words being searched for:</p>
					<ul class="footer-word-list reset-list">
						<?php foreach (getBadWords() as $word): ?>
							<li><?php echo $word ?></li>
						<?php endforeach ?>
					</ul>
					<p>I have the ability to freely add and remove words based on trends or suggestions.</p>
				</details>
				<details>
					<summary>How accurate is it?</summary>
					<p>It's not 100%. I can't distinguise between 'Jordan puts her bust on display' and 'Airline to go bust in 1 week'. I manually clean out any incorrectly logged articles as they are found and welcome any errors being pointed out by anyone viewing the content.</p>
				</details>
				<details>
					<summary>Disclaimer</summary>
					<p>This website is purely for "research", "computational analysis" and "entertainment" purposes only. All data and copyright material on display here is owned by The Daily Mail and their licensors.</p>
				</details>
				<details>
					<summary>Credits</summary>
					<p>All code used to web scrape or data mine has been written by me using PHP. No frameworks or grids are used for presentation. Except for the 3rd party tools below, everything has been developed from scratch.</p>
					<ul class="reset-list">
						<li>Bootstrap Tab - <a class="plain-link" href="http://getbootstrap.com/javascript/#tabs">link</a></li>
						<li>Chart JS - <a class="plain-link" href="http://www.chartjs.org/">link</a></li>
						<li>Lazysizes - <a class="plain-link" href="https://afarkas.github.io/lazysizes/">link</a></li>
						<li>Ramdom Color - <a href="https://github.com/davidmerfield/randomColor">link</a>
					</ul>
				</details>
			</div>
        </footer>
    </div>
    <script src="//localhost:35729/livereload.js"></script>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
<script src="js/lazysides.min.js"></script>
<script src="js/scripts.js"></script>
