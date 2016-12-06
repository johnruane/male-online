<?php
/**
 * @author James Morris <james@jmoz.co.uk>
 */

$html = <<<'EOF'
<html>
	<body>
		<h1>Foo</h1>
		<div id="content">
			<div class="foo">
				<div>
					<img class="fooimage" src="http://foo.com/bar.png" />
				</div>
					<p class="description">Foo bar</p>
			</div>
			<div class="foo">
				<div><img class="fooimage" src="http://foo.com/baz.png" /></div>
				<p class="description">Baz bat</p>
			</div>
		</div>
	</body>
</html>
EOF;
$doc = new DomDocument();
$doc->loadHTML($html);
$xpath = new DomXpath($doc);
$entries = $xpath->query("//div[@id='content']/div[@class='foo']");
$results = array();
foreach ($entries as $entry) {

    // pass in the $entry node as the context node, the the query is relative to it

    $node = $xpath->query("div/img[@class='fooimage']/attribute::src", $entry); // returns a DOMNodeList
    $result['image_src'] = $node->item(0)->value; // get the first node in the list which is a DOMAttr

    $node = $xpath->query("descendant::p[@class='description']", $entry);
    $result['desc'] = $node->item(0)->nodeValue;

    $results[] = $result;
}
print_r($results);
