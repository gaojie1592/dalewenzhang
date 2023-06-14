<div class="card border-0 bg-white mb-3">
    <div class="card-body">
        <p class="card-title"><?php _e('相似文章', 'dale6_com'); ?></p>
        <?php $post_num = 8;
        $exclude_id = $post->ID;
        $posttags = get_the_tags();
        $i = 0;
        if ($posttags) {
            $tags = '';
            foreach ($posttags as $tag) $tags .= $tag->term_id . ',';
            $args = array('post_status' => 'publish', 'tag__in' => explode(',', $tags), 'post__not_in' => explode(',', $exclude_id), 'ignore_sticky_posts' => 1, 'orderby' => 'comment_date', 'posts_per_page' => $post_num,);
            query_posts($args);
            while (have_posts()) {
                the_post(); ?>
                <p class="card-text"><a rel="bookmark" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a></p>
            <?php $exclude_id .= ',' . $post->ID;
                $i++;
            }
            wp_reset_query();
        }
        if ($i < $post_num) {
            $cats = '';
            foreach (get_the_category() as $cat) $cats .= $cat->cat_ID . ',';
            $args = array('category__in' => explode(',', $cats), 'post__not_in' => explode(',', $exclude_id), 'ignore_sticky_posts' => 1, 'orderby' => 'comment_date', 'posts_per_page' => $post_num - $i);
            query_posts($args);
            while (have_posts()) {
                the_post(); ?>
                <p class="card-text"><a rel="bookmark" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a></p>
        <?php $i++;
            }
            wp_reset_query();
        } ?>
        <?php if ($i == 0) : ?>
            <p class="card-text"><?php _e('没有找到相关文章,请自行搜索更靠谱!', 'dale6_com') ?></p>
        <?php endif ?>
    </div>
</div>