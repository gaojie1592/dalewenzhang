<div class="card border-0 bg-white mb-3">
    <div class="card-body fw-light">
        <?php $comments = get_comments(array(
            'number'  => 10,
        ));
        if ($comments) : ?>
            <p class="card-title"><?php _e('最新评论', 'dalewenzhang'); ?></p>
            <?php foreach ($comments as $comment) : ?>
                <div class="d-flex align-self-center py-1">
                    <div class="flex-shrink-0 dalewenzhang_user_ico">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($comment->user_id, array('size' => 24)) ?>" width="24" height="24" alt="<?php echo dalewenzhang_get_display_name($comment) ?>" role="img">
                    </div>
                    <?php
                    // 删除所有HTML标签
                    $comment_content = trim(strip_tags($comment->comment_content));
                    ?>
                    <a class="text-truncate card-text mb-0 ps-2 dl_a text-decoration-none" target="_blank" href="<?php echo get_permalink($comment->comment_post_ID); ?>">
                        <?php echo $comment_content; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="card-text"><?php _e('没有评论,请发布评论!', 'dalewenzhang'); ?></p>
        <?php endif; ?>
    </div>
</div>