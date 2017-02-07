<?php

$years = ['1994'];

//Create table
$sql_create_count_table = "CREATE TABLE current_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    publication_date DATE,
    word VARCHAR(20),
    count INT(3),
    articles TEXT(10000)
    )";

$sql_create_today_count_table = "CREATE TABLE today_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    publication_date DATE,
    word VARCHAR(20),
    articles TEXT(10000)
    )";

//Create table
$sql_create_yearly_table = "CREATE TABLE yearly_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    year VARCHAR(4),
    word VARCHAR(20),
    count INT(3)
    )";

// Select all
$sql_select_all = "SELECT * FROM current_count";

// $list_of_bad_words = array (
//     4 => ['boob','bust','pert','pins','pout','racy','sexy','slim','trim','vamp','PAYS'],
//     5 => ['ample','busty','leggy','perky','saucy','thigh','toned','yummy','price'],
//     6 => ['assets','curves','fuller','gushes','skimpy','skinny','steamy','teases','tennis'],
//     7 => ['ageless','braless','flashes','flaunts','midriff','scantly','sizable','slender','destroy'],
//     8 => ['cleavage','enviable','flashing','plunging','sideboob','sizzling'],
//     9 => ['postirior','revealing','underboob'],
//     10 => ['skin-tight','super-slim'],
//     11 => ['eye-popping'],
//     14 => ['figure-hugging']
// );

$list_of_bad_words = array (

    3 => ['for','all','the'],
    4 => ['seal','lion'],
    5 => ['world'],
    9 => ['possessed'],
);

/*
    Gets all the links from a Yearly archive page and returns them as an array
*/
function getLinks($url, $query) {
    $link_results = array();
    $html = file_get_contents($url);
    $dom = new \DOMDocument('1.0', 'UTF-8');

    $internalErrors = libxml_use_internal_errors(true); // set error level
    $dom->loadHTML($html);
    libxml_use_internal_errors($internalErrors); // Restore error level

    $xpath = new DomXpath($dom);
    $links = $xpath->query($query);

    foreach ($links as $article) {
    	$node = $xpath->query("descendant::a/attribute::href", $article);
    	array_push($link_results, "http://www.dailymail.co.uk" . $node->item(0)->textContent);
    }
    return $link_results;
}

/*
    Get all the links from the year link provided
*/
function queryLinks($ary_of_links, $container_div) {
    global $bad_words;
    $matched_articles = array();

    foreach ($ary_of_links as $link) {
        $html = file_get_contents($link);
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $internalErrors = libxml_use_internal_errors(true); // set error level
        $dom->loadHTML($html);
        libxml_use_internal_errors($internalErrors); // Restore error level

        $xpath = new DomXpath($dom);
        $articles = $xpath->query($container_div);

        foreach ($articles as $article) {
            //$node = $xpath->query("descendant::a[@href]", $article);

            // $temp_dom = new DOMDocument();
            // foreach($node as $n)
            // $temp_dom->appendChild($temp_dom->importNode($n,true));
            // print_r($temp_dom->saveHTML().'<br />');

            if ( is_object($article) ) {
                $node_text = $article->nodeValue;
                preg_replace('/\b[A-Za-z0-9]{1,x}\b\s?/i', '', $node_text);
                $article_found = searchForWordFrequency($node_text, $bad_words, [$link, $article, $xpath]);
                if ($article_found) array_push($matched_articles, $article_found);
            }

        }
    }
    return $matched_articles;
}

/*
    Searches for 'bad word'
*/
function searchForWordFrequency($article_string, $list_of_bad_words, $article_info) {
    global $list_of_bad_words;
    global $found_words_array;
    global $query;
    $pub_date;

    $article_string_array = preg_split('/\s+/', $article_string);
    foreach ($article_string_array as $article_word) { // loops through words from the article headline

        if (isset($list_of_bad_words[strlen($article_word)])) { // does the 'article word' have any matching 'bad words' (by length)
            foreach ($list_of_bad_words[strlen($article_word)] as $badword) { // loops over matching 'bads words'
                if (strcasecmp($article_word, $badword) == 0) { // case-insensitive string comparison
                    if ($article_info) {
                        $linkURL = explode('/', $article_info[0]);
                        switch ($query) {
                            case "archive":
                                $pub_date = preg_split("/[_.]/", end($linkURL))[1]; // get the date from the article url
                                break;
                            case "today":
                                $pub_date = date("Y-m-d");
                                break;
                        }
                        $matched_article['date'] = $pub_date;
                        $matched_article['word'] = $badword;
                        $node = $article_info[2]->query("descendant::a/attribute::href", $article_info[1]);
                        $matched_article['link'] = $node->item(0)->nodeValue;
                        return $matched_article;
                    } else {
                        array_push($found_words_array, $badword);
                    }
                }
            }
        }
    }
}
/* SETTERS */
function setFoundArticlesToCurrentDB($q_links) {
    $db = new Db();
    $sql = "INSERT INTO current_count (publication_date, word, count, articles) VALUES (?, ?, ?, ?)";
    $stmt = $db->connect()->prepare($sql);

    foreach($q_links as $value) {
        $count = 1;
        $stmt->bind_param("ssis", $value['date'], $value['word'], $count, $value['link']);
        $stmt->execute();
    }
}
function setTodaysArticles($q_links) {
    $db = new Db();
    $sql = "INSERT INTO today_count (publication_date, word, articles) VALUES (?, ?, ?)";
    $stmt = $db->connect()->prepare($sql);

    foreach($q_links as $value) {
        $stmt->bind_param("sss", $value['date'], $value['word'], $value['link']);
        $stmt->execute();
    }
}
function setYearlyTotalsByYear($year, $result) {
    $sql_count_yearly = "INSERT INTO yearly_count (year, word, count) VALUES (?,?,?)";
    $db = new Db();
    if ( $stmt = $db->connect()->prepare($sql_count_yearly) ) {
        foreach($result as $row) {
           $word = $row['word'];
           $count = $row['total'];
           $stmt->bind_param("ssi", $year, $row['word'], $row['total']);
           $stmt->execute();
        }
    } else {
        echo $db->error();
    }
}
/* GETTERS */
function getDailyCount() {
    $sql_select_daily = "SELECT word, count(*) AS total FROM today_count GROUP BY word";
    $db = new Db();
    return $db->select($sql_select_daily);
}
function getWeeklyCount() {
    $sql_select_weekly = "SELECT * FROM current_count ORDER BY publication_date DESC LIMIT 7";
    $db = new Db();
    return $db->select($sql_select_weekly);
}
function getCurrentCountsForYear($year) {
    $sql_select_yearly = "SELECT SUM(count) AS 'total', word, entry_id FROM current_count WHERE publication_date BETWEEN '$year-01-01' AND '$year-12-31' GROUP BY word";
    $db = new Db();
    return $db->select($sql_select_yearly);
}
function getYearlyTotals($year) {
    $sql_count_yearly = "SELECT * FROM yearly_count WHERE year=$year";
    $db = new Db();
    return $db->select($sql_count_yearly);
}
/* Other functions */
function cleanAllTables() {
    $sql_clean_current_count = "DELETE FROM current_count";
    $sql_clean_today_count = "DELETE FROM today_count";
    $sql_clean_yearly_count = "DELETE FROM yearly_count";
    $db = new Db();
    $db->query($sql_clean_current_count);
    $db->query($sql_clean_today_count);
    $db->query($sql_clean_yearly_count);
}
?>
