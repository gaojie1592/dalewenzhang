<?php while (have_posts()) : the_post(); ?>
    <div class="border-bottom bg-white d-flex flex-column position-relative p-3">
        <div class="mb-1">
            <div class="text-muted float-start"><?= get_the_author(); ?></div>
            <div class="text-muted float-end fw-light"><?php the_time('Y.m.d'); ?></div>
        </div>
        <h5>
            <?php $seo_arr = get_post_meta($post->ID, 'dale6_com_post_seo', true); ?>
            <a class="dl_a stretched-link text-decoration-none" href="<?php the_permalink(); ?>"><?= isset($seo_arr['biaotibiaoqian']) ? $seo_arr['biaotibiaoqian'] : '' ?>"<?= mb_strimwidth(strip_tags(apply_filters('dale6_com_the_title', get_the_title())), 0, 34, "..."); ?></a>
        </h5>
        <span class="mb-1 text-muted fw-light"><?= apply_filters('dale6_com_the_excerpt', get_the_content()); ?></span>
        <div class="d-flex align-items-center me-3 dsw-80">
            <span class="dale6_svg_eye wh-16px px-2"></span>
            <span class="text-muted fw-light mx-2">
                <?= dale6_com_the_views(); ?>
            </span>
            <span class="dale6_svg_up wh-16px px-2"></span>
            <span class="text-muted fw-light mx-2">
                <?= apply_filters('dale6_com_post_top', $post->ID); ?>
            </span>
            <span class="dale6_svg_pinglun wh-16px px-2"></span>
            <span class="text-muted fw-light mx-2">
                <?= get_comments_number(); ?>
            </span>
        </div>
    </div>
<?php endwhile; ?>
