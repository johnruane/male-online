<?php
require_once("mo.php");

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
        case 'populate-today-count':
            getListOfArticleLinks([$mo_homepage_url], $xpath_article_query_string);
            setTodaysArticles($matched_articles);
            error_log('Today count populated', 0);
            break;
        case 'set-yearly-count':
            $bad_words = getBadWords();
            foreach($years as $year) {
                foreach ($bad_words as $word) {
                    $word_result = getCurrentCountsForYearByWord($year, $word);
                    setYearlyTotalsByYear($year, $word, $word_result[0]['total']);
                }
            }
            error_log('Yearly count populated', 0);
            break;
        case 'populate-random-articles':
            $bad_words = getBadWords();
            foreach ($bad_words as $word) {
                populateRandomArticles($word);
            }
            error_log('Random table populated', 0);
            break;
    }
}
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
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bigshot+One" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>

<body>
    <div class="site-wrapper admin">
		<!-- <header class="main-header">
			<a class="site-logo" href="/index4.php">
				<span class="flam-text">Male </span>
				<span class="thin-text">Online</span>
			</a>
		</header> -->
		<div class="jumbotron">
			<div class="container">
				<h2>Admin panel</h2>
			</div>
		</div>
		<main class="container">
			<div class="admin-panel">
				<span class="admin-heading">DB functions</span>
				<div class="admin-body">
					<div class="admin-section">
						<div>
							<label class="php-action-label">Delete data from table</label>
							<p class="admin-markup">function cleanTable($table)</p>
						</div>
						<div class="admin-action">
							<select>
								<option value="-1">Select a year</option>
							<?php foreach($years as $y) { ?>
								<option value="<?php echo $y ?>"><?php echo $y ?></option>
							<?php } ?>
								<option value="all">ALL</option>
							</select>
							<button type="button" class="admin-btn-warning">Go</button>
						</div>
					</div>
					<div class="admin-section">
						<div>
							<label class="php-action-label">Search and archive</label>
							<p class="admin-markup">Performs full word search against years selected</p>
						</div>
						<div class="admin-action">
							<form class="input-matrix">
								<?php foreach($years as $y) { ?>
									<li><label for="<?php echo $y ?>-checkbox"><?php echo $y ?></label>
										<input id="<?php echo $y ?>-checkbox" type="checkbox" value="<?php echo $y ?>">
									</li>
								<?php } ?>
							</form>
							<button type="button" class="admin-btn-success">Go</button>
						</div>
					</div>
					<div class="admin-section">
						<div>
							<label class="php-action-label">Calculate yearly word frequencies</label>
							<p class="admin-markup">Performs frequency calculation from archive and populates yearly table</p>
						</div>
						<div class="admin-action">
							<form class="input-matrix">
								<?php foreach($years as $y) { ?>
									<li><label for="<?php echo $y ?>-checkbox"><?php echo $y ?></label>
										<input id="<?php echo $y ?>-checkbox" type="checkbox" value="<?php echo $y ?>">
									</li>
								<?php } ?>
									<li><label for="all-checkbox">ALL</label>
										<input id="all-checkbox" type="checkbox" value="all">
									</li>
							</form>
							<button type="button" class="admin-btn-success">Go</button>
						</div>
					</div>
					<div class="admin-section">
						<div>
							<label class="php-action-label">Populate 'random article' table</label>
							<p class="admin-markup">For each 'bad word' it selects 20 random articles from the 'archive', and populates the 'random' table</p>
						</div>
						<div class="admin-action">
							<button type="button" class="admin-btn-success">Go</button>
						</div>
					</div>
					<div class="admin-section">
						<div>
							<label class="php-action-label">New word addition</label>
							<p class="admin-markup">Performs full search and archive of new word supplied</p>
						</div>
						<div class="admin-action">
							<form>
								<input type="text" name="new-word">
								<button type="button" class="admin-btn-success admin-long-btn">Add</button>
							</form>
						</div>
					</div>
					<div class="admin-section">
						<div>
							<label class="php-action-label">Word removal</label>
							<p class="admin-markup">Deletes all entries from the 'archive' &amp; 'yearly' tables</p>
						</div>
						<div class="admin-action">
							<form>
								<select>
									<option value="-1">Select a word</option>
								<?php foreach(getBadWords() as $w) { ?>
									<option value="<?php echo $w ?>"><?php echo $w ?></option>
								<?php } ?>
								</select>
								<button type="button" class="admin-btn-warning admin-long-btn">Delete</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- <p>Clean tables: <input type="button" value="clean-all-tables"></p>
			<p>Populate archive_count: <input type="button" value="populate-current-count-from-years"></p>
			<p>Set yearly count: <input type="button" value="set-yearly-count"></p>
			<p>Populate today_count: <input type="button" value="populate-today-count"></p>
			<p>Populate random table: <input type="button" value="populate-random-articles"></p> -->
		</main>
	</div>
</body>
</html>
