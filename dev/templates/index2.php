<?php
    ini_set("error_reporting","-1");
    ini_set("display_errors","On");
    require_once("mo.php");

    $links = array();
    $articles = array();
    global $frequencey = array(); //global variables

    $links = getLinks('http://www.dailymail.co.uk/home/sitemaparchive/year_1994.html', '//ul[@class="split"]/li');
    $q_links = queryLinks($links);

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
                                            <?php echo $value['main'] ?>
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
