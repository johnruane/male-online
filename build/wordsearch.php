<?php
    ini_set("error_reporting","-1");
    ini_set("display_errors","On");
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");

    $article_string = "Lorem Ipsum is simply skinny dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, sizzling when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic perky typesetting, remaining essentially unchanged. flashing It was popularised in the 1960s with the release of Letraset skin-tight sheets containing Lorem Ipsum passages, skinny and more recently with desktop publishing skinny software like Aldus flashing PageMaker including versions of Lorem Ipsum.";

    $res = array_count_values(searchForWordFrequency($article_string, $list_of_bad_words, []));
    var_dump($res);
?>
