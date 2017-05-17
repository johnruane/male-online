<?php
    require_once("mo.php");
    require_once("conf.php");
    require_once("db.php");
?>
<?php $dailyResults = getDailyCount(); ?>
<?php if ($dailyResults) { ?>
    <ul class="reset-list today-grid">
        <?php foreach ($dailyResults as $row): ?>
            <li class="today-list-item card" data-toggle="modal" data-target="#<?php echo $row['word'] ?>" aria-expanded="false">
                <h2 class="word-key"><?php echo $row['word'] ?></h2>
                <span class="word-value"><?php echo $row['total'] ?></span>
                <span class="small">
                    <?php if ($row['total'] > 1) { ?>
                        times
                    <?php } else { ?>
                        time
                    <?php } ?>
                </span>
            </li>
            <div id="<?php echo $row['word'] ?>" class="daily-article-wrapper">
                <ul class="today-results-list reset-list">
                    <?php $articleResults = getDailyArticlesFromWord($row['word']); ?>
                    <?php foreach ($articleResults as $list): ?>
                        <li class="article-list-item"><a href="<?php echo $mo_home_domain ?><?php echo $list['article_link'] ?>" target="_blank">
                            <img src="<?php echo $list['thumbnail_link'] ?>">
                            <span class="article-text"><?php echo $list['article_text'] ?></span>
                        </a></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endforeach ?>
    </ul>
<?php } ?>
