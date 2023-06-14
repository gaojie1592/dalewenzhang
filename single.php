<?php get_header(); ?>

<body>
    <?php get_template_part('toolbar'); ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-9">
                <?php get_template_part('includes/single_content'); ?>
            </div>
            <div class="col-lg-3">
                <?php get_template_part('includes/tools_you'); ?>
            </div>
        </div>
    </div>
    <?php wp_footer() ?>
    <?php get_footer() ?>
</body>

</html>