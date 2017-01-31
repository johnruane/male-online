<?php
    ini_set("error_reporting","-1");
    ini_set("display_errors","On");
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");

    $article_string = "Lorem Ipsum is simply skinny dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, sizzling when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic perky typesetting, remaining essentially unchanged. flashing It was popularised in the 1960s with the release of Letraset skin-tight sheets containing Lorem Ipsum passages, skinny and more recently with desktop publishing skinny software like Aldus flashing PageMaker including versions of Lorem Ipsum.";

    $list_of_bad_words = array (
        4 => ['boob','bust','pert','pins','pout','racy','sexy','slim','trim','vamp'],
        5 => ['ample','busty','leggy','perky','saucy','thigh','toned','yummy','china'],
        6 => ['assets','curves','fuller','gushes','skimpy','skinny','steamy','teases','tennis'],
        7 => ['ageless','braless','flashes','flaunts','midriff','scantly','sizable','slender'],
        8 => ['cleavage','enviable','flashing','plunging','sideboob','sizzling'],
        9 => ['postirior','revealing','underboob'],
        10 => ['skin-tight','super-slim'],
        11 => ['eye-popping'],
        14 => ['figure-hugging']
    );
    $res = array_count_values(searchForWordFrequency($article_string, $list_of_bad_words, []));
    var_dump($res);
?>
