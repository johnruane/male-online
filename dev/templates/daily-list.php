<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
?>
<div class="mo-results-list">
    <h2 class="results-heading">Today</h2>
    <?php $dailyResults = getDailyCount(); ?>
    <?php if ($dailyResults) { ?>
        <ul class="mo-daily-list">
            <?php foreach ($dailyResults as $row): ?>
                <li class="mo-list-item" data-toggle="collapse" data-target="#<?php echo $row['word'] ?>"><span class="word-key"><?php echo $row['word'] ?></span>
                    <span class="word-value"><?php echo $row['total'] ?></span>
                </li>
                <div id="<?php echo $row['word'] ?>" class="daily-article-wrapper" style="display:none">
                    <ul class="daily-article-list">
                        <?php $articleResults = getDailyArticlesFromWord($row['word']); ?>
                        <?php foreach ($articleResults as $list): ?>
                            <li class="article-list-item"><a href="<?php echo $mo_home_domain ?><?php echo $list['article_link'] ?>" target="_blank">
                                <img src="<?php echo $list['thumbnail_link'] ?>">
                                <span><?php echo $list['article_text'] ?></span>
                            </a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
        </ul>
    <?php } ?>
</div>
