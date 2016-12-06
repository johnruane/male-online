<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//$bad_words = ['sues','drinks'];
$bad_words = ['noword','doing','anything'];

$html = file_get_contents('mail.html');
$doc = new DomDocument();
$doc->loadHTML($html);
$xpath = new DomXpath($doc);

$articles = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " article ")]');
$article_results = array();

// foreach ($articles as $article) {
// 	$node = $xpath->query("descendant::a", $article);
//     $result['a'] = $node->item(0)->textContent;
//
// 	$node = $xpath->query("descendant::p", $article);
//     $result['p'] = $node->item(0)->textContent;
//
// 	$node = $xpath->query("descendant::img/attribute::src", $article);
// 	$result['img'] = $node->item(0)->nodeValue;
//
// 	$article_results[] = $result;
//
// }
// print_r($article_results);


$list_articles = $xpath->query('//div[@class="femail"]//li');
$list_results = array();
$frequencey = array();

foreach ($list_articles as $article) {

	$node = $xpath->query('descendant::span[@class="pufftext"]', $article);
	$node_text = $node->item(0)->textContent;

	foreach ($bad_words as $word) {
		if ( stripos($node_text, $word) !== false ) {
			array_push($frequencey, strtolower($word));

			$result['span'] = $node->item(0)->textContent;

			$node = $xpath->query('descendant::a/attribute::href', $l_article);
			$result['a_href'] = $node->item(0)->value;

			$node = $xpath->query('descendant::img/attribute::src', $l_article);
			$result['img_src'] = $node->item(0)->value;

			if ( array_key_exists ( $word , $list_results ) ) {
				array_push($list_results[$word], $result);
			} else {
				$list_results[$word] = $result;
			}
		}
	}
}

$frequencycount = array_count_values($frequencey);
arsort($frequencycount);

?>
