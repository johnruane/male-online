<?php
    require_once("resources/mo.php");
    require_once("resources/conf.php");
    require_once("resources/db.php");
?>
<?php
    $matched_articles = searchArticlesForBadWords([$mo_homepage_url], $archive_homepage);
    $array_matched_words = array();
    foreach ($matched_articles as $article):
        array_push($array_matched_words, $article['word']);
    endforeach;
    $array_matched_words_count = array_count_values($array_matched_words);
    arsort($array_matched_words_count);
?>
<div class="today-grid">
    <?php foreach ($array_matched_words_count as $word => $count): ?>
        <div class="today-list-item card">
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
            <div id="<?php echo $word ?>" class="daily-article-wrapper">
              <div class="today-word-articles-text">
                <div>
                <div class="thumbnail-placeholder"></div>
                  <span class="article-text" data-highlighter="<?php echo $word ?>"></span>
                  <span class="article-link"></span>
                </div>
              </div>
                <div class="today-word-articles-images" data-toggle="today-article">
                    <?php foreach ($matches as $list): ?>
                        <img class="article-list-item lazyload" data-src="<?php echo $list['thumbnail_link'] ?>" data-article="<?php echo $list['article_text'] ?>" data-href="<?php echo $mo_domain.$list['article_link'] ?>">
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
