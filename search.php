<?php get_header(); ?>

<body <?php body_class(); ?>>
    <?php get_template_part('toolbar'); ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-12">
                <?php if (have_posts()) : ?>
                    <?php get_template_part('includes/content'); ?>
                    <?php get_template_part('tools/page_yema'); ?>
                <?php else : ?>
                    <?php get_template_part('includes/content-none'); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if (is_active_sidebar('xiabianlan')) : ?>
            <div class="row">
                <div class="col-12">
                    <?php dynamic_sidebar('xiabianlan'); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php get_footer() ?>
</body>

</html>