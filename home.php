<?php get_header(); ?>

<body>
    <?php get_template_part('toolbar'); ?>

    <?php if (have_posts()) : ?>
        <?php get_template_part('includes/home_content'); ?>
    <?php else : ?>
        <?php get_template_part('includes/content-none'); ?>
    <?php endif; ?>

    <?php get_footer() ?>

</body>

</html>