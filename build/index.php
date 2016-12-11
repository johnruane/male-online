<?php
    // ini_set('display_errors', 'On');
    // ini_set('html_errors', 0);

    $bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','curves','enviable','endless legs','eye-popping','figure-hugging','flat stomach','flashes','flashing','flaunt','flaunts','fuller','gushes','gym','leggy','midriff','perky','pert','pins','plunging','postirior','pout','racy','revealing','saucy','scantly','scanty','sexy','showcase','showcases','sideboob','sizable','sizzle','sizzles','sizzling','skimpy','skin-tight','skinny','slim','slender','steamy','super-slim','surgically-enhanced','thigh','teases','toned','trim','underboob','yummy','vamp'];
    $article_results = array();
    $frequencey = array();

    $html = file_get_contents('http://www.dailymail.co.uk');
    //$html = file_get_contents('mail.html');
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DomXpath($dom);

    $articles = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " article ")]');
    $results = array();
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

    			if ( array_key_exists ( $word , $results ) ) {
    				array_push($results[$word], $result);
    			} else {
    				$results[$word][] = $result;
    			}
    		}
    	}
    }

    $list_articles = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), "femail")]//li | //div[contains(concat(" ", normalize-space(@class), " "), "tvshowbiz")]//li');

    foreach ($list_articles as $article) {

    	$node = $xpath->query('descendant::span[@class="pufftext"]', $article);
    	$node_text = $node->item(0)->textContent;

    	foreach ($bad_words as $word) {

    		if ( stripos($node_text, ' ' . $word . ' ') !== false ) {
    			array_push($frequencey, strtolower($word));

    			$result['main'] = $node_text;

    			$node = $xpath->query('descendant::a/attribute::href', $article);
    			$result['link'] = $node->item(0)->nodeValue;

    			$node = $xpath->query('descendant::img/attribute::data-src', $article);
    			$result['img'] = $node->item(0)->nodeValue;

    			if ( array_key_exists ( $word , $results ) ) {
    				array_push($results[$word], $result);
    			} else {
    				$results[$word][] = $result;
    			}
    		}
    	}
    }

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

  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Eczar:800" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
    <nav class="title-wrapper">
        <h1>The <span>Male</span> Online</h1>
    </nav>
    <div class="content-wrapper">
        <div>
            <?php foreach ($frequencycount as $fkey => $fvalue):  ?>
                    <div class="word-wrapper" data-collapse="<?php echo $fkey ?>">
                        <p class="word">
                            <span class="word-key"><?php echo $fkey ?></span>
                            <span class="word-value"><?php echo $fvalue; ?></span>
                        </p>
                        <div id="<?php echo $fkey; ?>" class="keyword-wrapper">
                            <ul class="article-list">
                                <?php foreach($results[$fkey] as $mkey => $value): ?>
                                    <li><a href="<?php echo $value['link'] ?>">
                                            <img src="<?php echo $value['img'] ?>">
                                            <p><?php echo $value['main'] ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

<script>
$(function() {
    $('[data-collapse]').on('click', function() {
        var $moList = $(this).children('.keyword-wrapper')
        if ( $moList.is(':visible') ) {
            $($moList).slideUp(300);
        } else {
            $('.keyword-wrapper').slideUp(300);
            $($moList).slideDown(300);
        }
    });
});

</script>
