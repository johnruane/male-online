<?php
/**
 * @author James Morris <james@jmoz.co.uk>
 */

$html = file_get_contents('mail.html');
$doc = new DomDocument();
$doc->loadHTML($html);
$xpath = new DomXpath($doc);

$articles = $xpath->query('//div[contains(@class,"article")]');
$articlesresults = array();

foreach ($articles as $entry) {

	$node = $xpath->query("//a", $entry);
	$result['a'] = $node->item(0)->nodeValue;

	$node = $xpath->query("//p", $entry);
	$result['p'] = $node->item(0)->textContent;

    $results[] = $result;
}
print_r($results);

// $listarticles = $xpath->query('//div[@class="femail"]');
// $listarticlesresults = array();
//
// foreach ($listarticles as $entry) {
// 	$node = $xpath->query('//span[@class="pufftext"]', $entry);
// 	$listarticlesresults['image_href'] = $node->item(0)->textContent;
// }
// print_r($listarticlesresults);
