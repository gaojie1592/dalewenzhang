<?php get_header(); ?>

<body <?php body_class(); ?>>
    <?php get_template_part('toolbar'); ?>
    <div class="container mt-3">
        <div class="row">
            <?php
            $cole = is_active_sidebar('youbianlan') ? 4 : 0;
            $class1 = !empty($cole) ? 'col-lg-8' : 'col-lg-12';
            ?>
            <div class="<?php echo $class1; ?>">
                <?php get_template_part('includes/single_content'); ?>
            </div>
            <?php if (!empty($cole)) : ?>
                <div class="col-lg-<?php echo $cole; ?>">
                    <?php dynamic_sidebar('youbianlan'); ?>
                </div>
            <?php endif; ?>
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