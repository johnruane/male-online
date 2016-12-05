<?php
    // ini_set('display_errors', 'On');
    // ini_set('html_errors', 0);

    $arr = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','enviable','eye-popping','figure-hugging','flashes','flashing','flaunt','flaunts','gushes','gym','leggy','midriff','perky','pert','plunging','postirior','racy','revealing','scantly','scanty','sexy','showcase','showcases','skimpy','sideboob','sizable','sizzle','sizzling','skimpy','skin-tight','slim','steamy','thigh','toned','underboob','yummy'];
    $matchedanchors = [];
    $frequencey = [];

    $html = file_get_contents('http://mailonline.co.uk');
    $dom = new DOMDocument;
    $dom->loadHTML($html);

    foreach($dom->getElementsByTagName('a') as $element) {
        $node_ary = explode(' ', trim($element->nodeValue.'<br />'));
        foreach($node_ary as $n) {
            foreach($arr as $a) {
                if (strcasecmp($n, $a) == 0 && $element->getAttribute('href') != "#") {
                    array_push($frequencey, strtolower($n));
                    if (array_key_exists($n, $matchedanchors)) {
                        array_push($matchedanchors[$n], $element);
                    } else {
                        $matchedanchors[$n][] = $element;
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
    <div class="title-wrapper">
        <h1>The Male Online</h1>
    </div>
    <div class="content-wrapper">
        <?php foreach ($frequencycount as $fkey => $fvalue):  ?>
                <p onClick="openLinks('<?php echo $fkey; ?>')"><span><?php echo $fkey . " " . $fvalue; ?></span></p>
                <div id="<?php echo $fkey; ?>" class="keyword-wrapper">
                    <ul class="article-list">
                        <?php foreach($matchedanchors[$fkey] as $mkey => $mvalue): ?>
                            <li><a href="<?php echo $mvalue->getAttribute('href'); ?>">
                            <?php echo $mvalue->textContent; ?></a></li>
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
