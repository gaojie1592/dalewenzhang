<?php get_header(); ?>

<body <?php body_class(); ?>>
    <?php get_template_part('toolbar'); ?>

    <?php
    if (function_exists('wp_body_open')) {
        wp_body_open();
    } else {
        do_action('wp_body_open');
    }
    ?>

    <?php if (have_posts()) : ?>
        <?php get_template_part('includes/home_content'); ?>
    <?php else : ?>
        <?php get_template_part('includes/content-none'); ?>
    <?php endif; ?>

    <?php get_footer() ?>

</body>

</html>