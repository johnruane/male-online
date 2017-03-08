<?php
ini_set('max_execution_time', 0);

$mo_home_domain="http://dailymail.co.uk/";

$years_to_search = ['1994', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016'];

//$years_to_search = ['1994', '1996', '1997', '1998', '1999'];
//$years_to_search = ['2001', '2002', '2003'];
// $years_to_search = ['2004','2005','2006'];
// $years_to_search = ['2007,'2008','2009'];
// $years_to_search = ['2010','2011','2012'];
// $years_to_search = ['2013','2014','2015'];
// $years_to_search = ['2016','2017'];

//Create table
$sql_create_count_table = 'CREATE TABLE archive_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    publication_date DATE,
    word VARCHAR(20),
    article_text TEXT(10000),
    article_link TEXT(10000),
    thumbnail_link TEXT(10000)
    )';

$sql_create_today_count_table = 'CREATE TABLE today_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    publication_date DATE,
    word VARCHAR(20),
    article_text TEXT(1000),
    article_link TEXT(10000),
    thumbnail_link TEXT(10000)
    )';

//Create table
$sql_create_yearly_table = 'CREATE TABLE yearly_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    year VARCHAR(4),
    word VARCHAR(20),
    count INT(3)
    )';

// Select all
$sql_select_all = 'SELECT * FROM archive_count';

$list_of_bad_words = array (
    4 => ['boob','bust','pert','pout','racy','sexy','slim','trim','vamp'],
    5 => ['ample','busty','leggy','perky','saucy','thigh','toned','yummy'],
    6 => ['assets','curves','fuller','gushes','skimpy','skinny','steamy','teases',],
    7 => ['ageless','braless','flashes','flaunts','midriff','scantly','sizable','slender',],
    8 => ['cleavage','enviable','flashing','plunging','sideboob','sizzling'],
    9 => ['postirior','revealing','underboob'],
    10 => ['skin-tight','super-slim'],
    11 => ['eye-popping'],
    14 => ['figure-hugging']
);

// $list_of_bad_words = array (
//     3 => ['for','all','hot'],
//     4 => ['peep','lion','mend'],
//     5 => ['world', 'china'],
//     6 => ['likely'],
//     9 => ['possessed'],
// );

/*
    Gets all the Date links from a Yearly archive page and return them as an array
*/
function getDailyArchiveLinks($url, $xpath_string) {
    $html = file_get_contents($url);
    $dom = new \DOMDocument('1.0', 'UTF-8');

    $internalErrors = libxml_use_internal_errors(true); // set error level
    $dom->loadHTML($html);
    libxml_use_internal_errors($internalErrors); // Restore error level

    $xpath = new DomXpath($dom);
    $article_list = $xpath->query($xpath_string); // Returns all list items from a yearly page

    $article_links = array();
    foreach ($article_list as $article) {
    	$node = $xpath->query('descendant::a/attribute::href', $article);
    	array_push($article_links, "http://www.dailymail.co.uk" . $node->item(0)->textContent); // Gets all daily links
    }
    return $article_links; // Returns an array of daily links
}

/*
    Get all the links from the year link provided
*/
function getListOfArticleLinks($ary_of_links, $query_string) {
    global $matched_articles;
    global $list_of_bad_words;
    $pub_date = '';

    /*
        link = http://www.dailymail.co.uk/home/sitemaparchive/day_19941014.html
        or
        link = http://www.dailymail.co.uk/home/index.html
    */
    foreach ($ary_of_links as $link) {
        $html = file_get_contents($link);
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $internalErrors = libxml_use_internal_errors(true); // set error level
        $dom->loadHTML($html);
        libxml_use_internal_errors($internalErrors); // Restore error level

        $xpath = new DomXpath($dom);
        $articles = $xpath->query($query_string);

        foreach ($articles as $article) { // article = DOMElement
            if ( is_object($article) ) {
                $node_text = $article->nodeValue; // article[nodeValue] = string to search eg "She dropped her Chanel diamond ring" etc
                $node_text = preg_replace('/\b[A-Za-z0-9]{1,x}\b\s?/i', '', $node_text); // removes javascript
                $node_text = preg_replace('/\s+/', ' ', $node_text); // replace large whitespaces with a single whitespace
                $article_string_array = preg_split('/[\s,]+/', $node_text); // split string on any space into array

                foreach ($article_string_array as $article_word) { // loops through array of article[nodeValue]
                    $article_word = preg_replace('/[^A-Za-z\-]/', '', $article_word); // remove numerical & special characters

                    if (isset($list_of_bad_words[strlen($article_word)])) { // if the 'article word' length matches a 'bad words' length
                        foreach ($list_of_bad_words[strlen($article_word)] as $badword) {
                            if (strcasecmp($article_word, $badword) == 0) { // case-insensitive string comparison

                                // different date function for 'archive' or 'daily'
                                if (strpos($link, 'sitemaparchive') == true) {
                                    $linkURL = explode('/', $link);
                                    $pub_date = preg_split("/[_.]/", end($linkURL))[1]; // get the date from the article url
                                } else {
                                    $pub_date = date("Y-m-d");
                                }

                                $matched_article['date'] = $pub_date;
                                $matched_article['word'] = $badword;
                                $matched_article['article_text'] = $node_text;
                                $matched_article['article_link'] = $xpath->query('descendant::a/attribute::href', $article)->item(0)->nodeValue;
                                $thumbnail = $xpath->query('descendant::a/img/attribute::data-src', $article);
                                if (strpos($link, 'index') == true) {
                                    $matched_article['thumbnail_link'] = $thumbnail->item(0)->nodeValue;
                                }
                                array_push($matched_articles, $matched_article);
                            }
                        }
                    }
                }
                error_log($node_text, 0);
            }
        }
    }
}
/* SETTERS */
function setFoundArticlesToCurrentDB($q_links) {
    $db = new Db();
    $sql = 'INSERT INTO archive_count (publication_date, word, article_text, article_link, thumbnail_link) VALUES (?, ?, ?, ?, ?)';
    $stmt = $db->connect()->prepare($sql);

    foreach($q_links as $value) {
        $stmt->bind_param('sssss', $value['date'], $value['word'], $value['article_text'], $value['article_link'], $value['thumbnail_link']);
        $stmt->execute();
    }
}
function setTodaysArticles($q_links) {
    $db = new Db();
    $sql = 'INSERT INTO today_count (publication_date, word, article_text, article_link, thumbnail_link) VALUES (?, ?, ?, ?, ?)';
    $stmt = $db->connect()->prepare($sql);

    foreach($q_links as $value) {
        $stmt->bind_param('sssss', $value['date'], $value['word'], $value['article_text'], $value['article_link'], $value['thumbnail_link']);
        $stmt->execute();
    }
}
function setYearlyTotalsByYear($year, $result) {
    $sql_count_yearly = 'INSERT INTO yearly_count (year, word, count) VALUES (?,?,?)';
    $db = new Db();
    if ( $stmt = $db->connect()->prepare($sql_count_yearly) ) {
        foreach($result as $row) {
           $word = $row['word'];
           $count = $row['total'];
           $stmt->bind_param('ssi', $year, $row['word'], $row['total']);
           $stmt->execute();
        }
    } else {
        echo $db->error();
    }
}
/* GETTERS */
function getDailyCount() {
    $sql_select_daily = "SELECT word, count(*) AS total FROM today_count GROUP BY word ORDER BY total DESC";
    $db = new Db();
    return $db->select($sql_select_daily);
}
function getWeeklyCount($today, $lastSevenDays) {
    $sql_select_weekly = "SELECT word, count(*) AS 'total' FROM archive_count WHERE publication_date BETWEEN '$today' AND '$lastSevenDays' GROUP BY word";
    $db = new Db();
    return $db->select($sql_select_weekly);
}
function getCurrentCountsForYear($year) {
    $sql_select_yearly = "SELECT word, count(*) AS 'total' FROM archive_count WHERE publication_date BETWEEN '$year-01-01' AND '$year-12-31' GROUP BY word";
    $db = new Db();
    return $db->select($sql_select_yearly);
}
function getYearlyTotals($year) {
    $sql_count_yearly = "SELECT * FROM yearly_count WHERE year=$year ORDER BY count DESC";
    $db = new Db();
    return $db->select($sql_count_yearly);
}
function getWordCount($word) {
    error_log($word, 0);
    $sql_select_word = "SELECT year, count FROM yearly_count WHERE word = '$word'";
    $db = new Db();
    return $db->select($sql_select_word);
}
function getDailyArticlesFromWord($word) {
    $sql_select_word_articles = "SELECT article_text, article_link, thumbnail_link FROM today_count WHERE word = '$word'";
    $db = new Db();
    return $db->select($sql_select_word_articles);
}
/* Other functions */
function cleanAllTables() {
    $sql_clean_archive_count = "DELETE FROM archive_count";
    $sql_clean_today_count = "DELETE FROM today_count";
    $sql_clean_yearly_count = "DELETE FROM yearly_count";
    $db = new Db();
    $db->query($sql_clean_archive_count);
    $db->query($sql_clean_today_count);
    $db->query($sql_clean_yearly_count);
}
function getBadWords() {
    $list_of_bad_words_sorted = [];
    global $list_of_bad_words;
    foreach ($list_of_bad_words as $value) {
        foreach($value as $val) {
            array_push($list_of_bad_words_sorted, $val);
        }
    }
    return $list_of_bad_words_sorted;
}
function randomArticleByWord($word) {
    $sql_random_article = "SELECT article_text, article_link FROM archive_count WHERE word = '$word' ORDER BY rand() LIMIT 1";
    $db = new Db();
    return $db->select($sql_random_article);
}
function cmp($a, $b) {
    if ($a['value'] == $b['value']) {
        return 0;
    }
    return ($a['value'] < $b['value']) ? -1 : 1;
}
?>
