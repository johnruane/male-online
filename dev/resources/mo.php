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

$bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','curves','enviable','endless legs','eye-popping','figure-hugging','flat stomach','flashes','flashing','flaunt','flaunts','fuller','gushes','gym','leggy','midriff','perky','pert','pins','plunging','postirior','pout','racy','revealing','saucy','scantly','scanty','sexy','showcase','showcases','sideboob','sizable','sizzle','sizzles','sizzling','skimpy','skin-tight','skinny','slim','slender','steamy','super-slim','surgically-enhanced','thigh','teases','toned','trim','underboob','yummy','vamp'];

/*
    Gets all the links from a Yearly archive page and returns them as an array
*/
function getLinks($url, $query) {
    $link_results = array();
    $html = file_get_contents($url);
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DomXpath($dom);

    $links = $xpath->query($query);

    foreach ($links as $article) {
    	$node = $xpath->query("descendant::a/attribute::href", $article);
    	array_push($link_results, "http://www.dailymail.co.uk" . $node->item(0)->textContent);
    }
    return $link_results;
}

/*
    Takes an array for links, navigates to the articles for that link and searchs for a word in the link text.
*/
function queryLinks($ary_of_links) {
    global $bad_words;

    foreach ($ary_of_links as $link) {
        $html = file_get_contents($link);
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $xpath = new DomXpath($dom);

        $articles = $xpath->query('//ul[contains(concat(" ", normalize-space(@class), " "), " archive-articles ")]/li');

        foreach ($articles as $article) {
            $node = $xpath->query("descendant::a", $article);
            $node_text = $node->item(0)->textContent;
            preg_replace('/\b[A-Za-z0-9]{1,x}\b\s?/i', '', $node_text);
            $results = searchForWordFrequency($node_text, $bad_words, [$link, $article]);
        }
    }
    return $results;
}

function searchForWordFrequency($article_string, $list_of_bad_words, $article_info) {
    global $list_of_bad_words;
    $found_words_array = array();

    $article_string_array = explode(' ', $article_string);
    foreach ($article_string_array as $article_word) { // loops through words from the article headline

        if (isset($list_of_bad_words[strlen($article_word)])) { // does the 'article word' have any matching 'bad words' (by length)

            foreach ($list_of_bad_words[strlen($article_word)] as $badword) { // loops over matching 'bads words'
                if (strcasecmp($article_word, $badword) == 0) { // case-insensitive string comparison
                    if ($article_info) {
                        echo('info');
                        $matched_article['date'] = str_replace('.html', '', str_replace('day_', '', end(explode('/', $article_info[0]))));
                        $node = $xpath->query("descendant::a/attribute::href", $article_info[1]);
                        $matched_article['link'] = $node->item(0)->nodeValue;

                        if ( array_key_exists ( $badword , $found_words_array ) ) {
                            array_push($found_words_array[$badword], $matched_article);
                        } else {
                            $found_words_array[$badword][] = $matched_article;
                        }
                    } else {
                        echo('freq');
                        array_push($found_words_array, $badword);
                    }
                }
            }
        }
    }
    return $found_words_array;
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
