<?php
    function searchForWord($node_text) {
        $bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','curves','enviable','endless legs','eye-popping','figure-hugging','flat stomach','flashes','flashing','flaunt','flaunts','fuller','gushes','gym','leggy','midriff','perky','pert','pins','plunging','postirior','pout','racy','revealing','saucy','scantly','scanty','sexy','showcase','showcases','sideboob','sizable','sizzle','sizzles','sizzling','skimpy','skin-tight','skinny','slim','slender','steamy','super-slim','surgically-enhanced','thigh','teases','toned','trim','underboob','yummy','vamp'];
        $results = array();
        $frequencey = array();

        foreach ($bad_words as $word) {
            if ( stripos($node_text, ' ' . $word . ' ') !== false ) {

                array_push($frequencey, strtolower($word));

                $result['main'] = $node_text;

                $node = $xpath->query("descendant::a/attribute::href", $article);
                $result['link'] = $node->item(0)->nodeValue;

                if ( array_key_exists ( $word , $results ) ) {
                    array_push($results[$word], $result);
                } else {
                    $results[$word][] = $result;
                }
            }
        }
        var_dump($results);
        return $results;
    }
?>
