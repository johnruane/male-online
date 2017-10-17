<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
?>
<?php
    $matched_articles = searchArticlesForBadWords([$mo_homepage_url], $xpath_today);
    $array_matched_words = array();
    foreach ($matched_articles as $article):
        array_push($array_matched_words, $article['word']);
    endforeach;
    $array_matched_words_count = array_count_values($array_matched_words);
    arsort($array_matched_words_count);
    // var_dump($matched_articles);
?>
<div class="today-grid">
    <?php foreach ($array_matched_words_count as $word => $count): ?>
        <div class="today-list-item card" data-toggle="collapse" data-target="#<?php echo $row['word'] ?>" aria-expanded="false">
            <div class="item-card-heading">
                <h2 class="word-key"><?php echo $word ?></h2>
                <span class="today-times-display">
                    <span class="word-value"><?php echo $count ?></span>
                    <span class="small">
                        <?php if ($count > 1) { ?>
                            times
                        <?php } else { ?>
                            time
                        <?php } ?>
                    </span>
                </span>
            </div>
            <?php $matches = getMatchedArticlesFromWord($matched_articles, $word) ?>
            <div id="<?php echo $word ?>" class="daily-article-wrapper" data-highlighter="<?php echo $word ?>">
                <div class="today-word-articles-text">
                    <div id="<?php echo $word ?>-thumbnail-placeholder" class="thumbnail-placeholder"></div>
                    <?php $index=0 ?>
                    <?php foreach ($matches as $list): ?>
                        <div id="<?php echo $word ?>-word-<?php echo $index ?>" class="article-text">
                            <span class="article-text-span"><?php echo $list['article_text'] ?></span>
                            <a class="graph-link" href="<?php echo $mo_domain ?><?php echo $list['article_link'] ?>" target="_blank">Go to full article</a>
                        </div>
                        <?php $index++ ?>
                    <?php endforeach ?>
                    </div>
                <div class="today-word-articles-images">
                    <?php $index=0 ?>
                    <?php foreach ($matches as $list): ?>
                        <img data-id="#<?php echo $word ?>-word-<?php echo $index ?>" data-toggle="trends-reveal" class="article-list-item lazyload" data-src="<?php echo $list['thumbnail_link'] ?>">
                        <?php $index++ ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
