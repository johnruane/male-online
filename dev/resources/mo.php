<?php

// $bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','curves','enviable','endless legs','eye-popping','figure-hugging','flat stomach','flashes','flashing','flaunt','flaunts','fuller','gushes','gym','leggy','midriff','perky','pert','pins','plunging','postirior','pout','racy','revealing','saucy','scantly','scanty','sexy','showcase','showcases','sideboob','sizable','sizzle','sizzles','sizzling','skimpy','skin-tight','skinny','slim','slender','steamy','super-slim','surgically-enhanced','thigh','teases','toned','trim','underboob','yummy','vamp'];

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
    $bad_words = ['TENNIS','china'];

    foreach ($ary_of_links as $link) {
        $html = file_get_contents($link);
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $xpath = new DomXpath($dom);

        $articles = $xpath->query('//ul[contains(concat(" ", normalize-space(@class), " "), " archive-articles ")]/li');

        foreach ($articles as $article) {

            $node = $xpath->query("descendant::a", $article);
            $node_text = $node->item(0)->textContent;

            foreach ($bad_words as $word) {
                if ( stripos("/\b".$node_text."\b/i", $word) ) {

                    array_push($frequencey, strtolower($word));

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
    return $query_results;
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
