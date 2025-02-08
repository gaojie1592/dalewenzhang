<?php
/*
Template Name: [大乐主题]留言板
*/
?>
<?php get_header(); ?>

<body>
    <?php get_template_part('toolbar'); ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-9">
                <?php $lastposts = get_posts(array(
                    'post_status'         => 'publish',
                    'post_type'           => 'dale6_com_liuyanban',
                    'posts_per_page'      => 1,
                ));
                if ($lastposts) : ?>
                        <?php foreach ($lastposts as $k => $post) : ?>
                            <?php setup_postdata($post); ?>
                            <div class="accordion-item">
                                <div class="accordion-header" id="dale6_<?php echo $k ?>">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#dale6_t_<?php echo $k ?>" aria-expanded="true" aria-controls="dale6_t_<?php echo $k ?>">
                                        <h3 class="mb-0"><?php the_title(); ?></h3>
                                        <span class="text-muted ps-2">——<?php the_time('Y.m.d'); ?></span>
                                    </button>
                                </div>
                                <div id="dale6_t_<?php echo $k ?>" class="accordion-collapse collapse <?php echo $k > 0 ? '' : 'show' ?>" aria-labelledby="dale6_<?php echo $k ?>">
                                    <div class="accordion-body">
                                        <?php dale6_the_content($post->post_content); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-3">
                <?php get_template_part('template/tools_you'); ?>
            </div>
        </div>
    </div>

    <?php wp_footer() ?>
    <?php get_footer() ?>

</body>

</html>