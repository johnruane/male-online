<?php
    // ini_set('display_errors', 'On');
    // ini_set('html_errors', 0);

    //$bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','enviable','eye-popping','figure-hugging','flashes','flashing','flaunt','flaunts','gushes','gym','leggy','midriff','perky','pert','plunging','postirior','racy','revealing','scantly','scanty','sexy','showcase','showcases','skimpy','sideboob','sizable','sizzle','sizzling','skimpy','skin-tight','slim','steamy','thigh','toned','underboob','yummy'];
    $bad_words = ['sizzling','slim'];
    $article_results = array();
    $frequencey = array();

    //$html = file_get_contents('http://mailonline.co.uk');
    $html = file_get_contents('mail.html');
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DomXpath($dom);

    $articles = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " article ")]');
    $article_results = array();
    $frequencey = array();

    foreach ($articles as $article) {
    	$node = $xpath->query("descendant::p | descendant::a", $article);
    	$node_text = $node->item(0)->textContent;

    	foreach ($bad_words as $word) {
    		if ( stripos($node_text, ' ' . $word . ' ') !== false ) {

    	 		array_push($frequencey, strtolower($word));

                $node = $xpath->query("descendant::a", $article);
    			$result['main'] = $node->item(0)->textContent;

                $node = $xpath->query("descendant::a/attribute::href", $article);
                $result['link'] = $node->item(0)->nodeValue;

    			// $node = $xpath->query("descendant::p", $article);
    		    // $result['p'] = $node->item(0)->textContent;

    			$node = $xpath->query("descendant::img/attribute::data-src", $article);
    			$result['img'] = $node->item(0)->nodeValue;

    			if ( array_key_exists ( $word , $article_results ) ) {
    				array_push($article_results[$word], $result);
    			} else {
    				$article_results[$word][] = $result;
    			}
    		}
    	}
    }

    $list_articles = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), "femail")]//li | //div[contains(concat(" ", normalize-space(@class), " "), "tvshowbiz")]//li');
    $list_results = array();

    foreach ($list_articles as $article) {

    	$node = $xpath->query('descendant::span[@class="pufftext"]', $article);
    	$node_text = $node->item(0)->textContent;

    	foreach ($bad_words as $word) {

    		if ( stripos($node_text, $word) !== false ) {
    			array_push($frequencey, strtolower($word));

    			$result['main'] = $node->item(0)->textContent;

    			$node = $xpath->query('descendant::a/attribute::href', $article);
    			$result['link'] = $node->item(0)->nodeValue;

    			$node = $xpath->query('descendant::img/attribute::data-src', $article);
    			$result['img'] = $node->item(0)->nodeValue;

    			if ( array_key_exists ( $word , $list_results ) ) {
    				array_push($list_results[$word], $result);
    			} else {
    				$list_results[$word][] = $result;
    			}
    		}
    	}
    }
    //array merge issue
    $merge_results = array_merge($article_results, $list_results);


    $frequencycount = array_count_values($frequencey);
    arsort($frequencycount);

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
    <div class="title-wrapper">
        <h1>The Male Online</h1>
    </div>
    <div class="content-wrapper">
        <?php foreach ($frequencycount as $fkey => $fvalue):  ?>
                <p onClick="openLinks('<?php echo $fkey; ?>')"><span><?php echo $fkey . " " . $fvalue; ?></span></p>
                <div id="<?php echo $fkey; ?>" class="keyword-wrapper">
                    <ul class="article-list">
                        <?php foreach($merge_results[$fkey] as $mkey => $value): ?>
                            <li><a href="<?php echo $value['href'] ?>"><img src="<?php echo $value['img'] ?>"><p><?php echo $value['main'] ?></p></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<script>
    function openLinks(id) {
        document.getElementById(id).classList.toggle('active');
    }
</script>
