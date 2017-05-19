<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
?>
<?php $dailyResults = getDailyCount(); ?>
<?php if ($dailyResults) { ?>
    <div class="today-grid">
        <?php foreach ($dailyResults as $row): ?>
            <div class="today-list-item card" data-toggle="collapse" data-target="#<?php echo $row['word'] ?>" aria-expanded="false">
                <div class="item-card-heading">
                    <h2 class="word-key"><?php echo $row['word'] ?></h2>
                    <span class="today-times-display">
                        <span class="word-value"><?php echo $row['total'] ?></span>
                        <span class="small">
                            <?php if ($row['total'] > 1) { ?>
                                times
                            <?php } else { ?>
                                time
                            <?php } ?>
                        </span>
                    </span>
                </div>
                <div id="<?php echo $row['word'] ?>" class="daily-article-wrapper">
                    <?php $articleResults = getDailyArticlesFromWord($row['word']); ?>
                    <div class="today-word-articles-text">
                        <?php $index=0 ?>
                        <?php foreach ($articleResults as $list): ?>
                            <div id="trends-word-<?php echo $index ?>" class="article-text">
                                <span><?php echo $list['article_text'] ?></span>
                                <a href="<?php echo $mo_home_domain ?><?php echo $list['article_link'] ?>" target="_blank">Read</a>
                            </div>
                            <?php $index++ ?>
                        <?php endforeach ?>
                    </div>
                    <div class="today-word-articles-images">
                        <?php $index=0 ?>
                        <?php foreach ($articleResults as $list): ?>
                            <img data-id="#trends-word-<?php echo $index ?>" data-toggle="trends-reveal" class="article-list-item" src="<?php echo $list['thumbnail_link'] ?>">
                            <?php $index++ ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php } ?>
