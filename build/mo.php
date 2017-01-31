<?php

$years = ['1994', '1995', '1996'];

//Create table
$sql_create_count_table = "CREATE TABLE current_count (
    entry_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    publication_date DATE,
    word VARCHAR(20),
    count INT(3),
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
    5 => ['world', 'sheer'],
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
function queryLinks($ary_of_links) {
    global $bad_words;
    $matched_articles = array();

    foreach ($ary_of_links as $link) {
        $html = file_get_contents($link);
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $internalErrors = libxml_use_internal_errors(true); // set error level
        $dom->loadHTML($html);
        libxml_use_internal_errors($internalErrors); // Restore error level

        $xpath = new DomXpath($dom);
        $articles = $xpath->query('//ul[contains(concat(" ", normalize-space(@class), " "), " archive-articles ")]/li');

        foreach ($articles as $article) {
            $node = $xpath->query("descendant::a", $article);
            $node_text = $node->item(0)->textContent;
            preg_replace('/\b[A-Za-z0-9]{1,x}\b\s?/i', '', $node_text);
            $article_found = searchForWordFrequency($node_text, $bad_words, [$link, $article, $xpath]);
            if ($article_found) array_push($matched_articles, $article_found);
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

    $article_string_array = explode(' ', $article_string);
    foreach ($article_string_array as $article_word) { // loops through words from the article headline

        if (isset($list_of_bad_words[strlen($article_word)])) { // does the 'article word' have any matching 'bad words' (by length)

            foreach ($list_of_bad_words[strlen($article_word)] as $badword) { // loops over matching 'bads words'
                if (strcasecmp($article_word, $badword) == 0) { // case-insensitive string comparison
                    /*
                        $article_info[0] = $link
                        $article_info[1] = $article
                        $article_info[2] = $xpath
                    */
                    if ($article_info) {
                        $linkURL = explode('/', $article_info[0]);
                        $matched_article['date'] = str_replace('.html', '', str_replace('day_', '', end($linkURL))); // get the date from the article url
                        $matched_article['word'] = $badword;
                        $node = $article_info[2]->query("descendant::a/attribute::href", $article_info[1]);
                        $matched_article['link'] = $node->item(0)->nodeValue;
                        // echo $matched_article['link'];
                        // echo $matched_article['word'];
                        // echo $matched_article['date'];

                        //array_push($found_words_array, $matched_article);

                        // if ( array_key_exists ( $badword , $found_words_array ) ) {
                        //     array_push($found_words_array[$badword], $matched_article);
                        // } else {
                        //     $found_words_array[$badword] = $matched_article;
                        // }
                        return $matched_article;
                    } else {
                        array_push($found_words_array, $badword);
                    }
                }
            }
        }
    }
}

function writeArrayToDB($q_links) {
    $db = new Db();
    $sql = "INSERT INTO current_count (publication_date, word, count, articles) VALUES (?, ?, ?, ?)";
    $stmt = $db->connect()->prepare($sql);

    foreach($q_links as $value) {
        $count = 1;
        $stmt->bind_param("ssis", $value['date'], $value['word'], $count, $value['link']);
        $stmt->execute();
    }
}

function runCountAgainstLink() {
    global $years;
    $sql = "INSERT INTO current_count (publication_date, word, count, articles) VALUES (?, ?, ?, ?)";
    $stmt = $db->connect()->prepare($sql);

    foreach($q_links as $key => $value) {
        $word = strtolower($key);
        foreach($value as $v) {
            $articles .= $v['text'].$v['link'].";";
        }
        $count = $frequencycount[$word];

        $stmt->bind_param("ssis", $v['date'], $word, $count, $articles);
        $stmt->execute();
    }
}

function yearlyWordCount() {
    global $years;
    $sql_select_yearly = "SELECT SUM(count) AS 'total', word FROM current_count WHERE publication_date BETWEEN ? AND ? GROUP BY word";

    $db = new Db();

    foreach($years as $year) {
        if ( $stmt = $db->connect()->prepare($sql_select_yearly) ) {
            $start_date = $year.'-01-01';
            $end_date = $year.'-12-31';
            $stmt->bind_param("ss", $start_date, $end_date);
            $stmt->execute();
            $result = $stmt->get_result();
            storeYearlyWordCount($year, $result);
        } else {
            echo $db->error();
        }
    }
}

function storeYearlyWordCount($year, $result) {
    $sql_count_yearly = "INSERT INTO yearly_count (year, word, count) VALUES (?,?,?)";

    $db = new Db();

    if ( $stmt = $db->connect()->prepare($sql_count_yearly) ) {
        while($row = mysqli_fetch_assoc($result)) {
           $word = $row['word'];
           $count = $row['total'];
           $stmt->bind_param("ssi", $year, $row['word'], $row['total']);
           $stmt->execute();
        }
    } else {
        echo $db->error();
    }
}

// class FrequencyCount {
// 		function add_entry($frquencyArr) {
//             $sql = "INSERT INTO demo (
//                 'id',
//                 'tennis',
//                 'china') VALUES (
//                     '1',
//                     $frquencyArr['tennis'],
//                     $frquencyArr['china'])";
// 		 }
// }
?>
