<?php get_header(); ?>

<body <?php body_class(); ?>>
    <?php get_template_part('toolbar'); ?>
    <div class="container mt-3">
        <?php get_template_part('tools/sousuo'); ?>
        <div class="row">
            <div class="col-lg-12">
                <!-- 文章排列 -->
                <?php if (have_posts()) : ?>
                    <?php get_template_part('includes/content'); ?>
                    <?php get_template_part('tools/page_yema'); ?>
                <?php else : ?>
                    <?php get_template_part('includes/content-none'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php get_footer() ?>
</body>

</html>