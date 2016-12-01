<?php

// ini_set('display_errors', 'On');
// ini_set('html_errors', 0);

$arr = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','enviable','eye-popping','figure-hugging','flashes','flashing','flaunt','flaunts','gushes','gym','leggy','midriff','perky','pert','plunging','postirior','racy','revealing','scantly','scanty','sexy','showcase','showcases','skimpy','sideboob','sizable','sizzle','sizzling','skimpy','skin-tight','slim','steamy','thigh','toned','underboob','yummy'];
$matchedanchors = [];
$frequencey = [];

$html = file_get_contents('http://www.dailymail.co.uk/');
$dom = new DOMDocument;
$dom->loadHTML($html);

// foreach($arr as $a) {
//     if (strcasecmp($n, $a) == 0 && $element->getAttribute('href') != "#") {
//         array_push($matchedanchors, $n . ';' . $element->getAttribute('href'));
//     }
// }

foreach($dom->getElementsByTagName('a') as $element) {
    $node_ary = explode(' ', trim($element->nodeValue.'<br />'));
    if (is_array($node_ary)) {
        foreach($node_ary as $n) {
            foreach($arr as $a) {
                if (strcasecmp($n, $a) == 0 && $element->getAttribute('href') != "#") {
                    array_push($frequencey, strtolower($n));
                    if (array_key_exists($n, $matchedanchors)) {
                        $matchedanchors[$n] = $matchedanchors[$n] . ';' . $element->getAttribute('href');
                    } else {
                        $matchedanchors[$n] = $element->getAttribute('href');
                    }
                }
            }
        }
    }
}
array_change_key_case($matchedanchors, CASE_LOWER);
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
    <!-- <div class="title-wrapper">
        <h1>The Male Online</h1>
    </div> -->
    <div class="content-wrapper">
        <?php
            // while (list($key, $val) = each($matchedanchors)) {
            //     echo "<p><span>$key: $val</span></p>";
            // }
        ?>
    </div>
</body>
</html>
