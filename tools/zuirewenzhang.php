<div class="card border-0 bg-white mb-3">
    <div class="card-body fw-light">
        <?php
        // $lastposts = get_posts(array(
        //     'fields' => '*',
        //     'post_password'       => '',
        //     'post_status'         => 'publish',
        //     'ignore_sticky_posts' => 1, // 排除置顶文章. 
        //     'orderby'             => 'meta_value_num',
        //     'posts_per_page'      => 10,
        //     'meta_key'            => 'views',
        // ));
        global $wpdb;
        // file_put_contents(__DIR__ . '/tmp', print_r($wpdb, true), FILE_APPEND);
        // SELECT * FROM wp_posts INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1 = 1 AND ( wp_postmeta.meta_key = 'views' ) AND wp_posts.post_password = '' AND wp_posts.post_type = 'post' AND (( wp_posts.post_status = 'publish' )) GROUP BY wp_posts.ID ORDER BY wp_postmeta.meta_value + 0 DESC LIMIT 0, 10
        $res = $wpdb->get_results("SELECT $wpdb->posts.ID,$wpdb->posts.post_title,$wpdb->postmeta.meta_value FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id ) WHERE 1 = 1 AND ( $wpdb->postmeta.meta_key = 'views' ) AND $wpdb->posts.post_password = '' AND $wpdb->posts.post_type = 'post' AND (( $wpdb->posts.post_status = 'publish' )) GROUP BY $wpdb->posts.ID ORDER BY $wpdb->postmeta.meta_value + 0 DESC LIMIT 0, 10");
        $a = 1;
        if (count($res) > 0) :
        ?>
            <p class="card-title"><?php _e('最热文章', 'dale6_com'); ?></p>
            <?php foreach ($res as $posta) : ?>
                <div class="d-flex py-1">
                    <span class="align-self-center me-2">
                        <?php echo $a++; ?>.
                    </span>
                    <p class="flex-grow-1 text-truncate card-text mb-0">
                        <?php
                        $title = trim($posta->post_title);
                        if (empty($title)) $title = __('未知标题', 'dale6_com');
                        ?>
                        <a class="dl_a text-decoration-none" target="_blank" href="<?php echo get_permalink($posta->ID); ?>"><?php echo $title; ?></a>
                    </p>
                    <span class="text-muted align-self-center ms-2">
                        <?php echo $posta->meta_value; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="card-text"><?php _e('没有文章,请发布文章', 'dale6_com'); ?></p>
        <?php endif; ?>
    </div>
</div>