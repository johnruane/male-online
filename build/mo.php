<?php

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

function queryLinks($ary_of_links) {
    $query_results = array();
    $results = array();
    global $frequencey;
    // $bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','curves','enviable','endless legs','eye-popping','figure-hugging','flat stomach','flashes','flashing','flaunt','flaunts','fuller','gushes','gym','leggy','midriff','perky','pert','pins','plunging','postirior','pout','racy','revealing','saucy','scantly','scanty','sexy','showcase','showcases','sideboob','sizable','sizzle','sizzles','sizzling','skimpy','skin-tight','skinny','slim','slender','steamy','super-slim','surgically-enhanced','thigh','teases','toned','trim','underboob','yummy','vamp'];
    $bad_words = ['ageless','as','tennis'];

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

            $node_text_ary = explode(' ', $node_text);

            foreach ($bad_words as $word) {
                // if ( stripos("/\b".$node_text."\b/i", $word) ) {
                foreach ($node_text_ary as $text) {
                    //echo $text.$word.'<br />';
                    if (strcasecmp($text, $word) == 0) {

                        array_push($frequencey, strtolower($word));
                        $result['date'] = str_replace('.html', '', str_replace('day_', '', end(explode('/', $link))));

                        $new = str_replace($word, 'TEST', $node_text);
                        echo $new;

                        $result['text'] = $node_text;

                        $node = $xpath->query("descendant::a/attribute::href", $article);
                        $result['link'] = $node->item(0)->nodeValue;

                        if ( array_key_exists ( $word , $results ) ) {
                            array_push($query_results[$word], $result);
                        } else {
                            $query_results[$word][] = $result;
                        }
                    }
                }
            }
        }
    }
    return $query_results;
}

function runCountAgainstLink() {
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

function countYearAndStore() {
    $years = ['1994', '1995', '1996'];
    foreach($years as $year) {
        
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
