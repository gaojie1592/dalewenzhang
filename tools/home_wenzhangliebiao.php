<?php
// 获取所有置顶文章
$sticky_posts = get_option('sticky_posts');
if (is_array($sticky_posts) && count($sticky_posts) > 0) $sticky_posts = array_flip($sticky_posts);
?>
<?php while (have_posts()) : the_post(); ?>
    <div class="border-bottom bg-white d-flex flex-column position-relative p-3">
        <div class="mb-1">
            <div class="text-muted float-start"><?php echo get_the_author(); ?></div>
            <div class="text-muted float-end fw-light"><?php the_time('Y.m.d'); ?></div>
        </div>
        <h5>
            <?php $seo_arr = get_post_meta($post->ID, 'dalewenzhang_post_seo', true); ?>
            <a class="dl_a stretched-link text-decoration-none" href="<?php the_permalink(); ?>">
                <?php if (isset($sticky_posts[$post->ID])) : ?>
                    <i class="bi bi-pin-angle"></i>
                <?php endif; ?>
                <?php echo isset($seo_arr['biaotibiaoqian']) ? $seo_arr['biaotibiaoqian'] : '' ?>
                <?php echo mb_strimwidth(strip_tags(apply_filters('dalewenzhang_the_title', get_the_title())), 0, 34, "..."); ?>
            </a>
        </h5>
        <span class="mb-1 text-muted fw-light"><?php echo apply_filters('dalewenzhang_the_excerpt', get_the_content()); ?></span>
        <div class="d-flex align-items-center me-3 dsw-80">
            <i class="bi bi-chat-square-text"></i>
            <span class="text-muted fw-light mx-2">
                <?php echo get_comments_number(); ?>
            </span>
        </div>
    </div>
<?php endwhile; ?>