<div class="card border-0 bg-white mb-3">
    <div class="card-body fw-light">
        <?php $lastposts = get_posts(array(
            'post_password'       => '',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 0,
            'orderby'             => 'comment_count',
            'posts_per_page'      => 10,
        ));
        $a = 1;
        if ($lastposts) : ?>
            <p class="card-title"><?php _e('最多评论', 'dalewenzhang'); ?></p>
            <?php foreach ($lastposts as $posta) : ?>
                <div class="d-flex py-1">
                    <span class="align-self-center me-2">
                        <?php echo $a++; ?>.
                    </span>
                    <p class="flex-grow-1 text-truncate card-text mb-0">
                        <?php
                        $title = trim($posta->post_title);
                        if (empty($title)) $title = __('未知标题', 'dalewenzhang');
                        ?>
                        <a class="dl_a text-decoration-none" target="_blank" href="<?php echo get_permalink($posta->ID); ?>"><?php echo $title; ?></a>
                    </p>
                    <span class="text-muted align-self-center ms-2">
                        <?php echo $posta->comment_count; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="card-text"><?php _e('没有评论,请发布评论!', 'dalewenzhang'); ?></p>
        <?php endif; ?>
    </div>
</div>